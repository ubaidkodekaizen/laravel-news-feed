@extends('layouts.main')

@section('styles')
    <style>
        body {
            overflow: hidden !important;
        }

        .pagination-container {
            margin: 40px 0 60px 0;
        }

        .avatar-initials {
            width: 110px;
            height: 110px;
            border-radius: 50%;
            background: #394a93;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
            letter-spacing: 1px;
            border: 8px solid #fff;
        }

        .pagination-wrapper {
            flex: 1;
            display: flex;
            justify-content: end;
            order: 3;
        }

        #recordsInfo {
            white-space: nowrap;
            min-width: 150px;
            text-align: right;
            order: 2;
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 17.33px;
            line-height: 100%;
            color: #696969;
        }

        .recordPerPageBox {
            order: 1;
        }

        .recordPerPageBox span {
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 17.33px;
            line-height: 100%;
            color: #696969;
        }

        #perPageSelect {
            font-family: "Inter", sans-serif;
            font-weight: 300;
            font-size: 18.67px;
            line-height: 100%;
            letter-spacing: 0px;
            color: #2C2C2C;
            padding: 10px 30px 10px 20px;
            border: 1.33px solid #E1E0E0;
            border-radius: 10.67px;
        }

        /* Initially hide State and County filters */
        [data-bs-target="#stateFilter"],
        [data-bs-target="#countyFilter"] {
            display: none;
        }

        @media(max-width: 1250px) {
            .pagination-container-inner {
                flex-direction: column;
            }
        }

        @media (max-width: 480px) {
            .recordPerPageBox span {
                font-size: 16px;
            }

            #recordsInfo {
                font-size: 16px;
            }

            #perPageSelect {
                font-size: 16px;
            }

            .pagination .page-link {
                font-size: 16px !important;
                padding: 8px 12px !important;
            }
        }
    </style>
@endsection


