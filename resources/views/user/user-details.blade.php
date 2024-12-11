@extends('layouts.main')
@section('content')

<section class="user_company_profile">
    <div class="container">
        <div class="custom_card_profile card_profile_first new_user_details">
            <form action="{{ route('user.details.update') }}" method="POST" enctype="multipart/form-data"
                id="user_details">
                @csrf
                <div class="row">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-12">
                                <div class="profile_pic">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="imageUpload"  name="photo" accept=".png, .jpg, .jpeg" />
                                            <label for="imageUpload"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imagePreview">
                                                <img src="{{ $user->photo ? asset('storage/' .$user->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
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
                            <div class="col-lg-6">
                                <label for="first_name">First Name</label>
                                <input type="text" name="first_name" id="first_name" class="form-control"
                                    value="{{ old('first_name', $user->first_name) }}">
                            </div>
        
                            <div class="col-lg-6">
                                <label for="last_name">Last Name</label>
                                <input type="text" name="last_name" id="last_name" class="form-control"
                                    value="{{ old('last_name', $user->last_name) }}">
                            </div>
                            <div class="col-lg-12">
                                <label for="email">Email</label>
                                <input type="email" name="email" id="email" class="form-control"
                                    value="{{ old('email', $user->email) }}">
                            </div>
        
                            <div class="col-lg-12">
                                <label for="phone">Phone</label>
                                <input type="tel" name="phone" id="phone" class="form-control"
                                    value="{{ old('phone', $user->phone) }}">
                            </div>
        
                            <div class="col-lg-12">
                                <label for="linkedin_url">LinkedIn</label>
                                <div class="input-group">
                                    <div class="input-group-text">https://www.linkedin.com/in/</div>
                                    <input type="text" name="linkedin_user" id="linkedin_user" class="form-control"
                                        value="{{ old('linkedin_url', str_replace('https://www.linkedin.com/in/', '', $user->linkedin_url)) }}">
                                </div>
                                <input type="hidden" name="linkedin_url" id="linkedin_url_hidden" value="">
        
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
                                <label for="facebook_url" class="mt-0">Facebook URL</label>
                                <input type="text" name="facebook_url" id="facebook_url" class="form-control"
                                    value="{{ old('facebook_url', $user->facebook_url) }}" placeholder="Link">
                            </div>
        
                            <div class="col-lg-12">
                                <label for="address">Address</label>
                                <input type="text" name="address" id="address" class="form-control" placeholder="Enter your address" value="{{ old('address', $user->address) }}">
        
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
                                <input type="text" name="zip_code" id="zip_code" class="form-control"
                                    value="{{ old('zip_code', $user->zip_code) }}">
                            </div>
        
                            <div class="col-lg-12">
                                <label for="industry_to_connect">Industry you would like to Connect to</label>
                                {!! \App\Helpers\DropDownHelper::renderIndustryDropdownForUser(
                                    $user->industry_to_connect,
                                    $user->sub_category_to_connect,
                                ) !!}
                                <div id="industry_other_field" style="display: none;">
                                    <label for="industry_to_connect_other">Other Industry</label>
                                    <input type="text" name="industry_to_connect_other" id="industry_to_connect_other" class="form-control" placeholder="Enter other industry">
                                </div>
                            </div>
                            
                            <div class="col-lg-12">
                                <label for="sub_category_to_connect">Sub Category to Connect</label>
                                <select name="sub_category_to_connect" id="sub_category_to_connect" class="form-select">
                                    <option value="">Select Company Sub Category</option>
                                </select>
                                <div id="subcategory_other_field" style="display: none;">
                                    <label for="sub_category_to_connect_other">Other Sub Category</label>
                                    <input type="text" name="sub_category_to_connect_other" id="sub_category_to_connect_other" class="form-control" placeholder="Enter other sub category">
                                </div>
                            </div>
                            
                            
                             
                            <div class="col-lg-12">
                                <label for="community_interest">Community Interest</label>
                                {!! \App\Helpers\DropDownHelper::renderCommunityInterestDropdown($user->community_interest) !!}
                                <div id="community_interest_other_field" style="display: none;">
                                    <label for="community_interest_other">Other Community Interest</label>
                                    <input type="text" name="community_interest_other" id="community_interest_other" class="form-control" placeholder="Enter other community interest">
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
</section> 


    
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const industryDropdown = document.getElementById("industry_to_connect");
            const subCategoryDropdown = document.getElementById("sub_category_to_connect");
            const selectedSubcategory = "{{ $user->sub_category_to_connect }}";

            industryDropdown.addEventListener("change", function() {
                loadSubcategories(industryDropdown.value);
            });

            function loadSubcategories(industryName) {
                subCategoryDropdown.innerHTML = "<option value=\'\'>Select Sub Industry</option>";

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


        document.addEventListener('DOMContentLoaded', function () {
            // Add event listeners for each dropdown
            document.querySelector('select[name="industry_to_connect"]').addEventListener('change', function () {
                toggleOtherField(this, 'industry_other_field');
            });

            document.querySelector('select[name="sub_category_to_connect"]').addEventListener('change', function () {
                toggleOtherField(this, 'subcategory_other_field');
            });

            document.querySelector('select[name="community_interest"]').addEventListener('change', function () {
                toggleOtherField(this, 'community_interest_other_field');
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


@endsection
