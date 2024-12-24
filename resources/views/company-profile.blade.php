@extends('layouts.main')
@section('content')
    <div class="details_card">
        <div class="user_profile_flex align-items-center">
            <div class="profile_square">
                <img src="{{ $company->company_logo ? Storage::url($company->company_logo) : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcd5J_YDIyLfeZCHcsBpcuN8irwbIJ_VDl0Q&s' }}"
                    alt="Company Logo">
            </div>
            <div class="basic_details">
                <div class="row">
                    <div class="col-lg-6">
                        <label class="details_label">
                            Company Name
                        </label>
                        <p class="details_data">
                            {{ $company->company_name }}
                        </p>
                    </div>
                    <div class="col-lg-6">
                        <label class="details_label">
                            Company Email
                        </label>
                        <p class="details_data">
                            {{ substr($company->company_email, 0, 1) . '*********@********m' }}

                        </p>
                    </div>
                    <div class="col-lg-6">
                        <label class="details_label">
                            Company Website
                        </label>
                        <p class="details_data">
                            <a href="{{ $company->company_web_url }}" target="_blank">{{ $company->company_web_url }}</a>
                        </p>
                    </div>
                    <div class="col-lg-6">
                        <label class="details_label">
                            Company Linkedin
                        </label>
                        <p class="details_data">
                            <a href="{{ $company->company_linkedin_url }}"
                                target="_blank">{{ $company->company_linkedin_url }}</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
        <div class="all_details">
            <h3>Company Description</h3>
            <div class="row">
                <div class="col-lg-12">
                    <p class="details_data">
                        {{ $company->company_about ?? 'Not provided' }}
                    </p>
                </div>
            </div>
        </div>
        <div class="all_details">
            <h3>Business Details</h3>
            <div class="row">
                {{-- <div class="col-lg-4">
                <label class="details_label">
                    Position/Designation
                </label>
                <p class="details_data">
                    {{ $company->company_position }}
                </p>
            </div> --}}

                <div class="col-lg-4">
                    <label class="details_label">
                        Revenue
                    </label>
                    <p class="details_data">
                        ${{ $company->company_revenue }}
                    </p>
                </div>



                <div class="col-lg-4">
                    <label class="details_label">
                        No. of Employees
                    </label>
                    <p class="details_data">
                        {{ $company->company_no_of_employee }}
                    </p>
                </div>
                <div class="col-lg-4">
                    <label class="details_label">
                        Business Type
                    </label>
                    <p class="details_data">
                        {{ $company->company_business_type }}
                    </p>
                </div>
                <div class="col-lg-4">
                    <label class="details_label">
                        Industry
                    </label>
                    <p class="details_data">
                        {{ $company->company_sub_category }}, {{ $company->company_industry }}
                    </p>
                </div>

            </div>
        </div>
        <div class="all_details">
            <h3>Product / Service</h3>
            <div class="row">
                <div class="col-12">
                    <div class="accordion" id="accordionExample">
                        @forelse ($company->productServices as $productService)
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
        <div class="all_details">
            <h3>Location</h3>
            <div class="row">
                <div class="col-lg-4">
                    <label class="details_label">
                        Address
                    </label>
                    <p class="details_data">
                        {{ $company->company_address }}
                    </p>
                </div>
                <div class="col-lg-4">
                    <label class="details_label">
                        City
                    </label>
                    <p class="details_data">
                        {{ $company->company_city }}
                    </p>
                </div>
                <div class="col-lg-4">
                    <label class="details_label">
                        State
                    </label>
                    <p class="details_data">
                        {{ $company->company_state }}
                    </p>
                </div>

                <div class="col-lg-4">
                    <label class="details_label">
                        County
                    </label>
                    <p class="details_data">
                        {{ $company->company_county }}
                    </p>
                </div>

                <div class="col-lg-4">
                    <label class="details_label">
                        Country
                    </label>
                    <p class="details_data">
                        {{ $company->company_country }}
                    </p>
                </div>




                <div class="col-lg-4">
                    <label class="details_label">
                        Zip Code
                    </label>
                    <p class="details_data">
                        {{ $company->company_zip_code }}
                    </p>
                </div>
            </div>
        </div>
        <div class="all_details">
            <h3>Other Details</h3>
            <div class="row">
                <div class="col-lg-4">
                    <label class="details_label">
                        Does the business engage in community service?
                    </label>
                    <p class="details_data">
                        {{ $company->company_community_service }}
                    </p>
                </div>
                <div class="col-lg-4">
                    <label class="details_label">
                        Business Contributions to Muslim Community
                    </label>
                    <p class="details_data">
                        {{ $company->company_contribute_to_muslim_community }}
                    </p>
                </div>
                <div class="col-lg-4">
                    <label class="details_label">
                        Affiliations with Muslim Organizations/Networks
                    </label>
                    <p class="details_data">
                        {{ $company->company_affiliation_to_muslim_org }}
                    </p>
                </div>
            </div>
        </div>
        
    </div>
@endsection
