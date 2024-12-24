@extends('layouts.main')
@section('content')



    <section class="user_company_profile">
        <div class="container">
            <div class="custom_card_profile">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="user-details" data-bs-toggle="tab"
                            data-bs-target="#user-details-pane" type="button" role="tab"
                            aria-controls="user-details-pane" aria-selected="true">Personal</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="company-details-tab" data-bs-toggle="tab"
                            data-bs-target="#company-details-tab-pane" type="button" role="tab"
                            aria-controls="company-details-tab-pane" aria-selected="false">Professional</button>
                    </li>
                </ul>
                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="user-details-pane" role="tabpanel"
                        aria-labelledby="user-details" tabindex="0">
                        <div class="card_profile_first new_user_details">
                            <form action="{{ route('user.details.update') }}" method="POST" enctype="multipart/form-data"
                                id="user_details">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="profile_pic">
                                            <div class="avatar-upload mb-3">
                                                <div class="avatar-edit">
                                                    <input type='file' id="imageUpload" name="photo"
                                                        accept=".png, .jpg, .jpeg" />
                                                    <label for="imageUpload"></label>
                                                </div>
                                                <div class="avatar-preview">
                                                    <div id="imagePreview">
                                                        <img src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        <h1 class="profile_heading text-center">
                                            User Details
                                        </h1>
                                    </div>
                                    <div class="col-12">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="col-12">
                                        <label for="list_check_flex">Are You?<span class="text-danger">*</span></label>
                                        <ul class="list_check_flex">
                                            <li>
                                                <input type="checkbox" class="btn-check" id="accredited_investor"
                                                    name="are_you[]" value="Accredited Investor">
                                                <label class="btn btn-outline-secondary custom_btn"
                                                    for="accredited_investor">Accredited Investor</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" class="btn-check" id="business_owner"
                                                    name="are_you[]" value="Business Owner">
                                                <label class="btn btn-outline-secondary custom_btn"
                                                    for="business_owner">Business Owner</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" class="btn-check" id="board_member_advisor"
                                                    name="are_you[]" value="Board Member / Advisor">
                                                <label class="btn btn-outline-secondary custom_btn"
                                                    for="board_member_advisor">Board Member / Advisor</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" class="btn-check" id="corporate_executive"
                                                    name="are_you[]" value="Corporate Executive">
                                                <label class="btn btn-outline-secondary custom_btn"
                                                    for="corporate_executive">Corporate Executive</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" class="btn-check" id="educator_academia"
                                                    name="are_you[]" value="Educator / Academia">
                                                <label class="btn btn-outline-secondary custom_btn"
                                                    for="educator_academia">Educator / Academia</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" class="btn-check" id="govt_public_sector_leader"
                                                    name="are_you[]" value="Govt/Public Sector Leader">
                                                <label class="btn btn-outline-secondary custom_btn"
                                                    for="govt_public_sector_leader">Govt/Public Sector Leader</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" class="btn-check" id="job_seeker"
                                                    name="are_you[]" value="Job Seeker">
                                                <label class="btn btn-outline-secondary custom_btn" for="job_seeker">Job
                                                    Seeker</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" class="btn-check" id="non_profit_leader"
                                                    name="are_you[]" value="Non-Profit Leader">
                                                <label class="btn btn-outline-secondary custom_btn"
                                                    for="non_profit_leader">Non-Profit Leader</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" class="btn-check" id="investment_seeker"
                                                    name="are_you[]" value="Investment Seeker">
                                                <label class="btn btn-outline-secondary custom_btn"
                                                    for="investment_seeker">Investment Seeker</label>
                                            </li>
                                            <li>
                                                <input type="checkbox" class="btn-check" id="student_intern"
                                                    name="are_you[]" value="Student / Intern">
                                                <label class="btn btn-outline-secondary custom_btn"
                                                    for="student_intern">Student / Intern</label>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row">

                                            <div class="col-lg-6">
                                                <label for="first_name">First Name<span
                                                        class="text-danger">*</span></label>
                                                <input type="text" name="first_name" id="first_name"
                                                    class="form-control"
                                                    value="{{ old('first_name', $user->first_name) }}">
                                            </div>

                                            <div class="col-lg-6">
                                                <label for="last_name">Last Name<span class="text-danger">*</span></label>
                                                <input type="text" name="last_name" id="last_name"
                                                    class="form-control" value="{{ old('last_name', $user->last_name) }}">
                                            </div>

                                            <div class="col-lg-12">
                                                <label for="phone" class="toggle_flex">Cell / Mobile<span
                                                        class="text-danger">* </span>
                                                    <div class="cont">
                                                        (Public
                                                        <div class="toggle">
                                                            <input type="checkbox" id="mode-toggle"
                                                                class="toggle__input">
                                                            <label for="mode-toggle" class="toggle__label mt-0"></label>
                                                        </div>
                                                        Private)
                                                    </div>
                                                </label>
                                                <input type="tel" name="phone" id="phone" class="form-control"
                                                    value="{{ old('phone', $user->phone) }}">
                                            </div>

                                            <div class="col-lg-12">
                                                <label for="email">Email<span class="text-danger">*</span></label>
                                                <input type="email" name="email" id="email" class="form-control"
                                                    value="{{ old('email', $user->email) }}">
                                            </div>


                                            <div class="col-lg-12">
                                                <label for="linkedin_url">LinkedIn</label>
                                                <div class="input-group">
                                                    <div class="input-group-text">https://www.linkedin.com/in/</div>
                                                    <input type="text" name="linkedin_user" id="linkedin_user"
                                                        class="form-control"
                                                        value="{{ old('linkedin_url', str_replace('https://www.linkedin.com/in/', '', $user->linkedin_url)) }}">
                                                </div>
                                                <input type="hidden" name="linkedin_url" id="linkedin_url_hidden"
                                                    value="">

                                            </div>

                                            <div class="col-lg-12">
                                                <label for="x_url">X</label>
                                                <input type="text" name="x_url" id="x_url" class="form-control"
                                                    value="{{ old('x_url', $user->x_url) }}" placeholder="Link">
                                            </div>
                                        </div>

                                    </div>
                                    <div class="col-lg-6 mobile_margin_30">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <label for="facebook_url">Facebook URL</label>
                                                <input type="text" name="facebook_url" id="facebook_url"
                                                    class="form-control"
                                                    value="{{ old('facebook_url', $user->facebook_url) }}"
                                                    placeholder="Link">
                                            </div>

                                            <div class="col-lg-12">
                                                <label for="address">Address</label>
                                                <input type="text" name="address" id="address" class="form-control"
                                                    placeholder="Enter your address"
                                                    value="{{ old('address', $user->address) }}">

                                            </div>

                                            <div class="col-lg-12">
                                                <label for="country">Country</label>
                                                <input type="text" name="country" id="country" class="form-control"
                                                    value="{{ old('country', $user->country) }}">
                                                {{-- {!! \App\Helpers\DropDownHelper::renderCountryDropdownForUser($user->country) !!} --}}
                                            </div>

                                            <div class="col-lg-6">
                                                <label for="state">State</label>
                                                <input type="text" name="state" id="state" class="form-control"
                                                    value="{{ old('state', $user->state) }}">
                                                {{-- {!! \App\Helpers\DropDownHelper::renderStateDropdownForUser($user->country, $user->state) !!} --}}
                                            </div>

                                            <div class="col-lg-6">
                                                <label for="city">City</label>
                                                <input type="text" name="city" id="city" class="form-control"
                                                    value="{{ old('city', $user->city) }}">
                                                {{-- {!! \App\Helpers\DropDownHelper::renderCityDropdownForUser($user->state, $user->city) !!} --}}
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="county">County</label>
                                                <input type="text" name="county" id="county" class="form-control"
                                                    value="{{ old('county', $user->county) }}">
                                            </div>

                                            <div class="col-lg-6">
                                                <label for="zip_code">Zip Code</label>
                                                <input type="text" name="zip_code" id="zip_code"
                                                    class="form-control" value="{{ old('zip_code', $user->zip_code) }}">
                                            </div>

                                            <div class="col-lg-12">
                                                <label for="industry_to_connect">Industry you would like to Connect
                                                    to</label>
                                                {!! \App\Helpers\DropDownHelper::renderIndustryDropdownForUser(
                                                    $user->industry_to_connect,
                                                    $user->sub_category_to_connect,
                                                ) !!}
                                                <div id="industry_other_field_2" style="display: none;">
                                                    <label for="industry_to_connect_other">Other Industry</label>
                                                    <input type="text" name="industry_to_connect_other"
                                                        id="industry_to_connect_other" class="form-control"
                                                        placeholder="Enter other industry">
                                                </div>
                                            </div>

                                            <div class="col-lg-12">
                                                <label for="sub_category_to_connect">Sub Category to Connect</label>
                                                <select name="sub_category_to_connect" id="sub_category_to_connect"
                                                    class="form-select">
                                                    <option value="">Select Company Sub Category</option>
                                                </select>
                                                <div id="subcategory_other_field" style="display: none;">
                                                    <label for="sub_category_to_connect_other">Other Sub Category</label>
                                                    <input type="text" name="sub_category_to_connect_other"
                                                        id="sub_category_to_connect_other" class="form-control"
                                                        placeholder="Enter other sub category">
                                                </div>
                                            </div>



                                            <div class="col-lg-12">
                                                <label for="community_interest">Community Interest</label>
                                                {!! \App\Helpers\DropDownHelper::renderCommunityInterestDropdown($user->community_interest) !!}
                                                <div id="community_interest_other_field" style="display: none;">
                                                    <label for="community_interest_other">Other Community Interest</label>
                                                    <input type="text" name="community_interest_other"
                                                        id="community_interest_other" class="form-control"
                                                        placeholder="Enter other community interest">
                                                </div>
                                            </div>


                                            <div class="col-12 mt-5">
                                                <button type="submit" class="btn btn-primary w-100">Save</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>




                    <div class="tab-pane fade" id="company-details-tab-pane" role="tabpanel"
                        aria-labelledby="company-details-tab" tabindex="0">
                        <div class="card_profile_first new_user_details">
                            <form action="{{ route('user.company.update') }}" method="POST"
                                enctype="multipart/form-data" id="user_company">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="profile_pic">
                                            <div class="avatar-upload mb-3">
                                                <div class="avatar-edit">
                                                    <input type='file' id="imageUpload" name="company_logo"
                                                        accept=".png, .jpg, .jpeg" />
                                                    <label for="imageUpload"></label>
                                                </div>
                                                <div class="avatar-preview">
                                                    <div id="imagePreview">
                                                        <img src="{{ isset($company) && $company->company_logo ? asset('storage/' . $company->company_logo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
                                                            alt="">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <h1 class="profile_heading text-center mt-0">
                                            Company Details
                                        </h1>
                                    </div>
                                    <div class="col-12">
                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="row">


                                            <!-- Company Name -->
                                            <div class="col-lg-12">
                                                <label for="company_name">Company Name</label>
                                                <input type="text" name="company_name" id="company_name"
                                                    class="form-control"
                                                    value="{{ old('company_name', $company->company_name ?? '') }}"
                                                    required>
                                            </div>

                                            <!-- Company Email -->
                                            {{-- <div class="col-lg-6">
                                                <label for="company_email">Company Email</label>
                                                <input type="email" name="company_email" id="company_email"
                                                    class="form-control"
                                                    value="{{ old('company_email', $company->company_email ?? '') }}"
                                                    required>
                                            </div> --}}
                                            <!-- Company Web URL -->


                                            <div class="col-lg-12">
                                                <label for="company_web_url">Company URL</label>
                                                <input type="text" name="company_web_url" id="company_web_url"
                                                    class="form-control"
                                                    value="{{ old('company_web_url', $company->company_web_url ?? '') }}"
                                                    required>
                                            </div>



                                            <div class="col-lg-12 custom-select-dropdown">
                                                <label for="company_position">Title/Designation</label>

                                                <div class="selected-tags my-2">
                                                    {{ $company->company_position ?? '' }}
                                                </div>
                                                <input type="hidden" id="company_position_hidden"
                                                    name="company_position"
                                                    value="{{ old('company_position', $company->company_position ?? '') }}" />
                                                {!! \App\Helpers\DropDownHelper::designationDropdown($company->company_position ?? '') !!}

                                            </div>

                                            <div class="col-lg-12 company_position_other_div d-none">
                                                <label for="company_position_other">Title/Designation Other</label>
                                                <input type="text" name="company_position_other"
                                                    id="company_position_other" class="form-control" value=""
                                                    required>
                                            </div>

                                            <div class="col-lg-12 custom-select-dropdown mt-0">
                                                <label for="company_experience">Years of Experience</label>
                                                <select name="company_experience" id="company_experience" class="form-select">
                                                    <option value="Under 1" {{ $company->company_experience == 'Under 1' ? 'selected' : '' }}>Under 1</option>
                                                    <option value="1-5 years" {{ $company->company_experience == '1-5 years' ? 'selected' : '' }}>1-5 years</option>
                                                    <option value="5-10 years" {{ $company->company_experience == '5-10 years' ? 'selected' : '' }}>5-10 years</option>
                                                    <option value="10-20 years" {{ $company->company_experience == '10-20 years' ? 'selected' : '' }}>10-20 years</option>
                                                    <option value="20+ years" {{ $company->company_experience == '20+ years' ? 'selected' : '' }}>20+ years</option>
                                                </select>
                                            </div>
                                            

                                            <div class="col-lg-12">
                                                <label for="work_phone_num">Work Phone Number</label>
                                                <input type="tel" name="company_phone" id="company_phone"
                                                    class="form-control"
                                                    value="{{ old('company_phone', $company->company_phone ?? '') }}"
                                                    required>
                                            </div>


                                        </div>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="row">
                                            <!-- Company Linkedin URL -->
                                            <div class="col-lg-12">
                                                <label for="company_linkedin_user">Company LinkedIn Page</label>
                                                <div class="input-group">
                                                    <div class="input-group-text">https://www.linkedin.com/company/</div>
                                                    <input type="text" name="company_linkedin_user"
                                                        id="company_linkedin_user" class="form-control"
                                                        value="{{ old('company_linkedin_user', str_replace('https://www.linkedin.com/company/', '', $company->company_linkedin_url ?? '')) }}"
                                                        required>
                                                </div>
                                                <input type="hidden" name="company_linkedin_url"
                                                    id="company_linkedin_url_hidden" value="">

                                            </div>

                                            <div class="col-lg-12">
                                                <label for="company_industry">Industry</label>
                                                {!! \App\Helpers\DropDownHelper::renderIndustryDropdown(
                                                    $company->company_industry ?? '',
                                                    $company->company_sub_category ?? '',
                                                ) !!}
                                                <div id="industry_other_field" style="display: none;">
                                                    <label for="industry_other">Other Industry</label>
                                                    <input type="text" name="company_industry_other"
                                                        id="industry_other" class="form-control"
                                                        placeholder="Enter other industry">
                                                </div>
                                            </div>

                                            <!-- Business Type Dropdown -->
                                            <div class="col-lg-12">
                                                <label for="company_business_type">Company Type</label>
                                                {!! \App\Helpers\DropDownHelper::renderBusinessTypeDropdown($company->company_business_type ?? '') !!}
                                                <div id="business_type_other_field" style="display: none;">
                                                    <label for="business_type_other">Other Company Type</label>
                                                    <input type="text" name="company_business_type_other"
                                                        id="business_type_other" class="form-control"
                                                        placeholder="Enter other business type">
                                                </div>
                                            </div>

                                            <!-- Company Revenue -->
                                            <div class="col-lg-12">
                                                <label for="company_revenue">Company Revenue:</label>
                                                {!! \App\Helpers\DropDownHelper::renderRevenueDropdown($company->company_revenue ?? '') !!}
                                            </div>

                                            <!-- Number of Employees Dropdown -->
                                            <div class="col-lg-12">
                                                <label for="company_no_of_employee">Company No. of Employees</label>
                                                {!! \App\Helpers\DropDownHelper::renderEmployeeSizeDropdown($company->company_no_of_employee ?? '') !!}
                                            </div>


                                        </div>
                                    </div>

                                    <!-- Products/Services -->
                                    <div class="col-12">
                                        <label for="product_service_name">List of Services/Products</label>
                                        <div class="row" id="product-service-row">
                                            <div class="col-lg-4">
                                                <label for="product_service_name" class="mt-2">Name</label>
                                                <input type="text" name="product_service_name[]"
                                                    id="product_service_name" class="form-control"
                                                    value="{{ old('product_service_name[]', $company->productServices[0]->product_service_name ?? '') }}"
                                                    required>
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="product_service_description"
                                                    class="mt-2">Description</label>
                                                <input type="text" name="product_service_description[]"
                                                    id="product_service_description"
                                                    value="{{ old('product_service_description[]', $company->productServices[0]->product_service_description ?? '') }}"class="form-control"
                                                    required>
                                            </div>
                                            <div class="col-lg-4">
                                                <div class="flex_field_btn h-100">
                                                    <button type="button" id="add-product-service"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Add
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-12 mt-4 text-end">
                                        <button type="submit" class="btn theme-btn">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>




        </div>
    </section>
