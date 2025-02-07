<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Conversation;

class User extends Authenticatable
{
    
    use HasApiTokens, HasFactory, Notifiable;

   
    protected $fillable = [
        'first_name',
        'last_name',
        'slug',
        'email',
        'password',
        'phone',
        'linkedin_url',
        'x_url',
        'instagram_url',
        'facebook_url',
        'address',
        'country',
        'state',
        'city',
        'county',
        'zip_code',
        'industry_to_connect',
        'sub_category_to_connect',
        'community_interest',
        'status',
        'paid',
        'phone_public',
        'email_public',
        'user_position',
        'gender',
        'age_group',
        'ethnicity',
        'nationality',
        'languages',
        'marital_status',
    ];

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class);
    }

    public function userEducations()
    {
        return $this->hasMany(UserEducation::class);
    } 
    public function conversations()
    {
        return $this->hasMany(Conversation::class); // Assuming Conversation model exists
    }

    
    protected $hidden = [
        'password',
        'remember_token',
    ];

  
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
