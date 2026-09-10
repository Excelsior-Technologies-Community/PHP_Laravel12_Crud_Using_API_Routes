<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'detail',
        'price',
        'status',
        'image_path',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];
}