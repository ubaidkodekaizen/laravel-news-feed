<?php

namespace App\Models\Business;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Business\ProductService;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'company_logo',
        'company_name',
        'company_slug',
        'company_email',
        'company_web_url',
        'company_linkedin_url',
        'company_position',
        'company_about',
        'company_revenue',
        'company_address',
        'company_country',
        'company_state',
        'company_city',
        'company_county',
        'company_zip_code',
        'company_no_of_employee',
        'company_business_type',
        'company_industry',
        'company_sub_category',
        'company_community_service',
        'company_contribute_to_muslim_community',
        'company_affiliation_to_muslim_org',
        'company_experience',
        'company_phone',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function productServices()
    {
        return $this->hasMany(ProductService::class);
    }

  
}

