<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        "name",
        "price",
        "quantity",
        "is_verified",
        "user_id"
    ];

    public static $productRules = [
        'name' =>'required|string',
        'price' => 'required|numeric',
        'quantity' => 'required|integer|gt:1',
        'is_verified' => 'boolean'
    ];

}
