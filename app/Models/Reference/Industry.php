<?php

namespace App\Models\Reference;

use App\Models\Opportunities\Opportunity;
use App\Models\Users\User;
use Illuminate\Database\Eloquent\Model;

class Industry extends Model
{
    protected $fillable = ['name'];


    /**
     * Get the users in this industry
     * Assuming you have an industry_id or industry_to_connect column in users table
     */
    public function users()
    {
        // If you have industry_id in users table
        // return $this->hasMany(User::class, 'industry_id');

        // If you have industry_to_connect (which stores industry name)
        return $this->hasMany(User::class, 'industry_to_connect', 'name');
    }

    /**
     * Get all opportunities in this industry
     */
    public function opportunities()
    {
        return $this->hasMany(Opportunity::class);
    }
}
