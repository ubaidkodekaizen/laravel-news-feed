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
            <h2 class="mb-3">Products Offered by Muslim Lynk Members</h2>
            <div class="swiper">
                <div class="swiper-wrapper">
                    @forelse ($products as $product)
                        <div class="swiper-slide">
                            <div class="card">
                                <img src="{{ $product->product_image ? asset('storage/' . $product->product_image) : 'https://placehold.co/420x250' }}"
                                    alt="{{ $product->title }}">

                                <div class="card-content">
                                    <div class="service_price_duration my-0 event_price_label">
                                        <p class="service_price">

                                            <span>
                                                @if ($product->discounted_price && $product->discounted_price < $product->original_price)
                                                    <s>${{ $product->original_price }}</s> ${{ $product->discounted_price }}
                                                @else
                                                    ${{ $product->original_price }}
                                                @endif
                                                / {{ $product->quantity }}-{{ $product->unit_of_quantity }}
                                            </span>


                                        </p>
                                    </div>

                                    <!-- Product Title -->
                                    <div class="details">
                                        <h3>{{ $product->title }}</h3>
                                        <p>
                                            {{ Str::limit($product->short_description, 100) }}
                                        </p>

                                        <div class="service_posted_by mt-2">
                                            <div class="person_profile">
                                                <img src="{{ $product->user->photo ? asset('storage/' . $product->user->photo) : 'https://placehold.co/50x50' }}"
                                                    alt="{{ $product->user->first_name }}">

                                            </div>
                                            <div class="posted_name_date">
                                                <h6>{{ $product->user->first_name }}
                                                    <p>{{ $product->created_at->format('d M Y') }}</p>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Message Now Button -->
                                    <a href="javascript:void(0);" class="view-more w-100">Message Now</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No products available.</p>
                    @endforelse
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
                @foreach ($industries as $industry)
                    <div class="col-lg-3 col-md-6">
                        <a href="{{ route('industry', ['industry' => $industry['name']]) }}" class="industry_box">
                            <div class="icon">
                                <i class="{{ $industry['icon'] }}"></i>
                            </div>
                            <h2 class="industry_heading">{{ $industry['name'] }}</h2>
                        </a>
                    </div>
                @endforeach


            </div>
            <!-- Show More Button -->
            <div class="text-center mt-4">
                <button id="show-more-btn" class="btn btn-primary">Show More</button>
            </div>
        </div>
    </section>



    <section class="why_choose">
        <div class="container">
            <h2 class="mb-3">Muslim Lynk Redefines Lead Generation</h2>
            <div class="row">
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-up">
                                <div class="card-img">
                                    <i class="fa-solid fa-clock"></i>
                                </div>
                                <div class="card-heading">
                                    <h3>Real-Time Business Intelligence</h3>
                                    {{-- <span>Subtitle</span> --}}
                                </div>
                            </div>
                            <p>
                                Access a dynamic, up-to-date database of professionals and decision-makers, ensuring every connection is relevant and actionable.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-up">
                                <div class="card-img">
                                    <i class="fa-solid fa-message"></i>
                                </div>
                                <div class="card-heading">
                                    <h3>Seamless Direct Engagement With People</h3>
                                    {{-- <span>Subtitle</span> --}}
                                </div>
                            </div>
                            <p>
                                Reach the right people effortlessly through integrated direct messaging and LinkedIn connectivity, eliminating barriers to impactful conversations.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4">
                    <div class="card">
                        <div class="card-body">
                            <div class="card-up">
                                <div class="card-img">
                                    <i class="fa-solid fa-user"></i>
                                </div>
                                <div class="card-heading">
                                    <h3>Precision-Driven Lead Generation</h3>
                                    {{-- <span>Subtitle</span> --}}
                                </div>
                            </div>
                            <p>
                                Secure a direct pathway to industry leaders, cutting through noise and delivering high-value opportunities with unmatched efficiency.
                            </p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="articles">
        <div class="container">
            <h2 class="mb-3">Services Offered by Muslim Lynk Members</h2>
            <div class="services_slider_feed services_slider overflow-hidden">
                <div class="swiper-wrapper">
                    @forelse($services as $service)
                        <div class="swiper-slide">
                            <div class="card">
                                <div class="card-header p-0 border-0">
                                    <img src="{{ $service->service_image ? asset('storage/' . $service->service_image) : 'https://placehold.co/420x250' }}"
                                        alt="{{ $service->title }}" class="img-fluid rounded">
                                </div>
                                <div class="card-body">
                                    <h3 class="service_heading">{{ $service->title }}</h3>
                                    <p>{{ $service->short_description }}</p>
                                    <div class="service_price_duration">
                                        <div class="service_price">
                                            <div class="service_posted_by">
                                                <div class="person_profile">
                                                    <img src="{{ $service->user->photo ? asset('storage/' . $service->user->photo) : 'https://placehold.co/50x50' }}"
                                                        alt="{{ $service->user->first_name }}">
                                                </div>
                                                <div class="posted_name_date">
                                                    <h6>{{ $service->user->first_name }}
                                                    </h6>
                                                    <p>{{ $service->created_at->format('d M Y') }}</p>
                                                </div>
                                            </div>
                                            <p>
                                                <span>
                                                    @if ($service->discounted_price && $service->discounted_price < $service->original_price)
                                                        <s>${{ $service->original_price }}</s>
                                                        ${{ $service->discounted_price }}
                                                    @else
                                                        ${{ $service->original_price }}
                                                    @endif
                                                    / {{ $service->duration }}
                                                </span>


                                            </p>
                                        </div>
                                    </div>
                                    <a href="#" class="btn btn-primary w-100">Message Now</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No services available.</p>
                    @endforelse
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
            {{-- <div class="row">
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
            </div> --}}
            <p class="powered_by">
                Powered By <a href="https://amcob.org/" target="_blank" rel="noopener noreferrer">AMCOB</a>
            </p>
        </div>
    </section>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const industriesContainer = document.getElementById("industries-container");
            const showMoreBtn = document.getElementById("show-more-btn");
            const industries = Array.from(industriesContainer.querySelectorAll(".col-lg-3, .col-md-6"));
            const itemsPerRow = 4; // Number of items per row
            const initialRows = 2; // Number of rows to show initially
            let isExpanded = false;

            function updateVisibility() {
                industries.forEach((industry, index) => {
                    if (index < initialRows * itemsPerRow) {
                        industry.style.display = "block"; // Show initially visible items
                    } else {
                        industry.style.display = isExpanded ? "block" : "none"; // Toggle visibility
                    }
                });

                showMoreBtn.textContent = isExpanded ? "Show Less" : "Show More";
            }

            // Initial Setup
            updateVisibility();

            // Toggle on button click
            showMoreBtn.addEventListener("click", function () {
                isExpanded = !isExpanded;
                updateVisibility();
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
