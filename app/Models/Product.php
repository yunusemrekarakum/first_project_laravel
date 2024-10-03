<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Scout\Searchable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Product extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, Searchable;
    use HasFactory, InteractsWithMedia;
    protected $table = 'products';
    protected $guarded = [];
    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function searchableAs() :string
    {
        return 'products_index';
    }
    public function toSearchableArray()
    {
        return [
            'id' => (int) $this->id,
            'title' => $this->title,
            'image_path' => $this->image_path,
            'price' => $this->price,
            'features' => $this->features,
            'colors' => $this->colors,
        ];
    }
}
