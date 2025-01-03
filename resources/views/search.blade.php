@extends('layouts.main')
@section('content')
    <div class="navbar_d_flex">
        <!-- Toggler Button -->
        <button class="sidebar-toggler btn btn-primary d-lg-none" type="button" onclick="toggleSidebar()">
            Filters
        </button>
        <div class="sidebar" id="sidebar">
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
                        <div id="positionFilterOptions" style="display: none">
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
                        <div id="industryFilterOptions" style="display: none">
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
                        <div id="industrySubCategoryFilterOptions" style="display: none">
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
                        <div id="countryFilterOptions" style="display: none">
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
                        <div id="stateFilterOptions" style="display: none">
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
                        <div id="productServiceFilterOptions" style="display: none">
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
            const headerForm = new FormData(document.getElementById('search_form'));
            for (const [key, value] of headerForm.entries()) {
                if (value) {
                    filters[key] = Array.isArray(filters[key]) ? [...filters[key], value] : [value];
                }
            }
        }

        // Apply filters and refresh results
        function applyFilters() {
            syncHeaderFilters();
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
                },
                error: function(error) {
                    console.error('Error fetching results:', error);
                    alert('Failed to apply filters. Please try again.');
                }
            });
        }

        const debouncedApplyFilters = debounce(applyFilters, 300);

        // Add filter
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
            debouncedApplyFilters();
        }

        // Remove filter
        function removeFilter(category, value, targetId) {
            const filter = document.getElementById(`${category}-${value}`);
            if (filter) {
                document.getElementById(targetId).removeChild(filter);
            }
            filters[category] = filters[category].filter(item => item !== value);
            if (filters[category].length === 0) {
                delete filters[category];
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
            $('.selected-filter-group').empty();
            $('.filter-search').val('');
            applyFilters();
        }

        // Handle pagination click with filters applied
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            const pageUrl = $(this).attr('href');
            let newUrl = `${pageUrl}&${$.param(filters, true)}`;

            window.history.pushState({
                path: newUrl
            }, '', newUrl);

            $.ajax({
                url: pageUrl,
                data: filters,
                method: 'GET',
                success: function(response) {
                    $('#userResults').html(response);
                },
                error: function(error) {
                    console.error('Error fetching paginated results:', error);
                    alert('Failed to load results. Please try again.');
                }
            });
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
