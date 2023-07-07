<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'review',
        'is_active',
        'is_suspicious',
        'is_sanitized'
    ];

    public static $reviewRule = [
        'review' => 'required|string',
        'product_id'=> 'required|exists:products,id|integer'
    ];

    public function user():BelongsTo{
        return $this->belongsTo(User::class);
    }
}
