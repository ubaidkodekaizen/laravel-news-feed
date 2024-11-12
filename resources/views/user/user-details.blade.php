@extends('user.layouts.main')
@section('content')

<section class="stepper_form">
    <div class="container">
        <form action="{{ route('user.details.update') }}" method="POST" class="user_form" enctype="multipart/form-data" id="user_details">
            @csrf
            <div class="section_heading">
                <h1>User Details</h1>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="avatar-upload">
                        <div class="avatar-edit">
                            <input type='file' id="imageUpload" name="photo" accept=".png, .jpg, .jpeg" />
                            <label for="imageUpload"></label>
                        </div>
                        <div class="avatar-preview">
                            <div id="imagePreview" style="background-image: url('{{ $user->photo ? Storage::url($user->photo) : 'http://i.pravatar.cc/500?img=7' }}');">
                            </div>
                        </div>
                        <label for="" class="text-center w-100">Upload Profile</label>
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
        
                <div class="col-lg-6">
                    <label for="first_name">First Name</label>
                    <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}">
                </div>
        
                <div class="col-lg-6">
                    <label for="last_name">Last Name</label>
                    <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}">
                </div>
        
                <div class="col-lg-6">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}">
                </div>
        
                <div class="col-lg-6">
                    <label for="phone">Phone</label>
                    <input type="tel" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                </div>
        
                <div class="col-lg-6">
                    <label for="linkedin_url">Linkedin URL</label>
                    <input type="text" name="linkedin_url" id="linkedin_url" class="form-control" value="{{ old('linkedin_url', $user->linkedin_url) }}">
                </div>
        
                <div class="col-lg-6">
                    <label for="x_url">X URL</label>
                    <input type="text" name="x_url" id="x_url" class="form-control" value="{{ old('x_url', $user->x_url) }}">
                </div>
        
                <div class="col-lg-6">
                    <label for="instagram_url">Instagram URL</label>
                    <input type="text" name="instagram_url" id="instagram_url" class="form-control" value="{{ old('instagram_url', $user->instagram_url) }}">
                </div>
        
                <div class="col-lg-6">
                    <label for="facebook_url">Facebook URL</label>
                    <input type="text" name="facebook_url" id="facebook_url" class="form-control" value="{{ old('facebook_url', $user->facebook_url) }}">
                </div>
        
                <div class="col-lg-6">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address" class="form-control" value="{{ old('address', $user->address) }}">
                </div>
        
                <div class="col-lg-6">
                    <label for="country">Country</label>
                    {!! \App\Helpers\DropDownHelper::renderCountryDropdownForUser($user->country) !!}
                </div>
                
                <div class="col-lg-6">
                    <label for="state">State</label>
                    {!! \App\Helpers\DropDownHelper::renderStateDropdownForUser($user->country, $user->state) !!}
                </div>
                
                <div class="col-lg-6">
                    <label for="city">City</label>
                    {!! \App\Helpers\DropDownHelper::renderCityDropdownForUser($user->state, $user->city) !!}
                </div>
                
                
        
                <div class="col-lg-6">
                    <label for="county">County</label>
                    <input type="text" name="county" id="county" class="form-control" value="{{ old('county', $user->county) }}">
                </div>
        
                <div class="col-lg-6">
                    <label for="zip_code">Zip Code</label>
                    <input type="text" name="zip_code" id="zip_code" class="form-control" value="{{ old('zip_code', $user->zip_code) }}">
                </div>
        
                <div class="col-lg-6">
                    <label for="industry_to_connect">Industry you would like to Connect to</label>
                    {!! \App\Helpers\DropDownHelper::renderIndustryDropdownForUser($user->industry_to_connect, $user->sub_category_to_connect) !!}
                </div>
        
                <div class="col-lg-6">
                    <label for="sub_category_to_connect">Sub Category to Connect</label>
                    {!! \App\Helpers\DropDownHelper::renderSubcategoryDropdownForUser($user->industry_to_connect, $user->sub_category_to_connect) !!}
                </div>
        
                <div class="col-lg-6">
                    <label for="community_interest">Community Interest</label>
                    {!! \App\Helpers\DropDownHelper::renderCommunityInterestDropdown($user->community_interest) !!}
                </div>
        
                <div class="col-12 mt-4">
                    <button type="submit" class="btn btn-primary w-100">Save</button>
                </div>
            </div>
        </form>
        
    </div>
</section> 


@endsection