<?php

namespace App\GraphQL\Queries;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Request;

class ProductQuery
{
    public function products($_, array $args)
    {
        $perPage= $args['perPage'] ?? 10;
        $page= $args['page'] ?? 1;
        $query = Product::query();
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
}
