@extends('admin.layouts.main')
@section('content')

<main class="main-content">
    <section class="user_company_profile">
        <div class="container">
            <div class="custom_card_profile card_profile_first new_user_details">
                <form action="{{ route('admin.company.update') }}" method="POST" class="user_form" enctype="multipart/form-data"
                    id="user_company">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="row">
                                <div class="col-12">
                                    <div class="profile_pic">
                                        <div class="avatar-upload">
                                            <div class="avatar-edit">
                                                <input type='file' id="imageUpload" name="company_logo"
                                                    accept=".png, .jpg, .jpeg" />
                                                <label for="imageUpload"></label>
                                            </div>
                                            <div class="avatar-preview">
                                                <div id="imagePreview">
                                                    <img src="{{ isset($company) && $company->company_logo ? getImageUrl($company->company_logo) : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcd5J_YDIyLfeZCHcsBpcuN8irwbIJ_VDl0Q&s' }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <h1 class="profile_heading text-center">
                                        Company Details
                                    </h1>
                                </div>
                                <input type="hidden" name="user_id" value="{{$user->id}}"/> 
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
                                <!-- Company Name -->
                                <div class="col-lg-6">
                                    <label for="company_name">Company Name</label>
                                    <input type="text" name="company_name" id="company_name" class="form-control"
                                        value="{{ old('company_name', $company->company_name ?? '') }}" required>
                                </div>

                                <!-- Company Email -->
                                <div class="col-lg-6">
                                    <label for="company_email">Company Email</label>
                                    <input type="email" name="company_email" id="company_email" class="form-control"
                                        value="{{ old('company_email', $company->company_email ?? '') }}" required>
                                </div>
                                <!-- Company Web URL -->
                                <div class="col-lg-12">
                                    <label for="company_web_url">Company Web URL</label>
                                    <input type="text" name="company_web_url" id="company_web_url" class="form-control"
                                        value="{{ old('company_web_url', $company->company_web_url ?? '') }}" required>
                                </div>

                                <!-- Company Linkedin URL -->
                                <div class="col-lg-12">
                                    <label for="company_linkedin_url">Company LinkedIn URL</label>
                                    <input type="url" name="company_linkedin_url" id="company_linkedin_url"
                                        class="form-control"
                                        value="{{ old('company_linkedin_url', $company->company_linkedin_url ?? '') }}"
                                        placeholder="https://www.linkedin.com/company/your-company" required>

                                </div>

                                <!-- Position/Designation -->
                                <div class="col-lg-12">
                                    <label for="company_position">Position/Designation</label>
                                    <input type="text" name="company_position" id="company_position" class="form-control"
                                        value="{{ old('company_position', $company->company_position ?? '') }}" required>
                                </div>
                                
                                <!-- Company Address -->
                                <div class="col-lg-12">
                                    <label for="company_address">Company Address</label>
                                    <input type="text" name="company_address" id="company_address" class="form-control"
                                        value="{{ old('company_address', $company->company_address ?? '') }}" required>
                                </div>

                                <div class="col-lg-12">
                                    <label for="company_country">Company Country</label>
                                    <input type="text" name="company_country" id="company_country" class="form-control"
                                        value="{{ old('company_country', $company->company_country ?? '') }}" required>
                                    {{-- {!! \App\Helpers\DropDownHelper::renderCountryDropdown($company->company_country ?? '') !!} --}}
                                </div>


                                <div class="col-lg-6">
                                    <label for="company_state">Company State</label>
                                    <input type="text" name="company_state" id="company_state" class="form-control"
                                        value="{{ old('company_state', $company->company_state ?? '') }}" required>
                                    {{-- {!! \App\Helpers\DropDownHelper::renderStateDropdown($company->company_country ?? '', $company->company_state ?? '') !!} --}}
                                </div>

                                <!-- Company City -->
                                <div class="col-lg-6">
                                    <label for="company_city">Company City</label>
                                    <input type="text" name="company_city" id="company_city" class="form-control"
                                        value="{{ old('company_city', $company->company_city ?? '') }}" required>
                                    {{-- {!! \App\Helpers\DropDownHelper::renderCityDropdown($company->company_state ?? '', $company->company_city ?? '') !!} --}}
                                </div>

                                <!-- Company County -->
                                <div class="col-lg-6">
                                    <label for="company_county">Company County</label>
                                    <input type="text" name="company_county" id="company_county" class="form-control"
                                        value="{{ old('company_county', $company->company_county ?? '') }}" required>
                                </div>

                                <!-- Company Zip Code -->
                                <div class="col-lg-6">
                                    <label for="company_zip_code">Company Zip Code</label>
                                    <input type="text" name="company_zip_code" id="company_zip_code"
                                        class="form-control"
                                        value="{{ old('company_zip_code', $company->company_zip_code ?? '') }}" required>
                                </div>
                            </div>

                        </div>
                        <div class="col-lg-6">
                            <div class="row">

                                <!-- Company Description -->
                                <div class="col-lg-12">
                                    <label for="company_about">Company Description</label>
                                    <textarea name="company_about" id="company_about" class="form-control" cols="30" rows="5" required>{{ old('company_about', $company->company_about ?? '') }}</textarea>
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

                                <!-- Business Type Dropdown -->
                                <div class="col-lg-12">
                                    <label for="company_business_type">Company Business Type</label>
                                    {!! \App\Helpers\DropDownHelper::renderBusinessTypeDropdown($company->company_business_type ?? '') !!}
                                    <div id="business_type_other_field" style="display: none;">
                                        <label for="business_type_other">Other Business Type</label>
                                        <input type="text" name="company_business_type_other" id="business_type_other"
                                            class="form-control" placeholder="Enter other business type">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <label for="company_industry">Company Industry</label>
                                    {!! \App\Helpers\DropDownHelper::renderIndustryDropdown(
                                        $company->company_industry ?? '',
                                        $company->company_sub_category ?? '',
                                    ) !!}
                                    <div id="industry_other_field" style="display: none;">
                                        <label for="industry_other">Other Industry</label>
                                        <input type="text" name="company_industry_other" id="industry_other"
                                            class="form-control" placeholder="Enter other industry">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <label for="company_sub_category">Company Sub Industry</label>
                                    <select name="company_sub_category" id="company_sub_category" class="form-select">
                                        <option value="">Select Company Sub Industry</option>
                                    </select>
                                    <div id="subcategory_other_field" style="display: none;">
                                        <label for="subcategory_other">Other Sub Industry</label>
                                        <input type="text" name="company_sub_category_other" id="subcategory_other"
                                            class="form-control" placeholder="Enter other sub category">
                                    </div>
                                </div>

                                <!-- Community Service -->
                                <div class="col-lg-12">
                                    <label for="company_community_service">Does the business engage in community
                                        service?</label>
                                    <select name="company_community_service" id="company_community_service"
                                        class="form-select" required>
                                        <option value="">Select Company Community Service</option>
                                        <option value="Yes"
                                            {{ old('company_community_service', $company->company_community_service ?? '') == 'Yes' ? 'selected' : '' }}>
                                            Yes</option>
                                        <option value="No"
                                            {{ old('company_community_service', $company->company_community_service ?? '') == 'No' ? 'selected' : '' }}>
                                            No</option>
                                    </select>
                                </div>

                                <div class="col-lg-12">
                                    <label for="company_contribute_to_muslim_community">Business Contributions to Muslim
                                        Community</label>
                                    {!! \App\Helpers\DropDownHelper::renderBusinessContributionsToMuslimCommunityDropdown(
                                        old('company_contribute_to_muslim_community', $company->company_contribute_to_muslim_community ?? ''),
                                    ) !!}
                                    <div id="contribution_other_field" style="display: none;">
                                        <label for="contribution_other">Other Contribution</label>
                                        <input type="text" name="company_contribute_to_muslim_community_other" id="contribution_other"
                                            class="form-control" placeholder="Enter other contribution">
                                    </div>
                                </div>

                                <div class="col-lg-12">
                                    <label for="company_affiliation_to_muslim_org">Affiliations with Muslim
                                        Organizations/Networks?</label>
                                    {!! \App\Helpers\DropDownHelper::renderAffiliationToMuslimOrgDropdown(
                                        old('company_affiliation_to_muslim_org', $company->company_affiliation_to_muslim_org ?? ''),
                                    ) !!}
                                    <div id="affiliation_other_field" style="display: none;">
                                        <label for="affiliation_other">Other Affiliation</label>
                                        <input type="text" name="company_affiliation_to_muslim_org_other" id="affiliation_other"
                                            class="form-control" placeholder="Enter other affiliation">
                                    </div>
                                </div>


                            </div>
                        </div>

                        <!-- Products/Services -->
                        <div class="col-12">
                            <div class="row" id="product-service-row">
                                <div class="col-lg-4">
                                    <label for="product_service_name">Products/Services you offer Name</label>
                                    <input type="text" name="product_service_name[]" id="product_service_name"
                                        class="form-control" required>
                                </div>
                                <div class="col-lg-4">
                                    <label for="product_service_description">Products/Services you offer
                                        Description</label>
                                    <input type="text" name="product_service_description[]"
                                        id="product_service_description" class="form-control" required>
                                </div>
                                <div class="col-lg-4">
                                    <div class="flex_field_btn">
                                        <div class="field w-75">
                                            <label for="product_service_area">Product Service Area</label>
                                            <input type="text" name="product_service_area[]" id="product_service_area"
                                                class="form-control">
                                        </div>
                                        <button type="button" id="add-product-service" class="btn btn-primary">
                                            <i class="fas fa-plus"></i> Add
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Accreditation -->
                        <div class="col-12">
                            <div class="row align-items-end" id="accreditation-row">
                                <div class="col-lg-6">
                                    <label for="accreditation">Accreditation </label>
                                    <input type="text" name="accreditation[]" id="accreditation"
                                        class="form-control">
                                </div>
                                <div class="col-lg-6">
                                    <button type="button" id="add-accreditation" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Add Accreditation
                                    </button>
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
    </section>
