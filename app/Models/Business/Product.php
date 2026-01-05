<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'short_description',
        'original_price',
        'discounted_price',
        'quantity',
        'unit_of_quantity',
        'product_image',
    ];

    /**
     * Get the user who owns the product.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
