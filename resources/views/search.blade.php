@extends('user.layouts.main')
@section('content')
    <style>
        /* Basic styling for layout */
        .sidebar {
            width: 25%;
            background-color: var(--primary);
            padding: 20px;
            border-right: 1px solid #ddd;
            height: calc(100vh - 105px);
            overflow-y: auto;
            color: var(--white);
        }

        .main-content {
            width: 75%;
            padding: 20px;
            height: calc(100vh - 105px);
            overflow-y: auto;
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
            border-bottom: 1px solid var(--secondary);
            padding-bottom: 5px;
        }

        .filter-header {
            font-weight: bold;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            cursor: pointer;
        }

        .selected-filters,
        .selected-filter-group {
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

        .filter_heading {
            margin-bottom: 20px;
            text-transform: uppercase;
            /* border-bottom: 2px solid var(--secondary); */
            width: fit-content;
            padding-bottom: 2px;
        }

        .profile_name {
            display: flex;
            align-items: flex-start;
            /* margin-bottom: 20px */
        }

        .profile_details {
            margin-left: 20px;
            width: 75%;
        }

        .btn_flex {
            display: flex;
            align-items: center;
            gap: 20px;
            flex-wrap: wrap;
            width: 100%;
            justify-content: end;
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

        .user-card-flex {
            display: flex;
            align-items: flex-start;
        }

        .user-card .profile_name {
            width: 75%;
        }

        /* .user_about{
                white-space: nowrap;
                overflow: hidden;
                text-overflow: ellipsis;
                width: 100%;
            } */
        .collapse.show {
            background: #8080805c;
            padding: 5px 10px;
            border-radius: 5px;
            margin-top: 10px;
        }

        .indus_member {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            margin: 0px 0px 0px 100px;
        }

        .indus_member p {
            margin: 0;
        }
    </style>


    <div class="d-flex">

        <div class="sidebar">
            <h5 class="filter_heading">Filters</h5>
            <div id="filterContainer">

                @php
                    $filters = \App\Helpers\DropDownHelper::searchFilter();
                @endphp

                <!-- Position Filter Section -->
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#positionFilter">
                        Position <span class="toggle-icon">+</span>
                    </div>
                    <div id="positionFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedPositionFilters"></div>
                        <input type="text" name="company_position" class="filter-search" placeholder="Search Position..."
                            oninput="filterOptions(this, 'positionFilterOptions')">
                        <div id="positionFilterOptions">
                            @foreach ($filters['company_positions'] as $position)
                                <div class="filter-option"
                                    onclick="addFilter('company_position', '{{ $position }}', 'selectedPositionFilters')">
                                    {{ $position }}</div>
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
                        <input type="text" name="company_industry" class="filter-search" placeholder="Search Industry..."
                            oninput="filterOptions(this, 'industryFilterOptions')">
                        <div id="industryFilterOptions">
                            @foreach ($filters['company_industries'] as $industry)
                                <div class="filter-option"
                                    onclick="addFilter('company_industry', '{{ $industry }}', 'selectedIndustryFilters')">
                                    {{ $industry }}</div>
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
                        <input type="text" name="company_sub_category" class="filter-search"
                            placeholder="Search Industry..."
                            oninput="filterOptions(this, 'industrySubCategoryFilterOptions')">
                        <div id="industrySubCategoryFilterOptions">
                            @foreach ($filters['company_sub_categories'] as $sub_category)
                                <div class="filter-option"
                                    onclick="addFilter('company_sub_category', '{{ $sub_category }}', 'selectedIndustrySubCategoryFilters')">
                                    {{ $sub_category }}</div>
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
                        <input type="text" name="company_business_type" class="filter-search"
                            placeholder="Search Business Type..."
                            oninput="filterOptions(this, 'businessTypeFilterOptions')">
                        <div id="businessTypeFilterOptions">
                            @foreach ($filters['company_business_types'] as $business_type)
                                <div class="filter-option"
                                    onclick="addFilter('company_business_type', '{{ $business_type }}', 'selectedBusinessTypeFilters')">
                                    {{ $business_type }}</div>
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
                        <input type="text" name="company_revenue" class="filter-search" placeholder="Search Revenue..."
                            oninput="filterOptions(this, 'revenueFilterOptions')">
                        <div id="revenueFilterOptions">
                            @foreach ($filters['company_revenues'] as $revenue)
                                <div class="filter-option"
                                    onclick="addFilter('company_revenue', '{{ $revenue }}', 'selectedRevenueFilters')">
                                    ${{ $revenue }}</div>
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
                        <input type="text" name="company_no_of_employee" class="filter-search"
                            placeholder="Search Employees..." oninput="filterOptions(this, 'employeesFilterOptions')">
                        <div id="employeesFilterOptions">
                            @foreach ($filters['company_no_of_employees'] as $employee_count)
                                <div class="filter-option"
                                    onclick="addFilter('company_no_of_employee', '{{ $employee_count }}', 'selectedEmployeesFilters')">
                                    {{ $employee_count }}</div>
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
                        <input type="text" name="company_country" class="filter-search"
                            placeholder="Search Country..." oninput="filterOptions(this, 'countryFilterOptions')">
                        <div id="countryFilterOptions">
                            @foreach ($filters['company_countries'] as $country)
                                <div class="filter-option"
                                    onclick="addFilter('country', '{{ $country }}', 'selectedCountryFilters')">
                                    {{ $country }}</div>
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
                        <input type="text" name="company_state" class="filter-search" placeholder="Search State..."
                            oninput="filterOptions(this, 'stateFilterOptions')">
                        <div id="stateFilterOptions">
                            @foreach ($filters['company_states'] as $state)
                                <div class="filter-option"
                                    onclick="addFilter('state', '{{ $state }}', 'selectedStateFilters')">
                                    {{ $state }}</div>
                            @endforeach
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
                        <input type="text" name="product_service_name" class="filter-search"
                            placeholder="Search Product/Service..."
                            oninput="filterOptions(this, 'productServiceFilterOptions')">
                        <div id="productServiceFilterOptions">
                            @foreach ($filters['product_service_names'] as $product_service)
                                <div class="filter-option"
                                    onclick="addFilter('product_service_name', '{{ $product_service }}', 'selectedProductServiceFilters')">
                                    {{ $product_service }}</div>
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
                <div class="col-lg-12" id="userResults">
                    @include('partial.search-result', ['users' => $users])
                </div>
                <div class="col-12"></div>
            </div>
        </div>

    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        
        let filters = {};

        function applyFilters() {

            $('.filter-search').each(function() {
                let value = $(this).val();
                if (value) {
                    let filterName = $(this).attr('id');
                    if (filters[filterName]) {
                        filters[filterName].push(value);
                    } else {
                        filters[filterName] = [value];
                    }
                }
            });

            let newUrl = `${window.location.origin}${window.location.pathname}?${$.param(filters, true)}`;
            window.history.pushState({
                path: newUrl
            }, '', newUrl);

            $.ajax({
                url: window.location.pathname,
                data: filters,
                method: 'GET',
                success: function(response) {
                    $('#userResults').html(response);
                }
            });
        }

        function addFilter(category, value, targetId) {
            const targetElement = document.getElementById(targetId);
            if (!document.getElementById(`${category}-${value}`)) {
                const filter = document.createElement('div');
                filter.className = 'selected-filter';
                filter.id = `${category}-${value}`;
                filter.innerHTML =
                    `${value} <i class="fa fa-times" onclick="removeFilter('${category}', '${value}', '${targetId}')"></i>`;
                targetElement.appendChild(filter);
                if (!filters[category]) {
                    filters[category] = [];
                }
                filters[category].push(value);
            }
            applyFilters();
        }

        function removeFilter(category, value, targetId) {
            const filter = document.getElementById(`${category}-${value}`);
            if (filter) {
                document.getElementById(targetId).removeChild(filter);
            }
            filters[category] = filters[category].filter(item => item !== value);
            applyFilters();
        }

        function filterOptions(input, optionsContainerId) {
            const filter = input.value.toLowerCase();
            const options = document.getElementById(optionsContainerId).getElementsByClassName('filter-option');

            for (let option of options) {
                const optionText = option.textContent.toLowerCase();
                option.style.display = optionText.includes(filter) ? '' : 'none';
            }
        }
        
        document.querySelectorAll('.filter-header').forEach(header => {
            header.addEventListener('click', function() {
                const icon = this.querySelector('.toggle-icon');
                icon.textContent = icon.textContent === '+' ? '-' : '+';
            });
        });

    </script>
@endsection
