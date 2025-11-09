<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'description',
        'brand',
        'model',
        'presentation',
        'unit_measurement',
        'price',
        'minimum_stock',
        'maximum_stock',
        'category_id',
        'image'
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function movements()
    {
        return $this->hasMany(Movement::class);
    }
}
