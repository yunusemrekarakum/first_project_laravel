<?php
namespace App\GraphQL\Resolvers;

use Meilisearch\Client;
use Exception;

class SearchProducts
{
    public function SearchProducts($root, array $args)
    {
        if (!isset($args['filter'])) {
            throw new Exception("Filter parameter is required.");
        }
    
        $client = new Client('http://meilisearch:7700', 'aSampleMasterKey');
        $index = $client->index('products_index');
    
        // Filtreleri kullanarak arama yapın
        $searchParams = [
            'filters' => $args['filter'], // filter parametresini kullanın
        ];
    
        $results = $index->search('', $searchParams);
        return $results->getHits();
    }
}