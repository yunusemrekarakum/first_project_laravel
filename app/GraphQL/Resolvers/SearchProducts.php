<?php

namespace App\GraphQL\Resolvers;

use App\Models\Product;
use MeiliSearch\Client;
use Laravel\Scout\Searchable;
use Exception;
use Illuminate\Support\Facades\Redis;

class SearchProducts
{
    protected $meiliSearchClient;

    public function __construct()
    {
        $this->meiliSearchClient = new Client(env('MEILISEARCH_HOST'), env('MEILISEARCH_KEY'));
    }
    public function SearchProducts($root, array $args)
    {
        $index = $this->meiliSearchClient->index('products_index');

        $page = isset($args['page']) ? (int)$args['page'] : 1;
        $perPage = isset($args['perPage']) ? (int)$args['perPage'] : 10;
        $offset = ($page - 1) * $perPage;

        $options = [
            'filter' => [],
            'sort' => [],
            'limit' => $perPage,
            'offset' => $offset,
        ];
        if (isset($args['title']) && !empty($args['title']) && $args['title'] != 'null') {
            $title = $args['title'];
        } else {
            $title = '*';
        }

        if (isset($args['category']) && !empty($args['category']) && $args['category'] != 'null') {
            $category = $args['category'];
            $options['filter'][] = 'category.title CONTAINS "' . addslashes($category) . '"';
        }

        if (!empty($args['color']) and $args['color'] != 'null') {
            $color = $args['color'];
            $options['filter'][] = 'colors CONTAINS "' . addslashes($color) . '"';
        }
        if (isset($args['features']) and $args['features'] != 'null') {
            $features = $args['features'];
            $options['filter'][] = 'features CONTAINS "' . addslashes($features) . '"';
        }
        if (isset($args['min_price']) && is_numeric($args['min_price']) && $args['min_price'] != 'null') {
            $min_price = (int)$args['min_price'];
            $options['filter'][] = "price >= $min_price";
        }
        if (isset($args['max_price']) && is_numeric($args['max_price']) && $args['max_price'] != 'null') {
            $max_price = (int)$args['max_price'];
            $options['filter'][] = "price <= $max_price";
        }
        if ($args['sort'] == 'desc' || $args['sort'] == 'asc') {
            $sort = $args['sort'];
            $options['sort'][] = "created_at:$sort";
            $sort = implode(',', $options['sort']);
        } else {
            $sort = 'desc';
            $options['sort'][] = "created_at:$sort";
            $sort = implode(',', $options['sort']);
        }
        if (empty($options['filter'])) {
            $key = 'products:title' . $title . ':sort' . $sort  . ':paginate:' . $page;
            if (Redis::exists($key)) {
                $cachedData = json_decode(Redis::get($key), true);
                return [
                    'results' => $cachedData['results'],
                    'total' => $cachedData['total'],
                    'lastPage' => $cachedData['lastPage'],
                    'currentPage' => $page,
                ];
            } else {
                if (!empty($options['filter'])) {
                    $options['filter'] = implode(' AND ', $options['filter']);
                }
                $products = $index->search($title, $options);
                $total = $products->getRaw()['nbHits'];
                $cachedData = [
                    'results' => $products->getHits(),
                    'total' => $total,
                    'lastPage' => ceil($total / $perPage),
                    'currentPage' => $page,
                ];
                Redis::set($key, json_encode($cachedData), 'EX', 3600);
                return [
                    'results' => $products->getHits(),
                    'total' => $total,
                    'lastPage' => ceil($total / $perPage),
                    'currentPage' => $page,
                ];
            }
        } else {
            $filters = $options['filter'];
            $cacheKey = 'products:title' . $title . ':filter' . implode('_', $filters) . ':sort' . $sort . ':page' . $page;
            if (Redis::exists($cacheKey)) {
                $cachedData = json_decode(Redis::get($cacheKey), true);
                return [
                    'results' => $cachedData['results'],
                    'total' => $cachedData['total'],
                    'lastPage' => $cachedData['lastPage'],
                    'currentPage' => $page,
                ];
            } else {
                if (!empty($options['filter'])) {
                    $options['filter'] = implode(' AND ', $options['filter']);
                }
                $products = $index->search($title, $options);
                $total = $products->getRaw()['nbHits'];
                $cachedData = [
                    'results' => $products->getHits(),
                    'total' => $total,
                    'lastPage' => ceil($total / $perPage),
                    'currentPage' => $page,
                ];
                Redis::set($cacheKey, json_encode($cachedData), 'EX', 3600);
                return $cachedData;
            }
        }
    }
}
