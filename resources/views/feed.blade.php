@extends('layouts.main')
@section('content')
    <style>
        .text-danger {
            font-size: 12px;
            margin-bottom: 5px;
            display: block;
        }
    </style>

    <section class="feed_lp">
        <div class="container">
            <h1 class="main_heading">
                Build Your Network
            </h1>
            {{-- <div class="filters">
                <div class="row align-items-end">
                    <!-- Job Title Filter -->
                    <div class="col-lg-3 col-md-6 col-6">
                        <label for="job_titile">Select Job Title</label>
                        <div class="custom-select-dropdown">
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" id="jobTitleDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Job Titles
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="jobTitleDropdown">
                                    <small class="text-danger">Select Job Title</small>
                                    <li>
                                        <div class="form-check">
                                            <label class="form-check-label" for="jobTitle1">Manager</label>
                                            <input class="form-check-input" type="checkbox" value="Manager" id="jobTitle1">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <label class="form-check-label" for="jobTitle2">Engineer</label>
                                            <input class="form-check-input" type="checkbox" value="Engineer" id="jobTitle2">
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Industry Filter -->
                    <div class="col-lg-3 col-md-6 col-6">
                        <label for="industry">Select Industry</label>
                        <div class="custom-select-dropdown">
                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" id="industryDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Industries
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="industryDropdown">
                                    <small class="text-danger">Select Industry</small>
                                    <li>
                                        <div class="form-check">
                                            <label class="form-check-label" for="industry1">IT</label>
                                            <input class="form-check-input" type="checkbox" value="IT" id="industry1">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <label class="form-check-label" for="industry2">Healthcare</label>
                                            <input class="form-check-input" type="checkbox" value="Healthcare"
                                                id="industry2">
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Sub-Industry Filter -->
                    <div class="col-lg-3 col-6">
                        <label for="sub_industry">Select Sub Industry</label>
                        <div class="custom-select-dropdown">

                            <div class="dropdown">
                                <button class="btn btn-light dropdown-toggle" type="button" id="subIndustryDropdown"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                    Sub Industries
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="subIndustryDropdown">
                                    <small class="text-danger">Select Sub Industry</small>
                                    <li>
                                        <div class="form-check">
                                            <label class="form-check-label" for="subIndustry1">Software</label>
                                            <input class="form-check-input" type="checkbox" value="Software"
                                                id="subIndustry1">
                                        </div>
                                    </li>
                                    <li>
                                        <div class="form-check">
                                            <label class="form-check-label" for="subIndustry2">Hardware</label>
                                            <input class="form-check-input" type="checkbox" value="Hardware"
                                                id="subIndustry2">
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Search Button -->
                    <div class="col-lg-3 col-6">
                        <div class="text-center">
                            <button class="btn btn-primary w-50">Search</button>
                        </div>
                    </div>
                </div>
            </div> --}}
        </div>

    </section>

    {{-- <section class="event_slider">
        <div class="container">
            <h2 class="mb-3">Products</h2>
            <div class="swiper">
                <div class="swiper-wrapper">

                    @forelse($events as $event)
                        <div class="swiper-slide">
                            <div class="card">
                                <img src="{{ $event->image ? asset('storage/' . $event->image) : 'https://via.placeholder.com/300x180' }}"
                                    alt="Event Image">
                                <div class="card-content">
                                    <!-- Event Title -->
                                    <div class="details">
                                        <h3>{{ $event->title }}</h3>
                                        <!-- Event City & Venue -->
                                        <p><strong>City:</strong> {{ $event->city }}</p>
                                        <p><strong>Venue:</strong> {{ $event->venue }}</p>

                                        <!-- Event Date & Time -->

                                        <span><strong>Date:</strong>
                                            {{ \Carbon\Carbon::parse($event->date)->format('F j, Y') }}</span>
                                        <span><strong>Time:</strong>
                                            {{ \Carbon\Carbon::parse($event->time)->format('h:i A') }}</span>
                                    </div>

                                    <!-- Book Now Button -->
                                    <a href="{{ $event->url }}" class="view-more">Book Now</a>
                                </div>
                            </div>
                        </div>

                    @empty
                        <div class="swiper-slide">
                            <div class="card">
                                <img src="https://via.placeholder.com/300x180" alt="No Events">
                                <div class="card-content">
                                    <h3>No Events Available</h3>
                                    <p>Stay tuned for upcoming events.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse

                </div>
                <!-- Add Pagination and Navigation -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>

    </section> --}}

    <section class="event_slider">
        <div class="container">
            <h2 class="mb-3">Products</h2>
            <div class="swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="card">
                            <img src="https://placehold.co/300x180" alt="Event Image">
                            <div class="card-content">
                                <div class="service_price_duration my-0 event_price_label">
                                    <p class="service_price">
                                        <span>
                                            <span class="original_price">$ <span class="discounted_price">70</span>
                                                90</span> / 10 Units
                                        </span>
                                    </p>
                                </div>
                                <!-- Event Title -->
                                <div class="details">
                                    <h3>Product 1</h3>
                                    <p>
                                        This is product description This is product description This is product description
                                        This is product description This is product description.
                                    </p>

                                    <div class="service_posted_by mt-2">
                                        <div class="person_profile">
                                            <img src="https://placehold.co/50x50" alt="">
                                        </div>
                                        <div class="posted_name_date">
                                            <h6>Jahanzaib Ansari</h6>
                                            <p>13 Feb 2025</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Book Now Button -->
                                <a href="javascript:void(0);" class="view-more w-100">Message Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card">
                            <img src="https://placehold.co/300x180" alt="Event Image">
                            <div class="card-content">
                                <div class="service_price_duration my-0 event_price_label">
                                    <p class="service_price">
                                        <span>
                                            <span class="original_price">$ <span class="discounted_price">70</span>
                                                90</span> / 10 Units
                                        </span>
                                    </p>
                                </div>
                                <!-- Event Title -->
                                <div class="details">
                                    <h3>Product 1</h3>
                                    <p>
                                        This is product description This is product description This is product description
                                        This is product description This is product description.
                                    </p>

                                    <div class="service_posted_by mt-2">
                                        <div class="person_profile">
                                            <img src="https://placehold.co/50x50" alt="">
                                        </div>
                                        <div class="posted_name_date">
                                            <h6>Jahanzaib Ansari</h6>
                                            <p>13 Feb 2025</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Book Now Button -->
                                <a href="javascript:void(0);" class="view-more w-100">Message Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card">
                            <img src="https://placehold.co/300x180" alt="Event Image">
                            <div class="card-content">
                                <div class="service_price_duration my-0 event_price_label">
                                    <p class="service_price">
                                        <span>
                                            <span class="original_price">$ <span class="discounted_price">70</span>
                                                90</span> / 10 Units
                                        </span>
                                    </p>
                                </div>
                                <!-- Event Title -->
                                <div class="details">
                                    <h3>Product 1</h3>
                                    <p>
                                        This is product description This is product description This is product description
                                        This is product description This is product description.
                                    </p>

                                    <div class="service_posted_by mt-2">
                                        <div class="person_profile">
                                            <img src="https://placehold.co/50x50" alt="">
                                        </div>
                                        <div class="posted_name_date">
                                            <h6>Jahanzaib Ansari</h6>
                                            <p>13 Feb 2025</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Book Now Button -->
                                <a href="javascript:void(0);" class="view-more w-100">Message Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card">
                            <img src="https://placehold.co/300x180" alt="Event Image">
                            <div class="card-content">
                                <div class="service_price_duration my-0 event_price_label">
                                    <p class="service_price">
                                        <span>
                                            <span class="original_price">$ <span class="discounted_price">70</span>
                                                90</span> / 10 Units
                                        </span>
                                    </p>
                                </div>
                                <!-- Event Title -->
                                <div class="details">
                                    <h3>Product 1</h3>
                                    <p>
                                        This is product description This is product description This is product description
                                        This is product description This is product description.
                                    </p>

                                    <div class="service_posted_by mt-2">
                                        <div class="person_profile">
                                            <img src="https://placehold.co/50x50" alt="">
                                        </div>
                                        <div class="posted_name_date">
                                            <h6>Jahanzaib Ansari</h6>
                                            <p>13 Feb 2025</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Book Now Button -->
                                <a href="javascript:void(0);" class="view-more w-100">Message Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card">
                            <img src="https://placehold.co/300x180" alt="Event Image">
                            <div class="card-content">
                                <div class="service_price_duration my-0 event_price_label">
                                    <p class="service_price">
                                        <span>
                                            <span class="original_price">$ <span class="discounted_price">70</span>
                                                90</span> / 10 Units
                                        </span>
                                    </p>
                                </div>
                                <!-- Event Title -->
                                <div class="details">
                                    <h3>Product 1</h3>
                                    <p>
                                        This is product description This is product description This is product description
                                        This is product description This is product description.
                                    </p>

                                    <div class="service_posted_by mt-2">
                                        <div class="person_profile">
                                            <img src="https://placehold.co/50x50" alt="">
                                        </div>
                                        <div class="posted_name_date">
                                            <h6>Jahanzaib Ansari</h6>
                                            <p>13 Feb 2025</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Book Now Button -->
                                <a href="javascript:void(0);" class="view-more w-100">Message Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card">
                            <img src="https://placehold.co/300x180" alt="Event Image">
                            <div class="card-content">
                                <div class="service_price_duration my-0 event_price_label">
                                    <p class="service_price">
                                        <span>
                                            <span class="original_price">$ <span class="discounted_price">70</span>
                                                90</span> / 10 Units
                                        </span>
                                    </p>
                                </div>
                                <!-- Event Title -->
                                <div class="details">
                                    <h3>Product 1</h3>
                                    <p>
                                        This is product description This is product description This is product description
                                        This is product description This is product description.
                                    </p>

                                    <div class="service_posted_by mt-2">
                                        <div class="person_profile">
                                            <img src="https://placehold.co/50x50" alt="">
                                        </div>
                                        <div class="posted_name_date">
                                            <h6>Jahanzaib Ansari</h6>
                                            <p>13 Feb 2025</p>
                                        </div>
                                    </div>
                                </div>

                                <!-- Book Now Button -->
                                <a href="javascript:void(0);" class="view-more w-100">Message Now</a>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Add Pagination and Navigation -->
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>

    </section>

    <section class="industries_sec">
        <div class="container">
            <h2 class="mb-3">Industries</h2>
            <div class="row g-4" id="industries-container">
                <!-- Technology -->
                <div class="col-lg-3 col-md-6">
                    <a href="{{ Route('industry') }}" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-laptop-code"></i>
                        </div>
                        <h2 class="industry_heading">Technology</h2>
                    </a>
                </div>

                <!-- Healthcare -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-heartbeat"></i>
                        </div>
                        <h2 class="industry_heading">Healthcare</h2>
                    </a>
                </div>

                <!-- Finance -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fa-solid fa-file-invoice-dollar"></i>
                        </div>
                        <h2 class="industry_heading">Finance</h2>
                    </a>
                </div>

                <!-- Retail -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <h2 class="industry_heading">Retail</h2>
                    </a>
                </div>

                <!-- Agriculture, Forestry, Fishing and Hunting -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-tractor"></i>
                        </div>
                        <h2 class="industry_heading">Agriculture, Forestry, Fishing and Hunting</h2>
                    </a>
                </div>

                <!-- Mining, Quarrying, and Oil and Gas Extraction -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fa-solid fa-oil-well"></i>
                        </div>
                        <h2 class="industry_heading">Mining, Quarrying, and Oil and Gas Extraction</h2>
                    </a>
                </div>

                <!-- Utilities -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-bolt"></i>
                        </div>
                        <h2 class="industry_heading">Utilities</h2>
                    </a>
                </div>

                <!-- Construction -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fa-solid fa-person-digging"></i>
                        </div>
                        <h2 class="industry_heading">Construction</h2>
                    </a>
                </div>

                <!-- Manufacturing -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-industry"></i>
                        </div>
                        <h2 class="industry_heading">Manufacturing</h2>
                    </a>
                </div>

                <!-- Wholesale Trade -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                        <h2 class="industry_heading">Wholesale Trade</h2>
                    </a>
                </div>

                <!-- Retail Trade -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-store"></i>
                        </div>
                        <h2 class="industry_heading">Retail Trade</h2>
                    </a>
                </div>

                <!-- Transportation and Warehousing -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-truck"></i>
                        </div>
                        <h2 class="industry_heading">Transportation and Warehousing</h2>
                    </a>
                </div>

                <!-- Information -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-info-circle"></i>
                        </div>
                        <h2 class="industry_heading">Information</h2>
                    </a>
                </div>

                <!-- Finance and Insurance -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fa-solid fa-hand-holding-dollar"></i>
                        </div>
                        <h2 class="industry_heading">Finance and Insurance</h2>
                    </a>
                </div>

                <!-- Real Estate and Rental and Leasing -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-home"></i>
                        </div>
                        <h2 class="industry_heading">Real Estate and Rental and Leasing</h2>
                    </a>
                </div>

                <!-- Professional, Scientific, and Technical Services -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-flask"></i>
                        </div>
                        <h2 class="industry_heading">Professional, Scientific, and Technical Services</h2>
                    </a>
                </div>

                <!-- Management of Companies and Enterprises -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-building"></i>
                        </div>
                        <h2 class="industry_heading">Management of Companies and Enterprises</h2>
                    </a>
                </div>

                <!-- Administrative and Support and Waste Management and Remediation Services -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fa-solid fa-user-gear"></i>
                        </div>
                        <h2 class="industry_heading">Administrative and Support and Waste Management and Remediation
                            Services</h2>
                    </a>
                </div>

                <!-- Educational Services -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h2 class="industry_heading">Educational Services</h2>
                    </a>
                </div>

                <!-- Health Care and Social Assistance -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-hospital"></i>
                        </div>
                        <h2 class="industry_heading">Health Care and Social Assistance</h2>
                    </a>
                </div>

                <!-- Arts, Entertainment, and Recreation -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-theater-masks"></i>
                        </div>
                        <h2 class="industry_heading">Arts, Entertainment, and Recreation</h2>
                    </a>
                </div>

                <!-- Accommodation and Food Services -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-utensils"></i>
                        </div>
                        <h2 class="industry_heading">Accommodation and Food Services</h2>
                    </a>
                </div>

                <!-- Other Services (except Public Administration) -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h2 class="industry_heading">Other Services (except Public Administration)</h2>
                    </a>
                </div>

                <!-- Public Administration -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-landmark"></i>
                        </div>
                        <h2 class="industry_heading">Public Administration</h2>
                    </a>
                </div>

                <!-- Navy -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fa-solid fa-ferry"></i>
                        </div>
                        <h2 class="industry_heading">Navy</h2>
                    </a>
                </div>

                <!-- Other (Duplicate) -->
                <div class="col-lg-3 col-md-6">
                    <a href="javascript:void(0);" class="industry_box">
                        <div class="icon">
                            <i class="fas fa-question-circle"></i>
                        </div>
                        <h2 class="industry_heading">Other</h2>
                    </a>
                </div>
            </div>
            <!-- Show More Button -->
            <div class="text-center mt-4">
                <button id="show-more-btn" class="btn btn-primary">Show More</button>
            </div>
        </div>
    </section>



    <section class="why_choose">
        <div class="container">
            <h2 class="mb-3">Why to choose Care Hotels</h2>
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-up">
                                <div class="card-img">
                                    <i class="fa-solid fa-tag"></i>
                                </div>
                                <div class="card-heading">
                                    <h3>Special Discount</h3>
                                    <span>Subtitle</span>
                                </div>
                            </div>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                laboris nisi ut aliquip ex ea commodo consequat.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-up">
                                <div class="card-img">
                                    <i class="fa-regular fa-calendar"></i>
                                </div>
                                <div class="card-heading">
                                    <h3>AMCOB Peer Advisory</h3>
                                    <span>Subtitle</span>
                                </div>
                            </div>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                laboris nisi ut aliquip ex ea commodo consequat.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-up">
                                <div class="card-img">
                                    <i class="fa-solid fa-leaf"></i>
                                </div>
                                <div class="card-heading">
                                    <h3>Eco-friendly stays</h3>
                                    <span>Subtitle</span>
                                </div>
                            </div>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                laboris nisi ut aliquip ex ea commodo consequat.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <section class="articles">
        <div class="container">
            <h2 class="mb-3">Services</h2>
            <div class="services_slider overflow-hidden">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="card">
                            <div class="card-header p-0 border-0">
                                <img src="https://placehold.co/420x250" alt="Service 1" class="img-fluid rounded">
                            </div>
                            <div class="card-body">
                                <h3 class="service_heading">Service 1</h3>
                                <p>
                                    This is service description This is service description This is service description
                                    This is service description This is service description.
                                </p>
                                <div class="service_price_duration">
                                    <div class="service_price">
                                        <div class="service_posted_by">
                                            <div class="person_profile">
                                                <img src="https://placehold.co/50x50" alt="">
                                            </div>
                                            <div class="posted_name_date">
                                                <h6>Jahanzaib Ansari</h6>
                                                <p>13 Feb 2025</p>
                                            </div>
                                        </div>
                                        <p>
                                            <span>
                                                <span class="original_price">$ <span class="discounted_price">70</span>
                                                    90</span> / One Time
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <a href="#" class="btn btn-primary w-100">Message Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card">
                            <div class="card-header p-0 border-0">
                                <img src="https://placehold.co/420x250" alt="Service 1" class="img-fluid rounded">
                            </div>
                            <div class="card-body">
                                <h3 class="service_heading">Service 1</h3>
                                <p>
                                    This is service description This is service description This is service description
                                    This is service description This is service description.
                                </p>
                                <div class="service_price_duration">
                                    <div class="service_price">
                                        <div class="service_posted_by">
                                            <div class="person_profile">
                                                <img src="https://placehold.co/50x50" alt="">
                                            </div>
                                            <div class="posted_name_date">
                                                <h6>Jahanzaib Ansari</h6>
                                                <p>13 Feb 2025</p>
                                            </div>
                                        </div>
                                        <p>
                                            <span>
                                                <span class="original_price">$ <span class="discounted_price">70</span>
                                                    90</span> / One Time
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <a href="#" class="btn btn-primary w-100">Message Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card">
                            <div class="card-header p-0 border-0">
                                <img src="https://placehold.co/420x250" alt="Service 1" class="img-fluid rounded">
                            </div>
                            <div class="card-body">
                                <h3 class="service_heading">Service 1</h3>
                                <p>
                                    This is service description This is service description This is service description
                                    This is service description This is service description.
                                </p>
                                <div class="service_price_duration">
                                    <div class="service_price">
                                        <div class="service_posted_by">
                                            <div class="person_profile">
                                                <img src="https://placehold.co/50x50" alt="">
                                            </div>
                                            <div class="posted_name_date">
                                                <h6>Jahanzaib Ansari</h6>
                                                <p>13 Feb 2025</p>
                                            </div>
                                        </div>
                                        <p>
                                            <span>
                                                <span class="original_price">$ <span class="discounted_price">70</span>
                                                    90</span> / One Time
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <a href="#" class="btn btn-primary w-100">Message Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card">
                            <div class="card-header p-0 border-0">
                                <img src="https://placehold.co/420x250" alt="Service 1" class="img-fluid rounded">
                            </div>
                            <div class="card-body">
                                <h3 class="service_heading">Service 1</h3>
                                <p>
                                    This is service description This is service description This is service description
                                    This is service description This is service description.
                                </p>
                                <div class="service_price_duration">
                                    <div class="service_price">
                                        <div class="service_posted_by">
                                            <div class="person_profile">
                                                <img src="https://placehold.co/50x50" alt="">
                                            </div>
                                            <div class="posted_name_date">
                                                <h6>Jahanzaib Ansari</h6>
                                                <p>13 Feb 2025</p>
                                            </div>
                                        </div>
                                        <p>
                                            <span>
                                                <span class="original_price">$ <span class="discounted_price">70</span>
                                                    90</span> / One Time
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <a href="#" class="btn btn-primary w-100">Message Now</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card">
                            <div class="card-header p-0 border-0">
                                <img src="https://placehold.co/420x250" alt="Service 1" class="img-fluid rounded">
                            </div>
                            <div class="card-body">
                                <h3 class="service_heading">Service 1</h3>
                                <p>
                                    This is service description This is service description This is service description
                                    This is service description This is service description.
                                </p>
                                <div class="service_price_duration">
                                    <div class="service_price">
                                        <div class="service_posted_by">
                                            <div class="person_profile">
                                                <img src="https://placehold.co/50x50" alt="">
                                            </div>
                                            <div class="posted_name_date">
                                                <h6>Jahanzaib Ansari</h6>
                                                <p>13 Feb 2025</p>
                                            </div>
                                        </div>
                                        <p>
                                            <span>
                                                <span class="original_price">$ <span class="discounted_price">70</span>
                                                    90</span> / One Time
                                            </span>
                                        </p>
                                    </div>
                                </div>

                                <a href="#" class="btn btn-primary w-100">Message Now</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </section>
    {{-- <section class="articles">
        <div class="container">
            <h2 class="mb-3">Articles</h2>
            <div class="article_slider overflow-hidden">
                <div class="swiper-wrapper">
                    @forelse($blogs as $blog)
                        <div class="swiper-slide">

                            <div class="card">
                                <div class="card-header p-0 border-0">
                                    <!-- Blog Image -->
                                    <img src="{{ $blog->image ? asset('storage/' . $blog->image) : 'https://via.placeholder.com/650x300' }}"
                                        alt="{{ $blog->title }}" class="img-fluid rounded">
                                </div>
                                <div class="card-body">

                                    <h3>{{ $blog->title }}</h3>


                                    <p>{!! \Illuminate\Support\Str::limit(strip_tags($blog->content), 150) !!}</p>



                                    <a href="#" class="btn btn-primary">Read More</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="col-12">
                                    <p>No blogs available at the moment. Please check back later.</p>
                                </div>
                            </div>
                        </div>
                    @endforelse
                </div>
                <div class="swiper-button-prev"></div>
                <div class="swiper-button-next"></div>
            </div>
        </div>
    </section> --}}

    <section class="lp_footer">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>STAYS</h3>
                    <ul class="footer_list">
                        <li>
                            <a href="javascript:void(0);">
                                Hotels
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Resorts
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Villas
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Farm Stays
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Appartments
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    <h3>ABOUT US</h3>
                    <ul class="footer_list">
                        <li>
                            <a href="javascript:void(0);">
                                Our team
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Our branches
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Join us
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                For a sustainable world
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Campaigns
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    <h3>SERVICES</h3>
                    <ul class="footer_list">
                        <li>
                            <a href="javascript:void(0);">
                                Holidays stays
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Conferences
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Conventions
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Presentations
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Team building
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    <h3>POLICY</h3>
                    <ul class="footer_list">
                        <li>
                            <a href="javascript:void(0);">
                                Terms and conditions
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Privacy
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Cookies
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Legal information
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Sustainablility
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Safety Resources Center
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <p class="powered_by">
                Powered By <a href="https://amcob.org/" target="_blank" rel="noopener noreferrer">AMCOB</a>
            </p>
        </div>
    </section>
@endsection
@section('script')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const industriesContainer = document.getElementById("industries-container");
            const showMoreBtn = document.getElementById("show-more-btn");
            const industries = industriesContainer.querySelectorAll(".col-lg-3");
            const itemsPerRow = 4; // Number of items per row
            const initialRows = 2; // Number of rows to show initially
            let isExpanded = false;

            // Hide all industries beyond the initial rows
            for (let i = initialRows * itemsPerRow; i < industries.length; i++) {
                industries[i].style.display = "none";
            }

            // Toggle visibility on button click
            showMoreBtn.addEventListener("click", function() {
                isExpanded = !isExpanded;
                for (let i = initialRows * itemsPerRow; i < industries.length; i++) {
                    industries[i].style.display = isExpanded ? "block" : "none";
                }
                showMoreBtn.textContent = isExpanded ? "Show Less" : "Show More";
            });
        });
        // Prevent dropdown menu from closing when clicking inside
        document.querySelectorAll('.dropdown-menu').forEach((dropdown) => {
            dropdown.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });
    </script>
@endsection
