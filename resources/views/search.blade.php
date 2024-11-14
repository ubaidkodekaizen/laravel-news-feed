@extends('user.layouts.main')
@section('content')

<style>
    /* Basic styling for layout */
    .sidebar {
        width: 25%;
        background-color: var(--primary);
        padding: 20px;
        border-right: 1px solid #ddd;
        height: 100vh;
        overflow-y: auto;
        color: var(--white);
    }
    .main-content {
        width: 75%;
        padding: 20px;
    }
    .user-card {
        background: #fff;
        border: 2px solid var(--secondary);
        border-radius: 8px;
        padding: 20px;
        margin-bottom: 15px;
    }
    .user-card img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        object-fit: cover;
        object-position: center;
    }
    .filter-group {
        margin-bottom: 15px;
    }
    .filter-section {
    margin-bottom: 20px;
    }

    .filter-header {
        font-weight: bold;
        font-size: 14px;
        display: flex;
        justify-content: space-between;
        cursor: pointer;
    }

    .selected-filters, .selected-filter-group {
        margin-bottom: 10px;
    }

    .selected-filter {
        display: inline-flex;
        align-items: center;
        padding: 5px 10px;
        background-color: #e9ecef;
        border-radius: 15px;
        margin: 5px 5px 5px 0;
        font-size: 12px;
        color: var(--primary);
    }

    .selected-filter i {
        margin-left: 5px;
        cursor: pointer;
    }

    .filter-search {
        width: 100%;
        padding: 5px;
        margin-bottom: 10px;
        border: 1px solid #ddd;
        border-radius: 5px;
    }

    .filter-option {
        cursor: pointer;
        padding: 5px 0;
    }
    .filter_heading{
        margin-bottom: 20px;
    }
    .profile_name{
        display: flex;
        align-items: flex-start;
        margin-bottom: 20px
    }
    .profile_details{
        margin-left: 20px;
        width: 75%;
    }
    .btn_flex {
        display: flex;
        align-items: center;
        gap: 20px;
        flex-wrap: wrap;
    }
    .profile_details .user_name {
        text-decoration: none;
        color: #0073b1;
        font-weight: 600;
        font-size: 21px;
    }

    .profile_details p {
        margin-bottom: 5px;
    }

    .profile_details .user_position {
        font-size: 17px;
        font-weight: 500;
    }

    .profile_details a {
        text-decoration: none;
        color: #666666;
    }

    .profile_details a:hover {
        color: #0073b1;
        text-decoration: underline;
    }
    .user-card{
        display: flex;
        align-items: flex-start;
    }
    .user-card .profile_name{
        width: 75%;
    }
    .user_about{
        white-space: nowrap;     
        overflow: hidden;         
        text-overflow: ellipsis;   
        width: 100%; 
    }
    .collapse.show {
        background: #8080805c;
        padding: 5px 10px;
        border-radius: 5px;
        margin-top: 10px;
    }

</style>


