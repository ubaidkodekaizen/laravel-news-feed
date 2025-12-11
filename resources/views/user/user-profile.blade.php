@extends('layouts.main')
@section('content')
    <style>
        body{
            overflow: hidden;
        }


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

        .company_link,
        .company_contact,
        .company_position,
        .company_experience {
            font-size: 20.92px;
            font-family: "inter";
            font-weight: 500;
            color: #333333;
            margin-bottom: 36px;
            line-height: 150%;
            display: flex;
        }

        a {
            text-decoration: none;
        }

        .company_card span i {
            margin-right: 0;
            color: #273572;
            width: 20px;
            height: 20px;
        }
        .fa-people-group:before {
            content: "\e533";
            margin-left: -3px;
        }

        .company_experience span,
        .company_position span,
        .company_contact span{
            padding: 8px 11px 10px 11px;
            border: 1px solid #B8C034;
            background: #B8C034;
            border-radius: 50%;
            margin-right: 14px;
        }

        .event_slider .card,
        .services_slider .card {
            width: 100%;
            min-height: unset !important;
            box-shadow: none !important;
            border-radius: 0px !important;
            border: none !important;
        }

        .services_slider .card {
            box-shadow: 0px 0px 10px 0px #0000001a;
            border-radius: 10px;
            border: 1px solid #e7e7e7;
            overflow: hidden;
        }

        .event_slider .swiper-slide,
        .services_profile_border .swiper-slide {
            width: 414px !important;
        }

       

        /* .company_card {
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            height: auto !important;
            border-right: 1px solid #E9EBF0;
            background: #f4f5fb;
        } */

            .company_card {
            padding: 40px 34px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            height: calc(-100px + 100.5vh) !important;
            overflow-y: auto;
            border-right: 1px solid #E9EBF0;
            background: #f4f5fb;
        }

        .profile_qualification_sec {
            padding: 30px 20px;
            background: #273572;
            box-shadow: 0px 0px 10px 0px #0000001a;
            border-radius: 10px;
            border: 1px solid #e7e7e7;
        }

        .company_card_details {
            padding: 50px 20px 20px;
            padding-bottom: 10px;
            background: #fff;
            border: 1px solid #E5E5E5;
            box-shadow: none;
            border-radius: 10px;
        }

        .company_logo {
            background: #fff;
            box-shadow: none;
            border-radius: 10px;
            border: 1px solid #E5E5E5;
            min-height: 150px;
        }

        .company_profile_section,
        .company_profile_section .col-lg-3,
        .services_profile_border {
            border: none;
        }

        section.user_profile_view .container{
            max-width: 100% !important;
            padding: 0;
        }

        .profile-container {
            border: none;
            /* overflow: hidden; */
            margin: 0;
            height: auto;
        }

        .profile-image {
            width: 144px;
            height: 201px;
            border-radius: 10px;
            border: none;
            bottom: 0px;
            transform: translateX(0%);
            position: unset;
        }

        .profile-container .profile-details {
            top: 0px;
            padding-bottom: 0px;
            text-align: left;
        }

        .contact_social_flex { 
            border-bottom: none;
            padding-bottom: 0px;
            max-width: 100%;
            margin: 0;
            top: -8px;
        }

        .event_slider h2,
        .services_profile_border h2 {
            font-size: 24px;
            margin-bottom: 19px !important;
        }

        .services_profile_border {
            margin-top: 15px;
            padding-right: 20px;
            width: 94%;
            margin: 30px auto;
        }

        .generalInfoHeading{
            padding: 0 0px;
            margin-top: 0px;
            margin-bottom: 0px;
            font-size: 24px;
            font-family: "Inter";
            font-weight: 600;
            max-width: max-content;
            padding-bottom: 8px;
            color: #273572;
        }

        .company_profile_section .profile_heading{
            margin-top: 30px;
            margin-bottom: 0px;
            font-size: 18.65px;
            font-family: "Inter";
            font-weight: 600;
            max-width: max-content;
            color: #333333;
        }



        .direct-message-btn {
           border-radius: 25px;
            padding: 6px 20px;
            font-size: 16.85px;
            font-family: "Inter";
            font-weight: 600;
            color: #273572;
        }

        .company_profile_section .articles .card .card-body {
            padding: 10px;
        }

        .company_profile_section .event_slider .card-content h3,
        .company_profile_section .articles .card-body h3 {
            margin-top: 0;
            margin-bottom: 14px;
            font-size: 21px;
            font-family: "Inter";
            font-weight: 500;
            color: #000000;
        }

        .event_price_label,
        .service_price_duration {
            background: var(--secondary);
            color: var(--white);
            padding: 4px 12px;
            border-radius: 30px;
            margin-left: auto;
            position: absolute;
            top: -45px;
            left: 20px;
            height: 35px;
            width: fit-content;
        }

        .service_price_duration {
            top: -55px;
        }

        .event_slider .card-content p,
        .company_profile_section .articles .card-body p {
            font-size: 16px;
            font-family: "Inter";
            font-weight: 400;
            color: #555 !important;
        }

        .company_profile_section .event_slider .card-content,
        .company_profile_section .articles .card .card-body {
            padding: 20px;
            background: #F2F2F2;
            position: relative;
        }

        .company_profile_section .event_slider .event_price_label p span,
        .service_price p span{
            font-size: 16px;
            padding: 4px 14px;
            color: #000000;
            font-weight: 500;
        }

        .service_price p { margin-bottom: 1px !important;}

         /* .company_profile_section .event_slider .event_price_label {
              
        } */

      

        .company_profile_section .event_slider .card img,
        .company_profile_section .articles .card img {
            height: 414px;
        }

        .company_profile_section .event_slider {
            padding: 10px 0px;
            padding-right: 20px;
        }

        .company_profile_section{
            top: 0px;
        }

        .company_logo .logo_img { padding: 0;}

        h4.education-title{
            color: #B8C034;
            font-weight: 700;
            font-size: 20.92px;
            font-family: "Inter";
            line-height: 140%;
        }

        p.education-details.mb-0 {
             color: #ffffff;
            font-size: 18px;
            font-weight: 400;
            font-family: "Inter";
        }


        .mainProfileImage {
            background: #fff;
            display: flex;
            gap: 26px;
            width: 94%;
            margin: 30px auto 0;
            border: 1px solid #E5E5E5;
            border-radius: 14.11px;
            padding: 14px;
        }


        .profile-details h1 {
            font-size: 35.86px;
            justify-content: left;
            font-family: "Inter";
            font-weight: 600;
            color: #273572;
        }
        .profile-details p {
            font-size: 18.92px;
            text-align: left;
            margin: 0;
            line-height: 140%;
            font-family: "Inter";
            font-weight: 300;
            color: #273572;
        }

        .profileHeadingAndSocials {
            display: flex;
            gap: 20px;
        }

        .list_check_flex {
            gap: 10px;}

         .list_check_flex li{
            border-radius: 50%;
            overflow: hidden;
         }

        .list_check_flex li a svg {
            border: 1px solid #B8C034;
            padding: 9px;
            width: 44px;
            height: 44px;
            /* border-radius: 50%; */
            background: #B8C034;
            fill: #273572 !important;
            color: #273572 !important;
            accent-color: #273572 !important;
        }

        .profileInfoMain {
            background: #fff;
            width: 94%;
            margin: 30px auto;
            border: 1px solid #E5E5E5;
            border-radius: 14.11px;
            padding: 40px;
        }

        .profileInfoInner .row {
            margin-top: 40px;
        }

        h2.profileInfoHeading {
            font-size: 24px;
            font-family: "inter";
            font-weight: 600;
            color: #273572;
        }

        h3.profileInfoInnerInfoHead {
            font-size: 20.92px;
            font-weight: 400;
            font-family: "Inter";
            line-height: 140%;
        }

        p.profileInfoInnerInfoAns {
            font-size: 20.92px;
            font-weight: 700;
            font-family: "Inter";
            margin: 0;
            word-wrap: break-word;
        }

        .offeredHeadingMain {
            display: flex;
            flex-direction: column;
            justify-self: center;
            align-items: center;
        }

        .offeredProductSubHeading{
            background-color: rgb(184, 192, 52);
            color: rgb(39, 53, 114);
            font-size: 18px;
            font-family: Inter, sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            font-weight: 700;
            padding: 9px 17px;
            border-radius: 53px;
            width: max-content;
        }

        .offeredProductMainHeading span{
            color: rgb(184, 192, 52);
        }

        .offeredProductMainHeading{
            color: rgb(39, 53, 114);
            font-size: 58.7px !important;
            font-family: Inter, sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            font-weight: 700;
            margin-top: 20px;
            text-align: center;
        }

        .swiper.swiper-initialized.swiper-horizontal.swiper-backface-hidden {
            width: 94%;
            margin: 30px auto;
        }

        #userEducation .noEduFound {
            color: #ffffffff;
            font-size: 12.7px !important;
                font-family: Inter, sans-serif;
         }

         .mainProfileContent{
            background: #FAFBFF;
            height: calc(-100px + 100.5vh) !important;
            overflow-y: auto;
         }

        p.mainProfilePosition {
            top: -6px;
            left: 3px;
            position: relative;
        }

         /* .col-lg-3 {
             flex: 0 0 20%;} */

        .profile-details .location {
            font-size: 20.92px;
            color: #273572;
            font-weight: 400;
            margin-top: 16px;
        }

        .list_check_flex li a img {
            width: 44px;
        }

         @media (max-width: 1280px) {
            .company_profile_section .col-lg-3{
                width: 30%;
            }

            .mainProfileContent {
               width: 68%;
        margin-left: 20px;
            }
        }

        @media (max-width: 998px) {
            .company_profile_section .col-lg-3{
                width: 30%;
            }

            .mainProfileContent {
               width: 70%;
        margin-left: 0px;
            }
        }

         @media (max-width: 788px) {
            /* .company_profile_section .col-lg-3{
                width: 0%;
            } */

            .mainProfileContent {
               width: 70%;
            margin-left: 0px;
                }

                .mainProfileContent {
                width: 100%;
                margin-left: 10px;
            }

            .sidebar {
            background: transparent !important;}

            .company_card{
                  height: auto !important;
              padding: 120px 20px 20px 20px;
            }

            .company_profile_section .col-lg-3 {
            padding-right: 0 !important;
            padding-left: 0;}

            .mainProfileImage {
            flex-direction: column;}

            .profileHeadingAndSocials {
                gap: 0;
            flex-direction: column;}

            .col-lg-3 {
                flex: 0 0 100%;
                 margin-top: 22px;
            }
            .profileInfoInner .row {
                margin-top: 0;
            }

            .addressInfo {
                flex: 0 0 100%;
                margin-top: 22px;
                padding: 0 14px;
            }

            .sidebar {
            width: 250px;
            background: #f8f9fa;
            position: fixed;
            left: -250px;
            top: 0;
            height: 100%;
            transition: left 0.3s ease;
        }

        .sidebar.open {
            width: 100% !important;
            left: 0;
            z-index: 99;
        }
        }

        


        
    </style>

