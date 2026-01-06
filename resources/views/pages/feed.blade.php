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
            border: none;
            padding: 0;
            margin: 0;
            background: transparent;
            color: #B8C034;
            font-family: "Inter";
            font-size: 22px;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
            margin-top: -5px !important;
            text-align: left;
        }

        .read-more-btn:hover,
        .read-more-btn:focus,
        .read-more-btn:active {
            background: transparent !important;
            color: var(--secondary) !important;
            border: none !important;
        }

        #productModal .modal-dialog.modal-lg {
            max-width: 1139px;
            width: 100%;
        }

        .productModalImageBox {
            height: 100%;
            border-radius: 14.47px;
            border: 3px solid #B8C034;
            position: relative;
            overflow: hidden;
        }

        #productModalImage {
            height: 100%;
            width: 100%;
            object-fit: cover;
            object-position: top center;
        }

        .productModalPriceBox {
            background: var(--secondary);
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: fit-content;
            gap: 10px;
            color: #000;
            font-family: "Inter";
            font-size: 16px;
            font-weight: 500;
            padding: 6px 12px;
            border-radius: 50px;
        }

        h6#productModalUserName {
            font-size: 18.71px;
            font-family: Inter;
            font-weight: 400;
            line-height: 26.73px;
            color: #fff;
        }

        .productModalContent {
            padding: 10px;
            min-height: 365px;
            width: 100%;
        }

        small#productModalDate {
            font-size: 16.04px;
            font-family: Inter;
            font-weight: 400;
            line-height: 18.71px;
            color: #fff;
        }

        .event_slider h2,
        .articles h2,
        .why_choose h2,
        .industries_sec h2 {
            text-align: center;
            margin-bottom: 40px !important;
        }

        #productModal .modal-footer {
            padding: 0 0 0 34px;
            border: none;
            width: 100%;
            max-width: 60%;
            display: flex;
            align-items: stretch;
            justify-content: space-between;
        }

        .productModalUserProfileBox {
            display: flex;
            align-items: center;
            justify-content: start;
            width: 100%;
            max-width: 48%;
        }

        .productModalUserProfileBox img {
            object-fit: cover;
        }

        #productModal .direct-message-btn {
            height: 60px !important;
            font-size: 18px;
            font-weight: 500;
            font-family: "Poppins";
            flex: 1;
            border-radius: 10px;
            background: var(--secondary);
            color: #273572;
            display: flex;
            align-items: center;
            justify-content: center;
            text-decoration: none;
            max-width: 326px;
            height: 100%;
            transition: .3s;
        }

        #productModal .direct-message-btn:hover {
            color: #ffffffff;
            transition: .3s;
        }

        #productModal .modal-content {
            margin-top: 150px;
            height: fit-content;
            overflow: hidden;
            flex-direction: row;
            background: linear-gradient(45deg, #1F2C77 50%, #2C3FB3 110%);
            padding: 26px;
            border: 3px solid #fff;
            border-radius: 17px !important;
            overflow: hidden;
        }

        h5.customHeading {
            color: #fff !important;
            width: fit-content;
            text-align: left;
            font-family: "Bebas Neue", sans-serif;
            font-weight: 400;
            font-style: normal;
            font-size: 64px;
            line-height: 106%;
            margin: 18px 0;
            position: relative;
        }

        h5.customHeading::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            width: 50px;
            height: 6px;
            background-color: #b8c034;
        }

        #productModalDescription {

            font-size: 22px;
            font-family: "inter";
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
            line-height: 140%;
            width: 100%;
            color: #fff;
        }

        .modal-footer .btn-close {
            --bs-btn-close-bg: url("{{ asset('assets/images/modalCloseVector.svg') }}") !important;
            position: absolute;
            top: 5px;
            right: 5px;
            background-color: #434F9C;
            color: #ffffff !important;
            --bs-btn-close-opacity: 1;
            padding: 7px 12px 18px 14px;
            border-radius: 50%;
        }

        .modal-footer .btn-close img {
            width: 15px;

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



        .productSliderSection .container a.viewMoreBtn,
        .articles .container a.viewMoreBtn {
            font-size: 18px;
            font-weight: 500;
            font-family: "Poppins";
            border-radius: 9.77px;
            color: #273572;
            background-color: #B8C034;
            padding: 10px 60px;
            text-decoration: none;
            margin: 30px auto 0;
            border: 1px solid #B8C034;
            display: flex;
            justify-content: center;
            width: fit-content;
        }

        .avatar-initials {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            background: #394a93;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 18px;
            letter-spacing: 1px;
        }

        .author-avatar {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            overflow: hidden;
        }

        .author-avatar img {
            width: 100%;
            height: 100%;
            object-fit: cover;
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
    <link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">


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
                        data-image="{{ $product->product_image ? getImageUrl($product->product_image) : 'assets/images/MuslimLynkPlaceholder.png' }}"
                        data-price="{{ $product->discounted_price && $product->discounted_price < $product->original_price ? '$' . $product->discounted_price . ' (was $' . $product->original_price . ')' : '$' . $product->original_price }}"
                        data-quantity="{{ $product->quantity }}-{{ $product->unit_of_quantity }}"
                        data-user-name="{{ $product->user->first_name }}"
                        data-user-photo="{{ $product->user_has_photo ? getImageUrl($product->user->photo) : '' }}"
                        data-user-initials="{{ $product->user_initials }}"
                        data-date="{{ $product->created_at->format('d-M-Y') }}">
                        <div class="container">
                            <div class="productSliderSecInnerCol">
                                <h2 class="industriesMainHeading">{{ $product->title }}</h2>
                                <p class="industriesMainPara">{{ Str::limit($product->short_description, 120) }}</p>
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
                                            @if ($product->user_has_photo)
                                                <img src="{{ getImageUrl($product->user->photo) }}"
                                                    alt="{{ $product->user->first_name }}">
                                            @else
                                                <div class="avatar-initials">
                                                    {{ $product->user_initials }}
                                                </div>
                                            @endif
                                        </div>
                                        <div class="author-details">
                                            <div class="author-name">{{ $product->user->first_name }}</div>
                                            <div class="author-date">{{ $product->created_at->format('d-M-Y') }}</div>

                                        </div>
                                    </div>
                                </div>
                                @if (Auth::id() !== $product->user->id)
                                    <button class="sliderUserMessageBtn view-more direct-message-btn"
                                        data-receiver-id="{{ $product->user->id }}">Message now</button>
                                @endif

                            </div>
                            <div class="productSliderSecInnerCol">
                                <img src="{{ $product->product_image ? getImageUrl($product->product_image) : 'assets/images/MuslimLynkPlaceholder.png' }}"
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
        <div class="container">
            <a class="viewMoreBtn" href="{{ route('products') }}" target="_blank">View All</a>
        </div>
    </section>




    <section class="leadGenSec">
        <div class="container">
            <h1 class="main_heading">
                MuslimLynk Redefines<span class="leadGenSecHeading"> Lead Generation</span>
            </h1>
            <p class="leadGenPara">MuslimLynk empowers its members through cutting-edge lead generation tools and
                strategies. By leveraging advanced business intelligence and seamless communication platforms, MuslimLynk
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
                @forelse($services as $index => $service)
                    <div class="kodereach-accordion-item {{ $index === 0 ? 'active' : '' }}">
                        <!-- LEFT SECTION: Heading and Content -->
                        <div class="serviceAccordionConInner">
                            <div class="kodereach-accordion-header {{ $index === 0 ? 'active' : '' }}"
                                onclick="toggleKodereachAccordion(this)">
                                <h3 class="kodereach-accordion-title">{{ $service->title }}</h3>
                                <div class="kodereach-accordion-arrow">
                                    <svg fill="#000000" width="20px" height="20px" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M3.293,20.707a1,1,0,0,1,0-1.414L17.586,5H12a1,1,0,0,1,0-2h8a1,1,0,0,1,1,1v8a1,1,0,0,1-2,0V6.414L4.707,20.707a1,1,0,0,1-1.414,0Z" />
                                    </svg>
                                </div>
                            </div>
                            <div class="kodereach-accordion-content" style="{{ $index === 0 ? 'display: block;' : '' }}">
                                <div class="kodereach-accordion-content-inner">
                                    <p class="description">
                                        {{ $service->short_description }}
                                    </p>

                                    @if (Auth::id() !== $service->user->id)
                                        <button class="message-btn direct-message-btn"
                                            data-receiver-id="{{ $service->user->id }}">
                                            Message Now
                                        </button>
                                    @endif


                                    <div class="author-info">
                                        <div class="author-avatar">
                                            @if ($service->user_has_photo)
                                                <img src="{{ getImageUrl($service->user->photo) }}"
                                                    alt="{{ $service->user->first_name }}">
                                            @else
                                                <div class="avatar-initials">
                                                    {{ $service->user_initials }}
                                                </div>
                                            @endif
                                        </div>

                                        <div class="author-details">
                                            <div class="author-name">{{ $service->user->first_name }}
                                                {{ $service->user->last_name }}</div>
                                            <div class="author-date">{{ $service->created_at->format('d M Y') }}</div>
                                        </div>
                                    </div>




                                </div>
                            </div>
                        </div>

                        <!-- RIGHT SECTION: Service Image -->
                        <div class="serviceAccordionConInner">
                            <div class="servideAccordionImgCon">
                                <img src="{{ $service->service_image ? getImageUrl($service->service_image) : asset('assets/images/servicePlaceholderImg.png') }}"
                                    alt="{{ $service->title }}" class="img-fluid serviceImg">
                                <span class="servicePricing">
                                    @if ($service->discounted_price && $service->discounted_price < $service->original_price)
                                        ${{ $service->discounted_price }} / {{ $service->duration }}
                                    @else
                                        ${{ $service->original_price }} / {{ $service->duration }}
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-4">
                        <p>No services available at the moment.</p>
                    </div>
                @endforelse
            </div>
        </div>
        <div class="container">
            <a class="viewMoreBtn" href="{{ route('services') }}" target="_blank">View All</a>
        </div>
    </section>


    @include('layouts.home-footer')
    <!-- Main Modal -->
    <div class="modal fade" id="mainModal" tabindex="-1" aria-labelledby="mainModalLabel">
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
    <div class="modal fade" id="productModal" tabindex="-1" aria-labelledby="productModalLabel">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">

                <div class="modal-body p-0">
                    <div class="productModalImageBox">
                        <img id="productModalImage" src="" class="img-fluid mb-3" alt="Product image" />

                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><img
                            src="assets/images/closeIcon.webp" alt=""></button>
                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                    <div class="productModalContent">
                        <p class="productModalPriceBox"> <span id="productModalPrice"></span><span
                                id="productModalQuantity"></span></p>


                        <h5 class="modal-title customHeading" id="productModalLabel">Product Title</h5>


                        <p class="mt-2"><span id="productModalDescription"></span></p>


                    </div>
                    <div class="productModalUserProfileBox">

                        <a href="javascript:void(0)" class="view-more direct-message-btn" data-receiver-id=""
                            data-bs-dismiss="modal">Message Now</a>
                    </div>
                    <div class="productModalUserProfileBox">
                        <img id="productModalUserPhoto" src="" alt="User photo" class="rounded-circle me-2"
                            width="50" height="50">
                        <div>
                            <h6 id="productModalUserName" class="mb-0"></h6>
                            <small id="productModalDate"></small>
                        </div>
                    </div>

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
            let autoSlideInterval = null;
            const AUTO_SLIDE_DELAY = 5000; // 3 seconds

            // Elements
            const sliderTrack = document.getElementById('sliderTrack');
            const slides = document.querySelectorAll('.slide');
            const totalSlides = slides.length;
            const dotsContainer = document.getElementById('sliderDots');

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
            }

            // Update slider position
            function updateSlider() {
                const offset = currentIndex * -100;
                sliderTrack.style.transform = `translateX(${offset}%)`;
                updateButtons();
                updateDots();
            }

            // Update button states
            function updateButtons() {
                const prevButtons = document.querySelectorAll('[data-action="prev"]');
                const nextButtons = document.querySelectorAll('[data-action="next"]');

                prevButtons.forEach(btn =>
                    btn.classList.toggle('disabled', currentIndex === 0)
                );

                nextButtons.forEach(btn =>
                    btn.classList.toggle('disabled', currentIndex === totalSlides - 1)
                );
            }

            // Update dots
            function updateDots() {
                const dots = dotsContainer.querySelectorAll('.dot');
                dots.forEach((dot, index) =>
                    dot.classList.toggle('active', index === currentIndex)
                );
            }

            // Next slide
            function nextSlide() {
                if (currentIndex < totalSlides - 1) {
                    currentIndex++;
                } else {
                    currentIndex = 0; // loop back to start
                }
                updateSlider();
            }

            // Previous slide
            function prevSlide() {
                if (currentIndex > 0) {
                    currentIndex--;
                    updateSlider();
                }
            }

            // Go to specific slide
            function goToSlide(index) {
                currentIndex = index;
                updateSlider();
            }

            // Auto slide functions
            function startAutoSlide() {
                stopAutoSlide();
                autoSlideInterval = setInterval(nextSlide, AUTO_SLIDE_DELAY);
            }

            function stopAutoSlide() {
                if (autoSlideInterval) {
                    clearInterval(autoSlideInterval);
                    autoSlideInterval = null;
                }
            }

            // Event delegation
            document.addEventListener('click', function(e) {
                const target = e.target;

                if (target.hasAttribute('data-action')) {
                    e.preventDefault();
                    stopAutoSlide();

                    if (target.getAttribute('data-action') === 'next') nextSlide();
                    if (target.getAttribute('data-action') === 'prev') prevSlide();

                    startAutoSlide();
                }

                if (target.classList.contains('dot')) {
                    e.preventDefault();
                    stopAutoSlide();
                    goToSlide(parseInt(target.dataset.index));
                    startAutoSlide();
                }
            });

            // Keyboard navigation
            document.addEventListener('keydown', function(e) {
                stopAutoSlide();
                if (e.key === 'ArrowLeft') prevSlide();
                if (e.key === 'ArrowRight') nextSlide();
                startAutoSlide();
            });

            // Touch support
            let touchStartX = 0;
            let touchEndX = 0;

            sliderTrack.addEventListener('touchstart', e => {
                stopAutoSlide();
                touchStartX = e.changedTouches[0].screenX;
            }, {
                passive: true
            });

            sliderTrack.addEventListener('touchend', e => {
                touchEndX = e.changedTouches[0].screenX;
                const diff = touchStartX - touchEndX;
                if (Math.abs(diff) > 50) diff > 0 ? nextSlide() : prevSlide();
                startAutoSlide();
            }, {
                passive: true
            });

            // Pause on hover
            sliderTrack.addEventListener('mouseenter', stopAutoSlide);
            sliderTrack.addEventListener('mouseleave', startAutoSlide);

            // Init
            createDots();
            updateSlider();
            startAutoSlide();

            console.log('Auto slider initialized');
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

                // Hide button if all industries are already visible
                if (industries.length <= initialRows * itemsPerRow) {
                    showMoreBtn.style.display = "none";
                } else {
                    showMoreBtn.style.display = "block";
                    showMoreBtn.textContent = isExpanded ? "View Less" : "View All";
                }
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
            // console.log("directMessageBtn", directMessageBtn);
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
                    url: "{{ route('sendMessage') }}",
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
        window.AUTH_USER_ID = {{ Auth::id() ?? 'null' }};
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

                modal.querySelector('#productModalUserName').textContent = wrapper.dataset.userName;
                modal.querySelector('#productModalDate').textContent = "Posted on " + wrapper.dataset.date;
                modal.querySelector('.direct-message-btn').setAttribute('data-receiver-id', wrapper.dataset
                    .id);

                const receiverId = wrapper.dataset.id;
                // hide button if it's the same user
                if (window.AUTH_USER_ID && parseInt(window.AUTH_USER_ID) === parseInt(receiverId)) {
                    modal.querySelector('.direct-message-btn').closest('.productModalUserProfileBox').style
                        .display = 'none';
                } else {
                    modal.querySelector('.direct-message-btn').closest('.productModalUserProfileBox').style
                        .display = 'inline-flex'; // or 'block' based on your CSS
                }


                // Handle user photo or initials
                const userPhotoElement = modal.querySelector('#productModalUserPhoto');
                const userPhoto = wrapper.dataset.userPhoto;

                if (userPhoto) {
                    // Show photo - replace with img if initials div exists
                    if (userPhotoElement.tagName !== 'IMG') {
                        const img = document.createElement('img');
                        img.id = 'productModalUserPhoto';
                        img.className = 'rounded-circle me-2';
                        img.width = 50;
                        img.height = 50;
                        img.src = userPhoto;
                        img.alt = wrapper.dataset.userName;
                        userPhotoElement.replaceWith(img);
                    } else {
                        userPhotoElement.src = userPhoto;
                        userPhotoElement.alt = wrapper.dataset.userName;
                    }
                } else {
                    // Show initials - replace img with div if needed
                    if (userPhotoElement.tagName === 'IMG') {
                        const initialsDiv = document.createElement('div');
                        initialsDiv.id = 'productModalUserPhoto';
                        initialsDiv.className = 'avatar-initials me-2';
                        initialsDiv.textContent = wrapper.dataset.userInitials;
                        userPhotoElement.replaceWith(initialsDiv);
                    } else {
                        userPhotoElement.textContent = wrapper.dataset.userInitials;
                    }
                }

                bsModal.show();
            });
        });
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Make FIRST accordion active by default
            const firstItem = document.querySelector(".kodereach-accordion-item");
            if (firstItem) {
                const firstContent = firstItem.querySelector(".kodereach-accordion-content");
                if (firstContent) {
                    firstItem.classList.add("active");
                    firstContent.style.maxHeight = firstContent.scrollHeight + "px";
                }
            }
        });

        function toggleKodereachAccordion(header) {
            const item = header.closest(".kodereach-accordion-item");
            const content = item.querySelector(".kodereach-accordion-content");
            const isOpen = item.classList.contains("active");

            // Close all accordions first
            document.querySelectorAll(".kodereach-accordion-item").forEach(acc => {
                acc.classList.remove("active");
                const accContent = acc.querySelector(".kodereach-accordion-content");
                if (accContent) {
                    accContent.style.maxHeight = null;
                }
            });

            // Re-open clicked one ONLY if it was not open
            if (!isOpen) {
                item.classList.add("active");
                content.style.maxHeight = content.scrollHeight + "px";
            }
        }
    </script>
@endsection
