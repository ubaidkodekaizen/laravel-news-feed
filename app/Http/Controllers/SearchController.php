<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\ProductService;
use App\Models\User;
use Illuminate\Http\Request;


class SearchController extends Controller
{
    

    public function SearchUserCompany()
    {
        
        $company_positions = Company::pluck('company_position')->unique();
        $company_industries = Company::pluck('company_industry')->unique();
        $company_sub_categories = Company::pluck('company_sub_category')->unique();
        $company_business_types = Company::pluck('company_business_type')->unique();
        $company_no_of_employees = Company::pluck('company_no_of_employee')->unique();
        $company_states = User::pluck('state')->unique();
        $company_countries = User::pluck('country')->unique();
        $company_revenues = Company::pluck('company_revenue')->unique();
        $product_service_names = ProductService::pluck('product_service_name')->unique();
        
        
        
        $users = User::where('status', 'complete')
            ->whereHas('company', function ($query) {
                $query->where('status', 'complete');
            })
            ->with([
                'company' => function ($query) {
                    $query->with(['productServices', 'accreditations']);
                }
            ])
            ->orderBy('id', 'desc')
            ->paginate(1);       

            return view('search', compact(
                'users', 
                'company_positions', 
                'company_industries', 
                'company_sub_categories', 
                'company_business_types', 
                'company_no_of_employees', 
                'company_states', 
                'company_countries', 
                'company_revenues', 
                'product_service_names'
            ));
    }

    public function showUserBySlug($slug)
    {
        $user = User::where('slug', $slug)
            ->with('company')
            ->firstOrFail();
            

        return view('user-profile', compact('user'));
    }


    public function showCompanyBySlug($companySlug)
    {
        $company = Company::where('company_slug', $companySlug)
            ->with(['productServices', 'accreditations'])
            ->firstOrFail();

        return view('company-profile', compact('company'));
    }







}
