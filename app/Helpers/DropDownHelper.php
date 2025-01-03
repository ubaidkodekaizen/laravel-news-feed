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


    public static function renderIndustryDropdown($selectedIndustry = null, $selectedSubcategory = null)
    {
        // Get old input values for pre-selection
        $selectedIndustry = old('company_industry', $selectedIndustry);
        $selectedSubcategory = old('company_sub_category', $selectedSubcategory);

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
        $html = '<select name="company_industry" id="company_industry" class="form-select">';
        $html .= '<option value="">Select Industry</option>';

        // Loop through industries to build the options
        foreach ($industries as $industry) {
            $isSelected = $industry->name == $selectedIndustry ? 'selected' : '';
            $html .= '<option value="' . $industry->name . '" ' . $isSelected . '>' . $industry->name . '</option>';
        }

        $html .= '</select>';

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




    // public static function renderCountryDropdownForUser($selectedCountry = null)
    // {
    //     $selectedCountry = old('country', $selectedCountry);

    //     $countries = [
    //         'USA' => 'USA',
    //         'UK' => 'UK',
    //         'Canada' => 'Canada'
    //     ];

    //     $html = '<select name="country" id="country" class="form-select">';
    //     $html .= '<option value="">Select Country</option>';

    //     foreach ($countries as $code => $name) {
    //         $isSelected = $code == $selectedCountry ? 'selected' : '';
    //         $html .= '<option value="' . $code . '" ' . $isSelected . '>' . $name . '</option>';
    //     }

    //     $html .= '</select>';
    //     $html .= '
    //         <script>
    //             const countryData = {
    //                 "USA": {
    //                     "California": ["Irvine", "Los Angeles", "San Francisco", "San Diego", "Sacramento", "Fresno", "Long Beach", "Oakland", "Bakersfield", "Anaheim", "Santa Ana"],
    //                     "Texas": ["Houston", "Dallas", "Austin", "San Antonio", "Fort Worth", "El Paso", "Arlington", "Corpus Christi", "Plano", "Laredo"],
    //                     "Florida": ["Miami", "Orlando", "Tampa", "Jacksonville", "St. Petersburg", "Fort Lauderdale", "Tallahassee", "Hialeah", "Port St. Lucie", "Cape Coral"],
    //                     "New York": ["New York City", "Buffalo", "Rochester", "Albany", "Syracuse", "Yonkers", "Schenectady", "Binghamton", "Saratoga Springs", "Ithaca"],
    //                     "Illinois": ["Chicago", "Aurora", "Naperville", "Joliet", "Rockford", "Springfield", "Peoria", "Elgin", "Champaign", "Waukegan"],
    //                     "Pennsylvania": ["Philadelphia", "Pittsburgh", "Allentown", "Erie", "Reading", "Scranton", "Bethlehem", "Lancaster", "Harrisburg", "York"],
    //                     "Ohio": ["Columbus", "Cleveland", "Cincinnati", "Toledo", "Akron", "Dayton", "Parma", "Canton", "Youngstown", "Lorain"],
    //                     "Georgia": ["Atlanta", "Augusta", "Columbus", "Macon", "Savannah", "Athens", "Sandy Springs", "Roswell", "Albany", "Marietta"],
    //                     "Michigan": ["Detroit", "Grand Rapids", "Warren", "Sterling Heights", "Lansing", "Ann Arbor", "Flint", "Kalamazoo", "Saginaw", "Dearborn"],
    //                     "North Carolina": ["Charlotte", "Raleigh", "Greensboro", "Durham", "Winston-Salem", "Fayetteville", "Cary", "High Point", "Gastonia", "Chapel Hill"]
    //                 },
    //                 "UK": {
    //                     "England": ["London", "Manchester", "Birmingham", "Liverpool", "Nottingham", "Sheffield", "Leeds", "Newcastle", "Bradford", "Bristol"],
    //                     "Scotland": ["Edinburgh", "Glasgow", "Aberdeen", "Dundee", "Inverness", "Perth", "Stirling", "Ayr", "East Kilbride", "Livingston"],
    //                     "Wales": ["Cardiff", "Swansea", "Newport", "Wrexham", "Barry", "Cwmbran", "Bangor", "Colwyn Bay", "Aberystwyth", "Merthyr Tydfil"],
    //                     "Northern Ireland": ["Belfast", "Derry", "Lisburn", "Newtownabbey", "Bangor", "Omagh", "Craigavon", "Antrim", "Coleraine", "Armagh"]
    //                 },
    //                 "Canada": {
    //                     "Ontario": ["Toronto", "Ottawa", "Mississauga", "Brampton", "Hamilton", "London", "Markham", "Vaughan", "Kitchener", "Windsor"],
    //                     "Quebec": ["Montreal", "Quebec City", "Laval", "Gatineau", "Sherbrooke", "Trois-Rivières", "Saguenay", "Drummondville", "Saint-Jean-sur-Richelieu", "Chicoutimi"],
    //                     "British Columbia": ["Vancouver", "Victoria", "Surrey", "Burnaby", "Kelowna", "Abbotsford", "Kamloops", "Nanaimo", "Richmond", "Langley"],
    //                     "Alberta": ["Calgary", "Edmonton", "Red Deer", "Lethbridge", "St. Albert", "Sherwood Park", "Medicine Hat", "Grande Prairie", "Airdrie", "Fort McMurray"],
    //                     "Manitoba": ["Winnipeg", "Brandon", "Steinbach", "Thompson", "Portage la Prairie", "Winkler", "Selkirk", "Morden", "Dauphin", "The Pas"],
    //                     "Saskatchewan": ["Saskatoon", "Regina", "Moose Jaw", "Prince Albert", "Yorkton", "Swift Current", "Kindersley", "Humboldt", "Estevan", "North Battleford"],
    //                     "Nova Scotia": ["Halifax", "Sydney", "Dartmouth", "Truro", "New Glasgow", "Glace Bay", "Yarmouth", "Bridgewater", "Antigonish", "Lunenburg"],
    //                     "New Brunswick": ["Fredericton", "Saint John", "Moncton", "Miramichi", "Edmundston", "Bathurst", "Rothesay", "Dieppe", "Woodstock", "Campbellton"],
    //                     "Prince Edward Island": ["Charlottetown", "Summerside", "Montague", "Kings County", "Queens County", "Souris", "Alberton", "Borden-Carleton", "Cornwall", "Hunter River"],
    //                     "Newfoundland and Labrador": ["St. Johns", "Corner Brook", "Mount Pearl", "Gander", "Grand Falls-Windsor", "Stephenville", "Labrador City", "Happy Valley-Goose Bay", "Conception Bay South", "Torbay"]
    //                 }
    //             };

    //             // Preselect the country if a value is provided
    //             if ("' . $selectedCountry . '" !== "") {
    //                 document.getElementById("country").value = "' . $selectedCountry . '";
    //                 // Trigger the change event to load states
    //                 document.getElementById("country").dispatchEvent(new Event("change"));
    //             }
    //         </script>
    //     ';
    //     return $html;
    // }


    // public static function renderStateDropdownForUser($selectedCountry = null, $selectedState = null)
    // {
    //     $selectedState = old('state', $selectedState);
    //     $selectedCountry = old('country', $selectedCountry);

    //     $html = '<select name="state" id="state" class="form-select">';
    //     $html .= '<option value="">Select State</option>';
    //     $html .= '</select>';

    //     $html .= '
    //         <script>
    //             document.getElementById("country").addEventListener("change", function() {
    //                 const selectedCountry = this.value;
    //                 const stateDropdown = document.getElementById("state");
    //                 const cityDropdown = document.getElementById("city");

    //                 // Clear previous options
    //                 stateDropdown.innerHTML = "<option value=\'\'>Select State</option>";
    //                 cityDropdown.innerHTML = "<option value=\'\'>Select City</option>";

    //                 if (countryData[selectedCountry]) {
    //                     Object.keys(countryData[selectedCountry]).forEach(state => {
    //                         const option = document.createElement("option");
    //                         option.value = state;
    //                         option.text = state;
    //                         stateDropdown.appendChild(option);
    //                     });
    //                     // Preselect the state if a value is provided
    //                     if ("' . $selectedState . '" !== "") {
    //                         stateDropdown.value = "' . $selectedState . '";
    //                         // Trigger the change event to load cities
    //                         stateDropdown.dispatchEvent(new Event("change"));
    //                     }
    //                 }
    //             });

    //             // Trigger the change event for preselected country
    //             document.getElementById("country").dispatchEvent(new Event("change"));
    //         </script>
    //     ';

    //     return $html;
    // }


    // public static function renderCityDropdownForUser($selectedState = null, $selectedCity = null)
    // {
    //     $selectedState = old('state', $selectedState);
    //     $selectedCity = old('city', $selectedCity);


    //     $html = '<select name="city" id="city" class="form-select">';
    //     $html .= '<option value="">Select City</option>';
    //     $html .= '</select>';

    //     $html .= '
    //         <script>
    //             document.getElementById("state").addEventListener("change", function() {
    //                 const selectedCountry = document.getElementById("country").value;
    //                 const selectedState = this.value;
    //                 const cityDropdown = document.getElementById("city");

    //                 cityDropdown.innerHTML = "<option value=\'\'>Select City</option>";

    //                 if (countryData[selectedCountry] && countryData[selectedCountry][selectedState]) {
    //                     countryData[selectedCountry][selectedState].forEach(city => {
    //                         const option = document.createElement("option");
    //                         option.value = city;
    //                         option.text = city;
    //                         cityDropdown.appendChild(option);
    //                     });
    //                     // Preselect the city if a value is provided
    //                     if ("' . $selectedCity . '" !== "") {
    //                         cityDropdown.value = "' . $selectedCity . '";
    //                     }
    //                 }
    //             });

    //             // Trigger the change event for preselected state
    //             document.getElementById("state").dispatchEvent(new Event("change"));
    //         </script>
    //     ';

    //     return $html;
    // }



    // public static function renderCountryDropdown($selectedCountry = null)
    // {
    //     $selectedCountry = old('company_country', $selectedCountry);

    //     $countries = [
    //         'USA' => 'USA',
    //         'UK' => 'UK',
    //         'Canada' => 'Canada'
    //     ];

    //     $html = '<select name="company_country" id="company_country" class="form-select">';
    //     $html .= '<option value="">Select Country</option>';

    //     foreach ($countries as $code => $name) {
    //         $isSelected = $code == $selectedCountry ? 'selected' : '';
    //         $html .= '<option value="' . $code . '" ' . $isSelected . '>' . $name . '</option>';
    //     }

    //     $html .= '</select>';
    //     $html .= '
    //         <script>
    //             const countryData = {
    //                 "USA": {
    //                     "California": ["Irvine", "Los Angeles", "San Francisco", "San Diego", "Sacramento", "Fresno", "Long Beach", "Oakland", "Bakersfield", "Anaheim", "Santa Ana"],
    //                     "Texas": ["Houston", "Dallas", "Austin", "San Antonio", "Fort Worth", "El Paso", "Arlington", "Corpus Christi", "Plano", "Laredo"],
    //                     "Florida": ["Miami", "Orlando", "Tampa", "Jacksonville", "St. Petersburg", "Fort Lauderdale", "Tallahassee", "Hialeah", "Port St. Lucie", "Cape Coral"],
    //                     "New York": ["New York City", "Buffalo", "Rochester", "Albany", "Syracuse", "Yonkers", "Schenectady", "Binghamton", "Saratoga Springs", "Ithaca"],
    //                     "Illinois": ["Chicago", "Aurora", "Naperville", "Joliet", "Rockford", "Springfield", "Peoria", "Elgin", "Champaign", "Waukegan"],
    //                     "Pennsylvania": ["Philadelphia", "Pittsburgh", "Allentown", "Erie", "Reading", "Scranton", "Bethlehem", "Lancaster", "Harrisburg", "York"],
    //                     "Ohio": ["Columbus", "Cleveland", "Cincinnati", "Toledo", "Akron", "Dayton", "Parma", "Canton", "Youngstown", "Lorain"],
    //                     "Georgia": ["Atlanta", "Augusta", "Columbus", "Macon", "Savannah", "Athens", "Sandy Springs", "Roswell", "Albany", "Marietta"],
    //                     "Michigan": ["Detroit", "Grand Rapids", "Warren", "Sterling Heights", "Lansing", "Ann Arbor", "Flint", "Kalamazoo", "Saginaw", "Dearborn"],
    //                     "North Carolina": ["Charlotte", "Raleigh", "Greensboro", "Durham", "Winston-Salem", "Fayetteville", "Cary", "High Point", "Gastonia", "Chapel Hill"]
    //                 },
    //                 "UK": {
    //                     "England": ["London", "Manchester", "Birmingham", "Liverpool", "Nottingham", "Sheffield", "Leeds", "Newcastle", "Bradford", "Bristol"],
    //                     "Scotland": ["Edinburgh", "Glasgow", "Aberdeen", "Dundee", "Inverness", "Perth", "Stirling", "Ayr", "East Kilbride", "Livingston"],
    //                     "Wales": ["Cardiff", "Swansea", "Newport", "Wrexham", "Barry", "Cwmbran", "Bangor", "Colwyn Bay", "Aberystwyth", "Merthyr Tydfil"],
    //                     "Northern Ireland": ["Belfast", "Derry", "Lisburn", "Newtownabbey", "Bangor", "Omagh", "Craigavon", "Antrim", "Coleraine", "Armagh"]
    //                 },
    //                 "Canada": {
    //                     "Ontario": ["Toronto", "Ottawa", "Mississauga", "Brampton", "Hamilton", "London", "Markham", "Vaughan", "Kitchener", "Windsor"],
    //                     "Quebec": ["Montreal", "Quebec City", "Laval", "Gatineau", "Sherbrooke", "Trois-Rivières", "Saguenay", "Drummondville", "Saint-Jean-sur-Richelieu", "Chicoutimi"],
    //                     "British Columbia": ["Vancouver", "Victoria", "Surrey", "Burnaby", "Kelowna", "Abbotsford", "Kamloops", "Nanaimo", "Richmond", "Langley"],
    //                     "Alberta": ["Calgary", "Edmonton", "Red Deer", "Lethbridge", "St. Albert", "Sherwood Park", "Medicine Hat", "Grande Prairie", "Airdrie", "Fort McMurray"],
    //                     "Manitoba": ["Winnipeg", "Brandon", "Steinbach", "Thompson", "Portage la Prairie", "Winkler", "Selkirk", "Morden", "Dauphin", "The Pas"],
    //                     "Saskatchewan": ["Saskatoon", "Regina", "Moose Jaw", "Prince Albert", "Yorkton", "Swift Current", "Kindersley", "Humboldt", "Estevan", "North Battleford"],
    //                     "Nova Scotia": ["Halifax", "Sydney", "Dartmouth", "Truro", "New Glasgow", "Glace Bay", "Yarmouth", "Bridgewater", "Antigonish", "Lunenburg"],
    //                     "New Brunswick": ["Fredericton", "Saint John", "Moncton", "Miramichi", "Edmundston", "Bathurst", "Rothesay", "Dieppe", "Woodstock", "Campbellton"],
    //                     "Prince Edward Island": ["Charlottetown", "Summerside", "Montague", "Kings County", "Queens County", "Souris", "Alberton", "Borden-Carleton", "Cornwall", "Hunter River"],
    //                     "Newfoundland and Labrador": ["St. Johns", "Corner Brook", "Mount Pearl", "Gander", "Grand Falls-Windsor", "Stephenville", "Labrador City", "Happy Valley-Goose Bay", "Conception Bay South", "Torbay"]
    //                 }
    //             };

    //             // Preselect the country if a value is provided
    //             if ("' . $selectedCountry . '" !== "") {
    //                 document.getElementById("company_country").value = "' . $selectedCountry . '";
    //                 // Trigger the change event to load states
    //                 document.getElementById("company_country").dispatchEvent(new Event("change"));
    //             }
    //         </script>
    //     ';
    //     return $html;
    // }


    // public static function renderStateDropdown($selectedCountry = null, $selectedState = null)
    // {
    //     $selectedState = old('company_state', $selectedState);

    //     $html = '<select name="company_state" id="company_state" class="form-select">';
    //     $html .= '<option value="">Select State</option>';
    //     $html .= '</select>';

    //     $html .= '
    //         <script>
    //             document.getElementById("company_country").addEventListener("change", function() {
    //                 const selectedCountry = this.value;
    //                 const stateDropdown = document.getElementById("company_state");
    //                 const cityDropdown = document.getElementById("company_city");

    //                 // Clear previous options
    //                 stateDropdown.innerHTML = "<option value=\'\'>Select State</option>";
    //                 cityDropdown.innerHTML = "<option value=\'\'>Select City</option>";

    //                 if (countryData[selectedCountry]) {
    //                     Object.keys(countryData[selectedCountry]).forEach(state => {
    //                         const option = document.createElement("option");
    //                         option.value = state;
    //                         option.text = state;
    //                         stateDropdown.appendChild(option);
    //                     });
    //                     // Preselect the state if a value is provided
    //                     if ("' . $selectedState . '" !== "") {
    //                         stateDropdown.value = "' . $selectedState . '";
    //                         // Trigger the change event to load cities
    //                         stateDropdown.dispatchEvent(new Event("change"));
    //                     }
    //                 }
    //             });

    //             // Trigger the change event for preselected country
    //             document.getElementById("company_country").dispatchEvent(new Event("change"));
    //         </script>
    //     ';

    //     return $html;
    // }


    // public static function renderCityDropdown($selectedState = null, $selectedCity = null)
    // {
    //     $selectedCity = old('company_city', $selectedCity);

    //     $html = '<select name="company_city" id="company_city" class="form-select">';
    //     $html .= '<option value="">Select City</option>';
    //     $html .= '</select>';

    //     $html .= '
    //         <script>
    //             document.getElementById("company_state").addEventListener("change", function() {
    //                 const selectedCountry = document.getElementById("company_country").value;
    //                 const selectedState = this.value;
    //                 const cityDropdown = document.getElementById("company_city");

    //                 cityDropdown.innerHTML = "<option value=\'\'>Select City</option>";

    //                 if (countryData[selectedCountry] && countryData[selectedCountry][selectedState]) {
    //                     countryData[selectedCountry][selectedState].forEach(city => {
    //                         const option = document.createElement("option");
    //                         option.value = city;
    //                         option.text = city;
    //                         cityDropdown.appendChild(option);
    //                     });
    //                     // Preselect the city if a value is provided
    //                     if ("' . $selectedCity . '" !== "") {
    //                         cityDropdown.value = "' . $selectedCity . '";
    //                     }
    //                 }
    //             });

    //             // Trigger the change event for preselected state
    //             document.getElementById("company_state").dispatchEvent(new Event("change"));
    //         </script>
    //     ';

    //     return $html;
    // }







}