</main>    



@endsection

@section('scripts')

<script>

    document.addEventListener("DOMContentLoaded", function() {
        const industryDropdown = document.getElementById("company_industry");
        const subCategoryDropdown = document.getElementById("company_sub_category");
        const selectedSubcategory = "{{ $company->company_sub_category ?? '' }}";


        industryDropdown.addEventListener("change", function() {
            loadSubcategories(industryDropdown.value);
        });

        function loadSubcategories(industryName) {
            subCategoryDropdown.innerHTML = "<option value=\'\'>Select Company Sub Industry</option>";

            if (industryName) {
                fetch("{{ route('get-category', ['industryId' => '__industryName__']) }}".replace('__industryName__', industryName))
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(function(subCategory) {
                            let option = document.createElement("option");
                            option.value = subCategory.name;
                            option.text = subCategory.name;
                            option.selected = (subCategory.name === selectedSubcategory);
                            subCategoryDropdown.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error("Error fetching subcategories:", error);
                    });
            }
        }
        loadSubcategories(industryDropdown.value);
    });
    
    
    
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelector('select[name="company_business_type"]').addEventListener('change', function() {
            toggleOtherField(this, 'business_type_other_field');
        });

        document.querySelector('select[name="company_industry"]').addEventListener('change', function() {
            toggleOtherField(this, 'industry_other_field');
        });

        document.querySelector('select[name="company_sub_category"]').addEventListener('change', function() {
            toggleOtherField(this, 'subcategory_other_field');
        });

        document.querySelector('select[name="company_contribute_to_muslim_community"]').addEventListener(
            'change',
            function() {
                toggleOtherField(this, 'contribution_other_field');
            });

        document.querySelector('select[name="company_affiliation_to_muslim_org"]').addEventListener('change',
            function() {
                toggleOtherField(this, 'affiliation_other_field');
            });

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
        // No hidden field; full URL is entered directly now.
    });


    let accreditationCount = 1;

    document.getElementById('add-accreditation').addEventListener('click', function() {
        accreditationCount++;
        const rowContainer = document.getElementById('accreditation-row');

        // Create new row
        const newRow = document.createElement('div');
        newRow.classList.add('row', 'align-items-end', 'mt-2');

        // Accreditation input column
        const accredCol = document.createElement('div');
        accredCol.classList.add('col-lg-6');
        const accredLabel = document.createElement('label');
        accredLabel.setAttribute('for', 'accreditation_' + accreditationCount);
        accredLabel.innerText = 'Accreditation ' + accreditationCount;
        const accredInput = document.createElement('input');
        accredInput.type = 'text';
        accredInput.name = 'accreditation[]';
        accredInput.id = 'accreditation_' + accreditationCount;
        accredInput.classList.add('form-control');
        accredCol.appendChild(accredLabel);
        accredCol.appendChild(accredInput);

        // Delete button column
        const deleteCol = document.createElement('div');
        deleteCol.classList.add('col-lg-6');
        const deleteButton = document.createElement('button');
        deleteButton.type = 'button';
        deleteButton.classList.add('btn', 'btn-danger');
        deleteButton.innerHTML = '<i class="fas fa-trash-alt"></i> Delete Accreditation';

        // Delete button functionality
        deleteButton.addEventListener('click', function() {
            rowContainer.removeChild(newRow);
            accreditationCount = accreditationCount - 1;
        });

        deleteCol.appendChild(deleteButton);

        // Append columns to new row
        newRow.appendChild(accredCol);
        newRow.appendChild(deleteCol);

        // Add new row to container
        rowContainer.appendChild(newRow);
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

        const fieldDiv = document.createElement('div');
        fieldDiv.classList.add('field', 'w-75');

        const areaLabel = document.createElement('label');
        areaLabel.setAttribute('for', 'product_service_area_' + count);
        areaLabel.innerText = 'Product Service Area ' + count;

        const areaInput = document.createElement('input');
        areaInput.type = 'text';
        areaInput.name = 'product_service_area[]';
        areaInput.id = 'product_service_area_' + count;
        areaInput.classList.add('form-control');

        fieldDiv.appendChild(areaLabel);
        fieldDiv.appendChild(areaInput);
        flexFieldBtn.appendChild(fieldDiv);

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
