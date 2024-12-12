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
            <div class="filters">
                <div class="row align-items-end">
                    <!-- Job Title Filter -->
                    <div class="col-lg-3 col-6">
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
                    <div class="col-lg-3 col-6">
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
            </div>
        </div>

    </section>

    <section class="event_slider">
        <div class="container">
            <div class="swiper">
                <div class="swiper-wrapper">
                    <div class="swiper-slide">
                        <div class="card">
                            <img src="https://via.placeholder.com/300x180" alt="Event Image">
                            <div class="card-content">
                                <h3>Event 1</h3>
                                <div class="details">
                                    <span>Janurary 7, 2025</span>
                                    <span>10:00 AM - 11:00 AM</span>
                                </div>
                                <a href="#" class="view-more">View More</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card">
                            <img src="https://via.placeholder.com/300x180" alt="Event Image">
                            <div class="card-content">
                                <h3>Event 2</h3>
                                <div class="details">
                                    <span>Janurary 7, 2025</span>
                                    <span>10:00 AM - 11:00 AM</span>
                                </div>
                                <a href="#" class="view-more">View More</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card">
                            <img src="https://via.placeholder.com/300x180" alt="Event Image">
                            <div class="card-content">
                                <h3>Event 3</h3>
                                <div class="details">
                                    <span>Janurary 7, 2025</span>
                                    <span>10:00 AM - 11:00 AM</span>
                                </div>
                                <a href="#" class="view-more">View More</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card">
                            <img src="https://via.placeholder.com/300x180" alt="Event Image">
                            <div class="card-content">
                                <h3>Event 4</h3>
                                <div class="details">
                                    <span>Janurary 7, 2025</span>
                                    <span>10:00 AM - 11:00 AM</span>
                                </div>
                                <a href="#" class="view-more">View More</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card">
                            <img src="https://via.placeholder.com/300x180" alt="Event Image">
                            <div class="card-content">
                                <h3>Event 5</h3>
                                <div class="details">
                                    <span>Janurary 7, 2025</span>
                                    <span>10:00 AM - 11:00 AM</span>
                                </div>
                                <a href="#" class="view-more">View More</a>
                            </div>
                        </div>
                    </div>
                    <div class="swiper-slide">
                        <div class="card">
                            <img src="https://via.placeholder.com/300x180" alt="Event Image">
                            <div class="card-content">
                                <h3>Event 6</h3>
                                <div class="details">
                                    <span>Janurary 7, 2025</span>
                                    <span>10:00 AM - 11:00 AM</span>
                                </div>
                                <a href="#" class="view-more">View More</a>
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
            <h2 class="mb-3">Articles</h2>
            <div class="row">
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header p-0 border-0">
                            <img src="https://via.placeholder.com/650x300" alt="" class="img-fluid rounded">
                        </div>
                        <div class="card-body">
                            <h3>
                                Don't let your luggage be a burden
                            </h3>
                            <p>
                                Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                                labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                                laboris nisi ut aliquip ex ea commodo consequat.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header p-0 border-0">
                            <img src="https://via.placeholder.com/650x300" alt="" class="img-fluid rounded">
                        </div>
                        <div class="card-body">
                            <h3>
                               Disconnect like never before without getting lost
                            </h3>
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

    <script>
        // Prevent dropdown menu from closing when clicking inside
        document.querySelectorAll('.dropdown-menu').forEach((dropdown) => {
            dropdown.addEventListener('click', (e) => {
                e.stopPropagation();
            });
        });
    </script>
@endsection
