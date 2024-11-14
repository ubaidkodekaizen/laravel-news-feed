@extends('user.layouts.main')
@section('content')
<section class="stepper_form">
    <div class="container">
        <form action="{{ route('user.company.update') }}" method="POST" class="user_form" enctype="multipart/form-data" id="user_company">
            @csrf
            <div class="section_heading">
                <h1>Company Details</h1>
            </div>
            <div class="row">
                <!-- Avatar Upload -->
                <div class="col-12">
                    <div class="avatar-upload">
                        <div class="avatar-edit">
                            <input type='file' id="imageUpload"  name="company_logo" accept=".png, .jpg, .jpeg" />
                            <label for="imageUpload"></label>
                        </div>
                        <div class="avatar-preview">
                            <div id="imagePreview" style="background-image: url('{{ isset($company) && $company->company_logo ? Storage::url($company->company_logo) : 'http://i.pravatar.cc/500?img=7' }}');">
                            </div>
                        </div>
                        <label class="text-center w-100">Upload Logo</label>
                    </div>
                </div>

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
        
                <!-- Company Name -->
                <div class="col-lg-6">
                    <label for="company_name">Company Name</label>
                    <input type="text" name="company_name" id="company_name" class="form-control" value="{{ old('company_name', $company->company_name ?? '') }}" required>
                </div>
        
                <!-- Company Email -->
                <div class="col-lg-6">
                    <label for="company_email">Company Email</label>
                    <input type="email" name="company_email" id="company_email" class="form-control" value="{{ old('company_email', $company->company_email ?? '') }}" required>
                </div>
        
                <!-- Company Web URL -->
                <div class="col-lg-6">
                    <label for="company_web_url">Company Web URL</label>
                    <input type="text" name="company_web_url" id="company_web_url" class="form-control" value="{{ old('company_web_url', $company->company_web_url ?? '') }}" required>
                </div>
        
                <!-- Company Linkedin URL -->
                <div class="col-lg-6">
                    <label for="company_linkedin_url">Company Linkedin URL</label>
                    <input type="text" name="company_linkedin_url" id="company_linkedin_url" class="form-control" value="{{ old('company_linkedin_url', $company->company_linkedin_url ?? '') }}" required>
                </div>
        
                <!-- Position/Designation -->
                <div class="col-lg-6">
                    <label for="company_position">Position/Designation</label>
                    <input type="text" name="company_position" id="company_position" class="form-control" value="{{ old('company_position', $company->company_position ?? '') }}" required>
                </div>

                <!-- Company Revenue -->
                <div class="col-lg-6">
                    <label for="company_revenue">Company Revenue:</label>
                    {!! \App\Helpers\DropDownHelper::renderRevenueDropdown($company->company_revenue ?? '') !!}
                </div>
        
                <!-- Company Description -->
                <div class="col-lg-12">
                    <label for="company_about">Company Description</label>
                    <textarea name="company_about" id="company_about" class="form-control" cols="30" rows="5" required>{{ old('company_about', $company->company_about ?? '') }}</textarea>
                </div>
        
                <!-- Company Address -->
                <div class="col-lg-6">
                    <label for="company_address">Company Address</label>
                    <input type="text" name="company_address" id="company_address" class="form-control" value="{{ old('company_address', $company->company_address ?? '') }}" required>
                </div>
        
                <div class="col-lg-6">
                    <label for="company_country">Company Country</label>
                    {!! \App\Helpers\DropDownHelper::renderCountryDropdown($company->company_country ?? '') !!}
                </div>
                
               
                <div class="col-lg-6">
                    <label for="company_state">Company State</label>
                    {!! \App\Helpers\DropDownHelper::renderStateDropdown($company->company_country ?? '', $company->company_state ?? '') !!}
                </div>
        
                <!-- Company City -->
                <div class="col-lg-6">
                    <label for="company_city">Company City</label>
                    {!! \App\Helpers\DropDownHelper::renderCityDropdown($company->company_state ?? '', $company->company_city ?? '') !!}
                </div>
        
                <!-- Company County -->
                <div class="col-lg-6">
                    <label for="company_county">Company County</label>
                    <input type="text" name="company_county" id="company_county" class="form-control" value="{{ old('company_county', $company->company_county ?? '') }}" required>
                </div>
        
                <!-- Company Zip Code -->
                <div class="col-lg-6">
                    <label for="company_zip_code">Company Zip Code</label>
                    <input type="text" name="company_zip_code" id="company_zip_code" class="form-control" value="{{ old('company_zip_code', $company->company_zip_code ?? '') }}" required>
                </div>
        
                <!-- Number of Employees Dropdown -->
                <div class="col-lg-6">
                    <label for="company_no_of_employee">Company No. of Employees</label>
                    {!! \App\Helpers\DropDownHelper::renderEmployeeSizeDropdown($company->company_no_of_employee ?? '') !!}
                </div>
        
                <!-- Business Type Dropdown -->
                <div class="col-lg-6">
                    <label for="company_business_type">Company Business Type</label>
                    {!! \App\Helpers\DropDownHelper::renderBusinessTypeDropdown($company->company_business_type ?? '') !!}
                </div>
        
                <!-- Industry Dropdown -->
                <div class="col-lg-6">
                    <label for="company_industry">Company Industry</label>
                    {!! \App\Helpers\DropDownHelper::renderIndustryDropdown($company->company_industry ?? '', $company->company_sub_category ?? '') !!}
                </div>
        
                <!-- Sub Category Dropdown -->
                <div class="col-lg-6">
                    <label for="company_sub_category">Company Sub Category</label>
                    {!! \App\Helpers\DropDownHelper::renderSubcategoryDropdown( $company->company_industry ?? '', $company->company_sub_category ?? '') !!}
                </div>
        
                <!-- Community Service -->
                <div class="col-lg-6">
                    <label for="company_community_service">Does the business engage in community service?</label>
                    <select name="company_community_service" id="company_community_service" class="form-select" required>
                        <option value="">Select Company Community Service</option>
                        <option value="Yes" {{ old('company_community_service', $company->company_community_service ?? '') == 'Yes' ? 'selected' : '' }}>Yes</option>
                        <option value="No" {{ old('company_community_service', $company->company_community_service ?? '') == 'No' ? 'selected' : '' }}>No</option>
                    </select>
                </div>
        
                <!-- Contributions to Muslim Community Dropdown -->
                <div class="col-lg-6">
                    <label for="company_contribute_to_muslim_community">Business Contributions to Muslim Community</label>
                    {!! \App\Helpers\DropDownHelper::renderBusinessContributionsToMuslimCommunityDropdown(old('company_contribute_to_muslim_community', $company->company_contribute_to_muslim_community ?? '')) !!}
                </div>
        
                <!-- Affiliations with Muslim Organizations Dropdown -->
                <div class="col-lg-6">
                    <label for="company_affiliation_to_muslim_org">Affiliations with Muslim Organizations/Networks?</label>
                    {!! \App\Helpers\DropDownHelper::renderAffiliationToMuslimOrgDropdown(old('company_affiliation_to_muslim_org', $company->company_affiliation_to_muslim_org ?? '')) !!}
                </div>
        
                <!-- Products/Services -->
                <div class="col-12">
                    <div class="row" id="product-service-row">
                        <div class="col-lg-4">
                            <label for="product_service_name">Products/Services you offer Name</label>
                            <input type="text" name="product_service_name[]" id="product_service_name" class="form-control" required>
                        </div>
                        <div class="col-lg-4">
                            <label for="product_service_description">Products/Services you offer Description</label>
                            <input type="text" name="product_service_description[]" id="product_service_description" class="form-control" required>
                        </div>
                        <div class="col-lg-4">
                            <div class="flex_field_btn">
                                <div class="field w-75">
                                    <label for="product_service_area">Product Service Area</label>
                                    <input type="text" name="product_service_area[]" id="product_service_area" class="form-control">
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
                            <input type="text" name="accreditation[]" id="accreditation" class="form-control">
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
</section>
<script>
    let accreditationCount = 1;

    document.getElementById('add-accreditation').addEventListener('click', function () {
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
        deleteButton.addEventListener('click', function () {
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
</script>


<script>
    let count = 1;

    document.getElementById('add-product-service').addEventListener('click', function () {
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
        deleteButton.onclick = function () {
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