<button class="sidebar-toggler btn btn-primary d-lg-none" 
        type="button" 
        onclick="toggleSidebar()">
    Company Info
</button>
    <section class="user_profile_view">
        <div class="container">
            <!-- LinkedIn Profile View -->
            <div class="profile-container">
                <!-- Cover Image -->
                <!-- <div class="cover-image"></div> -->

                <!-- Profile Image -->
                <div class="position-relative">
                    <div class="company_profile_section">
                        <div class="row ">
                            <div id="sidebar" class="col-lg-3 sidebar">
                                <div class="company_card">
                                    <h2 class="generalInfoHeading">Company Info</h2>
                                    <div class="company_logo">
                                        <img src="{{ isset($user->company) && $user->company->company_logo ? asset('storage/' . $user->company->company_logo) : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcd5J_YDIyLfeZCHcsBpcuN8irwbIJ_VDl0Q&s' }}"
                                            alt="Company Logo" class="logo_img">
                                    </div>
                                    <div class="company_card_details">
                                        <!-- <div class="company_name_icon_flex">
                                            @if (!empty($user->company->company_name))
                                                <h2 class="company_name">
                                                    <span><i class="fa-solid fa-building"></i></span>
                                                    {{ $user->company->company_name }}
                                                </h2>
                                            @endif

                                            <div class="icons_flex">
                                                @if (!empty($user->company->company_web_url))
                                                    <a href="{{ $user->company->company_web_url }}" class="company_link"
                                                        target="_blank">
                                                        <i class="fa-solid fa-link"></i>
                                                    </a>
                                                @endif

                                                @if (!empty($user->company->company_linkedin_url))
                                                    <a href="{{ $user->company->company_linkedin_url }}" target="_blank"
                                                        class="company_link" rel="noopener noreferrer">
                                                        <i class="fa-brands fa-linkedin"></i>
                                                    </a>
                                                @endif
                                            </div>
                                        </div> -->

                                        @if (!empty($user->company->company_business_type))
                                            <!-- <p class="company_experience">
                                                <span><i class="fa-solid fa-landmark"></i></span>
                                                {{ $user->company->company_business_type }}
                                            </p> -->

                                            <div class="company_experience">
                                                <div><span><i class="fa-solid fa-landmark"></i></span></div>
                                                <div>{{ $user->company->company_business_type }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($user->company->company_position))
                                            <!-- <p class="company_position">
                                                <span><i class="fa-solid fa-user-tie"></i></span>
                                                {{ $user->company->company_position }}
                                            </p> -->
                                            <div class="company_position">
                                                <div> <span><i class="fa-solid fa-user-tie"></i></span></div>
                                                <div>{{ $user->company->company_position }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($user->company->company_experience))
                                            <!-- <p class="company_experience">
                                                <span><i class="fa-solid fa-business-time"></i></span>
                                                {{ $user->company->company_experience }}
                                            </p> -->
                                            <div class="company_experience">
                                                <div><span><i class="fa-solid fa-business-time"></i></span></div>
                                                <div>{{ $user->company->company_experience }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($user->company->company_phone))
                                            <!-- <a href="tel:{{ $user->company->company_phone }}" class="company_contact">
                                                <span><i class="fa-solid fa-phone"></i></span>
                                                {{ $user->company->company_phone }}
                                                
                                            </a> -->
                                            <a href="tel:{{ $user->company->company_phone }}">
                                            <div class="company_contact">
                                                <div><span><i class="fa-solid fa-phone"></i></span></div>
                                                <div>{{ $user->company->company_phone }}</div>
                                            </div>
                                            </a>
                                        @endif

                                        @if (!empty($user->company->company_revenue))
                                            <!-- <p class="company_experience">
                                                <span><i class="fa-solid fa-money-bill-trend-up"></i></span>
                                                ${{ $user->company->company_revenue }}
                                            </p> -->
                                            <div class="company_experience">
                                                <div><span><i class="fa-solid fa-money-bill-trend-up"></i></span></div>
                                                <div> ${{ $user->company->company_revenue }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($user->company->company_no_of_employee))
                                            <!-- <p class="company_experience">
                                                <span><i class="fa-solid fa-people-group"></i></span>
                                                {{ $user->company->company_no_of_employee }}
                                            </p>  -->
                                            <div class="company_experience">
                                                <div><span><i class="fa-solid fa-people-group"></i></span></div>
                                                <div>{{ $user->company->company_no_of_employee }}</div>
                                            </div>
                                        @endif

                                        @if (!empty($user->company->company_industry))
                                            <!-- <p class="company_experience">
                                                <span><i class="fa-solid fa-industry"></i></span>
                                                {{ $user->company->company_industry }}
                                            </p> -->
                                            <div class="company_experience">
                                                <div><span><i class="fa-solid fa-industry"></i></span></div>
                                                <div>{{ $user->company->company_industry }}</div>
                                            </div>
                                        @endif
                                    </div>

                                    <h1 class="profile_data profile_heading">
                                        Qualifications
                                    </h1>
                                    <div class="profile_qualification_sec">
                                        <!-- <div class="accordion" id="userEducation">
                                            @forelse ($user->userEducations as $education)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header">
                                                        <button class="accordion-button" type="button"
                                                            data-bs-toggle="collapse"
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
                                                            {{ $education->college_university ?? '' }} -
                                                            {{ $education->year ?? '' }}
                                                        </div>
                                                    </div>
                                                </div>
                                            @empty
                                                <p>No Education is added to show.</p>
                                            @endforelse
                                        </div> -->
                                        <div id="userEducation">
                                            @forelse ($user->userEducations as $education)
                                                <div class="education-item mb-3">
                                                    <h4 class="education-title">
                                                        {{ $education->degree_diploma ?? '' }}
                                                    </h4>
                                                    <p class="education-details mb-0">
                                                        {{ $education->college_university ?? '' }} -
                                                        {{ $education->year ?? '' }}
                                                    </p>
                                                </div>
                                            @empty
                                                <p class="noEduFound">No Education is added to show.</p>
                                            @endforelse
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="col-lg-9 mainProfileContent">
                                <div class="event_slider">
                                    <div class="container">
                                        <div class="mainProfileImage">
                                            <div class="profile-image">
                        <img src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
                            alt="Profile Image">
                    </div>
                    <!-- Profile Details -->
                    <div class="profile-details">
                        <div class="profileHeadingAndSocials">

                            <h1>
                                {{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}
                            </h1>
                            <div class="contact_social_flex">
                        <!-- <div class="contact_email">
                            @if ($user->phone_public == 'Yes')
                                <div class="contact_info_flex">
                                    <i class="fa-solid fa-phone"></i>
                                    <div class="contact_name_info">
                                        <a href="tel:{{ $user->phone ?? '' }}">{{ $user->phone ?? '' }}</a>
                                    </div>
                                </div>
                            @endif
                            @if ($user->email_public == 'Yes')
                                <div class="contact_info_flex">
                                    <i class="fa-solid fa-envelope"></i>
                                    <div class="contact_name_info">
                                        <a href="mailto:{{ $user->email ?? '' }}">{{ $user->email ?? '' }}</a>
                                    </div>
                                </div>
                            @endif
                        </div> -->
                        <ul class="list_check_flex">
                            @if ($user->linkedin_url)
                                <li>
                                    <a href="https://www.linkedin.com/in/{{ $user->linkedin_url }}" target="_blank" title="LinkedIn" aria-label="LinkedIn profile">
                                        <img src="{{ asset('assets/images/linkedInIcon.svg') }}" alt="">
                                    </a>
                                </li>
                            @endif
                            @if ($user->facebook_url)
                                <li>
                                    <a href="{{ $user->facebook_url }}" target="_blank" title="Facebook">
                                        <img src="{{ asset('assets/images/facebookIcon.svg') }}" alt="">
                                    </a>
                                </li>
                            @endif

                            @if ($user->x_url)
                                <li>
                                    <a href="{{ $user->x_url }}" target="_blank" title="X (Formerly Twitter)">
                                        <img src="{{ asset('assets/images/twitterXiCon.svg') }}" alt="">
                                    </a>
                                </li>
                            @endif

                            @if ($user->instagram_url)
                                <li>
                                    <a href="{{ $user->instagram_url }}" target="_blank" title="Instagram">
                                        <img src="{{ asset('assets/images/instagramIcon.svg') }}" alt="">
                                    </a>
                                </li>
                            @endif
                            

                            @if ($user->tiktok_url)
                                <li>
                                    <a href="{{ $user->tiktok_url }}" target="_blank" title="TikTok">
                                        <img src="{{ asset('assets/images/tiktokIcon.svg') }}" alt="">
                                    </a>
                                </li>
                            @endif

                            @if ($user->youtube_url)
                                <li>
                                    <a href="{{ $user->youtube_url }}" target="_blank" title="YouTube">
                                        <img src="{{ asset('assets/images/youtubeIcon.svg') }}" alt="">
                                    </a>
                                </li>
                                @endif
                        </ul>
                    </div>
                        </div>
                        <p class="mainProfilePosition"> {{ $user->user_position ?? 'Not Provided' }} </p>
                        <p class="location mainProfileLocation"> <span><img src="{{ asset('assets/images/location.svg') }}"></span> {{ $user->city ?? '' }}, {{ $user->county ?? '' }}, {{ $user->state ?? '' }},
                            {{ $user->country ?? '' }}</p>
                        <!-- <a class="contact-info" href="javascript:void(0);" data-bs-toggle="modal"
                            data-bs-target="#moreDetailsModal">More Details</a> -->
                        <div class="mt-3">
                            <a href="javascript:void(0)" class="btn btn-secondary direct-message-btn"
                                data-receiver-id="{{ $user->id }}">
                                Direct Message
                            </a>
                        </div>
                    </div>

                                        </div>

                    <div class="profileInfoMain">
                        <div class="profileInfoInner">
                            <h2 class="profileInfoHeading">Personal Information</h2>
                            <div class="row">
                                <div class="col-lg-3">
                                    <h3 class="profileInfoInnerInfoHead">First Name</h3>
                                    <p  class="profileInfoInnerInfoAns">{{ $user->first_name ?? '' }}</p>
                                </div>
                                <div class="col-lg-3">
                                    <h3 class="profileInfoInnerInfoHead">Last Name</h3>
                                    <p  class="profileInfoInnerInfoAns">{{ $user->last_name ?? '' }}</p>
                                </div>
                                <!-- <div class="col-lg-3">
                                    <h3 class="profileInfoInnerInfoHead">Date of Birth</h3>
                                    <p  class="profileInfoInnerInfoAns">-</p>
                                </div> -->
                                <div class="col-lg-3">
                                    <h3 class="profileInfoInnerInfoHead">Ethnicity</h3>
                                    <p  class="profileInfoInnerInfoAns">{{ $user->ethnicity }}</p>
                                </div>

                                <div class="col-lg-3">
                                    <h3 class="profileInfoInnerInfoHead">Gender</h3>
                                    <p  class="profileInfoInnerInfoAns">{{ $user->gender }}</p>
                                </div>
                                
                            </div>
                            <div class="row">
                                
                                <div class="col-lg-3">
                                    <h3 class="profileInfoInnerInfoHead">Email Address</h3>
                                    <p  class="profileInfoInnerInfoAns">{{ $user->email ?? '' }}</p>
                                </div>
                                <div class="col-lg-3">
                                    <h3 class="profileInfoInnerInfoHead">Phone Number</h3>
                                    <p  class="profileInfoInnerInfoAns">{{ $user->phone ?? '' }}</p>
                                </div>
                                <div class="col-lg-3">
                                    <h3 class="profileInfoInnerInfoHead">Martial Status</h3>
                                    <p  class="profileInfoInnerInfoAns">{{ $user->marital_status }}</p>
                                </div>
                                <div class="col-lg-3">
                                    <h3 class="profileInfoInnerInfoHead">Nationality</h3>
                                    <p  class="profileInfoInnerInfoAns">{{ $user->nationality }}</p>
                                </div>
                            </div>
                            <div class="row">
                                
                                <div class="col-lg-3">
                                    <h3 class="profileInfoInnerInfoHead">Age Group</h3>
                                    <p  class="profileInfoInnerInfoAns">{{ $user->age_group }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="profileInfoMain">
                        <div class="profileInfoInner">
                            <h2 class="profileInfoHeading">Address</h2>
                            <div class="row">
                                <div class="col-lg-3 addressInfo">
                                    <h3 class="profileInfoInnerInfoHead">Country</h3>
                                    <p  class="profileInfoInnerInfoAns">{{ $user->country ?? '' }}</p>
                                </div>
                                <div class="col-lg-3 addressInfo">
                                    <h3 class="profileInfoInnerInfoHead">City</h3>
                                    <p  class="profileInfoInnerInfoAns">{{ $user->city ?? '' }}</p>
                                </div>
                                <div class="col-lg-3 addressInfo">
                                    <h3 class="profileInfoInnerInfoHead">Zip Code</h3>
                                    <p  class="profileInfoInnerInfoAns">{{ $user->zip_code ?? '' }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                                        

                    <!-- <div class="contact_social_flex">
                        <div class="contact_email">
                            @if ($user->phone_public == 'Yes')
                                <div class="contact_info_flex">
                                    <i class="fa-solid fa-phone"></i>
                                    <div class="contact_name_info">
                                        <a href="tel:{{ $user->phone ?? '' }}">{{ $user->phone ?? '' }}</a>
                                    </div>
                                </div>
                            @endif
                            @if ($user->email_public == 'Yes')
                                <div class="contact_info_flex">
                                    <i class="fa-solid fa-envelope"></i>
                                    <div class="contact_name_info">
                                        <a href="mailto:{{ $user->email ?? '' }}">{{ $user->email ?? '' }}</a>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <ul class="list_check_flex">
                            @if ($user->linkedin_url)
                                <li>
                                    <a href="https://www.linkedin.com/in/{{ $user->linkedin_url }}" target="_blank"
                                        title="Facebook">
                                        <img src="{{ asset('assets/images/social-icons/linkedin.png') }}" alt="">
                                    </a>
                                </li>
                            @endif
                            @if ($user->facebook_url)
                                <li>
                                    <a href="{{ $user->facebook_url }}" target="_blank" title="Facebook">
                                        <img src="{{ asset('assets/images/social-icons/facebook.png') }}" alt="">
                                    </a>
                                </li>
                            @endif

                            @if ($user->x_url)
                                <li>
                                    <a href="{{ $user->x_url }}" target="_blank" title="X (Formerly Twitter)">
                                        <img src="{{ asset('assets/images/social-icons/twitter.png') }}" alt="">
                                    </a>
                                </li>
                            @endif

                            @if ($user->instagram_url)
                                <li>
                                    <a href="{{ $user->instagram_url }}" target="_blank" title="Instagram">
                                        <img src="{{ asset('assets/images/social-icons/instagram.png') }}" alt="">
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
                                        <img src="{{ asset('assets/images/social-icons/youtube.png') }}" alt="">
                                    </a>
                                </li>
                            @endif
                        </ul>               
                    </div> -->             
                    
                                        <div class="offeredHeadingMain">

                                            <span class="offeredProductSubHeading">MuslimLynk</span>
                                            <h2 class="mb-3 offeredProductMainHeading">Offered <span>Products</span></h2>
                                        </div>
                                        <div class="swiper">
                                            <div class="swiper-wrapper">
                                                @forelse($user->products as $product)
                                                    <div class="swiper-slide">
                                                        <div class="card">
                                                            <img src="{{ $product->product_image ? asset('storage/' . $product->product_image) : asset('https://placehold.co/420x250') }}"
                                                                alt="{{ $product->title }}">

                                                            <div class="card-content">
                                                                <div class="service_price_duration my-0 event_price_label">
                                                                    <p class="service_price">
                                                                        <span>
                                                                            @if ($product->original_price)
                                                                                <s>${{ number_format($product->original_price, 2) }}</s>
                                                                            @endif
                                                                            ${{ number_format($product->discounted_price ?? $product->original_price, 2) }}
                                                                            / {{ $product->unit_of_quantity ?? '' }}
                                                                        </span>
                                                                    </p>
                                                                </div>

                                                                <!-- Product Title -->
                                                                <div class="details">
                                                                    <h3>{{ $product->title }}</h3>
                                                                    <p>{{ Str::limit($product->short_description, 100, '...') }}
                                                                    </p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-center text-muted">No products available.</p>
                                                @endforelse

                                            </div>

                                            <!-- Add Pagination and Navigation -->
                                            <div class="swiper-button-prev"></div>
                                            <div class="swiper-button-next"></div>
                                        </div>


                                    </div>

                                </div>
                                <div class="services_profile_border">
                                    <div class="container">
                                         <div class="offeredHeadingMain">

                                            <span class="offeredProductSubHeading">MuslimLynk</span>
                                            <h2 class="mb-3 offeredProductMainHeading">Offered <span>Service</span></h2>
                                        </div>
                                        <!-- <h2 class="mb-3 service_heading">Services</h2> -->
                                        <div
                                            class="services_slider services_profile_slider articles overflow-hidden pb-0 pt-0">
                                            <div class="swiper-wrapper">
                                                @forelse($user->services as $service)
                                                    <div class="swiper-slide">
                                                        <div class="card">
                                                            <img src="{{ $service->service_image ? asset('storage/' . $service->service_image) : asset('https://placehold.co/420x250') }}"
                                                                alt="{{ $service->title }}">

                                                            <div class="card-body">
                                                                <h3 class="">{{ $service->title }}</h3>
                                                                <p>{{ Str::limit($service->short_description, 100, '...') }}
                                                                </p>
                                                                <div class="service_price_duration">
                                                                    <div class="service_price">
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
                                                            </div>
                                                        </div>
                                                    </div>
                                                @empty
                                                    <p class="text-center text-muted">No services available.</p>
                                                @endforelse
                                            </div>

                                            <div class="swiper-button-prev"></div>
                                            <div class="swiper-button-next"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


            </div>

        </div>
    </section>

    <!-- Modal -->
    <div class="modal fade" id="moreDetailsModal" tabindex="-1" aria-labelledby="moreDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="moreDetailsModalLabel">More Details</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    @if (!empty($user->gender))
                        <div class="contact_info_flex">
                            <i class="fa-solid fa-venus-mars"></i>
                            <div class="contact_name_info">
                                <label for="gender" class="contact_heading">Gender</label>
                                <p>{{ $user->gender }}</p>
                            </div>
                        </div>
                    @endif

                    @if (!empty($user->age_group))
                        <div class="contact_info_flex">
                            <i class="fa-regular fa-calendar-days"></i>
                            <div class="contact_name_info">
                                <label for="age_group" class="contact_heading">Age Group</label>
                                <p>{{ $user->age_group }}</p>
                            </div>
                        </div>
                    @endif

                    @if (!empty($user->ethnicity))
                        <div class="contact_info_flex">
                            <i class="fa-solid fa-users-between-lines"></i>
                            <div class="contact_name_info">
                                <label for="ethnicity" class="contact_heading">Ethnicity</label>
                                <p>{{ $user->ethnicity }}</p>
                            </div>
                        </div>
                    @endif

                    @if (!empty($user->nationality))
                        <div class="contact_info_flex">
                            <i class="fa-solid fa-passport"></i>
                            <div class="contact_name_info">
                                <label for="nationality" class="contact_heading">Nationality</label>
                                <p>{{ $user->nationality }}</p>
                            </div>
                        </div>
                    @endif

                    @if (!empty($user->languages))
                        <div class="contact_info_flex">
                            <i class="fa-solid fa-language"></i>
                            <div class="contact_name_info">
                                <label for="languages" class="contact_heading">Languages</label>
                                <p>{{ $user->languages }}</p>
                            </div>
                        </div>
                    @endif

                    @if (!empty($user->marital_status))
                        <div class="contact_info_flex">
                            <i class="fa-solid fa-person-circle-question"></i>
                            <div class="contact_name_info">
                                <label for="marital_status" class="contact_heading">Marital Status</label>
                                <p>{{ $user->marital_status }}</p>
                            </div>
                        </div>
                    @endif

                </div>

            </div>
        </div>
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
                        <input type="hidden" name="receiver_id" id="receiver_id" value="{{ $user->id }}">
                        <!-- Receiver ID will be set dynamically -->

                        <div class="mb-3">
                            <label for="messageContent" class="form-label">Your Message</label>
                            <textarea class="form-control" id="messageContent" name="content" rows="4" required>Hi {{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}, 
I came across your profile and was really impressed by your work. I’d love to connect and exchange ideas.

Looking forward to connecting! 

Best Regards,
{{ Auth::user()->first_name }} {{ Auth::user()->last_name }}</textarea>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 ">Send Message</button>
                    </form>
                    <div id="messageStatus" class="mt-3 text-center"></div> <!-- Status Message -->
                </div>
            </div>
        </div>
    </div>
@endsection


@section('scripts')
    <script>
        jQuery(document).ready(function($) {
            // Check if conversation exists before opening modal
            $('.direct-message-btn').on('click', function() {
                const receiverId = $(this).data('receiver-id');

                // Check if conversation exists
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
                            $('#receiver_id').val(receiverId);
                            $('#mainModal').modal('show');
                        }
                    },
                    error: function(xhr) {
                        console.error('Error checking conversation:', xhr);
                    }
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

        function toggleSidebar() {
            const sidebar = document.getElementById("sidebar");
            if (sidebar) {
                sidebar.classList.toggle("open");
            } else {
                console.error("Sidebar element not found!");
            }
        }

        let filters = {};
    </script>
@endsection
