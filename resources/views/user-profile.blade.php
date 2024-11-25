@extends('user.layouts.main')
@section('content')

<section class="user_company_profile">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="custom_card_profile card_profile_first">
                    
                    <div class="row">
                        <div class="col-12">
                            <div class="profile_pic">
                                <img src="{{ $user->photo ? Storage::url($user->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}" alt="">
                            </div>
                            <h1 class="profile_heading text-center">
                                My Profile
                            </h1>
                        </div>
                        <div class="col-lg-6">
                            <p class="profile_data">
                                {{ $user->first_name }}
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="profile_data">
                                {{ $user->last_name }}
                            </p>
                        </div>
                        <div class="col-lg-12">
                            <p class="profile_data">
                                {{$user->company->company_position}}
                            </p>
                        </div>
                        <div class="col-lg-12">
                            <p class="profile_data">
                                {{$user->company->company_name}}
                            </p>
                        </div>
                    </div>
                    <div class="btn-flex">
                        <a href="javascript:void(0)" class="btn btn-secondary">Direct Message</a>
                        <a href="{{ $user->linkedin_url ?? 'javascript:void(0)' }}" target="_blank" class="btn btn-primary">Connect Via Linkedin</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="custom_card_profile">
                    <div class="company_logo profile_data mt-0">
                        <img src="{{ isset($user->company) && $user->company->company_logo ? Storage::url($user->company->company_logo) : 'http://i.pravatar.cc/500?img=7' }}" alt="">
                    </div>
                    <p class="profile_data">
                        {{$user->company->company_name}}
                    </p>
                    <h1 class="profile_heading">
                        Description
                    </h1>
                    <p class="profile_data mt-2 description_border">
                        {{ $user->company->company_about ?? 'Not provided' }}
                    </p>
                    <h1 class="profile_data profile_heading ">
                        Location
                    </h1>
                    <div class="row location_data">
                        <div class="col-lg-12">
                            <p class="profile_data">
                                {{ $user->company->company_address }}
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="profile_data">
                                {{ $user->company->company_city }}
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="profile_data">
                                {{ $user->company->company_state }}
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="profile_data">
                                {{ $user->company->company_county }}
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="profile_data">
                                {{ $user->company->company_country }}
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <p class="profile_data">
                                {{ $user->company->company_zip_code }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="custom_card_profile">
                   
                    <h1 class="profile_data profile_heading mt-0">
                        Business Details
                    </h1>
                    <div class="row location_data">
                        <div class="col-lg-6">
                            <h2 class="profile_subheading">
                                Revenue
                            </h2>
                            <p class="profile_data">
                                ${{ $user->company->company_revenue }}
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <h2 class="profile_subheading">
                                No. of Employees
                            </h2>
                            <p class="profile_data">
                                {{ $user->company->company_no_of_employee }}
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <h2 class="profile_subheading">
                                Business Type
                            </h2>
                            <p class="profile_data">
                                {{ $user->company->company_business_type }}
                            </p>
                        </div>
                        <div class="col-lg-6">
                            <h2 class="profile_subheading">
                                Industry
                            </h2>
                            <p class="profile_data">
                                {{ $user->company->company_sub_category }}, {{ $user->company->company_industry }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12">
                <div class="custom_card_profile">
                   
                    <h1 class="profile_data profile_heading mt-0">
                        Product/Services
                    </h1>
                    <div class="accordion mt-4" id="accordionExample">
                        @forelse ($user->company->productServices as $productService)
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $loop->index }}" aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                            aria-controls="collapse{{ $loop->index }}">
                                        {{ $productService->product_service_name }}
                                    </button>
                                </h2>
                                <div id="collapse{{ $loop->index }}" class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                    data-bs-parent="#accordionExample">
                                    <div class="accordion-body">
                                        {{ $productService->product_service_description }}
                                    </div>
                                </div>
                            </div>
                        @empty
                        <p>No Product or Service to show.</p>    
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</section>


@endsection