@extends('layouts.main')
@section('content')
    <style>
        .user_company_profile .profile_pic img {
            border-radius: 15px;
            height: 300px;
            width: 300px;
            margin: 0 auto;
            object-fit: cover;
            max-width: 100%;
            display: block;
            border: 2px solid var(--primary);
        }
    </style>

    <section class="user_company_profile">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="custom_card_profile card_profile_first">

                        <div class="row">
                            <div class="col-12">
                                <div class="profile_pic">
                                    <img src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
                                        alt="">
                                </div>
                                <h1 class="profile_heading text-center">
                                    User Profile
                                </h1>
                            </div>
                            <div class="col-lg-12">
                                <div class="profile_div">
                                    <label for="i_am">I am</label>
                                    <p class="profile_data">
                                        {{ $user->user_position ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="profile_div">
                                    <label for="first_name">First Name</label>
                                    <p class="profile_data">
                                        {{ $user->first_name ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="profile_div">
                                    <label for="first_name">Last Name</label>
                                    <p class="profile_data">
                                        {{ $user->last_name ?? '' }}
                                    </p>
                                </div>
                            </div>

                            @if ($user->phone_public == 'Yes')
                                <div class="col-lg-6">
                                    <div class="profile_div">
                                        <label for="mobile">Cell/Mobile</label>
                                        <p class="profile_data">
                                            {{ $user->phone ?? '' }}
                                        </p>
                                    </div>

                                </div>
                            @endif

                            @if ($user->email_public == 'Yes')
                                <div class="col-lg-6">

                                    <div class="profile_div">
                                        <label for="email">Email</label>
                                        <p class="profile_data">
                                            {{ $user->email ?? '' }}
                                        </p>
                                    </div>

                                </div>
                            @endif

                            <div class="col-lg-6">
                                <div class="profile_div">
                                    <label for="city">City</label>
                                    <p class="profile_data">
                                        {{ $user->city ?? '' }}
                                    </p>
                                </div>
                            </div>
                            @if ($user->county)
                                <div class="col-lg-6">
                                    <div class="profile_div">
                                        <label for="county">County</label>
                                        <p class="profile_data">
                                            {{ $user->county ?? '' }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                            @if ($user->state)
                                <div class="col-lg-6">
                                    <div class="profile_div">
                                        <label for="state">State</label>
                                        <p class="profile_data">
                                            {{ $user->state ?? '' }}
                                        </p>
                                    </div>
                                </div>
                            @endif
                            <div class="col-lg-6">
                                <div class="profile_div">
                                    <label for="country">Country</label>
                                    <p class="profile_data">
                                        {{ $user->country ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="profile_div">
                                    <label for="gender">Gender</label>
                                    <p class="profile_data">
                                        {{ $user->gender ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="profile_div">
                                    <label for="age_group">Age Group</label>
                                    <p class="profile_data">
                                        {{ $user->age_group ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="profile_div">
                                    <label for="ethnicity">Ethnicity</label>
                                    <p class="profile_data">
                                        {{ $user->ethnicity ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="profile_div">
                                    <label for="nationality">Nationality</label>
                                    <p class="profile_data">
                                        {{ $user->nationality ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="profile_div">
                                    <label for="languages">Languages</label>
                                    <p class="profile_data">
                                        {{ $user->languages ?? '' }}
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="profile_div">
                                    <label for="marital_status">Marital Status</label>
                                    <p class="profile_data">
                                        {{ $user->marital_status ?? '' }}
                                    </p>
                                </div>
                            </div>

                            <h1 class="profile_data profile_heading mt-4">
                                Social Links
                            </h1>
                            <ul class="list_check_flex px-3">
                                @if ($user->facebook_url)
                                    <li>
                                        <a href="{{ $user->facebook_url }}" target="_blank" title="Facebook">
                                            <img src="{{ asset('assets/images/social-icons/facebook.png') }}"
                                                alt="">
                                        </a>
                                    </li>
                                @endif

                                @if ($user->x_url)
                                    <li>
                                        <a href="{{ $user->x_url }}" target="_blank" title="X (Formerly Twitter)">
                                            <img src="{{ asset('assets/images/social-icons/twitter.png') }}"
                                                alt="">
                                        </a>
                                    </li>
                                @endif

                                @if ($user->instagram_url)
                                    <li>
                                        <a href="{{ $user->instagram_url }}" target="_blank" title="Instagram">
                                            <img src="{{ asset('assets/images/social-icons/instagram.png') }}"
                                                alt="">
                                        </a>
                                    </li>
                                @endif

                                @if ($user->tiktok_url)
                                    <li>
                                        <a href="{{ $user->tiktok_url }}" target="_blank" title="TikTok">
                                            <img src="{{ asset('assets/images/social-icons/tiktok.png') }}" alt="">
                                        </a>
                                    </li>
                                @endif

                                @if ($user->youtube_url)
                                    <li>
                                        <a href="{{ $user->youtube_url }}" target="_blank" title="YouTube">
                                            <img src="{{ asset('assets/images/social-icons/youtube.png') }}"
                                                alt="">
                                        </a>
                                    </li>
                                @endif
                            </ul>



                        </div>
                        <div class="btn-flex">
                            <a href="javascript:void(0)" class="btn btn-secondary" data-bs-toggle="modal"
                                data-bs-target="#mainModal">
                                Direct Message
                            </a>
                            <a href="https://www.linkedin.com/in/{{ $user->linkedin_url }}" target="_blank"
                                class="btn btn-primary">Connect Via Linkedin</a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="custom_card_profile">
                        <div class="company_logo profile_data mt-0">
                            <img src="{{ isset($user->company) && $user->company->company_logo ? asset('storage/' . $user->company->company_logo) : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcd5J_YDIyLfeZCHcsBpcuN8irwbIJ_VDl0Q&s' }}"
                                alt="">
                        </div>
                        <div class="profile_div">
                            <label for="company_name">Company Name</label>
                            <p class="profile_data">
                                {{ $user->company->company_name }}
                            </p>
                        </div>
                        <div class="col-lg-12 profile_div">
                            <label for="title_designation">Title/Designation</label>
                            <p class="profile_data">
                                {{ $user->company->company_position }}
                            </p>
                        </div>
                        <div class="profile_div">
                            <label for="company_url">Company URL</label>
                            <p class="profile_data">
                                {{ $user->company->company_web_url }}
                            </p>
                        </div>
                        <div class="profile_div">
                            <label for="years_of_experience">Years of Experience</label>
                            <p class="profile_data">
                                {{ $user->company->company_experience }}
                            </p>
                        </div>
                        <div class="profile_div">
                            <label for="work_phone">Work Phone Number</label>
                            <p class="profile_data">
                                {{ $user->company->company_phone }}
                            </p>
                        </div>
                        <div class="profile_div">
                            <label for="company_linkedin">Company LinkedIn Page</label>
                            <p class="profile_data">
                                {{ $user->company->company_linkedin_url }}
                            </p>
                        </div>
                        {{-- <h1 class="profile_heading">
                        Description
                    </h1>
                    <p class="profile_data mt-2 description_border">
                        {{ $user->company->company_about ?? 'Not provided' }}
                    </p> --}}
                        {{-- <h1 class="profile_data profile_heading ">
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
                    </div> --}}
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
                                    {{ $user->company->company_industry }}
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="custom_card_profile">

                        <h1 class="profile_data profile_heading mt-0">
                            Product/Services
                        </h1>
                        <div class="accordion mt-4" id="accordionExample">
                            @forelse ($user->company->productServices as $productService)
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse{{ $loop->index }}"
                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                            aria-controls="collapse{{ $loop->index }}">
                                            {{ $productService->product_service_name }}
                                        </button>
                                    </h2>
                                    <div id="collapse{{ $loop->index }}"
                                        class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
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
                <div class="col-12">

                    <div class="custom_card_profile">

                        <h1 class="profile_data profile_heading mt-0">
                            User Education
                        </h1>
                        <div class="accordion mt-4" id="userEducation">
                            @forelse ($user->userEducations as $education)
                                <div class="accordion-item">
                                    <h2 class="accordion-header">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapse_edu{{ $loop->index }}"
                                            aria-expanded="{{ $loop->first ? 'true' : 'false' }}"
                                            aria-controls="collapse_edu{{ $loop->index }}">
                                            {{ $education->degree_diploma ?? '' }}
                                        </button>
                                    </h2>
                                    <div id="collapse_edu{{ $loop->index }}"
                                        class="accordion-collapse collapse {{ $loop->first ? 'show' : '' }}"
                                        data-bs-parent="#userEducation">
                                        <div class="accordion-body">
                                            {{ $education->college_university ?? '' }} - {{ $education->year ?? '' }}
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

    <!-- Main Modal -->
    <div class="modal fade" id="mainModal" tabindex="-1" aria-labelledby="mainModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--primary); color: #fff;">
                    <h5 class="modal-title" id="mainModalLabel">Feature Coming Soon</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <h3 class="text-center">Direct Messaging is Coming Soon!</h3>
                    <p class="text-center">
                        Our team is working hard to bring you an exciting <strong>Direct Messaging</strong> feature.
                        Soon, you'll be able to connect and communicate seamlessly with others on the platform.
                        Stay tuned for updates!
                    </p>

                </div>
                <div class="modal-footer" style="justify-content: center;">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
@endsection
