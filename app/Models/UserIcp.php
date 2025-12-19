<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserIcp extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'business_location',
        'is_decision_maker',
        'company_current_business_challenges',
        'company_business_goals',
        'company_attributes',
        'company_technologies_you_use',
        'company_buying_process',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
