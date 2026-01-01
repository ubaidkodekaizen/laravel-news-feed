<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Business\Subscription;

class Plan extends Model
{
    use HasFactory;

    protected $fillable = [
        'plan_name',
        'plan_amount',
        'status'
    ];

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }
}

