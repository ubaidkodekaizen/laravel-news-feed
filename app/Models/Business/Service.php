<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use App\Models\User;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'short_description',
        'original_price',
        'discounted_price',
        'duration',
        'service_image',
    ];

    /**
     * Get the user who owns the service.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
