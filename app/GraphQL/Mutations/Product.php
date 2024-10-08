<?php

declare(strict_types=1);

namespace App\GraphQL\Mutations;

use App\Models\Product as model_product;
use Illuminate\Http\UploadedFile;
use MeiliSearch\Client;
use Exception;

class Product
{
    protected $meiliSearchClient;

    public function __construct()
    {
        $this->meiliSearchClient = new Client('http://meilisearch:7700', 'masterKey');
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
            $data->delete();
            $index = $this->meiliSearchClient->index('products_index');
            $documents = $data->map(function ($product) {
                return $product->toSearchableArray();
            })->toArray();
            try {
                $index->deleteDocuments($documents);
            } catch (Exception $e) {
                throw new Exception('Veri ekleme hatası: ' . $e->getMessage());
            }
            return "silindi";
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
