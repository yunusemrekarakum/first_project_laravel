<?php
namespace App\GraphQL\Resolvers;

use App\Models\Product;
use MeiliSearch\Client;
use Exception;

class SearchProducts
{
    protected $meiliSearchClient;

    public function __construct()
    {
        $this->meiliSearchClient = new Client('http://meilisearch:7700', 'masterKey');
    }
    public function SearchProducts($root, array $args)
    {
        $options = ['filter' => [], 'sort' => []];
        if (isset($args['category']) && !empty($args['category']) && $args['category'] != 'null') {
            $category = $args['category'];
            $options['filter'][] = 'category CONTAINS "' . addslashes($category) . '"';
        }
        
        if(!empty($args['color']) and $args['color'] != 'null') {
            $color = $args['color'];
            $options['filter'][] = 'colors CONTAINS "' . addslashes($color) . '"';
        }
        if(isset($args['features']) and $args['features'] != 'null') {
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

        }
        if (!empty($options['filter'])) {
            $options['filter'] = implode(' AND ', $options['filter']);
        }
        
        try {
            $products = Product::search('', function ($meilisearch, $query) use ($options) {
                return $meilisearch->search($query, $options);
            })->get();
        } catch (Exception $e) {
            throw new Exception('Arama hatası: ' . $e->getMessage());
        }
        return $products;
    }
}
