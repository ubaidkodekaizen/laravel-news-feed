<?php

namespace App\Helpers;
use App\Models\Company;
use App\Models\User;
use App\Models\ProductService;

class DropDownHelper
{
   

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
    public static function searchFilter(){
        
        $company_positions = Company::pluck('company_position')->unique()->sort();
        $company_industries = Company::pluck('company_industry')->unique()->sort();
        $company_sub_categories = Company::pluck('company_sub_category')->unique()->sort();
        $company_business_types = Company::pluck('company_business_type')->unique()->sort();
        $company_no_of_employees = Company::pluck('company_no_of_employee')->unique()->sort();
        $company_states = User::pluck('state')->unique()->sort();
        $company_countries = User::pluck('country')->unique()->sort();
        $company_revenues = Company::pluck('company_revenue')->unique()->sort();
        $product_service_names = ProductService::pluck('product_service_name')->unique()->sort();
    
        return [
            'company_positions' => $company_positions,
            'company_industries' => $company_industries,
            'company_sub_categories' => $company_sub_categories,
            'company_business_types' => $company_business_types,
            'company_no_of_employees' => $company_no_of_employees,
            'company_states' => $company_states,
            'company_countries' => $company_countries,
            'company_revenues' => $company_revenues,
            'product_service_names' => $product_service_names,
        ];
    }
    
     
    public static function renderIndustryDropdown($selectedIndustry = null, $selectedSubcategory = null)
    {

        $selectedIndustry = old('company_industry', $selectedIndustry);
        $selectedSubcategory = old('company_sub_category', $selectedSubcategory);
    
        $industries = \DB::table('industries')->pluck('name', 'id');
    
        $html = '<select name="company_industry" id="company_industry" class="form-select">';
        $html .= '<option value="">Select Industry</option>';
    
        foreach ($industries as $industryId => $industryName) {
            $isSelected = $industryName == $selectedIndustry ? 'selected' : '';
            $html .= '<option value="' . $industryName . '" ' . $isSelected . '>' . $industryName . '</option>';
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
        $selectedIndustry = old('industry_to_connect', $selectedIndustry);
        $selectedSubcategory = old('sub_category_to_connect', $selectedSubcategory);
    
        $industries = \DB::table('industries')->pluck('name', 'id');
    
        $html = '<select name="industry_to_connect" id="industry_to_connect" class="form-select">';
        $html .= '<option value="">Select Industry</option>';
    
        foreach ($industries as $industryId => $industryName) {
            $isSelected = $industryName == $selectedIndustry ? 'selected' : '';
            $html .= '<option value="' . $industryName . '" ' . $isSelected . '>' . $industryName . '</option>';
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
