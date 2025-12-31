<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\Conversation;
use App\Models\Reaction;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{

    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;


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
        'is_amcob',
        'duration',
        'email_verified_at',
    ];

    public function company()
    {
        return $this->hasOne(Company::class)->whereHas('user', function ($query) {
            $query->whereNull('deleted_at');
        });
    }

    public function products()
    {
        return $this->hasMany(Product::class)->whereHas('user', function ($query) {
            $query->whereNull('deleted_at');
        });
    }

    public function services()
    {
        return $this->hasMany(Service::class)->whereHas('user', function ($query) {
            $query->whereNull('deleted_at');
        });
    }


    public function subscriptions()
    {
        return $this->hasMany(Subscription::class)->whereHas('user', function ($query) {
            $query->whereNull('deleted_at');
        });
    }

    public function userEducations()
    {
        return $this->hasMany(UserEducation::class)->whereHas('user', function ($query) {
            $query->whereNull('deleted_at');
        });
    }

    public function conversations()
    {
        // Conversations use user_one_id and user_two_id, so we need to check both
        // This relationship is handled manually in the controller for eager loading
        return $this->hasMany(Conversation::class, 'user_one_id');
    }

    public function userIcp()
    {
        return $this->hasOne(UserIcp::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
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
