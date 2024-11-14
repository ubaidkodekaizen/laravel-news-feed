@extends('user.layouts.main')
@section('content')
<div class="details_card">
    <div class="user_profile_flex">
        <div class="profile_square">
            <img src="{{ $user->photo ? Storage::url($user->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}" alt="User Image">
        </div>
        <div class="basic_details">
            <div class="row">
                <div class="col-lg-6">
                    <label class="details_label">
                        First Name
                    </label>
                    <p class="details_data">
                        {{ $user->first_name }}
                    </p>
                </div>
                <div class="col-lg-6">
                    <label class="details_label">
                        Last Name
                    </label>
                    <p class="details_data">
                        {{ $user->last_name }}
                    </p>
                </div>
                <div class="col-lg-6">
                    <label class="details_label">
                        Email
                    </label>
                    <p class="details_data">
                        {{ substr($user->email, 0, 1) . '*********@********m' }}
                    </p>
                </div>
                
                <div class="col-lg-6">
                    <label class="details_label">
                        Phone
                    </label>
                    <p class="details_data">
                        {{ $user->phone ? '************' : 'Not provided' }}
                    </p>
                </div>
                
            </div>
        </div>
    </div>
    <div class="all_details">
        <div class="row">
            <div class="col-lg-4">
                <label class="details_label">
                    Linkedin URL
                </label>
                <p class="details_data">
                    <a href="{{ $user->linkedin_url ?? 'javascript:void(0)' }}" target="_blank">{{ $user->linkedin_url ?? 'none' }}</a>
                </p>
            </div>
            <div class="col-lg-4">
                <label class="details_label">
                    X URL
                </label>
                <p class="details_data">
                    <a href="{{ $user->x_url ?? 'javascript:void(0)' }}">{{ $user->x_url ?? 'none' }}</a>
                </p>
            </div>
            <div class="col-lg-4">
                <label class="details_label">
                    Instagram URL
                </label>
                <p class="details_data">
                    <a href="{{ $user->instagram_url ?? 'javascript:void(0)' }}">{{ $user->instagram_url ?? 'none' }}</a>
                </p>
            </div>

            <div class="col-lg-4">
                <label class="details_label">
                    Facebook URL
                </label>
                <p class="details_data">
                    <a href="{{ $user->facebook_url ?? 'javascript:void(0)' }}">{{ $user->facebook_url ?? 'none' }}</a>
                </p>
            </div>
            <div class="col-lg-4">
                <label class="details_label">
                    Address
                </label>
                <p class="details_data">
                    {{ $user->address ?? 'Not provided' }}
                </p>
            </div>
            <div class="col-lg-4">
                <label class="details_label">
                    Country
                </label>
                <p class="details_data">
                    {{ $user->country ?? 'Not provided' }}
                </p>
            </div>

            <div class="col-lg-4">
                <label class="details_label">
                    State
                </label>
                <p class="details_data">
                    {{ $user->state ?? 'Not provided' }}
                </p>
            </div>
            <div class="col-lg-4">
                <label class="details_label">
                    City
                </label>
                <p class="details_data">
                    {{ $user->city ?? 'Not provided' }}
                </p>
            </div>

            <div class="col-lg-4">
                <label class="details_label">
                    County
                </label>
                <p class="details_data">
                    {{ $user->county ?? 'Not provided' }}
                </p>
            </div>
            <div class="col-lg-4">
                <label class="details_label">
                    Zip Code
                </label>
                <p class="details_data">
                    {{ $user->zip_code ?? 'Not provided' }}
                </p>
            </div>

            <div class="col-lg-4">
                <label class="details_label">
                    Industry Interested In
                </label>
                <p class="details_data">
                    {{ $user->sub_category_to_connect ?? 'Not provided' }}, {{ $user->industry_to_connect ?? 'Not provided' }}
                </p>
            </div>
            
            <div class="col-lg-4">
                <label class="details_label">
                    Community Interest
                </label>
                <p class="details_data">
                    {{ $user->community_interest ?? 'Not provided' }}
                </p>
            </div>
        </div>
    </div>
</div>

@endsection