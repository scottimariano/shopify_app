<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'shopify_id',
        'status',
        'financial_status',
        'subtotal_price',
        'total_price',
        'currency',
        'email',
        'created_at_shopify',
        'updated_at_shopify',
    ];

    protected $casts = [
        'created_at_shopify' => 'datetime',
        'updated_at_shopify' => 'datetime',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class)->withPivot(['quantity', 'price']);
    }
}