<div class="d-flex">
    <!-- Sidebar with filters -->
    
    {{-- <div class="sidebar">
        <h5 class="filter_heading">Filters</h5>
    
        <!-- Filter Sections -->
        <div id="filterContainer">
            <!-- Position Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#positionFilter">
                    Position <span class="toggle-icon">+</span>
                </div>
                <div id="positionFilter" class="collapse">
                    <!-- Selected Position Filters -->
                    <div class="selected-filter-group" id="selectedPositionFilters"></div>
                    
                    <!-- Search Input for Position Filter -->
                    <input type="text" class="filter-search" placeholder="Search Position..." oninput="filterOptions(this, 'positionFilterOptions')">
                    
                    <!-- Position Filter Options -->
                    <div id="positionFilterOptions">
                        <div class="filter-option" onclick="addFilter('Position', 'Manager', 'selectedPositionFilters')">Manager</div>
                        <div class="filter-option" onclick="addFilter('Position', 'Executive', 'selectedPositionFilters')">Executive</div>
                        <div class="filter-option" onclick="addFilter('Position', 'Director', 'selectedPositionFilters')">Director</div>
                    </div>
                </div>
            </div>
    
            <!-- Industry Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#industryFilter">
                    Industry <span class="toggle-icon">+</span>
                </div>
                <div id="industryFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedIndustryFilters"></div>
                    <input type="text" class="filter-search" placeholder="Search Industry..." oninput="filterOptions(this, 'industryFilterOptions')">
                    <div id="industryFilterOptions">
                        <div class="filter-option" onclick="addFilter('Industry', 'Tech', 'selectedIndustryFilters')">Tech</div>
                        <div class="filter-option" onclick="addFilter('Industry', 'Finance', 'selectedIndustryFilters')">Finance</div>
                        <div class="filter-option" onclick="addFilter('Industry', 'Healthcare', 'selectedIndustryFilters')">Healthcare</div>
                    </div>
                </div>
            </div>


            <!-- Sub - Category Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#industrySubCategoryFilter">
                    Sub - Industry <span class="toggle-icon">+</span>
                </div>
                <div id="industrySubCategoryFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedIndustrySubCategoryFilters"></div>
                    <input type="text" class="filter-search" placeholder="Search Industry..." oninput="filterOptions(this, 'industrySubCategoryFilterOptions')">
                    <div id="industrySubCategoryFilterOptions">
                        <div class="filter-option" onclick="addFilter('Industry', 'Tech', 'selectedIndustrySubCategoryFilters')">Tech</div>
                        <div class="filter-option" onclick="addFilter('Industry', 'Finance', 'selectedIndustrySubCategoryFilters')">Finance</div>
                        <div class="filter-option" onclick="addFilter('Industry', 'Healthcare', 'selectedIndustrySubCategoryFilters')">Healthcare</div>
                    </div>
                </div>
            </div>
    
            <!-- Business Type Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#businessTypeFilter">
                    Business Type <span class="toggle-icon">+</span>
                </div>
                <div id="businessTypeFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedBusinessTypeFilters"></div>
                    <input type="text" class="filter-search" placeholder="Search Business Type..." oninput="filterOptions(this, 'businessTypeFilterOptions')">
                    <div id="businessTypeFilterOptions">
                        <div class="filter-option" onclick="addFilter('Business Type', 'B2B', 'selectedBusinessTypeFilters')">B2B</div>
                        <div class="filter-option" onclick="addFilter('Business Type', 'B2C', 'selectedBusinessTypeFilters')">B2C</div>
                        <div class="filter-option" onclick="addFilter('Business Type', 'Non-Profit', 'selectedBusinessTypeFilters')">Non-Profit</div>
                    </div>
                </div>
            </div>
    
            <!-- Revenue Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#revenueFilter">
                    Revenue <span class="toggle-icon">+</span>
                </div>
                <div id="revenueFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedRevenueFilters"></div>
                    <input type="text" class="filter-search" placeholder="Search Revenue..." oninput="filterOptions(this, 'revenueFilterOptions')">
                    <div id="revenueFilterOptions">
                        <div class="filter-option" onclick="addFilter('Revenue', '$0-$1M', 'selectedRevenueFilters')">$0-$1M</div>
                        <div class="filter-option" onclick="addFilter('Revenue', '$1M-$10M', 'selectedRevenueFilters')">$1M-$10M</div>
                        <div class="filter-option" onclick="addFilter('Revenue', '$10M+', 'selectedRevenueFilters')">$10M+</div>
                    </div>
                </div>
            </div>
    
            <!-- Employees Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#employeesFilter">
                    Employees <span class="toggle-icon">+</span>
                </div>
                <div id="employeesFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedEmployeesFilters"></div>
                    <input type="text" class="filter-search" placeholder="Search Employees..." oninput="filterOptions(this, 'employeesFilterOptions')">
                    <div id="employeesFilterOptions">
                        <div class="filter-option" onclick="addFilter('Employees', '1-10', 'selectedEmployeesFilters')">1-10</div>
                        <div class="filter-option" onclick="addFilter('Employees', '10-50', 'selectedEmployeesFilters')">10-50</div>
                        <div class="filter-option" onclick="addFilter('Employees', '50+', 'selectedEmployeesFilters')">50+</div>
                    </div>
                </div>
            </div>

            <!-- Country Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#countryFilter">
                    Country <span class="toggle-icon">+</span>
                </div>
                <div id="countryFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedCountryFilters"></div>
                    <input type="text" class="filter-search" placeholder="Search Country..." oninput="filterOptions(this, 'countryFilterOptions')">
                    <div id="countryFilterOptions">
                        <div class="filter-option" onclick="addFilter('Country', 'New York', 'selectedCountryFilters')">New York</div>
                        <div class="filter-option" onclick="addFilter('Country', 'Los Angeles', 'selectedCountryFilters')">Los Angeles</div>
                        <div class="filter-option" onclick="addFilter('Country', 'Chicago', 'selectedCountryFilters')">Chicago</div>
                    </div>
                </div>
            </div>

            <!-- State Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#stateFilter">
                    State <span class="toggle-icon">+</span>
                </div>
                <div id="stateFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedStateFilters"></div>
                    <input type="text" class="filter-search" placeholder="Search State..." oninput="filterOptions(this, 'stateFilterOptions')">
                    <div id="stateFilterOptions">
                        <div class="filter-option" onclick="addFilter('State', 'New York', 'selectedStateFilters')">New York</div>
                        <div class="filter-option" onclick="addFilter('State', 'Los Angeles', 'selectedStateFilters')">Los Angeles</div>
                        <div class="filter-option" onclick="addFilter('State', 'Chicago', 'selectedStateFilters')">Chicago</div>
                    </div>
                </div>
            </div>

            <!-- City Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#cityFilter">
                    City <span class="toggle-icon">+</span>
                </div>
                <div id="cityFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedCityFilters"></div>
                    <input type="text" class="filter-search" placeholder="Search City..." oninput="filterOptions(this, 'cityFilterOptions')">
                    <div id="cityFilterOptions">
                        <div class="filter-option" onclick="addFilter('City', 'New York', 'selectedCityFilters')">New York</div>
                        <div class="filter-option" onclick="addFilter('City', 'Los Angeles', 'selectedCityFilters')">Los Angeles</div>
                        <div class="filter-option" onclick="addFilter('City', 'Chicago', 'selectedCityFilters')">Chicago</div>
                    </div>
                </div>
            </div>

            

            <!-- Product/Service Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#productServiceFilter">
                    Product/Service <span class="toggle-icon">+</span>
                </div>
                <div id="productServiceFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedProductServiceFilters"></div>
                    <input type="text" class="filter-search" placeholder="Search Product/Service..." oninput="filterOptions(this, 'productServiceFilterOptions')">
                    <div id="productServiceFilterOptions">
                        <div class="filter-option" onclick="addFilter('Product/Service', 'Consulting', 'selectedProductServiceFilters')">Consulting</div>
                        <div class="filter-option" onclick="addFilter('Product/Service', 'Software', 'selectedProductServiceFilters')">Software</div>
                        <div class="filter-option" onclick="addFilter('Product/Service', 'Marketing', 'selectedProductServiceFilters')">Marketing</div>
                    </div>
                </div>
            </div>

        </div>
    </div> --}}

    <div class="sidebar">
        <h5 class="filter_heading">Filters</h5>
    
        <!-- Filter Sections -->
        <div id="filterContainer">
            <!-- Position Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#positionFilter">
                    Position <span class="toggle-icon">+</span>
                </div>
                <div id="positionFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedPositionFilters"></div>
                    <input type="text" name="company_position" class="filter-search" placeholder="Search Position..." oninput="filterOptions(this, 'positionFilterOptions')">
                    <div id="positionFilterOptions">
                        @foreach ($company_positions as $position)
                            <div class="filter-option" onclick="addFilter('Position', '{{ $position }}', 'selectedPositionFilters')">{{ $position }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
    
            <!-- Industry Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#industryFilter">
                    Industry <span class="toggle-icon">+</span>
                </div>
                <div id="industryFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedIndustryFilters"></div>
                    <input type="text" name="company_industry"  class="filter-search" placeholder="Search Industry..." oninput="filterOptions(this, 'industryFilterOptions')">
                    <div id="industryFilterOptions">
                        @foreach ($company_industries as $industry)
                            <div class="filter-option" onclick="addFilter('Industry', '{{ $industry }}', 'selectedIndustryFilters')">{{ $industry }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
    
            <!-- Sub - Category Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#industrySubCategoryFilter">
                    Sub - Industry <span class="toggle-icon">+</span>
                </div>
                <div id="industrySubCategoryFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedIndustrySubCategoryFilters"></div>
                    <input type="text"  name="company_sub_category" class="filter-search" placeholder="Search Industry..." oninput="filterOptions(this, 'industrySubCategoryFilterOptions')">
                    <div id="industrySubCategoryFilterOptions">
                        @foreach ($company_sub_categories as $sub_category)
                            <div class="filter-option" onclick="addFilter('Sub-Category', '{{ $sub_category }}', 'selectedIndustrySubCategoryFilters')">{{ $sub_category }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
    
            <!-- Business Type Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#businessTypeFilter">
                    Business Type <span class="toggle-icon">+</span>
                </div>
                <div id="businessTypeFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedBusinessTypeFilters"></div>
                    <input type="text"  name="company_business_type" class="filter-search" placeholder="Search Business Type..." oninput="filterOptions(this, 'businessTypeFilterOptions')">
                    <div id="businessTypeFilterOptions">
                        @foreach ($company_business_types as $business_type)
                            <div class="filter-option" onclick="addFilter('Business Type', '{{ $business_type }}', 'selectedBusinessTypeFilters')">{{ $business_type }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
    
            <!-- Revenue Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#revenueFilter">
                    Revenue <span class="toggle-icon">+</span>
                </div>
                <div id="revenueFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedRevenueFilters"></div>
                    <input type="text"  name="company_revenue" class="filter-search" placeholder="Search Revenue..." oninput="filterOptions(this, 'revenueFilterOptions')">
                    <div id="revenueFilterOptions">
                        @foreach ($company_revenues as $revenue)
                            <div class="filter-option" onclick="addFilter('Revenue', '{{ $revenue }}', 'selectedRevenueFilters')">{{ $revenue }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
    
            <!-- Employees Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#employeesFilter">
                    Employees <span class="toggle-icon">+</span>
                </div>
                <div id="employeesFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedEmployeesFilters"></div>
                    <input type="text"  name="company_no_of_employee" class="filter-search" placeholder="Search Employees..." oninput="filterOptions(this, 'employeesFilterOptions')">
                    <div id="employeesFilterOptions">
                        @foreach ($company_no_of_employees as $employee_count)
                            <div class="filter-option" onclick="addFilter('Employees', '{{ $employee_count }}', 'selectedEmployeesFilters')">{{ $employee_count }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
    
            <!-- Country Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#countryFilter">
                    Country <span class="toggle-icon">+</span>
                </div>
                <div id="countryFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedCountryFilters"></div>
                    <input type="text"  name="company_country" class="filter-search" placeholder="Search Country..." oninput="filterOptions(this, 'countryFilterOptions')">
                    <div id="countryFilterOptions">
                        @foreach ($company_countries as $country)
                            <div class="filter-option" onclick="addFilter('Country', '{{ $country }}', 'selectedCountryFilters')">{{ $country }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
    
            <!-- State Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#stateFilter">
                    State <span class="toggle-icon">+</span>
                </div>
                <div id="stateFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedStateFilters"></div>
                    <input type="text"  name="company_state" class="filter-search" placeholder="Search State..." oninput="filterOptions(this, 'stateFilterOptions')">
                    <div id="stateFilterOptions">
                        @foreach ($company_states as $state)
                            <div class="filter-option" onclick="addFilter('State', '{{ $state }}', 'selectedStateFilters')">{{ $state }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
    
            <!-- City Filter Section -->
            {{-- <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#cityFilter">
                    City <span class="toggle-icon">+</span>
                </div>
                <div id="cityFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedCityFilters"></div>
                    <input type="text" class="filter-search" placeholder="Search City..." oninput="filterOptions(this, 'cityFilterOptions')">
                    <div id="cityFilterOptions">
                        @foreach ($company_cities as $city)
                            <div class="filter-option" onclick="addFilter('City', '{{ $city }}', 'selectedCityFilters')">{{ $city }}</div>
                        @endforeach
                    </div>
                </div>
            </div> --}}
    
            <!-- Product/Service Filter Section -->
            <div class="filter-section">
                <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#productServiceFilter">
                    Product/Service <span class="toggle-icon">+</span>
                </div>
                <div id="productServiceFilter" class="collapse">
                    <div class="selected-filter-group" id="selectedProductServiceFilters"></div>
                    <input type="text"  name="product_service_name" class="filter-search" placeholder="Search Product/Service..." oninput="filterOptions(this, 'productServiceFilterOptions')">
                    <div id="productServiceFilterOptions">
                        @foreach ($product_service_names as $product_service)
                            <div class="filter-option" onclick="addFilter('Product/Service', '{{ $product_service }}', 'selectedProductServiceFilters')">{{ $product_service }}</div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- User Details Card -->
        <div class="row">
            <div class="col-lg-12">

                @forelse($users as $user)
                <div class="user-card">
                    <div class="profile_name">
                        <div class="profile">
                            <img src="{{ $user->photo ? Storage::url($user->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}" alt="User Image">

                        </div>
                        <div class="profile_details">
                            <a href="{{ route('user.profile', ['slug' => $user->slug]) }}" target="_blank" class="user_name">{{ $user->first_name }} {{ $user->last_name }}</a>

                            <p class="user_position">{{$user->company->company_position}} at <a href="{{ route('company.profile', ['companySlug' => $user->company->company_slug]) }}" class="user_company">{{$user->company->company_name}}</a></p>
                            <p class="user_location">{{$user->city}}, {{$user->state}}, {{$user->country}}</p>
                            {{-- <p class="user_exp">11 months in role | 11 months in company</p> --}}
                            <p class="user_about"><strong>Industry: </strong> {{$user->company->company_sub_category}}, {{$user->company->company_industry}}</p>
                        </div>
                    </div>
                    <div class="btn_flex">
                        <a href="{{ route('user.profile', ['slug' => $user->slug]) }}" target="_blank" class="btn btn-success btn-sm">View Profile</a>
                        <a href="{{$user->linkedin_url}}" target="_blank" class="btn btn-primary btn-sm">Connect on Linkedin</a>
                    </div>
                    {{-- <p class="mt-3">Experienced in software development, project management, and team leadership...</p> --}}
                </div>
                @empty
                <div class="user-card">

                    <h2> No User Found </h2>
                    
                </div>
                @endforelse

            </div>

            <div class="pagination">
                {{ $users->links() }}
            </div>
        </div>

    </div>
</div>
<script>
    // public/js/search.js

$(document).ready(function() {
    // Trigger when any filter changes
    $('.filter').on('change', function() {
        var filters = getFilters();  // Get all filter values
        $.ajax({
            url: '{{ route('search') }}', // The search route
            method: 'GET',
            data: filters, // Send the filter values
            success: function(response) {
                $('#search-results').html(response);  // Update the results section with new data
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', error);
            }
        });
    });

    // Function to get filter values
    function getFilters() {
        return {
            company_position: $('#company_position').val(),
            company_industry: $('#company_industry').val(),
            company_sub_category: $('#company_sub_category').val(),
            company_business_type: $('#company_business_type').val(),
            company_no_of_employee: $('#company_no_of_employee').val(),
            company_revenue: $('#company_revenue').val(),
            company_country: $('#company_country').val(),
            company_state: $('#company_state').val(),
            product_service_name: $('#product_service_name').val()
        };
    }
});

    </script>

@endsection