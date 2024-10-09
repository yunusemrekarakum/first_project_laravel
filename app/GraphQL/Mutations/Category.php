<?php

namespace App\GraphQL\Mutations;

use App\Models\Category as modelCategory;
use Illuminate\Support\Collection;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class Category
{
    public function add_category($root, array $args, GraphQLContext $context) {
        $existingCategory = modelCategory::where('title', $args['title'])->first();
        if ($existingCategory){
            throw new \Exception("Bu isimle bir kategori zaten mevcut.");
        }
        return modelCategory::create([
            'title' => $args['title'],
        ]);
    }
    public function delete_category($root, array $args, GraphQLContext $context) {
        $category = modelCategory::find($args['id']);

        if ($category) {
            $category->delete();
            return "Kategori silindi.";
        }

        return "Kategori bulunamadı.";
    }
    public function edit_category($root, array $args, GraphQLContext $context) {
        $category = modelCategory::find($args['id']);
        if ($category) {
            $category->title = $args['title'];
            $category->save();
            return "Güncelleme İşlemi Başarılı";
        } else{
            throw new \Exception("Böyle Bir Veri Yok");
        }
    }
    public function edit_list_category($root, array $args, GraphQLContext $context) {
         $category = modelCategory::where('id', $args['id'])->first();
         return $category;
    }

}
