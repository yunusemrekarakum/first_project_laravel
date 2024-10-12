<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Product as model_product;
use Illuminate\Http\UploadedFile;
use MeiliSearch\Client;
use Exception;
use GuzzleHttp\Handler\Proxy;
use Illuminate\Support\Facades\Redis;
use Meilisearch\Meilisearch;

class Product
{
    protected $meiliSearchClient;

    public function __construct()
    {
        $this->meiliSearchClient = new Client('http://meilisearch:7700', 'masterKey');
    }
    public function product_admin_list($_, array $args)
    {
        $perPage = empty($args['perPage']) ? 20 : $args['perPage'];
        $page = empty($args['page']) ? 1 : $args['page'];
        $query = model_product::query();
        $products = $query->paginate($perPage, ['*'], 'page', $page);
        return [
            'data' => $products->items(),
            'paginatorInfo' => [
                'total' => $products->total(),
                'currentPage' => $products->currentPage(),
                'lastPage' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'hasMorePages' => $products->hasMorePages(),
            ]
        ];
    }
    public function product_add($_, array $args)
    {
        if (!isset($args['image_path']) || !($args['image_path'] instanceof UploadedFile)) {
            throw new Exception('Geçersiz dosya yüklemesi.');
        }

        $image = $args['image_path'];
        $image_path = asset('media/' . $image->store('products', 'media'));
        $product = model_product::create([
            'title' => $args['title'],
            'category_id' => (int) $args['category_id'],
            'price' => (int) $args['price'],
            'features' => $args['features'],
            'colors' => $args['colors'],
            'image_path' => $image_path,
        ]);
        $index = $this->meiliSearchClient->index('products_index');
        $products = model_product::all();
        $documents = $products->map(function ($product) {
            return $product->toSearchableArray();
        })->toArray();
        try {
            $index->addDocuments($documents);
            $keys = Redis::keys('products:*');
            if (!empty($keys)) {
                foreach ($keys as $key) {
                    $deleted = Redis::connection()->del($key);
                }
            }
        } catch (Exception $e) {
            throw new Exception('Veri ekleme hatası: ' . $e->getMessage());
        }
        return $product;
    }

    public function list_product($_, array $args)
    {
        return model_product::all();
    }

    public function product_delete($_, array $args)
    {
        $data = model_product::find($args['id']);
        if (!empty($data)) {
            $productId = $data->id;
            $data->delete();
            $index = $this->meiliSearchClient->index('products_index');

            try {
                $index->deleteDocuments([$productId]);
            } catch (Exception $e) {
                throw new Exception('Veri ekleme hatası: ' . $e->getMessage());
            }
            try {
                $keys = Redis::keys('products:*');
                if (!empty($keys)) {
                    foreach ($keys as $key) {
                        Redis::connection()->del($key);
                    }
                }
                return "silindi";
            } catch (Exception $e) {
                throw new Exception('Veri ekleme hatası: ' . $e->getMessage());
            }
        } else {
            return "silinmedi";
        }
    }

    public function product_list_edit($_, array $args)
    {
        $product = model_product::where('id', $args['id'])->first();
        return $product;
    }

    public function product_edit($_, array $args)
    {
        $product = model_product::find($args['id']);
        if (isset($args['image_path'])) {
            $image = $args['image_path'];
            $image_path = asset('media/' . $image->store('products', 'media'));
            $product->image_path = $image_path;
        }

        if ($product) {
            $product->title = $args['title'];
            $product->category_id = $args['category_id'];
            $product->price = $args['price'];
            $product->features = $args['features'];
            $product->colors = $args['colors'];


            $index = $this->meiliSearchClient->index('products_index');
            $products = model_product::all();
            $documents = $products->map(function ($product) {
                return $product->toSearchableArray();
            })->toArray();
            $keys = Redis::keys('products:*');
            if (!empty($keys)) {
                foreach ($keys as $key) {
                    Redis::connection()->del($key);
                }
            }
            try {
                $index->updateDocuments($documents);
            } catch (Exception $e) {
                throw new Exception('Veri ekleme hatası: ' . $e->getMessage());
            }


            $product->save();
        } else {
            throw new \Exception("Böyle Bir Veri Yok");
        }
    }
}
