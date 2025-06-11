<?php

namespace App\Helpers;
use App\Models\Company;
use App\Models\Product;
use App\Models\Service;
use App\Models\User;
use App\Models\Plan;
use App\Models\ProductService;
use DB;

class DropDownHelper
{

    //Search Bar country Dropdown
    public static function countryDropdown()
    {
        $countries = User::whereNotNull('country')->where('country', '!=', '')
            ->distinct()->pluck('country', 'country')->sort();
        $dropdown = '<select name="country" id="header_location" class="form-select select2">';
        $dropdown .= '<option value="">Select Location</option>';
        foreach ($countries as $country) {
            $dropdown .= '<option value="' . $country . '">' . ucfirst($country) . '</option>';
        }
        $dropdown .= '</select>';

        return $dropdown;
    }

    // SideBar Search Filters

    public static function searchFilterMobile()
    {


        $company_sub_categories = Company::pluck('company_sub_category')->unique()->sort();
        $company_business_types = Company::pluck('company_business_type')->unique()->sort();
        $company_no_of_employees = collect([
            '1-10',
            '11-50',
            '51-200',
            '201-500',
            '501-1000',
            '1001-5000',
            '5001-10,000',
            '10,001+'
        ]);

        $company_revenues = collect([
            '< 1M',
            '1-5M',
            '5-25M',
            '25-100M',
            '100M +'
        ]);

        $company_experiences = collect([
            'under 1',
            '1-5 Years',
            '5-10 Years',
            '10-20 Years',
            '20+ Years'
        ]);

        // From Users Table

        $company_states = User::pluck('state')->unique()->sort();
        $company_countries = User::pluck('country')->unique()->sort();

        $user_city = User::pluck('city')->unique()->sort();
        $user_county = User::pluck('county')->unique()->sort();
        $user_gender = User::pluck('gender')->unique()->sort();
        $user_age_group = User::pluck('age_group')->unique()->sort();
        $user_marital_status = User::pluck('marital_status')->unique()->sort();
        $user_ethnicity = User::pluck('ethnicity')->unique()->sort();

        $user_nationality = User::pluck('nationality')
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })
            ->unique()
            ->sort()
            ->values();

        $user_languages = User::pluck('languages')
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })
            ->unique()
            ->sort()
            ->values();

        $user_user_position = User::pluck('user_position')
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })
            ->unique()
            ->sort()
            ->values();


        $company_positions = Company::pluck('company_position')
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })
            ->unique()
            ->sort()
            ->values();

        $company_industries = Company::pluck('company_industry')
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })
            ->unique()
            ->sort()
            ->values();



        $product = Product::pluck('title')->unique()->sort();
        $service = Service::pluck('title')->unique()->sort();

        return [
            'company_position' => $company_positions,
            'company_industry' => $company_industries,
            'company_sub_category' => $company_sub_categories,
            'company_business_type' => $company_business_types,
            'company_no_of_employee' => $company_no_of_employees,
            'company_revenue' => $company_revenues,

            'company_state' => $company_states,
            'company_country' => $company_countries,

            'company_experiences' => $company_experiences,
            'user_city' => $user_city,
            'user_county' => $user_county,
            'user_position' => $user_user_position,
            'user_gender' => $user_gender,
            'user_age_group' => $user_age_group,
            'user_marital_status' => $user_marital_status,
            'user_ethnicity' => $user_ethnicity,
            'user_nationality' => $user_nationality,
            'user_language' => $user_languages,

            'product' => $product,
            'service' => $service,
        ];
    }
    public static function searchFilter()
    {


        $company_sub_categories = Company::pluck('company_sub_category')->unique()->sort();
        $company_business_types = Company::pluck('company_business_type')->unique()->sort();
        $company_no_of_employees = collect([
            '1-10',
            '11-50',
            '51-200',
            '201-500',
            '501-1000',
            '1001-5000',
            '5001-10,000',
            '10,001+'
        ]);

        $company_revenues = collect([
            '< 1M',
            '1-5M',
            '5-25M',
            '25-100M',
            '100M +'
        ]);

        $company_experiences = collect([
            'under 1',
            '1-5 Years',
            '5-10 Years',
            '10-20 Years',
            '20+ Years'
        ]);

        // From Users Table

        $company_states = User::pluck('state')->unique()->sort();
        $company_countries = User::pluck('country')->unique()->sort();

        $user_city = User::pluck('city')->unique()->sort();
        $user_county = User::pluck('county')->unique()->sort();
        $user_gender = User::pluck('gender')->unique()->sort();
        $user_age_group = User::pluck('age_group')->unique()->sort();
        $user_marital_status = User::pluck('marital_status')->unique()->sort();
        $user_ethnicity = User::pluck('ethnicity')->unique()->sort();

        $user_nationality = User::pluck('nationality')
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })
            ->unique()
            ->sort()
            ->values();

        $user_languages = User::pluck('languages')
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })
            ->unique()
            ->sort()
            ->values();

        $user_user_position = User::pluck('user_position')
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })
            ->unique()
            ->sort()
            ->values();


        $company_positions = Company::pluck('company_position')
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })
            ->unique()
            ->sort()
            ->values();

        $company_industries = Company::pluck('company_industry')
            ->flatMap(function ($item) {
                return array_map('trim', explode(',', $item));
            })
            ->unique()
            ->sort()
            ->values();



        $product = Product::pluck('title')->unique()->sort();
        $service = Service::pluck('title')->unique()->sort();

        return [
            'company_positions' => $company_positions,
            'company_industries' => $company_industries,
            'company_sub_categories' => $company_sub_categories,
            'company_business_types' => $company_business_types,
            'company_no_of_employees' => $company_no_of_employees,
            'company_revenues' => $company_revenues,

            'company_states' => $company_states,
            'company_countries' => $company_countries,

            'company_experiences' => $company_experiences,
            'user_city' => $user_city,
            'user_county' => $user_county,
            'user_position' => $user_user_position,
            'user_gender' => $user_gender,
            'user_age_group' => $user_age_group,
            'user_marital_status' => $user_marital_status,
            'user_ethnicity' => $user_ethnicity,
            'user_nationality' => $user_nationality,
            'user_languages' => $user_languages,

            'products' => $product,
            'services' => $service,
        ];
    }


    // Personal Tab Dropdown
    public static function nationalityDropdown($selected = null)
    {
        $nationalities = [
            "Afghan",
            "Albanian",
            "Algerian",
            "American",
            "Andorran",
            "Angolan",
            "Antiguans",
            "Argentine",
            "Armenian",
            "Australian",
            "Austrian",
            "Azerbaijani",
            "Bahamian",
            "Bahraini",
            "Bangladeshi",
            "Barbadian",
            "Barbudans",
            "Bashkir",
            "Bahamian",
            "Belgian",
            "Belizean",
            "Beninese",
            "Bhutanese",
            "Bolivian",
            "Bosnian",
            "Botswanan",
            "Brazilian",
            "British",
            "Bruneian",
            "Bulgarian",
            "Burkinese",
            "Burmese",
            "Burundian",
            "Cambodian",
            "Cameroonian",
            "Canadian",
            "Cape Verdean",
            "Central African",
            "Chadian",
            "Chilean",
            "Chinese",
            "Colombian",
            "Comorian",
            "Congolese",
            "Costa Rican",
            "Croatian",
            "Cuban",
            "Cypriot",
            "Czech",
            "Danish",
            "Djiboutian",
            "Dominican",
            "Dominican",
            "East Timorese",
            "Ecuadorean",
            "Egyptian",
            "Emirati",
            "Equatorial Guinean",
            "Eritrean",
            "Estonian",
            "Ethiopian",
            "Fijian",
            "Filipino",
            "Finnish",
            "French",
            "Gabonese",
            "Gambian",
            "Georgian",
            "German",
            "Ghanaian",
            "Greek",
            "Grenadian",
            "Guatemalan",
            "Guinean",
            "Guinea-Bissauan",
            "Guyanese",
            "Haitian",
            "Honduran",
            "Hungarian",
            "Icelander",
            "Indian",
            "Indonesian",
            "Iranian",
            "Iraqi",
            "Irish",
            "Israeli",
            "Italian",
            "Ivorian",
            "Jamaican",
            "Japanese",
            "Jordanian",
            "Kazakh",
            "Kenyan",
            "Kittian and Nevisian",
            "Korean",
            "Kuwaiti",
            "Kyrgyz",
            "Laotian",
            "Latvian",
            "Lebanese",
            "Liberian",
            "Libyan",
            "Liechtensteiner",
            "Lithuanian",
            "Luxembourgian",
            "Macedonian",
            "Malagasy",
            "Malawian",
            "Malaysian",
            "Maldivian",
            "Malian",
            "Malta",
            "Marshallese",
            "Mauritian",
            "Mauritian",
            "Mexican",
            "Micronesian",
            "Moldovan",
            "Monacan",
            "Mongolian",
            "Moroccan",
            "Mozambican",
            "Namibian",
            "Nauruan",
            "Nepalese",
            "New Zealander",
            "Nicaraguan",
            "Nigerian",
            "Nigerien",
            "Norwegian",
            "Omani",
            "Pakistani",
            "Palauan",
            "Panamanian",
            "Papua New Guinean",
            "Paraguayan",
            "Peruvian",
            "Polish",
            "Portuguese",
            "Qatari",
            "Romanian",
            "Russian",
            "Rwandan",
            "Saint Lucian",
            "Salvadoran",
            "Samoan",
            "San Marinese",
            "Sao Tomean",
            "Saudi",
            "Senegalese",
            "Serbian",
            "Seychellois",
            "Sierra Leonean",
            "Singaporean",
            "Slovak",
            "Slovene",
            "Solomon Islander",
            "Somali",
            "South African",
            "South Korean",
            "Spanish",
            "Sri Lankan",
            "Sudanese",
            "Surinamese",
            "Swazi",
            "Swedish",
            "Swiss",
            "Syrian",
            "Taiwanese",
            "Tajikistani",
            "Tanzanian",
            "Thai",
            "Togolese",
            "Tongan",
            "Trinidadian or Tobagonian",
            "Tunisian",
            "Turkish",
            "Turkmen",
            "Tuvaluan",
            "Ugandan",
            "Ukrainian",
            "Uruguayan",
            "Uzbekistani",
            "Vanuatu",
            "Venezuelan",
            "Vietnamese",
            "Yemeni",
            "Zambian",
            "Zimbabwean"
        ];

        $dropdown = '<select name="nationality" id="nationality" class="form-control">';
        foreach ($nationalities as $nationality) {
            $dropdown .= '<option value="' . $nationality . '" ' . ($selected == $nationality ? 'selected' : '') . '>' . $nationality . '</option>';
        }
        $dropdown .= '</select>';

        return $dropdown;
    }

    public static function designationDropdown($dropdownId = 'dropdownMenuButton', $selectedValues = [])
    {
        $designations = DB::table('designations')->pluck('name');

        $html = '<div class="dropdown">';
        $html .= '<button class="btn btn-light dropdown-toggle w-100" type="button" id="' . $dropdownId . '" data-bs-toggle="dropdown" aria-expanded="false">';
        $html .= 'Select Positions';
        $html .= '</button>';

        $html .= '<ul id="position-dropdown-menu" class="dropdown-menu position-dropdown-menu" aria-labelledby="' . $dropdownId . '">';
        $html .= '<li class="mb-2">';
        $html .= '<input type="text" id="search-dropdown" class="form-control mb-2" placeholder="Search...">';
        $html .= '</li>';

        foreach ($designations as $designation) {
            $designationId = 'position' . preg_replace('/[^a-zA-Z0-9]/', '', $designation);
            // Check if the current designation is selected
            $isChecked = in_array($designation, $selectedValues) ? 'checked' : '';

            $html .= '<li>';
            $html .= '<div class="form-check d-flex justify-content-between align-items-center">';
            $html .= '<label class="form-check-label" for="' . $designationId . '">' . htmlspecialchars($designation) . '</label>';
            $html .= '<input class="form-check-input ms-2" type="checkbox" value="' . htmlspecialchars($designation) . '" id="' . $designationId . '" ' . $isChecked . '>';
            $html .= '</div>';
            $html .= '</li>';
        }

        // Option for 'Other'
        $html .= '<li>';
        $html .= '<div class="form-check d-flex justify-content-between align-items-center">';
        $html .= '<label class="form-check-label" for="company_position_other_select">Other</label>';
        $html .= '<input class="form-check-input ms-2" type="checkbox" value="Other" id="company_position_other_select">';
        $html .= '</div>';
        $html .= '</li>';

        $html .= '</ul>';
        $html .= '</div>';

        return $html;
    }


    // Professional Tab Dropdown
    public static function industryDropdown($dropdownId = 'industryDropdownButton', $selectedValues = [])
    {
        $industries = DB::table('industries')->pluck('name');

        $html = '<div class="dropdown">';
        $html .= '<button class="btn btn-light dropdown-toggle w-100" type="button" id="' . $dropdownId . '" data-bs-toggle="dropdown" aria-expanded="false">';
        $html .= 'Select Industries';
        $html .= '</button>';

        $html .= '<ul id="industry-dropdown-menu" class="dropdown-menu industry-dropdown-menu" aria-labelledby="' . $dropdownId . '">';
        $html .= '<li class="mb-2">';
        $html .= '<input type="text" id="industry-search-dropdown" class="form-control mb-2" placeholder="Search...">';
        $html .= '</li>';

        foreach ($industries as $industry) {
            $industryId = 'industry' . preg_replace('/[^a-zA-Z0-9]/', '', $industry);
            $isChecked = in_array($industry, $selectedValues) ? 'checked' : '';

            $html .= '<li>';
            $html .= '<div class="form-check d-flex justify-content-between align-items-center">';
            $html .= '<label class="form-check-label" for="' . $industryId . '">' . htmlspecialchars($industry) . '</label>';
            $html .= '<input class="form-check-input ms-2" type="checkbox" value="' . htmlspecialchars($industry) . '" id="' . $industryId . '" ' . $isChecked . '>';
            $html .= '</div>';
            $html .= '</li>';
        }

        // Option for "Other"
        $html .= '<li>';
        $html .= '<div class="form-check d-flex justify-content-between align-items-center">';
        $html .= '<label class="form-check-label" for="industry_other_select">Other</label>';
        $html .= '<input class="form-check-input ms-2" type="checkbox" value="Other" id="industry_other_select">';
        $html .= '</div>';
        $html .= '</li>';

        $html .= '</ul>';
        $html .= '</div>';

        return $html;
    }


    public static function renderEmployeeSizeDropdown($selectedEmployeeSize = null)
    {
        $employee_sizes = [
            '1-10' => '1-10 employees',
            '11-50' => '11-50 employees',
            '51-200' => '51-200 employees',
            '201-500' => '201-500 employees',
            '501-1000' => '501-1000 employees',
            '1001-5000' => '1001-5000 employees',
            '5001-10,000' => '5001-10,000 employees',
            '10,001+' => '10,001+ employees',
        ];

        // Generate the HTML for employee size dropdown
        $html = '<select name="company_no_of_employee" id="company_no_of_employee" class="form-select">';
        $html .= '<option value="">Select No. of Employees</option>';

        foreach ($employee_sizes as $value => $label) {
            $isSelected = $value == $selectedEmployeeSize ? 'selected' : '';
            $html .= '<option value="' . $value . '" ' . $isSelected . '>' . $label . '</option>';
        }

        $html .= '</select>';

        return $html;
    }


    public static function renderBusinessTypeDropdown($selectedBusinessType = null)
    {
        $selectedBusinessType = old('company_business_type', $selectedBusinessType);

        $business_types = \DB::table('bussiness_types')
            ->pluck('name', 'name')
            ->toArray();

        $html = '<select name="company_business_type" id="company_business_type" class="form-select">';
        $html .= '<option value="">Select Company Business Type</option>';

        foreach ($business_types as $value => $label) {
            $isSelected = $value == $selectedBusinessType ? 'selected' : '';
            $html .= '<option value="' . $value . '" ' . $isSelected . '>' . $label . '</option>';
        }

        $html .= '</select>';

        return $html;
    }


    public static function renderRevenueDropdown($selectedRevenue = null)
    {
        $revenue_ranges = [
            '< 1M' => '< $1M',
            '1-5M' => '$1M -$5M',
            '5-25M' => '$5M - $25M',
            '25-100M' => '$25M - $100M',
            '100M +' => '$100M+',
        ];

        $html = '<select name="company_revenue" id="company_revenue" class="form-select">';
        $html .= '<option value="">Select Revenue</option>';

        foreach ($revenue_ranges as $value => $label) {
            $isSelected = $value == $selectedRevenue ? 'selected' : '';
            $html .= '<option value="' . $value . '" ' . $isSelected . '>' . $label . '</option>';
        }

        $html .= '</select>';

        return $html;
    }



    // Sign Up Page Dropdown
    public static function getPlanDropdown()
    {
        $urlHasAmcob = request()->has('amcob');

        // Get all plans ordered by amount ascending
        $plans = Plan::orderBy('plan_amount', 'asc')->get();
        $options = '<option value="" disabled selected>Choose a Plan</option>';

        if ($urlHasAmcob) {
            $testPlan = Plan::find(3);
            if ($testPlan) {
                $options .= sprintf(
                    '<option value="%s">%s / %s</option>',
                    htmlspecialchars($testPlan->id, ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($testPlan->plan_amount, ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($testPlan->plan_name, ENT_QUOTES, 'UTF-8')
                );
            }
        }

        foreach ($plans as $plan) {
            if ($plan->id == 3) {
                continue;
            }

            $options .= sprintf(
                '<option value="%s">%s / %s</option>',
                htmlspecialchars($plan->id, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($plan->plan_amount, ENT_QUOTES, 'UTF-8'),
                htmlspecialchars($plan->plan_name, ENT_QUOTES, 'UTF-8')
            );
        }

        return $options;
    }










}
