<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    // Campos que se pueden asignar de forma masiva
    protected $fillable = [
        'title',
        'description',
        'sku',
        'variant_id',
        'shopify_id'
    ];

    public function orders()
    {
        return $this->belongsToMany(Order::class)->withPivot(['quantity', 'price']);
    }
}
