<?php

namespace App\GraphQL\Resolvers;
use MeiliSearch\Client;
class Melisearch
{
    public function searchProducts($root, array $args)
    {
        $client = new Client('http://localhost:7700/');
        $index = $client->index('products');

        $filters = [];

        if (isset($args['category'])) {
            $filters[] = 'category = "' . $args['category'] . '"';
        }
        if (isset($args['color'])) {
            $filters[] = 'color = "' . $args['color'] . '"';
        }
        if (isset($args['priceRange'])) {
            $filters[] = 'price >= ' . $args['priceRange'][0] . ' AND price <= ' . $args['priceRange'][1];
        }
        $searchParams = [
            'filters' => implode(' AND ', $filters),
        ];

        $results = $index->search('', $searchParams);
        return $results->getHits();
    }
}
