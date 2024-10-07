<?php

namespace App\GraphQL\Queries;

use App\Models\Product;
use Illuminate\Support\Facades\Redis;
use Exception;
class ProductQuery
{
    public function products($_, array $args)
    {
        $perPage = isset($args['perPage']) ? (int) $args['perPage'] : 20;
        $page = isset($args['page']) ? (int) $args['page'] : 1;
        $key = 'products:paginate:'. $page;
        if(Redis::exists($key)) {
            $cachedData = json_decode(Redis::get($key), true);
            return [
                'data' => $cachedData['products'],
                'paginatorInfo' => [
                    'total' => $cachedData['total'],
                    'currentPage' => $page,
                    'lastPage' => $cachedData['lastPage'],
                    'per_page' => $perPage,
                    'hasMorePages' => $cachedData['hasMorePages'],
                ]
            ];
        } else {
            $products = Product::search("*")->paginate($perPage, 'page', $page);
            //$cachedData = [
            //    'products' => $products,
            //    'total' => $products->total(),
            //    'lastPage' => $products->lastPage(),
            //    'hasMorePages' => $products->hasMorePages(),
            //];
            //Redis::set($key, json_encode($cachedData));
            return [
                'data' => $products,
                'paginatorInfo' => [
                    'total' => $products->total(),
                    'currentPage' => $page,
                    'lastPage' => $products->lastPage(),
                    'per_page' => $perPage,
                    'hasMorePages' => $products->hasMorePages(),
                ]
            ];
        }
    }
}
