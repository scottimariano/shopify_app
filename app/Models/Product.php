<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{

    // Campos que se pueden asignar de forma masiva
    protected $fillable = ['name', 'description', 'price'];
}
