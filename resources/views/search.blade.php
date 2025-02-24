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
                <h5 class="filter_heading subheading_line">Professional</h5>

                <!-- Position Filter Section -->
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#positionFilter">
                        Position/Designation <span class="toggle-icon">+</span>
                    </div>
                    <div id="positionFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedPositionFilters"></div>
                        <input type="text" id="positionSearchInput" name="company_position" class="filter-search"
                            placeholder="Search Position..." oninput="filterOptions(this, 'positionFilterOptions')">

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

                <!-- Employees Filter Section -->
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#experienceFilter">
                        Years of Experience <span class="toggle-icon">+</span>
                    </div>
                    <div id="experienceFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedExperienceFilters"></div>
                        <input type="text" id="experienceSearchInput" name="company_experience" class="filter-search"
                            placeholder="Search Experience..." oninput="filterOptions(this, 'experienceFilterOptions')">
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

                <!-- Industry Filter Section -->
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#industryFilter">
                        Industry <span class="toggle-icon">+</span>
                    </div>
                    <div id="industryFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedIndustryFilters"></div>
                        <input type="text" id="industrySearchInput" name="company_industry" class="filter-search"
                            placeholder="Search Industry..." oninput="filterOptions(this, 'industryFilterOptions')">
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


                <!-- Sub - Category Filter Section -->
                {{-- <div class="filter-section">
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
                </div> --}}

                <!-- Business Type Filter Section -->
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#businessTypeFilter">
                        Business Type <span class="toggle-icon">+</span>
                    </div>
                    <div id="businessTypeFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedBusinessTypeFilters"></div>
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

                <!-- Revenue Filter Section -->
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#revenueFilter">
                        Business Revenue <span class="toggle-icon">+</span>
                    </div>
                    <div id="revenueFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedRevenueFilters"></div>
                        <input type="text" id="revenueSearchInput" name="company_revenue" class="filter-search"
                            placeholder="Search Revenue..." oninput="filterOptions(this, 'revenueFilterOptions')">
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

                <!-- Employees Filter Section -->
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#employeesFilter">
                        No. of Employees <span class="toggle-icon">+</span>
                    </div>
                    <div id="employeesFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedEmployeesFilters"></div>
                        <input type="text" id="employeesSearchInput" name="company_no_of_employee"
                            class="filter-search" placeholder="Search Employees..."
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

                <!-- Product/Service Filter Section -->
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#productFilter">
                        Products <span class="toggle-icon">+</span>
                    </div>
                    <div id="productFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedProductFilters"></div>
                        <input type="text" id="productSearchInput" name="product" class="filter-search"
                            placeholder="Search Product..." oninput="filterOptions(this, 'productFilterOptions')">
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

                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#serviceFilter">
                        Services <span class="toggle-icon">+</span>
                    </div>
                    <div id="serviceFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedServiceFilters"></div>
                        <input type="text" id="serviceSearchInput" name="service" class="filter-search"
                            placeholder="Search Service..." oninput="filterOptions(this, 'serviceFilterOptions')">
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



                <h5 class="filter_heading subheading_line">Personal</h5>


                {{-- Position Filter --}}
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#userPositionFilter">
                        Person You're Looking To Connect <span class="toggle-icon">+</span>
                    </div>
                    <div id="userPositionFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedUserPositionFilters"></div>
                        <input type="text" id="userPositionSearchInput" name="user_position" class="filter-search"
                            placeholder="Search type of person you want to connect..."
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



                {{-- Gender Filter --}}
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#genderFilter">
                        Gender <span class="toggle-icon">+</span>
                    </div>
                    <div id="genderFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedGenderFilters"></div>
                        <input type="text" id="genderSearchInput" name="user_gender" class="filter-search"
                            placeholder="Search Gender..." oninput="filterOptions(this, 'genderFilterOptions')">
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

                {{-- Age Group Filter --}}
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#ageGroupFilter">
                        Age Group <span class="toggle-icon">+</span>
                    </div>
                    <div id="ageGroupFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedAgeGroupFilters"></div>
                        <input type="text" id="ageGroupSearchInput" name="user_age_group" class="filter-search"
                            placeholder="Search Age Group..." oninput="filterOptions(this, 'ageGroupFilterOptions')">
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

                {{-- Ethnicity Filter --}}
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#ethnicityFilter">
                        Ethnicity <span class="toggle-icon">+</span>
                    </div>
                    <div id="ethnicityFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedEthnicityFilters"></div>
                        <input type="text" id="ethnicitySearchInput" name="user_ethnicity" class="filter-search"
                            placeholder="Search Ethnicity..." oninput="filterOptions(this, 'ethnicityFilterOptions')">
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
                <h5 class="filter_heading subheading_line">Geographical</h5>
                {{-- Nationality Filter --}}
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#nationalityFilter">
                        Nationality <span class="toggle-icon">+</span>
                    </div>
                    <div id="nationalityFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedNationalityFilters"></div>
                        <input type="text" id="nationalitySearchInput" name="user_nationality" class="filter-search"
                            placeholder="Search Nationality..." oninput="filterOptions(this, 'nationalityFilterOptions')">
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


                <!-- Country Filter Section -->
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#countryFilter">
                        Country <span class="toggle-icon">+</span>
                    </div>
                    <div id="countryFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedCountryFilters"></div>
                        <input type="text" id="countrySearchInput" name="company_country" class="filter-search"
                            placeholder="Search Country..." oninput="filterOptions(this, 'countryFilterOptions')">
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

                <!-- State Filter Section -->
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#stateFilter">
                        State <span class="toggle-icon">+</span>
                    </div>
                    <div id="stateFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedStateFilters"></div>
                        <input type="text" id="stateSearchInput" name="company_state" class="filter-search"
                            placeholder="Search State..." oninput="filterOptions(this, 'stateFilterOptions')">
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

                {{-- City Filter --}}
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#cityFilter">
                        City <span class="toggle-icon">+</span>
                    </div>
                    <div id="cityFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedCityFilters"></div>
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

                {{-- County Filter --}}
                <div class="filter-section">
                    <div class="filter-header" data-bs-toggle="collapse" data-bs-target="#countyFilter">
                        County <span class="toggle-icon">+</span>
                    </div>
                    <div id="countyFilter" class="collapse">
                        <div class="selected-filter-group" id="selectedCountyFilters"></div>
                        <input type="text" id="countySearchInput" name="user_county" class="filter-search"
                            placeholder="Search County..." oninput="filterOptions(this, 'countyFilterOptions')">
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


@section('scripts')
    <script>
        function directMessageBtn(receiverId) {
            $('#receiver_id').val(receiverId);


            // Check if conversation exists
            $.ajax({
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
                        $('#receiver_id').val(receiverId);
                        $("#messageContent").val(`Hi ${response.receiver.first_name ?? ''} ${response.receiver.last_name ?? ''}, 
I came across your profile and was really impressed by your work. I’d love to connect and exchange ideas.
Looking forward to connecting! 
Best Regards,
                        {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}`);
                        $('#mainModal').modal('show');
                    }
                },
                error: function(xhr) {
                    console.error('Error checking conversation:', xhr);
                }
            });
        }

        $(document).ready(function() {

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
        });
    </script>
@endsection
