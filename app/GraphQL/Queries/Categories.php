<?php declare(strict_types=1);

namespace App\GraphQL\Queries;

use App\Models\Category as CategoryModel;

final readonly class Categories
{
    public function __invoke(null $_, array $args)
    {
        return CategoryModel::all();
    }
}
