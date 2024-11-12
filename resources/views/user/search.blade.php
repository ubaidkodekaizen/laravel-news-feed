@extends('user.layouts.main')
@section('content')

<style>
    /* Basic styling for layout */
    .sidebar {
        width: 25%;
        background-color: #f8f9fa;
        padding: 20px;
        border-right: 1px solid #ddd;
        height: 100vh;
        overflow-y: auto;
    }
    .main-content {
        width: 75%;
        padding: 20px;
    }
    .user-card {
        background: #fff;
        border: 1px solid #ddd;
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

</style>


<div class="d-flex">
    <!-- Sidebar with filters -->
    
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
                    Sub - Category Industry <span class="toggle-icon">+</span>
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
    </div>
    

    <!-- Main Content Area -->
    <div class="main-content">
        <!-- User Details Card -->
        <div class="row">
            <div class="col-lg-12">

                <div class="user-card">
                    <div class="profile_name">
                        <div class="profile">
                            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" alt="User Image">
                        </div>
                        <div class="profile_details">
                            <a href="javascript:void(0);" class="user_name">Ubaid UR Rehman</a>
                            <p class="user_position">Chief Executive Officer | <a href="javascript:void(0);" class="user_company">Kode Kaizen</a></p>
                            <p class="user_location">Karāchi, Sindh, Pakistan</p>
                            <p class="user_exp">11 months in role | 11 months in company</p>
                            <p class="user_about"><strong>About: </strong> Passionate software engineer with a knack for problem-solving and a love for learning new languages and technologies.</p>
                        </div>
                    </div>
                    <div class="btn_flex">
                        <button class="btn btn-success btn-sm">View Profile</button>
                        <button class="btn btn-primary btn-sm">Connect on Linkedin</button>
                    </div>
                    {{-- <p class="mt-3">Experienced in software development, project management, and team leadership...</p> --}}
                </div>
            </div>
        </div>

    </div>
</div>


@endsection