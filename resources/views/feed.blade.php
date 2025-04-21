@extends('layouts.main')
@section('content')
    <style>
        .text-danger {
            font-size: 12px;
            margin-bottom: 5px;
            display: block;
        }

        .read-more-btn {
            width: 100%;
            background: transparent;
            border: none;
            border-top: 1px solid #ddd;
            border-radius: 0px;
            color: #686868;
            font-weight: 500;
            text-align: left;
        }

        .read-more-btn:hover,
        .read-more-btn:focus,
        .read-more-btn:active {
            background: transparent !important;
            color: var(--secondary) !important;
            border: none !important;
            border-top: 1px solid var(--secondary) !important;
        }

        #productModal .modal-dialog.modal-lg {
            max-width: 600px;
        }

        .productModalImageBox {
            height: 300px;
            position: relative;
        }

        #productModalImage {
            height: 100%;
            width: 100%;
            object-fit: cover;
            object-position: top center;
        }

        .productModalPriceBox {
            position: absolute;
            bottom: 5px;
            right: 5px;
            background: var(--secondary);
            color: #fff;
            margin: 0;
            border-radius: 25px;
            padding: 3px 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }

        .productModalContent {
            padding: 10px;
        }

        small#productModalDate {
            font-size: 12px;
            color: #4f4f4f;
        }

        .event_slider h2,
        .articles h2,
        .why_choose h2,
        .industries_sec h2 {
            text-align: center;
            margin-bottom: 40px !important;
        }

        #productModal .modal-footer {
            display: flex;
            align-items: stretch;
            justify-content: space-between;
        }

        .productModalUserProfileBox {
            display: flex;
            align-items: center;
            justify-content: start;
        }

        #productModal .direct-message-btn {
            flex: 1;
            border-radius: 10px;
            background: var(--secondary);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            max-width: 200px;
            transition: .3s;
        }

        #productModal .direct-message-btn:hover {
            background: var(--primary);
            transition: .3s;
        }

        #productModal .modal-content {
            overflow: hidden;
        }

        #productModalLabel {
            font-size: 18px;
            line-height: 1.3em;
        }

        .productModalImageBox .btn-close {
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #fff;
            color: #000;
            --bs-btn-close-opacity: 1;
            padding: 5px;
        }

        .trigger-element {
            cursor: pointer;
        }

        .articles .card {
            min-height: 610px;
        }

        .articles .card .card-body {
            display: flex;
            flex-direction: column;
        }

        .articles .card .direct-message-btn {
            padding: 10px 10px;
        }

        .service_slider_img_box {
            height: 250px;
            position: relative;
        }

        .customHeading {
            color: var(--primary);
            position: relative;
            display: inline-block;
            padding-bottom: 5px;
            font-weight: 700;
        }

        .customHeading::before {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100%;
            background: linear-gradient(to right, transparent, var(--secondary));
            height: 5px;
            border-radius: 10px;
        }

        .articles {
            overflow: hidden;
            position: relative;
        }

        .event_slider {
            position: relative;
        }

        .swiper-button-next,
        .swiper-button-prev {
            padding: 25px;
            border-radius: 9px;
            background: var(--secondary);
            box-shadow: 2px 2px 5px #00000040;
        }

        .swiper-button-prev {
            left: 50px;
        }

        .swiper-button-next {
            right: 50px;
        }

        .articles .swiper-button-next:after,
        .swiper-button-prev:after {
            color: #fff;
        }

        @media(max-width: 768px) {
            .swiper-button-prev {
                left:  0px;
            }

            .swiper-button-next {
                right:  0px;
            }
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
                            <div class="card product-trigger-wrapper" data-id="{{ $product->user->id }}"
                                data-title="{{ $product->title }}" data-description="{{ $product->short_description }}"
                                data-image="{{ $product->product_image ? asset('storage/' . $product->product_image) : 'https://placehold.co/420x250' }}"
                                data-price="{{ $product->discounted_price && $product->discounted_price < $product->original_price ? '$' . $product->discounted_price . ' (was $' . $product->original_price . ')' : '$' . $product->original_price }}"
                                data-quantity="{{ $product->quantity }}-{{ $product->unit_of_quantity }}"
                                data-user-name="{{ $product->user->first_name }}"
                                data-user-photo="{{ $product->user->photo ? asset('storage/' . $product->user->photo) : 'https://placehold.co/50x50' }}"
                                data-date="{{ $product->created_at->format('d M Y') }}">
                                <div class="event_slider_img_box">
                                    <img src="{{ $product->product_image ? asset('storage/' . $product->product_image) : 'https://placehold.co/420x250' }}"
                                        alt="{{ $product->title }}" class="trigger-element">
                                    <div class="service_price_duration my-0 event_price_label">
                                        <p class="service_price">

                                            <span>
                                                @if ($product->discounted_price && $product->discounted_price < $product->original_price)
                                                    <s>${{ $product->original_price }}</s>
                                                    ${{ $product->discounted_price }}
                                                @else
                                                    ${{ $product->original_price }}
                                                @endif
                                                / {{ $product->quantity }}-{{ $product->unit_of_quantity }}
                                            </span>


                                        </p>
                                    </div>
                                </div>

                                <div class="card-content">


                                    <!-- Product Title -->
                                    <div class="details">
                                        <h3 class="trigger-element">{{ $product->title }}</h3>
                                        <p>
                                            {{ Str::limit($product->short_description, 100) }}
                                        </p>
                                        <button type="button"
                                            class="btn btn-sm btn-primary mt-2 read-more-btn trigger-element">
                                            Read More
                                        </button>

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
                                    <a href="javascript:void(0)" class="view-more direct-message-btn w-100"
                                        data-receiver-id="{{ $product->user->id }}">Message Now</a>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No products available.</p>
                    @endforelse
                </div>

                <!-- Add Pagination and Navigation -->

            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>

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
                                Access a dynamic, up-to-date database of professionals and decision-makers, ensuring every
                                connection is relevant and actionable.
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
                                Reach the right people effortlessly through integrated direct messaging and LinkedIn
                                connectivity, eliminating barriers to impactful conversations.
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
                                Secure a direct pathway to industry leaders, cutting through noise and delivering high-value
                                opportunities with unmatched efficiency.
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
                            <div class="card service-trigger-wrapper" data-id="{{ $service->user->id }}"
                                data-title="{{ $service->title }}" data-description="{{ $service->short_description }}"
                                data-image="{{ $service->service_image ? asset('storage/' . $service->service_image) : 'https://placehold.co/420x250' }}"
                                data-price="{{ $service->discounted_price && $service->discounted_price < $service->original_price ? '$' . $service->discounted_price . ' (was $' . $service->original_price . ')' : '$' . $service->original_price }}"
                                data-quantity="{{ $service->duration }}" data-user-name="{{ $service->user->first_name }}"
                                data-user-photo="{{ $service->user->photo ? asset('storage/' . $service->user->photo) : 'https://placehold.co/50x50' }}"
                                data-date="{{ $service->created_at->format('d M Y') }}">
                                <div class="card-header p-0 border-0 service_slider_img_box">
                                    <img src="{{ $service->service_image ? asset('storage/' . $service->service_image) : 'https://placehold.co/420x250' }}"
                                        alt="{{ $service->title }}" class="img-fluid rounded trigger-element">
                                    <div class="service_price_duration my-0 event_price_label">
                                        <p class="service_price">

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
                                <div class="card-body">
                                    <h3 class="service_heading trigger-element">{{ $service->title }}</h3>
                                    <p>{{ Str::limit($service->short_description, 100) }}</p>
                                    <button type="button"
                                        class="btn btn-sm btn-primary mt-2 read-more-btn trigger-element">
                                        Read More
                                    </button>
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

                                        </div>
                                    </div>
                                    <a href="javascript:void(0)" class="btn btn-primary direct-message-btn w-100"
                                        data-receiver-id="{{ $service->user->id }}">Message Now</a>

                                </div>
                            </div>
                        </div>
                    @empty
                        <p>No services available.</p>
                    @endforelse
                </div>


            </div>
            <div class="swiper-button-prev"></div>
            <div class="swiper-button-next"></div>
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

            <p class="powered_by">
                Powered By <a href="https://amcob.org/" target="_blank" rel="noopener noreferrer">AMCOB</a>
            </p>
        </div>
    </section>
    <!-- Main Modal -->
    <div class="modal fade" id="mainModal" tabindex="-1" aria-labelledby="mainModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header" style="background-color: var(--primary); color: #fff;">
                    <h5 class="modal-title" id="mainModalLabel">Send Direct Message</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="directMessageForm">
                        <input type="hidden" name="receiver_id" id="receiver_id" value="">
                        <!-- Receiver ID will be set dynamically -->

                        <div class="mb-3">
                            <label for="messageContent" class="form-label">Your Message</label>
                            <textarea class="form-control" id="messageContent" name="content" rows="4" required></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 ">Send Message</button>
                    </form>
                    <div id="messageStatus" class="mt-3 text-center"></div> <!-- Status Message -->
                </div>
            </div>
        </div>
    </div>
    <!-- Modal -->
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-body p-0">
                    <div class="productModalImageBox">
                        <img id="productModalImage" src="" class="img-fluid mb-3" alt="Product image" />
                        <p class="productModalPriceBox"> <span id="productModalPrice"></span><span
                                id="productModalQuantity"></span></p>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="productModalContent">


                        <h5 class="modal-title customHeading" id="productModalLabel">Product Title</h5>


                        <p class="mt-2"><span id="productModalDescription"></span></p>


                    </div>

                </div>
                <div class="modal-footer">
                    <div class="productModalUserProfileBox">
                        <img id="productModalUserPhoto" src="" alt="User photo" class="rounded-circle me-2"
                            width="50" height="50">
                        <div>
                            <h6 id="productModalUserName" class="mb-0"></h6>
                            <small id="productModalDate"></small>
                        </div>
                    </div>
                    <a href="javascript:void(0)" class="view-more direct-message-btn" data-receiver-id=""
                        data-bs-dismiss="modal">Message Now</a>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        document.addEventListener("DOMContentLoaded", function() {
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
            showMoreBtn.addEventListener("click", function() {
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

    <script>
        jQuery(document).ready(function($) {

            let directMessageBtn = document.querySelectorAll('.direct-message-btn');
            console.log("directMessageBtn", directMessageBtn);
            directMessageBtn.forEach(element => {
                console.log("element", element);
                element.addEventListener("click", function() {
                    let receiverId = $(this).data('receiver-id');
                    $('#receiver_id').val(receiverId);
                    console.log("receiverId", receiverId);
                    $.ajax({
                        url: '/api/check-conversation',
                        method: 'GET',
                        data: {
                            receiver_id: receiverId
                        },
                        headers: {
                            "Authorization": localStorage.getItem("sanctum-token")
                        },
                        success: function(response) {
                            if (response.conversation_exists) {
                                // If conversation exists, open chat directly
                                if (window.openChatWithUser) {
                                    window.openChatWithUser(receiverId);
                                }
                            } else {
                                // If no conversation, open the modal
                                console.log(response.receiver);
                                $('#receiver_id').val(receiverId);
                                $("#messageContent").val(`Hi ${response.receiver.first_name ?? ''} ${response.receiver.last_name ?? ''}, 
I came across your profile and was really impressed by your work. I’d love to connect and exchange ideas.
Looking forward to connecting! 
Best Regards,
{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}`);
                                // $('#mainModal').modal('show');
                                var myModal = new bootstrap.Modal(document
                                    .getElementById('mainModal'));
                                myModal.show();
                            }
                        },
                        error: function(xhr) {
                            console.error('Error checking conversation:', xhr);
                        }
                    });
                });


            });

            $('#directMessageForm').on('submit', function(e) {
                e.preventDefault();

                const formData = {
                    receiver_id: $('#receiver_id').val(),
                    content: $('#messageContent').val(),
                    _token: '{{ csrf_token() }}'
                };

                $.ajax({
                    url: '{{ route('sendMessage') }}',
                    method: 'POST',
                    data: formData,
                    headers: {
                        "Authorization": localStorage.getItem("sanctum-token")
                    },
                    success: function(response) {
                        // Close the modal
                        $('#mainModal').modal('hide');

                        // Trigger opening the chat box and specific conversation
                        if (window.openChatWithUser) {
                            window.openChatWithUser(formData.receiver_id);
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.error ||
                            'An error occurred. Please try again.';
                        $('#messageStatus').html(
                            `<div class="alert alert-danger">${errorMsg}</div>`);
                    }
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = document.getElementById('productModal');
            const bsModal = new bootstrap.Modal(modal);

            document.body.addEventListener('click', function(e) {
                const trigger = e.target.closest('.trigger-element');
                if (!trigger) return;

                const wrapper = trigger.closest('.product-trigger-wrapper, .service-trigger-wrapper');
                if (!wrapper) return;

                modal.querySelector('.modal-title').textContent = wrapper.dataset.title;
                modal.querySelector('#productModalDescription').textContent = wrapper.dataset.description;
                modal.querySelector('#productModalImage').src = wrapper.dataset.image;
                modal.querySelector('#productModalPrice').textContent = wrapper.dataset.price;
                modal.querySelector('#productModalQuantity').textContent = wrapper.dataset.quantity;
                modal.querySelector('#productModalUserPhoto').src = wrapper.dataset.userPhoto;
                modal.querySelector('#productModalUserName').textContent = wrapper.dataset.userName;
                modal.querySelector('#productModalDate').textContent = "Posted on " + wrapper.dataset.date;
                modal.querySelector('.direct-message-btn').setAttribute('data-receiver-id', wrapper.dataset
                    .id);

                bsModal.show();
            });
        });
    </script>
@endsection