@section('content')
    <div class="navbar_d_flex">
        <!-- Toggler Button -->
        <button class="sidebar-toggler btn btn-primary d-lg-none" type="button" onclick="toggleSidebar()">
            Filters
        </button>
        <div id="sidebarMain">


            <div class="sidebar" id="sidebar">

                <div id="filterContainer">

                    @php
                        $filters = \App\Helpers\DropDownHelper::searchFilter();
                    @endphp
                    <h5 class="filter_heading subheading_line" data-bs-toggle="tooltip" data-bs-placement="right" title="Use this section to find professionals based on their career and business information. Filter by job titles, years of experience, company types, revenue ranges, and the products or services they offer.">Professional Information</h5>
                    <div class="filterContainerInner">
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse" data-bs-target="#positionFilter">
                                Professional Position <span class="toggle-icon">+</span>
                            </div>
                            <div id="positionFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedPositionFilters"></div>

                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="positionSearchInput" name="company_position"
                                        class="filter-search" placeholder="Search Position..."
                                        oninput="filterOptions(this, 'positionFilterOptions')">

                                    <div id="positionFilterOptions" style="display: none">
                                        @foreach ($filters['company_positions'] as $position)
                                            <div class="filter-option"
                                                onclick="addFilter('company_position', '{{ $position }}', 'selectedPositionFilters', 'positionSearchInput')">
                                                {{ $position }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Employees Filter Section -->
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#experienceFilter">
                                Years of Professional Experience <span class="toggle-icon">+</span>
                            </div>
                            <div id="experienceFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedExperienceFilters">
                                </div>

                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="experienceSearchInput" name="company_experience"
                                        class="filter-search" placeholder="Search Experience..."
                                        oninput="filterOptions(this, 'experienceFilterOptions')">
                                    <div id="experienceFilterOptions">

                                        @foreach ($filters['company_experiences'] as $experience)
                                            <div class="filter-option"
                                                onclick="addFilter('company_experience', '{{ $experience }}', 'selectedExperienceFilters', 'experienceSearchInput')">
                                                {{ $experience }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Type Filter Section -->
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#businessTypeFilter">
                                Company Type <span class="toggle-icon">+</span>
                            </div>
                            <div id="businessTypeFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedBusinessTypeFilters"></div>

                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="businessTypeSearchInput" name="company_business_type"
                                        class="filter-search" placeholder="Search Business Type..."
                                        oninput="filterOptions(this, 'businessTypeFilterOptions')">
                                    <div id="businessTypeFilterOptions">
                                        @foreach ($filters['company_business_types'] as $business_type)
                                            <div class="filter-option"
                                                onclick="addFilter('company_business_type', '{{ $business_type }}', 'selectedBusinessTypeFilters', 'businessTypeSearchInput')">
                                                {{ $business_type }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Revenue Filter Section -->
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse" data-bs-target="#revenueFilter">
                                Annual Revenue <span class="toggle-icon">+</span>
                            </div>
                            <div id="revenueFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedRevenueFilters"></div>


                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="revenueSearchInput" name="company_revenue"
                                        class="filter-search" placeholder="Search Revenue..."
                                        oninput="filterOptions(this, 'revenueFilterOptions')">
                                    <div id="revenueFilterOptions">
                                        @foreach ($filters['company_revenues'] as $revenue)
                                            <div class="filter-option"
                                                onclick="addFilter('company_revenue', '{{ $revenue }}', 'selectedRevenueFilters', 'revenueSearchInput')">
                                                ${{ $revenue }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>

                            </div>
                        </div>

                        <!-- Product/Service Filter Section -->
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse" data-bs-target="#productFilter">
                                Products <span class="toggle-icon">+</span>
                            </div>
                            <div id="productFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedProductFilters"></div>

                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="productSearchInput" name="product" class="filter-search"
                                        placeholder="Search Product..."
                                        oninput="filterOptions(this, 'productFilterOptions')">
                                    <div id="productFilterOptions">
                                        @foreach ($filters['products'] as $product)
                                            <div class="filter-option"
                                                onclick="addFilter('product', '{{ $product }}', 'selectedProductFilters', 'productSearchInput')">
                                                {{ $product }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#serviceFilter">
                                Services <span class="toggle-icon">+</span>
                            </div>
                            <div id="serviceFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedServiceFilters"></div>

                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="serviceSearchInput" name="service" class="filter-search"
                                        placeholder="Search Service..."
                                        oninput="filterOptions(this, 'serviceFilterOptions')">
                                    <div id="serviceFilterOptions">
                                        @foreach ($filters['services'] as $service)
                                            <div class="filter-option"
                                                onclick="addFilter('service', '{{ $service }}', 'selectedServiceFilters', 'serviceSearchInput')">
                                                {{ $service }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>

                    <!-- ICP Section -->
                    <h5 class="filter_heading subheading_line mt-5" data-bs-toggle="tooltip" data-bs-placement="right" title="Use this section to identify your ideal customers by filtering companies based on their industry sector, organization size, geographic location, current business challenges, strategic goals, organizational attributes, and whether they have decision-making authority for purchases.">Ideal Customer Profile (ICP)</h5>
                    <div class="filterContainerInner">
                        <!-- Industry Filter Section -->
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#industryFilter">
                                Industry Sector <span class="toggle-icon">+</span>
                            </div>
                            <div id="industryFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedIndustryFilters">

                                </div>
                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="industrySearchInput" name="company_industry"
                                        class="filter-search" placeholder="Search Industry..."
                                        oninput="filterOptions(this, 'industryFilterOptions')">
                                    <div id="industryFilterOptions" style="display: none">

                                        @foreach ($filters['company_industries'] as $industry)
                                            <div class="filter-option"
                                                onclick="addFilter('company_industry', '{{ $industry }}', 'selectedIndustryFilters', 'industrySearchInput')">
                                                {{ $industry }}
                                            </div>
                                        @endforeach
                                    </div>

                                </div>

                            </div>
                        </div>

                        <!-- Employees Filter Section -->
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#employeesFilter">
                                Organization Size <span class="toggle-icon">+</span>
                            </div>
                            <div id="employeesFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedEmployeesFilters"></div>


                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="employeesSearchInput" name="company_no_of_employee"
                                        class="filter-search" placeholder="Search Company Size..."
                                        oninput="filterOptions(this, 'employeesFilterOptions')">
                                    <div id="employeesFilterOptions">
                                        @foreach ($filters['company_no_of_employees'] as $employee_count)
                                            <div class="filter-option"
                                                onclick="addFilter('company_no_of_employee', '{{ $employee_count }}', 'selectedEmployeesFilters', 'employeesSearchInput')">
                                                {{ $employee_count }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Location Filter Section -->
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#businessLocationFilter">
                                Geographic Location <span class="toggle-icon">+</span>
                            </div>
                            <div id="businessLocationFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedBusinessLocationFilters"></div>

                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="businessLocationSearchInput" name="business_location"
                                        class="filter-search" placeholder="Search Business Location..."
                                        oninput="filterOptions(this, 'businessLocationFilterOptions')">
                                    <div id="businessLocationFilterOptions">
                                        @foreach ($filters['business_locations'] ?? [] as $location)
                                            <div class="filter-option"
                                                onclick="addFilter('business_location', '{{ $location }}', 'selectedBusinessLocationFilters', 'businessLocationSearchInput')">
                                                {{ $location }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Challenges Filter Section -->
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#businessChallengesFilter">
                                Current Business Challenges <span class="toggle-icon">+</span>
                            </div>
                            <div id="businessChallengesFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedBusinessChallengesFilters"></div>

                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="businessChallengesSearchInput"
                                        name="company_current_business_challenges" class="filter-search"
                                        placeholder="Search Business Challenges..."
                                        oninput="filterOptions(this, 'businessChallengesFilterOptions')">
                                    <div id="businessChallengesFilterOptions">
                                        @foreach ($filters['business_challenges'] ?? [] as $challenge)
                                            <div class="filter-option"
                                                onclick="addFilter('company_current_business_challenges', '{{ $challenge }}', 'selectedBusinessChallengesFilters', 'businessChallengesSearchInput')">
                                                {{ $challenge }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Business Goals Filter Section -->
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#businessGoalsFilter">
                                Strategic Business Goals <span class="toggle-icon">+</span>
                            </div>
                            <div id="businessGoalsFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedBusinessGoalsFilters"></div>

                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="businessGoalsSearchInput" name="company_business_goals"
                                        class="filter-search" placeholder="Search Business Goals..."
                                        oninput="filterOptions(this, 'businessGoalsFilterOptions')">
                                    <div id="businessGoalsFilterOptions">
                                        @foreach ($filters['business_goals'] ?? [] as $goal)
                                            <div class="filter-option"
                                                onclick="addFilter('company_business_goals', '{{ $goal }}', 'selectedBusinessGoalsFilters', 'businessGoalsSearchInput')">
                                                {{ $goal }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Company Attributes Filter Section -->
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#companyAttributesFilter">
                                Organizational Attributes <span class="toggle-icon">+</span>
                            </div>
                            <div id="companyAttributesFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedCompanyAttributesFilters"></div>

                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="companyAttributesSearchInput" name="company_attributes"
                                        class="filter-search" placeholder="Search Company Attributes..."
                                        oninput="filterOptions(this, 'companyAttributesFilterOptions')">
                                    <div id="companyAttributesFilterOptions">
                                        @foreach ($filters['company_attributes'] ?? [] as $attribute)
                                            <div class="filter-option"
                                                onclick="addFilter('company_attributes', '{{ $attribute }}', 'selectedCompanyAttributesFilters', 'companyAttributesSearchInput')">
                                                {{ $attribute }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Decision Maker Filter Section -->
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#decisionMakerFilter">
                                Decision-Making Authority <span class="toggle-icon">+</span>
                            </div>
                            <div id="decisionMakerFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedDecisionMakerFilters"></div>

                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="decisionMakerSearchInput" name="is_decision_maker"
                                        class="filter-search" placeholder="Search Decision Maker..."
                                        oninput="filterOptions(this, 'decisionMakerFilterOptions')"
                                        style="display: none;">
                                    <div id="decisionMakerFilterOptions" style="display: block;">
                                        <div class="filter-option"
                                            onclick="addFilter('is_decision_maker', 'Yes', 'selectedDecisionMakerFilters', 'decisionMakerSearchInput')">
                                            Yes
                                        </div>
                                        <div class="filter-option"
                                            onclick="addFilter('is_decision_maker', 'No', 'selectedDecisionMakerFilters', 'decisionMakerSearchInput')">
                                            No
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                    <!-- Position Filter Section -->


                    <h5 class="filter_heading  subheading_line mt-5" data-bs-toggle="tooltip" data-bs-placement="right" title="Use this section to find people based on personal characteristics. Filter by the type of person you want to connect with, their gender, age range, marital status, and ethnic background to refine your search results.">Personal Information</h5>

                    <div class="filterContainerInner">
                        {{-- Position Filter --}}
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#userPositionFilter">
                                Target Connection Profile <span class="toggle-icon">+</span>
                            </div>
                            <div id="userPositionFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedUserPositionFilters"></div>


                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="userPositionSearchInput" name="user_position"
                                        class="filter-search" placeholder="Search type of person you want to connect..."
                                        oninput="filterOptions(this, 'userPositionFilterOptions')">
                                    <div id="userPositionFilterOptions">
                                        @foreach ($filters['user_position'] as $position)
                                            <div class="filter-option"
                                                onclick="addFilter('user_position', '{{ $position }}', 'selectedUserPositionFilters', 'userPositionSearchInput')">
                                                {{ $position }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>



                        {{-- Gender Filter --}}
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#genderFilter">
                                Gender <span class="toggle-icon">+</span>
                            </div>
                            <div id="genderFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedGenderFilters"></div>


                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="genderSearchInput" name="user_gender"
                                        class="filter-search" placeholder="Search Gender..."
                                        oninput="filterOptions(this, 'genderFilterOptions')">
                                    <div id="genderFilterOptions">
                                        @foreach ($filters['user_gender'] as $gender)
                                            <div class="filter-option"
                                                onclick="addFilter('user_gender', '{{ $gender }}', 'selectedGenderFilters', 'genderSearchInput')">
                                                {{ $gender }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Age Group Filter --}}
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#ageGroupFilter">
                                Age Range <span class="toggle-icon">+</span>
                            </div>
                            <div id="ageGroupFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedAgeGroupFilters"></div>


                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="ageGroupSearchInput" name="user_age_group"
                                        class="filter-search" placeholder="Search Age Group..."
                                        oninput="filterOptions(this, 'ageGroupFilterOptions')">
                                    <div id="ageGroupFilterOptions">
                                        @foreach ($filters['user_age_group'] as $ageGroup)
                                            <div class="filter-option"
                                                onclick="addFilter('user_age_group', '{{ $ageGroup }}', 'selectedAgeGroupFilters', 'ageGroupSearchInput')">
                                                {{ $ageGroup }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Marital Status Filter --}}
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#maritalStatusFilter">
                                Marital Status <span class="toggle-icon">+</span>
                            </div>
                            <div id="maritalStatusFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedMaritalStatusFilters"></div>


                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="maritalStatusSearchInput" name="marital_status"
                                        class="filter-search" placeholder="Search Marital Status..."
                                        oninput="filterOptions(this, 'maritalStatusFilterOptions')">
                                    <div id="maritalStatusFilterOptions">
                                        @foreach ($filters['user_marital_status'] as $maritalStatus)
                                            <div class="filter-option"
                                                onclick="addFilter('marital_status', '{{ $maritalStatus }}', 'selectedMaritalStatusFilters', 'maritalStatusSearchInput')">
                                                {{ $maritalStatus }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Ethnicity Filter --}}
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#ethnicityFilter">
                                Ethnicity <span class="toggle-icon">+</span>
                            </div>
                            <div id="ethnicityFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedEthnicityFilters"></div>


                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="ethnicitySearchInput" name="user_ethnicity"
                                        class="filter-search" placeholder="Search Ethnicity..."
                                        oninput="filterOptions(this, 'ethnicityFilterOptions')">
                                    <div id="ethnicityFilterOptions">
                                        @foreach ($filters['user_ethnicity'] as $ethnicity)
                                            <div class="filter-option"
                                                onclick="addFilter('user_ethnicity', '{{ $ethnicity }}', 'selectedEthnicityFilters', 'ethnicitySearchInput')">
                                                {{ $ethnicity }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <h5 class="filter_heading subheading_line mt-5" data-bs-toggle="tooltip" data-bs-placement="right" title="Use this section to narrow down your search by location. Filter users based on their nationality, country, state or province, and city to find connections in specific geographic regions.">Geographical Information</h5>
                    <div class="filterContainerInner">
                        {{-- Nationality Filter --}}

                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#nationalityFilter">
                                Nationality <span class="toggle-icon">+</span>
                            </div>
                            <div id="nationalityFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedNationalityFilters"></div>


                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="nationalitySearchInput" name="user_nationality"
                                        class="filter-search" placeholder="Search Nationality..."
                                        oninput="filterOptions(this, 'nationalityFilterOptions')">
                                    <div id="nationalityFilterOptions">
                                        @foreach ($filters['user_nationality'] as $nationality)
                                            <div class="filter-option"
                                                onclick="addFilter('user_nationality', '{{ $nationality }}', 'selectedNationalityFilters', 'nationalitySearchInput')">
                                                {{ $nationality }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>


                        <!-- Country Filter Section -->
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#countryFilter">
                                Country <span class="toggle-icon">+</span>
                            </div>
                            <div id="countryFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedCountryFilters"></div>

                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="countrySearchInput" name="company_country"
                                        class="filter-search" placeholder="Search Country..."
                                        oninput="filterOptions(this, 'countryFilterOptions')">
                                    <div id="countryFilterOptions">
                                        @foreach ($filters['company_countries'] as $country)
                                            <div class="filter-option"
                                                onclick="addFilter('country', '{{ $country }}', 'selectedCountryFilters', 'countrySearchInput')">
                                                {{ $country }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- State Filter Section -->
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse" data-bs-target="#stateFilter">
                                State <span class="toggle-icon">+</span>
                            </div>
                            <div id="stateFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedStateFilters"></div>

                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="stateSearchInput" name="company_state"
                                        class="filter-search" placeholder="Search State..."
                                        oninput="filterOptions(this, 'stateFilterOptions')">
                                    <div id="stateFilterOptions">
                                        @foreach ($filters['company_states'] as $state)
                                            <div class="filter-option"
                                                onclick="addFilter('state', '{{ $state }}', 'selectedStateFilters', 'stateSearchInput')">
                                                {{ $state }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- City Filter --}}
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse" data-bs-target="#cityFilter">
                                City <span class="toggle-icon">+</span>
                            </div>
                            <div id="cityFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedCityFilters"></div>

                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="citySearchInput" name="user_city" class="filter-search"
                                        placeholder="Search City..." oninput="filterOptions(this, 'cityFilterOptions')">
                                    <div id="cityFilterOptions">
                                        @foreach ($filters['user_city'] as $city)
                                            <div class="filter-option"
                                                onclick="addFilter('user_city', '{{ $city }}', 'selectedCityFilters', 'citySearchInput')">
                                                {{ $city }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- County Filter --}}
                        <div class="filter-section">
                            <div class="filter-header collapsed" data-bs-toggle="collapse"
                                data-bs-target="#countyFilter">
                                County <span class="toggle-icon">+</span>
                            </div>
                            <div id="countyFilter" class="filterCollapseBox collapse">
                                <div class="selected-filter-group" id="selectedCountyFilters"></div>

                                <div class="searchBarAndFilterCon">
                                    <input type="text" id="countySearchInput" name="user_county"
                                        class="filter-search" placeholder="Search County..."
                                        oninput="filterOptions(this, 'countyFilterOptions')">
                                    <div id="countyFilterOptions">
                                        @foreach ($filters['user_county'] as $county)
                                            <div class="filter-option"
                                                onclick="addFilter('user_county', '{{ $county }}', 'selectedCountyFilters', 'countySearchInput')">
                                                {{ $county }}
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>




                </div>
            </div>
        </div>

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- User Details Card - THIS gets replaced by AJAX -->
            <div class="row g-4" id="userResults">
                @include('partial.search-result', ['users' => $users])
            </div>
        </div>

    </div>

    <!-- Main Modal -->
    <div class="modal fade" id="mainModal" tabindex="-1" aria-labelledby="mainModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--primary); color: #fff;">
                    <h5 class="modal-title" id="mainModalLabel">Send Direct Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="directMessageForm">
                        <input type="hidden" name="receiver_id" id="receiver_id" value="">
                        <!-- Receiver ID will be set dynamically -->

                        <div class="mb-3">
                            <label for="messageContent" class="form-label">Your Message</label>
                            <textarea class="form-control" id="messageContent" name="content" rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 ">Send Message</button>
                    </form>
                    <div id="messageStatus" class="mt-3 text-center"></div> <!-- Status Message -->
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            if (sidebar) {
                sidebar.classList.toggle("open");
            } else {
                console.error("Sidebar element not found!");
            }
        }

        let filters = {};

        // Debounce function to delay execution
        function debounce(func, delay) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), delay);
            };
        }

        // Sync header filters into the global filters object
        function syncHeaderFilters() {
            const searchForm = document.getElementById('search_form');
            if (searchForm) {
                const headerForm = new FormData(searchForm);
                for (const [key, value] of headerForm.entries()) {
                    if (value) {
                        filters[key] = Array.isArray(filters[key]) ? [...filters[key], value] : [value];
                    }
                }
            }
        }

        // Function to check if USA/US is selected and toggle State/County visibility
        function toggleUSFilters() {
            const countryFilters = filters['country'] || [];
            const isUSASelected = countryFilters.some(country =>
                country.toLowerCase() === 'usa' ||
                country.toLowerCase() === 'us' ||
                country.toLowerCase() === 'united states' ||
                country.toLowerCase() === 'united states of america'
            );

            const stateFilter = document.querySelector('[data-bs-target="#stateFilter"]').closest('.filter-section');
            const countyFilter = document.querySelector('[data-bs-target="#countyFilter"]').closest('.filter-section');

            if (isUSASelected) {
                stateFilter.style.display = 'block';
                countyFilter.style.display = 'block';
            } else {
                stateFilter.style.display = 'none';
                countyFilter.style.display = 'none';

                // Clear state and county filters if they exist
                if (filters['state']) {
                    delete filters['state'];
                    document.getElementById('selectedStateFilters').innerHTML = '';
                }
                if (filters['user_county']) {
                    delete filters['user_county'];
                    document.getElementById('selectedCountyFilters').innerHTML = '';
                }
            }
        }

        // Function to attach per page selector event
        function attachPerPageEvent() {
            const perPageSelect = document.getElementById('perPageSelect');
            if (perPageSelect) {
                // Remove old event listener by cloning (prevents duplicate listeners)
                const newPerPageSelect = perPageSelect.cloneNode(true);
                perPageSelect.parentNode.replaceChild(newPerPageSelect, perPageSelect);

                // Attach new event listener
                newPerPageSelect.addEventListener('change', function() {
                    const perPage = this.value;
                    console.log('Per page changed to:', perPage);

                    // Update filter
                    filters['per_page'] = perPage;

                    // Reset to page 1 when changing per page
                    applyFilters(1);
                });
            }
        }

        // Apply filters and refresh results
        function applyFilters(page = 1) {
            syncHeaderFilters();

            // Add page to filters
            const requestData = {
                ...filters,
                page: page
            };

            // Ensure per_page is included if it exists
            if (filters.per_page) {
                requestData.per_page = filters.per_page;
            }

            let newUrl = `${window.location.origin}${window.location.pathname}?${jQuery.param(requestData, true)}`;
            window.history.pushState({
                path: newUrl
            }, '', newUrl);

            jQuery.ajax({
                url: window.location.pathname,
                data: requestData,
                method: 'GET',
                success: function(response) {
                    jQuery('#userResults').html(response);
                    // Reattach per page event after AJAX load
                    attachPerPageEvent();
                    // Reinitialize direct message buttons after AJAX load
                    initDirectMessageButtons();
                },
                error: function(error) {
                    console.error('Error fetching results:', error);
                    alert('Failed to apply filters. Please try again.');
                }
            });
        }

        const debouncedApplyFilters = debounce(applyFilters, 300);

        // Add filter
        function addFilter(category, value, targetId, inputId) {
            const targetElement = document.getElementById(targetId);
            const searchInput = document.getElementById(inputId);

            if (!targetElement || !searchInput) {
                console.error("Missing target element or search input:", {
                    targetId,
                    inputId
                });
                return;
            }

            if (!document.getElementById(`${category}-${value}`)) {
                const filter = document.createElement('div');
                filter.className = 'selected-filter';
                filter.id = `${category}-${value}`;
                filter.innerHTML =
                    `${value} <i class="fa fa-times" onclick="removeFilter('${category}', '${value}', '${targetId}', '${inputId}')"></i>`;
                targetElement.appendChild(filter);

                if (!filters[category]) {
                    filters[category] = [];
                }
                filters[category].push(value);

                // Hide the selected option from the dropdown
                const option = document.querySelector(`.filter-option[onclick*="'${value}'"]`);
                if (option) {
                    option.style.display = 'none';
                } else {
                    console.warn("Option not found for hiding:", value);
                }

                // Clear search input
                searchInput.value = '';
            }

            // Check if country filter changed and toggle US-specific filters
            if (category === 'country') {
                toggleUSFilters();
            }

            debouncedApplyFilters();
        }

        // Remove filter
        function removeFilter(category, value, targetId, inputId) {
            const filter = document.getElementById(`${category}-${value}`);
            if (filter) {
                document.getElementById(targetId).removeChild(filter);
            }
            filters[category] = filters[category].filter(item => item !== value);
            if (filters[category].length === 0) {
                delete filters[category];
            }

            // Show the option back in the dropdown
            const option = document.querySelector(`.filter-option[onclick*="'${value}'"]`);
            if (option) {
                option.style.display = '';
            } else {
                console.warn("Option not found for showing back:", value);
            }

            // Clear search input
            const searchInput = document.getElementById(inputId);
            if (searchInput) {
                searchInput.value = '';
            }

            // Check if country filter changed and toggle US-specific filters
            if (category === 'country') {
                toggleUSFilters();
            }

            debouncedApplyFilters();
        }

        // Filter dropdown options
        function filterOptions(input, optionsContainerId) {
            const filter = input.value.toLowerCase();
            const optionsContainer = document.getElementById(optionsContainerId);
            const options = optionsContainer.getElementsByClassName('filter-option');
            let hasVisibleOptions = false;

            for (let option of options) {
                const optionText = option.textContent.toLowerCase();
                if (optionText.includes(filter)) {
                    option.style.display = '';
                    hasVisibleOptions = true;
                } else {
                    option.style.display = 'none';
                }
            }

            optionsContainer.style.display = filter.length === 0 || !hasVisibleOptions ? 'none' : 'block';
        }

        // Reset all filters
        function resetFilters() {
            filters = {};
            jQuery('.selected-filter-group').empty();
            jQuery('.filter-search').val('');

            // Reset per page to default
            filters['per_page'] = '12';

            // Hide state and county filters on reset
            toggleUSFilters();

            applyFilters();
        }

        // Consolidated initialization on document ready
        jQuery(document).ready(function() {
            // Initialize per_page from URL on page load
            const urlParams = new URLSearchParams(window.location.search);
            const perPageFromUrl = urlParams.get('per_page') || '12';

            filters['per_page'] = perPageFromUrl;

            // Attach per page event on initial load
            attachPerPageEvent();

            // Initialize country filters from URL and check for USA
            const countryParam = urlParams.getAll('country[]');
            if (countryParam.length > 0) {
                filters['country'] = countryParam;
            }

            // Check and toggle US filters on page load
            toggleUSFilters();
        });

        // Handle pagination click with filters applied
        jQuery(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const pageUrl = jQuery(this).attr('href');

            // Extract page number from URL
            const urlParams = new URLSearchParams(pageUrl.split('?')[1]);
            const page = urlParams.get('page') || 1;

            applyFilters(page);
        });

        // Toggle filter sections
        document.querySelectorAll('.filter-header').forEach(header => {
            header.addEventListener('click', function() {
                const icon = this.querySelector('.toggle-icon');
                icon.textContent = icon.textContent === '+' ? '-' : '+';
            });
        });
    </script>
@endsection


@section('scripts')
    <script>
        // Initialize direct message buttons
        function initDirectMessageButtons() {
            let directMessageBtn = document.querySelectorAll('.direct-message-btn');

            directMessageBtn.forEach(element => {
                // Remove existing event listeners by cloning
                const newElement = element.cloneNode(true);
                element.parentNode.replaceChild(newElement, element);

                newElement.addEventListener("click", function() {
                    let receiverId = jQuery(this).data('receiver-id');
                    jQuery('#receiver_id').val(receiverId);

                    // Check if conversation exists
                    jQuery.ajax({
                        url: '/api/check-conversation',
                        method: 'GET',
                        data: {
                            receiver_id: receiverId
                        },
                        headers: {
                            "Authorization": localStorage.getItem("sanctum-token")
                        },
                        success: function(response) {
                            if (response.conversation_exists) {
                                // If conversation exists, open chat directly
                                if (window.openChatWithUser) {
                                    window.openChatWithUser(receiverId);
                                }
                            } else {
                                // If no conversation, open the modal
                                console.log(response.receiver);
                                jQuery('#receiver_id').val(receiverId);
                                jQuery("#messageContent").val(`Hi ${response.receiver.first_name ?? ''} ${response.receiver.last_name ?? ''},
I came across your profile and was really impressed by your work. I'd love to connect and exchange ideas.
Looking forward to connecting!
Best Regards,
                {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}`);
                                var myModal = new bootstrap.Modal(document.getElementById(
                                    'mainModal'));
                                myModal.show();
                            }
                        },
                        error: function(xhr) {
                            console.error('Error checking conversation:', xhr);
                        }
                    });
                });
            });
        }

        jQuery(document).ready(function($) {
            // Initialize on page load
            initDirectMessageButtons();

            $('#directMessageForm').on('submit', function(e) {
                e.preventDefault();

                const formData = {
                    receiver_id: $('#receiver_id').val(),
                    content: $('#messageContent').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: '{{ route('sendMessage') }}',
                    method: 'POST',
                    data: formData,
                    headers: {
                        "Authorization": localStorage.getItem("sanctum-token")
                    },
                    success: function(response) {
                        // Close the modal
                        $('#mainModal').modal('hide');

                        // Trigger opening the chat box and specific conversation
                        if (window.openChatWithUser) {
                            window.openChatWithUser(formData.receiver_id);
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.error ||
                            'An error occurred. Please try again.';
                        $('#messageStatus').html(
                            `<div class="alert alert-danger">${errorMsg}</div>`);
                    }
                });
            });

            // Initialize Bootstrap tooltips for section headings
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
