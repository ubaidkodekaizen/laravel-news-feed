<?php

namespace App\Helpers;
use App\Models\Company;
use App\Models\User;
use App\Models\Plan;
use App\Models\ProductService;
use DB;

class DropDownHelper
{


    // In your helper.php (or any helper file you include)
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

    public static function searchFilter()
    {

        //$company_positions = Company::pluck('company_position')->unique()->sort();
        //$company_industries = Company::pluck('company_industry')->unique()->sort();
        $company_sub_categories = Company::pluck('company_sub_category')->unique()->sort();
        $company_business_types = Company::pluck('company_business_type')->unique()->sort();
        $company_no_of_employees = Company::pluck('company_no_of_employee')->unique()->sort();
        $company_revenues = Company::pluck('company_revenue')->unique()->sort();
        $company_experiences = Company::pluck('company_experience')->unique()->sort();

        $company_states = User::pluck('state')->unique()->sort();
        $company_countries = User::pluck('country')->unique()->sort();

        $user_city = User::pluck('city')->unique()->sort();
        $user_county = User::pluck('county')->unique()->sort();
        // $user_user_position = User::pluck('user_position')->unique()->sort();
        $user_gender = User::pluck('gender')->unique()->sort();
        $user_age_group = User::pluck('age_group')->unique()->sort();
        $user_ethnicity = User::pluck('ethnicity')->unique()->sort();
        // $user_nationality = User::pluck('nationality')->unique()->sort();
        // $user_languages = User::pluck('languages')->unique()->sort();

        // Extract and split comma-separated values for user-related fields
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

        // Extract and split comma-separated values for company-related fields
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



        $product_service_names = ProductService::pluck('product_service_name')->unique()->sort();

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
            'user_ethnicity' => $user_ethnicity,
            'user_nationality' => $user_nationality,
            'user_languages' => $user_languages,

            'product_service_names' => $product_service_names,
        ];
    }

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

    // public static function renderIndustryDropdown($selectedValues = [])
    // {
    //     // Ensure $selectedValues is always an array
    //     $selectedValues = is_array($selectedValues) ? $selectedValues : explode(',', $selectedValues);

    //     // Fetch industries ordered by name, except "Other"
    //     $industries = \DB::table('industries')
    //         ->select('id', 'name')
    //         ->where('name', '!=', 'Other')
    //         ->orderBy('name', 'asc')
    //         ->get();

    //     // Add "Other" to the end of the list
    //     $otherIndustry = \DB::table('industries')
    //         ->where('name', 'Other')
    //         ->select('id', 'name')
    //         ->first();

    //     if ($otherIndustry) {
    //         $industries->push($otherIndustry);
    //     }

    //     // Start the dropdown HTML
    //     $html = '<div class="dropdown">';
    //     $html .= '<button class="btn btn-light dropdown-toggle w-100" type="button" id="industryDropdownButton" data-bs-toggle="dropdown" aria-expanded="false">';
    //     $html .= 'Select Industries';
    //     $html .= '</button>';

    //     $html .= '<ul id="industry-dropdown-menu" class="dropdown-menu position-dropdown-menu" aria-labelledby="industryDropdownButton">';
    //     $html .= '<li class="mb-2">';
    //     $html .= '<input type="text" id="search-industry-dropdown" class="form-control mb-2" placeholder="Search...">';
    //     $html .= '</li>';

    //     // Loop through industries to build the list items with checkboxes
    //     foreach ($industries as $industry) {
    //         $industryId = 'industry' . preg_replace('/[^a-zA-Z0-9]/', '', $industry->name);
    //         $isChecked = in_array($industry->name, $selectedValues) ? 'checked' : '';

    //         $html .= '<li>';
    //         $html .= '<div class="form-check d-flex justify-content-between align-items-center">';
    //         $html .= '<label class="form-check-label" for="' . $industryId . '">' . htmlspecialchars($industry->name) . '</label>';
    //         $html .= '<input class="form-check-input ms-2 industry-checkbox" type="checkbox" value="' . htmlspecialchars($industry->name) . '" id="' . $industryId . '" ' . $isChecked . '>';
    //         $html .= '</div>';
    //         $html .= '</li>';
    //     }

    //     // Option for "Other"
    //     $html .= '<li>';
    //     $html .= '<div class="form-check d-flex justify-content-between align-items-center">';
    //     $html .= '<label class="form-check-label" for="industry_other_select">Other</label>';
    //     $html .= '<input class="form-check-input ms-2 industry-checkbox" type="checkbox" value="Other" id="industry_other_select">';
    //     $html .= '</div>';
    //     $html .= '</li>';

    //     $html .= '</ul>';
    //     $html .= '</div>';

    //     return $html;
    // }



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



    public static function renderBusinessContributionsToMuslimCommunityDropdown($selectedContribution = null)
    {
        $selectedContribution = old('company_contribute_to_muslim_community', $selectedContribution);
        $contributions = \DB::table('bussiness_contributions')
            ->pluck('name', 'name')
            ->toArray();

        $html = '<select name="company_contribute_to_muslim_community" id="company_contribute_to_muslim_community" class="form-select">';
        $html .= '<option value="">Select Business Contributions to Muslim Community</option>';

        foreach ($contributions as $value => $label) {
            $isSelected = $value == $selectedContribution ? 'selected' : '';
            $html .= '<option value="' . $value . '" ' . $isSelected . '>' . $label . '</option>';
        }

        $html .= '</select>';

        return $html;
    }


    public static function renderAffiliationToMuslimOrgDropdown($selectedAffiliation = null)
    {
        $selectedAffiliation = old('company_affiliation_to_muslim_org', $selectedAffiliation);
        $affiliations = \DB::table('muslim_organizations')
            ->pluck('name', 'name')
            ->toArray();

        $html = '<select name="company_affiliation_to_muslim_org" id="company_affiliation_to_muslim_org" class="form-select">';
        $html .= '<option value="">Select Company Affiliation to Muslim Organization</option>';

        foreach ($affiliations as $value => $label) {
            $isSelected = $value == $selectedAffiliation ? 'selected' : '';
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




    // DropDown For User
    public static function renderIndustryDropdownForUser($selectedIndustry = null, $selectedSubcategory = null)
    {
        // Get old input values for pre-selection
        $selectedIndustry = old('industry_to_connect', $selectedIndustry);
        $selectedSubcategory = old('sub_category_to_connect', $selectedSubcategory);

        // Fetch industries ordered by name, except "Other" which should appear last
        $industries = \DB::table('industries')
            ->select('id', 'name')
            ->where('name', '!=', 'Other')
            ->orderBy('name', 'asc')
            ->get();

        // Add "Other" to the end of the list
        $otherIndustry = \DB::table('industries')
            ->where('name', 'Other')
            ->select('id', 'name')
            ->first();

        if ($otherIndustry) {
            $industries->push($otherIndustry);
        }

        // Start the dropdown HTML
        $html = '<select name="industry_to_connect" id="industry_to_connect" class="form-select">';
        $html .= '<option value="">Select Industry</option>';

        // Loop through industries to build the options
        foreach ($industries as $industry) {
            $isSelected = $industry->name == $selectedIndustry ? 'selected' : '';
            $html .= '<option value="' . $industry->name . '" ' . $isSelected . '>' . $industry->name . '</option>';
        }

        $html .= '</select>';

        return $html;
    }


    public static function renderCommunityInterestDropdown($selectedInterest = null)
    {
        $selectedInterest = old('community_interest', $selectedInterest);
        $communityInterests = \DB::table('community_interests')
            ->pluck('name')
            ->toArray();
        $html = '<select name="community_interest" id="community_interest" class="form-select">';
        $html .= '<option value="">Select Community Interest</option>';

        foreach ($communityInterests as $interest) {
            $isSelected = $interest == $selectedInterest ? 'selected' : '';
            $html .= '<option value="' . $interest . '" ' . $isSelected . '>' . $interest . '</option>';
        }

        $html .= '</select>';

        return $html;
    }

    public static function getPlanDropdown()
    {
        // Check if the URL contains the 'amcob' query parameter
        $urlHasAmcob = request()->has('amcob');

        $plans = Plan::all();
        $options = '<option value="" disabled selected>Choose a Plan</option>';

        // If 'amcob' exists in the URL, add the "Test" plan (ID 3) first
        if ($urlHasAmcob) {
            $testPlan = Plan::find(3); // Find the "Test" plan with ID 3
            if ($testPlan) {
                $options .= sprintf(
                    '<option value="%s">%s / %s</option>',
                    htmlspecialchars($testPlan->id, ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($testPlan->plan_amount, ENT_QUOTES, 'UTF-8'),
                    htmlspecialchars($testPlan->plan_name, ENT_QUOTES, 'UTF-8')
                );
            }
        }

        // Add the other plans to the dropdown
        foreach ($plans as $plan) {
            // Skip adding the "Test" plan again if it was already added
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
