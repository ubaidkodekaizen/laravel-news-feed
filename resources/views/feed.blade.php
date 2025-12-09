@extends('layouts.main')
@section('content')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

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

        #footer {
            background-color: #B8C034;
        }

        #footer p {
            text-align: center;
            color: #273572;
            font-family: "Inter", Sans-serif;
            font-size: 18px;
            font-weight: 700;
            margin: 0;
            padding: 20px 0;
        }

        @media(max-width: 768px) {
            .swiper-button-prev {
                left: 0px;
            }

            .swiper-button-next {
                right: 0px;
            }
        }
    </style>

    <section class="feed_lp">
        <div class="container">
            <h1 class="main_heading">
                Products Offered by <span class="feedLpSec"><span class="feedLpPri">Muslim</span>Lynk Members</span>
            </h1>
            <p class="feedLpPara">MuslimLynk members offer a diverse range of products and services designed to support
                businesses at every stage. From e-commerce fulfillment to specialized manufacturing and compliance
                solutions, the community provides reliable, professional offerings that empower growth and scalability.
                Featured products include:</p>

        </div>

    </section>


    <section class="productSliderSection">
        <div class="slider-wrapper">
            <div class="slider-track" id="sliderTrack">

                @forelse ($products as $product)
                    <div class="slide product-trigger-wrapper" data-id="{{ $product->user->id }}"
                        data-title="{{ $product->title }}" data-description="{{ $product->short_description }}"
                        data-image="{{ $product->product_image ? asset('storage/' . $product->product_image) : 'https://placehold.co/420x250' }}"
                        data-price="{{ $product->discounted_price && $product->discounted_price < $product->original_price ? '$' . $product->discounted_price . ' (was $' . $product->original_price . ')' : '$' . $product->original_price }}"
                        data-quantity="{{ $product->quantity }}-{{ $product->unit_of_quantity }}"
                        data-user-name="{{ $product->user->first_name }}"
                        data-user-photo="{{ $product->user->photo ? asset('storage/' . $product->user->photo) : 'https://placehold.co/50x50' }}">
                        <div class="container">
                            <div class="productSliderSecInnerCol">
                                <h2 class="industriesMainHeading">{{ $product->title }}</h2>
                                <p class="industriesMainPara">{{ Str::limit($product->short_description, 100) }}</p>
                                <button type="button" class="btn btn-sm btn-primary mt-2 read-more-btn trigger-element">
                                    Read More
                                </button>
                                <div class="sliderUserAndPricingInfo">
                                    <div class="sliderPricing">
                                        <span class="sliderPricingUnit">
                                            @if ($product->discounted_price && $product->discounted_price < $product->original_price)
                                                <s>${{ $product->original_price }}</s>
                                                ${{ $product->discounted_price }}
                                            @else
                                                ${{ $product->original_price }}
                                            @endif
                                            / {{ $product->quantity }}-{{ $product->unit_of_quantity }}
                                        </span>
                                    </div>

                                    <div class="author-info">
                                        <div class="author-avatar">
                                            <img src="{{ $product->user->photo ? asset('storage/' . $product->user->photo) : 'https://placehold.co/50x50' }}"
                                                alt="User Profile Image">
                                        </div>
                                        <div class="author-details">
                                            <div class="author-name">{{ $product->user->first_name }}</div>
                                            <div class="author-date">{{ $product->created_at->format('d M Y') }}</div>

                                        </div>
                                    </div>
                                </div>

                                <button class="sliderUserMessageBtn view-more direct-message-btn"
                                    data-receiver-id="{{ $product->user->id }}">Message now</button>

                            </div>
                            <div class="productSliderSecInnerCol">
                                <img src="{{ $product->product_image ? asset('storage/' . $product->product_image) : 'https://placehold.co/420x250' }}"
                                    alt="ProductImage">
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

            <!-- Slider Dots -->
            <div class="container">
                <div class="sliderArrows">
                    <div class="sliderPreviousArrow" data-action="prev">
                        <svg fill="#000000" width="20px" height="20px" viewBox="-78.5 0 512 512"
                            xmlns="http://www.w3.org/2000/svg">
                            <title>left</title>
                            <path d="M257 64L291 98 128 262 291 426 257 460 61 262 257 64Z" />
                        </svg>
                    </div>
                    <div class="sliderNextArrow" data-action="next">
                        <svg fill="#000000" width="20px" height="20px" viewBox="-77 0 512 512"
                            xmlns="http://www.w3.org/2000/svg">
                            <title>right</title>
                            <path d="M98 460L64 426 227 262 64 98 98 64 294 262 98 460Z" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="slider-dots" id="sliderDots"></div>
        </div>
    </section>




    <section class="leadGenSec">
        <div class="container">
            <h1 class="main_heading">
                Muslim Lynk Redefines<span class="leadGenSecHeading"> Lead Generation</span>
            </h1>
            <p class="leadGenPara">MuslimLynk empowers its members through cutting-edge lead generation tools and
                strategies. By leveraging advanced business intelligence and seamless communication platforms, Muslim Lynk
                enables professionals to connect meaningfully and grow their network effectively. The key offerings include:
            </p>

        </div>

        <div class="container">
            <div class="leadGenBoxMain">
                <div class="leadGenBox">
                    <img src="assets/images/realTimeBusinessIntelligent.svg" alt="">
                    <h3>Real-Time Business Intelligence</h3>
                    <p>Access a dynamic, up-to-date database of professionals and decision-makers, ensuring every connection
                        is relevant and actionable.</p>
                </div>
                <div class="leadGenBox">
                    <img src="assets/images/seamlessDirectEngagementWithPeople.svg" alt="">
                    <h3>Seamless Direct Engagement With People</h3>
                    <p>Reach the right people effortlessly through integrated direct messaging and LinkedIn connectivity,
                        eliminating barriers to impactful conversations.</p>
                </div>
                <div class="leadGenBox">
                    <img src="assets/images/precisionDrivenLeadGeneration.svg" alt="">
                    <h3>Precision-Driven Lead Generation</h3>
                    <p>Secure a direct pathway to industry leaders, cutting through noise and delivering high-value
                        opportunities with unmatched efficiency.</p>
                </div>
            </div>
        </div>
    </section>

    <section class="industries_sec">
        <div class="container">
            <h2 class="mb-3 industriesMainHeading">MUSLIMLYNK <span class="industriesSecHeading">INDUSTRIES</span></h2>
            <div class="row g-4" id="industries-container">

                @foreach ($industries as $index => $industry)
                    <div class="col-lg-3 col-md-6 ">
                        <a href="{{ route('industry', ['industry' => $industry['name']]) }}" class="industry_box">
                            <div class="icon">
                                <span class="industry_number">
                                    {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                                </span>
                            </div>
                            <h2 class="industry_heading">{{ $industry['name'] }}</h2>
                        </a>
                    </div>
                @endforeach



            </div>
            <!-- Show More Button -->
            <div class="text-center mt-4">
                <button id="show-more-btn" class="btn btn-primary viewAllBtn">View All</button>
            </div>
        </div>
    </section>


    <section class="articles">
        <div class="container">
            <h2 class="main_heading">
                Services Offered by <span class="feedLpSec"><span class="feedLpPri">Muslim</span>Lynk Members</span>
            </h2>
            <p class="articlesPara">MuslimLynk members offer a diverse range of products and services designed to support
                businesses at every stage. From e-commerce fulfillment to specialized manufacturing and compliance
                solutions, the community provides reliable, professional offerings that empower growth and scalability.
                Featured products include:</p>

            <div class="serviceAccordionCon">
                <div class="serviceAccordionConInner">
                    @forelse($services as $index => $service)
                        <div class="kodereach-accordion-section">
                            <div class="kodereach-accordion-item">
                                <div class="kodereach-accordion-header {{ $index === 0 ? 'active' : '' }}"
                                    onclick="toggleKodereachAccordion(this)"
                                    data-service-image="{{ $service->service_image ? asset('storage/' . $service->service_image) : asset('assets/images/kodeReachLogo.png') }}"
                                    data-service-price="@if ($service->discounted_price && $service->discounted_price < $service->original_price) ${{ $service->discounted_price }}@else${{ $service->original_price }} @endif"
                                    data-service-duration="{{ $service->duration }}">
                                    <h3 class="kodereach-accordion-title">{{ $service->title }}</h3>
                                    <div class="kodereach-accordion-arrow">
                                        <svg fill="#000000" width="20px" height="20px" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M3.293,20.707a1,1,0,0,1,0-1.414L17.586,5H12a1,1,0,0,1,0-2h8a1,1,0,0,1,1,1v8a1,1,0,0,1-2,0V6.414L4.707,20.707a1,1,0,0,1-1.414,0Z" />
                                        </svg>
                                    </div>
                                </div>
                                <div class="kodereach-accordion-content"
                                    style="{{ $index === 0 ? 'display: block;' : '' }}">
                                    <div class="kodereach-accordion-content-inner">
                                        <p class="description">
                                            {{ $service->short_description }}
                                        </p>

                                        <button class="message-btn direct-message-btn"
                                            data-receiver-id="{{ $service->user->id }}">
                                            Message Now
                                        </button>

                                        <div class="author-info">
                                            <div class="author-avatar">
                                                <img src="{{ $service->user->photo ? asset('storage/' . $service->user->photo) : asset('assets/images/placeholderUser.png') }}"
                                                    alt="{{ $service->user->first_name }}">
                                            </div>
                                            <div class="author-details">
                                                <div class="author-name">{{ $service->user->first_name }}</div>
                                                <div class="author-date">{{ $service->created_at->format('d M Y') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-4">
                            <p>No services available at the moment.</p>
                        </div>
                    @endforelse
                </div>
                <div class="serviceAccordionConInner">
                    <img id="serviceImage"
                        src="{{ $services->isNotEmpty() && $services->first()->service_image ? asset('storage/' . $services->first()->service_image) : asset('assets/images/kodeReachLogo.png') }}"
                        alt="Service Image" class="img-fluid">
                    <span id="servicePricing">
                        @if ($services->isNotEmpty())
                            @if ($services->first()->discounted_price && $services->first()->discounted_price < $services->first()->original_price)
                                ${{ $services->first()->discounted_price }} / {{ $services->first()->duration }}
                            @else
                                ${{ $services->first()->original_price }} / {{ $services->first()->duration }}
                            @endif
                        @else
                            $1.00 / Starting
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </section>


    <div id="footer">
        <p>© 2025 – Powered By AMCOB LLC. All Rights Reserved.</p>
    </div>
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
        (function() {
            // State
            let currentIndex = 0;

            // Elements
            const sliderTrack = document.getElementById('sliderTrack');
            const slides = document.querySelectorAll('.slide');
            const totalSlides = slides.length;
            const dotsContainer = document.getElementById('sliderDots');

            console.log('Slider initialized with', totalSlides, 'slides');

            // Create dots
            function createDots() {
                dotsContainer.innerHTML = '';
                for (let i = 0; i < totalSlides; i++) {
                    const dot = document.createElement('div');
                    dot.className = 'dot';
                    if (i === 0) dot.classList.add('active');
                    dot.setAttribute('data-index', i);
                    dotsContainer.appendChild(dot);
                }
                console.log('Dots created:', totalSlides);
            }

            // Update slider position
            function updateSlider() {
                const offset = currentIndex * -100;
                sliderTrack.style.transform = `translateX(${offset}%)`;
                console.log('Slider moved to index:', currentIndex, 'offset:', offset + '%');
                updateButtons();
                updateDots();
            }

            // Update button states
            function updateButtons() {
                const prevButtons = document.querySelectorAll('[data-action="prev"]');
                const nextButtons = document.querySelectorAll('[data-action="next"]');

                prevButtons.forEach(btn => {
                    if (currentIndex === 0) {
                        btn.classList.add('disabled');
                    } else {
                        btn.classList.remove('disabled');
                    }
                });

                nextButtons.forEach(btn => {
                    if (currentIndex === totalSlides - 1) {
                        btn.classList.add('disabled');
                    } else {
                        btn.classList.remove('disabled');
                    }
                });
            }

            // Update dots
            function updateDots() {
                const dots = dotsContainer.querySelectorAll('.dot');
                dots.forEach((dot, index) => {
                    if (index === currentIndex) {
                        dot.classList.add('active');
                    } else {
                        dot.classList.remove('active');
                    }
                });
            }

            // Next slide
            function nextSlide() {
                console.log('Next clicked, current:', currentIndex);
                if (currentIndex < totalSlides - 1) {
                    currentIndex++;
                    updateSlider();
                }
            }

            // Previous slide
            function prevSlide() {
                console.log('Prev clicked, current:', currentIndex);
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            }

            // Go to specific slide
            function goToSlide(index) {
                console.log('Go to slide:', index);
                currentIndex = index;
                updateSlider();
            }

            // Event delegation for arrow buttons
            document.addEventListener('click', function(e) {
                const target = e.target;

                if (target.hasAttribute('data-action')) {
                    e.preventDefault();
                    const action = target.getAttribute('data-action');

                    if (action === 'next') {
                        nextSlide();
                    } else if (action === 'prev') {
                        prevSlide();
                    }
                }

                if (target.classList.contains('dot')) {
                    e.preventDefault();
                    const index = parseInt(target.getAttribute('data-index'));
                    if (!isNaN(index)) {
                        goToSlide(index);
                    }
                }
            });

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                if (e.key === 'ArrowLeft') {
                    prevSlide();
                } else if (e.key === 'ArrowRight') {
                    nextSlide();
                }
            });

            // Touch/swipe support
            let touchStartX = 0;
            let touchEndX = 0;

            sliderTrack.addEventListener('touchstart', function(e) {
                touchStartX = e.changedTouches[0].screenX;
            }, {
                passive: true
            });

            sliderTrack.addEventListener('touchend', function(e) {
                touchEndX = e.changedTouches[0].screenX;
                handleSwipe();
            }, {
                passive: true
            });

            function handleSwipe() {
                const swipeThreshold = 50;
                const diff = touchStartX - touchEndX;

                if (Math.abs(diff) > swipeThreshold) {
                    if (diff > 0) {
                        nextSlide();
                    } else {
                        prevSlide();
                    }
                }
            }

            // Initialize
            createDots();
            updateButtons();

            console.log('Slider ready!');
        })();
    </script>

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

                showMoreBtn.textContent = isExpanded ? "View Less" : "View All";
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
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Make FIRST accordion active by default
            const firstItem = document.querySelector(".kodereach-accordion-item");
            if (firstItem) {
                const firstHeader = firstItem.querySelector(".kodereach-accordion-header");
                const firstContent = firstItem.querySelector(".kodereach-accordion-content");

                firstItem.classList.add("active");
                firstContent.style.maxHeight = firstContent.scrollHeight + "px";

                // Update image and pricing for first item
                if (firstHeader) {
                    updateServiceDisplay(firstHeader);
                }
            }
        });

        function toggleKodereachAccordion(header) {
            const item = header.parentElement;
            const content = item.querySelector(".kodereach-accordion-content");
            const isOpen = item.classList.contains("active");

            // Close all accordions first
            document.querySelectorAll(".kodereach-accordion-item").forEach(acc => {
                acc.classList.remove("active");
                acc.querySelector(".kodereach-accordion-content").style.maxHeight = null;
            });

            // Re-open clicked one ONLY if it was not open
            if (!isOpen) {
                item.classList.add("active");
                content.style.maxHeight = content.scrollHeight + "px";

                // Update service image and pricing on the right
                updateServiceDisplay(header);
            }
        }

        function updateServiceDisplay(header) {
            const serviceImage = header.getAttribute('data-service-image');
            const servicePrice = header.getAttribute('data-service-price');
            const serviceDuration = header.getAttribute('data-service-duration');

            const serviceImageElement = document.getElementById('serviceImage');
            const servicePricingElement = document.getElementById('servicePricing');

            // Update image with smooth transition
            if (serviceImageElement && serviceImage) {
                serviceImageElement.style.opacity = '0';
                setTimeout(() => {
                    serviceImageElement.src = serviceImage;
                    serviceImageElement.style.opacity = '1';
                }, 200);
            }

            // Update pricing
            if (servicePricingElement && servicePrice && serviceDuration) {
                servicePricingElement.textContent = `${servicePrice} / ${serviceDuration}`;
            }
        }
    </script>
@endsection