@endsection
@section('scripts')
    <script>
       
        document.addEventListener('DOMContentLoaded', function() {
          
            document.querySelector('select[name="industry_to_connect"]').addEventListener('change', function() {
                toggleOtherField(this, 'industry_other_field_2');
            });

            document.querySelector('select[name="sub_category_to_connect"]').addEventListener('change', function() {
                toggleOtherField(this, 'subcategory_other_field');
            });

            document.querySelector('select[name="community_interest"]').addEventListener('change', function() {
                toggleOtherField(this, 'community_interest_other_field');
            });

            function toggleOtherField(dropdown, fieldId) {
                const otherField = document.getElementById(fieldId);
                if (dropdown.value.toLowerCase() === 'other') {
                    otherField.style.display = 'block';
                } else {
                    otherField.style.display = 'none';
                    otherField.querySelector('input').value = '';
                }
            }
        });


        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault();

            const userInput = document.getElementById('linkedin_user').value.trim();
            if (userInput) {
                const combinedUrl = `https://www.linkedin.com/in/${userInput}`;
                document.getElementById('linkedin_url_hidden').value = combinedUrl;
                event.target.submit();
            } else {
                alert('Please enter a valid LinkedIn username.');
            }
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const checkboxes = document.querySelectorAll('.form-check-input');
            const hiddenInput = document.getElementById('company_position_hidden');
            const otherCheckbox = document.getElementById('company_position_other_select');
            const otherFieldDiv = document.querySelector('.company_position_other_div');
            const otherInput = document.getElementById('company_position_other');
            const tagContainer = document.querySelector('.selected-tags'); // Container for tags
            const searchInput = document.getElementById('search-dropdown'); // Search input for dropdown

            const selectedPositions = hiddenInput.value.split(',').map(pos => pos
                .trim()); // Split saved positions into an array

            const updateHiddenInput = () => {
                const selected = Array.from(document.querySelectorAll('.form-check-input:checked'))
                    .filter(cb => cb !== otherCheckbox)
                    .map(cb => cb.labels[0].textContent.trim());

                if (otherCheckbox.checked && otherInput.value.trim()) {
                    selected.push(otherInput.value.trim());
                }

                hiddenInput.value = selected.join(', '); // Update hidden input with selected positions
                updateTags(); // Reflect changes in tags
            };

            const updateTags = () => {
                tagContainer.innerHTML = ''; // Clear existing tags
                const selectedCheckboxes = Array.from(document.querySelectorAll('.form-check-input:checked'));
                selectedCheckboxes.forEach((checkbox) => {
                    const label = checkbox.labels[0]; // Get associated label
                    if (label) { // Check if label exists
                        const tagText = label.textContent.trim();
                        createTag(tagText, checkbox);
                    }
                });

                // Handle "Other" tag if selected
                if (otherCheckbox.checked && otherInput.value.trim()) {
                    createTag(otherInput.value.trim(), null, true); // Add "Other" as a tag
                }
            };

            const createTag = (text, checkbox, isOther = false) => {
                const tag = document.createElement('span');
                tag.className = 'tag';
                tag.textContent = text;

                const closeButton = document.createElement('span');
                closeButton.className = 'tag-close';
                closeButton.textContent = '×';
                closeButton.addEventListener('click', () => {
                    tag.remove();
                    if (isOther) {
                        otherCheckbox.checked = false;
                        otherInput.value = '';
                        otherFieldDiv.classList.add('d-none');
                        otherFieldDiv.classList.remove('d-block');
                    } else if (checkbox) {
                        checkbox.checked = false;
                    }
                    updateHiddenInput();
                });

                tag.appendChild(closeButton);
                tagContainer.appendChild(tag);
            };



            selectedPositions.forEach((position) => {
                const checkbox = Array.from(checkboxes).find(cb => {
                    const label = cb.labels[0];
                    return label && label.textContent.trim() === position;
                });

                if (checkbox) {
                    checkbox.checked = true;
                } else if (position === "Other") {
                    otherCheckbox.checked = true;
                    otherFieldDiv.classList.remove('d-none');
                    otherFieldDiv.classList.add('d-block');
                    otherInput.value = position;
                }
            });



            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', () => {
                    if (otherCheckbox.checked) {
                        otherFieldDiv.classList.remove('d-none');
                        otherFieldDiv.classList.add('d-block');
                    } else {
                        otherFieldDiv.classList.add('d-none');
                        otherFieldDiv.classList.remove('d-block');
                    }
                    updateHiddenInput();
                });
            });


            otherInput.addEventListener('keyup', updateHiddenInput);


            searchInput.addEventListener('input', () => {
                const dropdownMenu = document.querySelector('.position-dropdown-menu');
                const searchTerm = searchInput.value.toLowerCase().trim();
                const listItems = dropdownMenu.querySelectorAll('li');

                listItems.forEach((item) => {
                    const label = item.querySelector('.form-check-label');
                    if (label) {
                        const labelText = label.textContent
                            .toLowerCase();


                        if (labelText.includes(searchTerm)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>



    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     const industryDropdown = document.getElementById("company_industry");
        //     // const subCategoryDropdown = document.getElementById("company_sub_category");
        //     // const selectedSubcategory = "{{ $company->company_sub_category ?? '' }}";

        //     // industryDropdown.addEventListener("change", function() {
        //     //     loadSubcategories(industryDropdown.value);
        //     // });

        //     // function loadSubcategories(industryName) {
        //     //     subCategoryDropdown.innerHTML = "<option value=\'\'>Select Company Sub Industry</option>";

        //     //     if (industryName) {
        //     //         fetch("{{ route('get-category', ['industryId' => '__industryName__']) }}".replace(
        //     //                 '__industryName__', industryName))
        //     //             .then(response => response.json())
        //     //             .then(data => {
        //     //                 data.forEach(function(subCategory) {
        //     //                     let option = document.createElement("option");
        //     //                     option.value = subCategory.name;
        //     //                     option.text = subCategory.name;
        //     //                     option.selected = (subCategory.name === selectedSubcategory);
        //     //                     subCategoryDropdown.appendChild(option);
        //     //                 });
        //     //             })
        //     //             .catch(error => {
        //     //                 console.error("Error fetching subcategories:", error);
        //     //             });
        //     //     }
        //     // }
        //     loadSubcategories(industryDropdown.value);
        // });



        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('select[name="company_business_type"]').addEventListener('change', function() {
                toggleOtherField(this, 'business_type_other_field');
            });

            document.querySelector('select[name="company_industry"]').addEventListener('change', function() {
                toggleOtherField(this, 'industry_other_field');
            });

            // document.querySelector('select[name="company_sub_category"]').addEventListener('change', function() {
            //     toggleOtherField(this, 'subcategory_other_field');
            // });

            // document.querySelector('select[name="company_contribute_to_muslim_community"]').addEventListener(
            //     'change',
            //     function() {
            //         toggleOtherField(this, 'contribution_other_field');
            //     });

            // document.querySelector('select[name="company_affiliation_to_muslim_org"]').addEventListener('change',
            //     function() {
            //         toggleOtherField(this, 'affiliation_other_field');
            //     });

            function toggleOtherField(dropdown, fieldId) {
                const otherField = document.getElementById(fieldId);
                if (dropdown.value.toLowerCase() === 'other') {
                    otherField.style.display = 'block';
                } else {
                    otherField.style.display = 'none';
                    otherField.querySelector('input').value = ''; // Clear the input field
                }
            }
        });

        document.querySelector('form').addEventListener('submit', function() {
            const companyInput = document.getElementById('company_linkedin_user').value.trim();
            const combinedCompanyUrl = `https://www.linkedin.com/company/${companyInput}`;
            document.getElementById('company_linkedin_url_hidden').value = combinedCompanyUrl;
        });




        let count = 1;

        document.getElementById('add-product-service').addEventListener('click', function() {
            count++;
            const rowContainer = document.getElementById('product-service-row');
            const newRow = document.createElement('div');
            newRow.classList.add('row', 'mt-2');

            // Product/Service Name Column
            const nameCol = document.createElement('div');
            nameCol.classList.add('col-lg-4');
            const nameLabel = document.createElement('label');
            nameLabel.setAttribute('for', 'product_service_name_' + count);
            nameLabel.innerText = 'Products/Services you offer Name ' + count;
            const nameInput = document.createElement('input');
            nameInput.type = 'text';
            nameInput.name = 'product_service_name[]';
            nameInput.id = 'product_service_name_' + count;
            nameInput.classList.add('form-control');
            nameCol.appendChild(nameLabel);
            nameCol.appendChild(nameInput);

            // Product/Service Description Column
            const descCol = document.createElement('div');
            descCol.classList.add('col-lg-4');
            const descLabel = document.createElement('label');
            descLabel.setAttribute('for', 'product_service_description_' + count);
            descLabel.innerText = 'Products/Services you offer Description ' + count;
            const descInput = document.createElement('input');
            descInput.type = 'text';
            descInput.name = 'product_service_description[]';
            descInput.id = 'product_service_description_' + count;
            descInput.classList.add('form-control');
            descCol.appendChild(descLabel);
            descCol.appendChild(descInput);

            // Product Service Area Column with Delete Button inside flex_field_btn
            const areaCol = document.createElement('div');
            areaCol.classList.add('col-lg-4');

            const flexFieldBtn = document.createElement('div');
            flexFieldBtn.classList.add('flex_field_btn');
            flexFieldBtn.classList.add('h-100');

            // const fieldDiv = document.createElement('div');
            // fieldDiv.classList.add('field', 'w-75');

            // const areaLabel = document.createElement('label');
            // areaLabel.setAttribute('for', 'product_service_area_' + count);
            // areaLabel.innerText = 'Product Service Area ' + count;

            // const areaInput = document.createElement('input');
            // areaInput.type = 'text';
            // areaInput.name = 'product_service_area[]';
            // areaInput.id = 'product_service_area_' + count;
            // areaInput.classList.add('form-control');

            // fieldDiv.appendChild(areaLabel);
            // fieldDiv.appendChild(areaInput);
            // flexFieldBtn.appendChild(fieldDiv);

            // Delete Button
            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.classList.add('btn', 'btn-danger', 'ml-2');
            deleteButton.innerHTML = '<i class="fas fa-trash-alt"></i>';
            deleteButton.onclick = function() {
                rowContainer.removeChild(newRow);
                count--;
            };
            flexFieldBtn.appendChild(deleteButton);

            areaCol.appendChild(flexFieldBtn);

            // Append all columns to the new row
            newRow.appendChild(nameCol);
            newRow.appendChild(descCol);
            newRow.appendChild(areaCol);

            // Add new row to container
            rowContainer.appendChild(newRow);
        });
    </script>
@endsection
