<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Muslim Lynk | Empowering Connections, Amplifying Impact</title>
    <meta name="description"
        content="Join Muslim Lynk to connect, collaborate, and grow. A dynamic network for Muslim professionals and entrepreneurs, driving success and community impact.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Muslim Lynk – Where Connections Create Impact">
    <meta property="og:description"
        content="Discover opportunities, build powerful networks, and strengthen our community’s economic future. Join the movement and let’s grow together!">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('assets/images/logo_bg.png') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    <link rel="icon" href="{{ asset('assets/images/logo_bg.png') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
    <style>
        *,
        body {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        .mainNavbar {
            background-color: #b8c034;
            padding: 10px 20px;
        }


        .mainNavbarInner {
            max-width: 1140px;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .mainNavbarList {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0;
            margin: 0;
        }

        .mainNavbarListItem {
            list-style: none;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .userProfilePic {
            width: 50px;
            border-radius: 50%;
            height: 50px;
            object-fit: cover;
        }

        .profile_name_dd {
            text-decoration: none;
            color: #fff;
            margin-left: 10px;
            transition: .3s;
            font-weight: 600;
            font-size: 16px;
        }

        .profile_name_dd:hover {
            color: #233273;
            transition: .3s;
        }

        .mainNavbarLink {
            text-decoration: none;
        }

        .mainNavbar .loginBtn {
            background-color: #263473;
            font-family: "Inter", Sans-serif;
            font-size: 16px;
            font-weight: 500;
            color: #FFFFFF !important;
            border-radius: 10px 10px 10px 10px;
            padding: 15px 35px 15px 35px !important;
        }

        #homeWrapper {
            max-width: 1140px;
            margin: auto;
            padding: 0 20px;
        }

        #homeFirstSec {
            background-color: #b8c034 !important;

            margin: 50px auto;
            border-radius: 40px;
            padding: 40px;
        }

        .homeFirstSecRow {
            display: flex;
            align-items: stretch;
            justify-content: center;
        }

        #homeFirstSec .title {
            font-family: "Inter", Sans-serif;
            font-size: 40px;
            font-weight: 500;
            text-transform: uppercase;
            line-height: 44px;
            color: #233273;
        }

        #homeFirstSec .para {
            color: #FFFFFF;
            font-family: "Inter", Sans-serif;
            font-size: 19px;
            font-weight: 400;
            line-height: 40px;
        }

        #homeSec2 {
            margin: auto;
        }

        .homeSec2Row2 {
            display: flex;
            align-items: stretch;
            justify-content: center;
            gap: 25px;
            margin: auto;
            margin-top: 20px;
            max-width: 97%;
        }

        .homeSec2Head {
            font-family: "Inter", Sans-serif;
            font-size: 40px;
            font-weight: 600;
            color: #FFFFFF;
            background-color: #233273;
            border-radius: 20px;
            padding: 14px 20px;
        }

        .homeFirstSecRow .homeFirstSecCol {}

        .homeSec2Col:last-child {
            max-width: 30%;
        }

        .homeSec2ColInner h4 {
            text-align: left;
            color: #233273;
            font-family: "Inter", Sans-serif;
            font-size: 19px;
            font-weight: 600;
            line-height: 25px;
        }

        .homeSec2ColInner p {
            text-align: left;
            color: #233273;
            font-family: "Inter", Sans-serif;
            font-size: 19px;
            font-weight: 400;
            line-height: 25px;
        }

        #homeSec3 {
            margin: 30px auto;
        }

        #homeSec3 .homeSec3Head {
            font-family: "Inter", Sans-serif;
            font-size: 40px;
            font-weight: 600;
            color: #FFFFFF;
            background-color: #b8c034;
            border-radius: 20px;
            padding: 14px 20px;
            text-align: center;
        }

        #homeSec3 .homeSec3Para {
            text-align: center;
            color: #233273;
            font-family: "Inter", Sans-serif;
            font-size: 30px;
            font-weight: 400;
            line-height: 40px;
            max-width: 95%;
            margin: auto;
        }

        .switch-container {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .switch {
            position: relative;
            display: inline-block;
            width: 100px;
            height: 30px;
        }

        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #2196f3;
            transition: .4s;
            border-radius: 30px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 24px;
            width: 24px;
            left: 3px;
            bottom: 3px;
            background-color: #fff;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked+.slider:before {
            transform: translateX(70px);
        }

        .slider .text {
            position: absolute;
            width: 100%;
            text-align: center;
            font-size: 12px;
            font-weight: 700;
            color: #fff;
            top: 50%;
            transform: translateY(-50%);
            transition: .4s;
        }

        .monthly-text {
            left: 10px;
            opacity: 1;
        }

        .yearly-text {
            right: 10px;
            opacity: 0;
        }

        input:checked+.slider .yearly-text {
            opacity: 1;
        }

        input:checked+.slider .monthly-text {
            opacity: 0;
        }

        .tab-container.active {
            display: flex;
            align-items: stretch;
            justify-content: center;
            gap: 20px;
        }

        .tab-container {
            display: none;
        }



        .tabs {
            display: flex;
            align-items: center;
            justify-content: start;
            flex-direction: column;
            gap: 5px;
            padding: 50px;
            margin: 20px 0;
            border-radius: 15px;
            box-shadow: 2px 2px 20px rgb(0 0 0 / 13%);
            max-width: 45%;
            width: 100%;
        }

        .tabs.active span {
            text-decoration: line-through;
            font-size: 30px;
            font-family: "Inter", Sans-serif;
            font-weight: 600;
            color: #B4BE32;
        }


        .tabs h4 {
            font-size: 40px;
            font-family: "Inter", sans-serif;
            font-weight: 900;
            letter-spacing: 0.02em;
            color: #B4BE32;
            text-shadow: 1px 1px #233273;
        }

        .tabs h2 {
            font-family: "Inter", Sans-serif;
            font-size: 24px;
            font-weight: 900;
            letter-spacing: 0.02em;
            color: #233273;
            text-transform: uppercase;
        }

        .tabs p {
            font-size: 18px;
            font-weight: 500;
            font-family: "Inter", sans-serif;
        }

        .tabs ul {
            display: flex;
            flex-direction: column;
            gap: 10px;
            padding-left: 15px;
        }

        .tabs ul li {
            font-family: 'Inter', sans-serif;
            font-size: 18px;
            font-weight: 500;
            list-style: none;
        }

        .tabs ul li i {
            font-size: 20px;
            color: #B4BE32;
        }

        .homeSec3Pricing {
            margin: 25px 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .homeSec3Pricing .signUpBtn {
            background-color: #233273;
            font-family: "Inter", Sans-serif;
            font-size: 22px;
            font-weight: 500;
            fill: #FFFFFF;
            color: #FFFFFF;
            border-radius: 15px 15px 15px 15px;
            padding: 12px 25px 12px 25px;
            text-decoration: none;
            transition: .3s;
        }

        .homeSec3Pricing .signUpBtn:hover {
            background: #b4be32;
            transition: .3s;
        }

        .homeSec3Pricing .pricingOffer {
            font-family: "Inter", Sans-serif;
            font-size: 16px;
            font-weight: 700;
            color: #233273;
            text-align: left;
            line-height: 1.3em;
            margin: 0;
            margin-top: 20px;
        }

        .stickySec {
            position: sticky;
            top: 10px;
        }

        #readMoreAccordion {
            margin: auto;
            margin-bottom: 20px;
            margin-top: 50px;
        }

        #readMoreAccordion h2 {
            font-family: "Inter", Sans-serif;
            color: #233273;
            font-size: 26px;
            font-weight: 600;
        }

        #readMoreAccordion h3 {
            font-family: "Inter", Sans-serif;
            color: #233273;
            font-size: 20px;
            font-weight: 600;
        }

        #readMoreAccordion p {
            font-family: "Inter", Sans-serif;
            color: #233273;
            font-size: 18px;
            font-weight: 300;
        }

        #readMoreAccordion a {
            color: #2196f3;
        }

        #readMoreAccordion .accordion-button {
            color: #fff;
            background-color: #b4be32;
            box-shadow: none;
            border-radius: 10px !important;
            padding: 20px 20px;
            font-weight: 600;
        }

        #readMoreAccordion .accordion-item {
            border: none;
        }

        #readMoreAccordion .accordion-button::after {
            filter: invert(1) brightness(100);
        }

        #readMoreAccordion .accordion-body {
            border: 1px solid #b4be32;
            border-radius: 10px;
            margin-top: 10px;
        }

        #footer {
            background-color: #2880FE;
        }

        #footer p {
            text-align: center;
            color: #FFFFFF;
            font-family: "Inter", Sans-serif;
            font-size: 18px;
            font-weight: 400;
            margin: 0;
            padding: 20px 0;
        }

        /* Download Banner css */
        .home_banner_sec {
            min-height: 500px;
            width: 100%;
            background-position: center;
            background-size: cover;
            overflow-x: hidden;
            padding: 40px 0;
        }

        .home_banner_sec .banner_container .banner_right_image img {
            width: 100%;
            height: 100%;
            max-width: 100%;
            object-fit: contain;
        }

        .home_banner_sec .banner_container img {
            max-width: 200px;
        }

        .banner_right_image {
            height: 500px;
            width: 100%;
        }

        .home_banner_sec .content h1 {
            color: white;
            font-size: 4rem;
            font-weight: 800;
            text-transform: uppercase;
        }

        .home_banner_sec .content p {
            color: white;
            font-size: 1.5rem;
            text-transform: uppercase;
            font-weight: 600;
        }

        .home_banner_sec .btn_flex {
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .theme_color {
            color: #b4be32;
        }

        .banner_container {
            width: 100%
        }

        @media (min-width: 100%) {
            .banner_container {
                max-width: 100%
            }
        }

        @media (min-width: 767px) {
            .banner_container {
                max-width: 767px
            }
        }

        @media (min-width: 992px) {
            .banner_container {
                max-width: 992px
            }


        }

        @media (min-width: 1200px) {
            .banner_container {
                max-width: 1200px
            }
        }

        @media (min-width: 1440px) {
            .banner_container {
                max-width: 1440px
            }

            .home_banner_sec .content h1 {
                font-size: 5rem;
            }

            .home_banner_sec .content p {
                font-size: 1.75rem;
            }
        }

        .banner_container {
            margin-left: auto;
            margin-right: auto;
            padding-left: .5rem;
            padding-right: .5rem
        }

        @media (min-width: 575.98px) {
            .banner_container {
                padding-left: .5rem;
                padding-right: .5rem
            }
        }

        @media (min-width: 767.98px) {
            .banner_container {
                padding-left: 1.25rem;
                padding-right: 1.25rem
            }
        }

        /* Download Banner css  */

        @media(max-width: 992px) {
            .homeFirstSecRow {
                flex-direction: column-reverse;
            }

            .homeSec2Row2 {
                align-items: center;
                max-width: 100%;
                flex-direction: column-reverse;
            }

            .stickySec {
                position: unset;
                width: 50%;
            }

            .homeFirstSecRow .homeFirstSecCol:last-child {
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .homeSec2Col:last-child {
                max-width: 100%;
                display: flex;
                align-items: center;
                justify-content: center;
            }

            .homeSec2Col:last-child .stickySec {
                width: 100%;
            }

            .tabs {
                padding: 35px;
            }

            .tabs h2 {
                font-size: 20px;
            }

            .tabs h4 {
                font-size: 30px;
            }

            .tabs p {
                font-size: 16px;
            }

            .tabs ul li {
                font-size: 16px;
            }

            .homeSec3Pricing .signUpBtn {
                font-size: 18px;
                border-radius: 10px;
                padding: 8px 20px 8px 20px;
            }
        }

        @media(max-width: 768px) {
            .banner_right_image {
                display: none
            }

            .home_banner_sec .content h1 {
                font-size: 2.25rem;
            }

            .home_banner_sec .content p {
                font-size: 1.25rem;
            }

            .home_banner_sec {
                min-height: fit-content;
                text-align: center;
            }

            .home_banner_sec .banner_container .content {
                padding: 20px 10px;
            }

            .home_banner_sec .btn_flex {
                padding-bottom: 30px;
            }

            #homeFirstSec .title {
                font-size: 24px;
                line-height: 1.3em;
            }

            #homeFirstSec {
                padding: 20px 20px;
            }

            #homeFirstSec .para {
                font-size: 16px;
                line-height: 26px;
            }

            #readMoreAccordion .accordion-button {
                border-radius: 20px !important;
            }

            #readMoreAccordion .accordion-body {
                border-radius: 20px;
            }

            .homeFirstSecRow .homeFirstSecCol {
                text-align: center;
            }

            .homeSec2Head {
                font-size: 24px;
                text-align: center;
            }

            .homeSec2ColInner h4 {
                text-align: center;
                font-size: 18px;
            }

            .homeSec2ColInner p {
                text-align: center;
                font-size: 16px;
            }

            #homeSec3 .homeSec3Head {
                font-size: 24px;
            }

            #homeSec3 .homeSec3Para {
                font-size: 16px;
                line-height: 26px;
            }

            .tabs.active h2 {
                font-size: 24px;
                margin: 0;
            }

            .tabs.active h4 {
                font-size: 18px;
            }

            .tabs.active span {
                font-size: 18px;
            }

            .homeSec3Pricing .signUpBtn {
                font-size: 18px;
                padding: 10px 20px 10px 20px;
            }

            .homeSec3Pricing .pricingOffer {
                font-size: 14px;
            }

            #readMoreAccordion h2 {
                font-size: 20px;
            }

            #readMoreAccordion h3 {
                font-size: 18px;
            }

            #readMoreAccordion p {
                font-size: 16px;
            }

            #footer p {
                font-size: 16px;
            }

            .tab-container.active {
                flex-direction: column;
                align-items: center;
                gap: 0px;
            }

            .tabs {
                width: 100%;
                max-width: 70%;
            }
        }

        @media(max-width: 500px) {
            #footer p {
                font-size: 12px;
            }

            .homeSec2Head {
                font-size: 18px;
                max-width: max-content;
                margin: 0 auto 20px auto;
                padding: 10px 20px;
                border-radius: 12px;
            }

            #homeSec3 .homeSec3Head {
                font-size: 18px;
                max-width: max-content;
                margin: 0 auto 20px auto;
                padding: 10px 20px;
                border-radius: 12px;
            }

            #readMoreAccordion .accordion-button {
                border-radius: 12px !important;
                max-width: max-content;
                padding: 10px 20px;
                margin: auto;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 10px;
            }

            #readMoreAccordion .accordion-body {
                text-align: center;
            }

            .mainNavbar .loginBtn {
                padding: 12px 30px 12px 30px !important;
            }

            .tabs {
                width: 100%;
                max-width: 100%;
                padding: 25px;
            }

            .tabs h4 {
                font-size: 24px;
            }

            .tabs h2 {
                font-size: 18px;
                margin: 0;
            }

            .tabs ul li i {
                font-size: 14px;
                color: #B4BE32;
            }

            .tabs p {
                font-size: 14px;
                line-height: 1.3em;
            }

            .tabs ul li {
                font-size: 14px;
                line-height: 1.3em;
            }

            .homeSec3Pricing .signUpBtn {
                font-size: 16px;
                padding: 8px 15px 8px 15px;
            }
        }
    </style>
</head>

<body>

    <nav class="mainNavbar">
        <div class="mainNavbarInner">
            <a class="mainNavbarBrand" href="{{ route('home') }}">
                <img src="{{ asset('assets/images/logo_bg.png') }}" width="70" class="img-fluid" alt="">
            </a>


            <ul class="mainNavbarList">
                <li class="mainNavbarListItem">
                    @if (Auth::check())
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
                            width="50" class="img-fluid userProfilePic" alt="">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="profile_name_dd dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->first_name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item logoutBtn" href="{{ route('logout') }}">Logout</a></li>
                            </ul>
                        </div>
                    @else
                        <a class="mainNavbarLink loginBtn" href="{{ route('login.form') }}">Login</a>
                    @endif

                </li>
            </ul>

        </div>
    </nav>
    <div id="homeWrapper">
        <div id="homeFirstSec">
            <div class="homeFirstSecRow">
                <div class="homeFirstSecCol">
                    <h4 class="title">Connecting Muslims Worldwide</h4>
                    <p class="para">Muslim Lynk is your gateway to empowerment, collaboration, and success within the
                        Muslim community. Whether you’re an educator, student, entrepreneur, or professional, we are
                        here to
                        unlock a world of opportunity for you. Built on the trusted foundation of AMCOB’s network,
                        Muslim
                        Lynk is more than a platform – it’s a movement to help you thrive in your career, business, and
                        life.</p>
                    <p class="para">Together, we can ensure that <strong>every dollar spent in our community
                            multiplies
                            its impact at least sevenfold before it leaves.</strong> By connecting, collaborating, and
                        supporting one another, we can foster economic empowerment and strengthen our shared values.</p>
                </div>
                <div class="homeFirstSecCol">
                    <img src="{{ asset('assets/images/logo_bg.png') }}" class="img-fluid stickySec" alt="">
                </div>
            </div>
        </div>

        <div id="homeSec2">
            <div class="homeSec2Head">
                The Muslim Lynk Advantage
            </div>
            <div class="homeSec2Row2">
                <div class="homeSec2Col">
                    <div class="homeSec2ColInner">
                        <h4>Connect & Network</h4>
                        <p>Discover and connect with professionals who share your vision, industry, or goals, organized
                            by
                            expertise and location.</p>
                    </div>
                    <div class="homeSec2ColInner">
                        <h4>Direct Messaging</h4>
                        <p>Build meaningful relationships with seamless, direct communication – no barriers, no delays.
                        </p>
                    </div>
                    <div class="homeSec2ColInner">
                        <h4>Smart Suggestions (Coming Soon) </h4>
                        <p>Let our technology guide you to valuable connections, resources, and opportunities tailored
                            to
                            your needs.</p>
                    </div>
                    <div class="homeSec2ColInner">
                        <h4>Marketplace (Coming Soon) </h4>
                        <p>A vibrant space to buy and sell services and products, driving growth and prosperity within
                            the
                            Muslim community.</p>
                    </div>
                    <div class="homeSec2ColInner">
                        <h4>Mobile Access (Coming Soon)</h4>
                        <p>Stay connected anytime, anywhere, with our upcoming mobile app designed for seamless
                            engagement
                            on the go.</p>
                    </div>
                </div>
                <div class="homeSec2Col">
                    <img src="{{ asset('assets/images/muslim-link-dashboard.png') }}" class="img-fluid stickySec"
                        alt="">

                </div>
            </div>
        </div>

        <div id="homeSec3">
            <h2 class="homeSec3Head">
                Join the Movement
            </h2>
            <p class="homeSec3Para">
                Join Muslim Lynk today, and let’s shape a future where the collective strength of our community uplifts
                every individual. Together, we’ll ensure the principle of “a dollar revolves seven times within the
                community before it goes out” becomes a reality. Let’s Lynk and grow!
            </p>
            <div class="homeSec3Pricing">
                <div class="switch-container">
                    <label class="switch">
                        <input type="checkbox" id="toggleSwitch">
                        <span class="slider">
                            <span class="text monthly-text">Monthly</span>
                            <span class="text yearly-text">Yearly</span>
                        </span>
                    </label>
                </div>

                <div id="monthlyTab" class="tab-container active">
                    <div class="tabs">
                        <h2>Basic</h2>
                        <h4>$1.99 / month</h4>
                        <p>Explore the Muslim Lynk App with core features at an affordable price.</p>
                        <ul>
                            <li><i class="fa-solid fa-circle-check"></i> Limited search filters</li>
                            <li><i class="fa-solid fa-circle-check"></i> View full user profiles (without contact
                                details)</li>
                            <li><i class="fa-solid fa-circle-check"></i> Messaging not available</li>
                            <li><i class="fa-solid fa-circle-check"></i> Add your products and services to be
                                discovered by others</li>
                        </ul>

                        <a href="{{ route('register.form') }}" class="signUpBtn">Sign-Up Now!</a>
                        <p class="pricingOffer">Ideal for: New users looking to browse and build their presence.</p>
                    </div>
                    <div class="tabs">
                        <h2>Pro</h2>
                        <h4>$4.99 / month</h4>
                        <p>Access the full power of the Muslim Lynk App and make meaningful connections with ease.</p>
                        <ul>
                            <li><i class="fa-solid fa-circle-check"></i> Access to all advanced filters</li>
                            <li><i class="fa-solid fa-circle-check"></i> View full user profiles, including contact
                                information</li>
                            <li><i class="fa-solid fa-circle-check"></i> In-app messaging to connect directly with
                                other users</li>
                            <li><i class="fa-solid fa-circle-check"></i> Add and promote your products and services
                                within the app</li>
                        </ul>
                        <a href="{{ route('register.form') }}" class="signUpBtn">Sign-Up Now!</a>
                        <p class="pricingOffer">Ideal for: Professionals, business owners, and users ready to grow
                            their network.</p>
                    </div>

                </div>

                <div id="yearlyTab" class="tab-container">
                    <div class="tabs">
                        <h2>Basic</h2>
                        <h4>$19.99 / year</h4>
                        <p>Explore the Muslim Lynk App with core features at an affordable price.</p>
                        <ul>
                            <li><i class="fa-solid fa-circle-check"></i> Limited search filters</li>
                            <li><i class="fa-solid fa-circle-check"></i> View full user profiles (without contact
                                details)</li>
                            <li><i class="fa-solid fa-circle-check"></i> Messaging not available</li>
                            <li><i class="fa-solid fa-circle-check"></i> Add your products and services to be
                                discovered by others</li>
                        </ul>

                        <a href="{{ route('register.form') }}" class="signUpBtn">Sign-Up Now!</a>
                        <p class="pricingOffer">Ideal for: New users looking to browse and build their presence.</p>
                    </div>
                    <div class="tabs">
                        <h2>Pro</h2>
                        <h4>$49.99 / year</h4>
                        <p>Access the full power of the Muslim Lynk App and make meaningful connections with ease.</p>
                        <ul>
                            <li><i class="fa-solid fa-circle-check"></i> Access to all advanced filters</li>
                            <li><i class="fa-solid fa-circle-check"></i> View full user profiles, including contact
                                information</li>
                            <li><i class="fa-solid fa-circle-check"></i> In-app messaging to connect directly with
                                other users</li>
                            <li><i class="fa-solid fa-circle-check"></i> Add and promote your products and services
                                within the app</li>
                        </ul>
                        <a href="{{ route('register.form') }}" class="signUpBtn">Sign-Up Now!</a>
                        <p class="pricingOffer">Ideal for: Professionals, business owners, and users ready to grow
                            their network.</p>
                    </div>

                </div>


            </div>

        </div>
        <div class="accordion" id="readMoreAccordion">
            <div class="accordion-item">
                <h2 class="accordion-header" id="headingOne">
                    <button class="accordion-button" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                        Read More
                    </button>
                </h2>
                <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne"
                    data-bs-parent="#readMoreAccordion">
                    <div class="accordion-body">
                        <h2>Muslim Lynk: Empowering Muslim Professionals through Networking
                            and Leadership</h2>
                        <p>In today’s interconnected world, networking has become a crucial
                            aspect of professional success. Among various platforms,<strong>Muslim Lynk</strong> stands
                            out
                            as a beacon for Muslim professionals, offering a space
                            to connect, collaborate, and grow. This article delves into how <strong>Muslim
                                Lynk</strong> empowers its members, fosters relationships, and
                            contributes to the global professional community.</p>
                        <h3>The Power of Networking for Muslim Professionals</h3>
                        <p><strong>Networking</strong> is the cornerstone of professional growth. It
                            is about building meaningful relationships that can help individuals achieve their career
                            and personal goals. For Muslim professionals, platforms like <strong>Muslim Lynk</strong>
                            provide a culturally sensitive environment to connect with
                            like-minded individuals and expand their reach. By leveraging <strong>business networking
                                groups</strong>, Muslim professionals can gain access to
                            mentorship, job opportunities, and industry insights.</p>
                        <h3>What is Networking? A Business Perspective</h3>
                        <p>Understanding the <strong>definition of networking in
                                business</strong> is essential. Networking in a professional
                            setting involves establishing connections that can lead to mutual benefits. These benefits
                            can range from sharing knowledge and resources to collaborating on projects. For Muslim
                            entrepreneurs and professionals, networking platforms like <a
                                href="{{ route('home') }}"><strong>Muslim Lynk</strong></a> serve as a bridge
                            to
                            <strong>networking groups</strong> that align with their values and ambitions.
                        </p>
                        <h3>The Role of Business Networking Groups in Career
                            Advancement</h3>
                        <p><strong>Business networking groups</strong> play a pivotal role in
                            fostering professional relationships. Groups like <strong>Muslim Lynk</strong> offer more
                            than
                            just a platform; they provide a structured
                            environment where professionals can share their expertise and learn from others. Such
                            platforms encourage the exchange of ideas and resources, ultimately benefiting both
                            individuals and their respective industries.</p>
                        <h3>Muslim Lynk: A Global Leadership Network</h3>
                        <p>As a part of the <strong>global leadership network</strong>, <strong>Muslim Lynk</strong>
                            connects professionals across borders. This network is not confined to a single industry or
                            region; instead, it spans the globe, bringing together leaders, innovators, and
                            entrepreneurs. The aim is to create a <strong>professionals network</strong> that is
                            inclusive,
                            supportive, and growth-oriented.</p>
                        <h3>The Importance of a Strong Professionals Network</h3>
                        <p>A robust <strong>professionals network</strong> is essential for career growth and personal
                            development. Through
                            platforms like <strong>Muslim Lynk</strong>, individuals can
                            access a diverse pool of talent and expertise. This not only helps in expanding one’s
                            horizons but also fosters a sense of community among professionals who share similar values
                            and goals.</p>
                        <h3>Understanding Networking Groups and Their Impact</h3>
                        <p><strong>Networking groups</strong> are communities designed to connect
                            professionals who share common interests or objectives. These groups can be
                            industry-specific or open to professionals from various fields. For Muslim professionals,
                            <strong>Muslim Lynk</strong> offers a unique platform where they
                            can engage with peers, seek mentorship, and explore new opportunities.
                        </p>
                        <h3>Entrepreneurs Organization: Supporting Business Leaders
                        </h3>
                        <p>The <strong>entrepreneurs organization</strong> is another key aspect of professional
                            networking.
                            This type of
                            organization supports business leaders by providing resources, mentorship, and a network of
                            like-minded individuals. <strong>Muslim Lynk</strong>
                            incorporates similar principles, offering a space for Muslim entrepreneurs to thrive and
                            collaborate.</p>
                        <h3>What is a Business Network? Defining the Concept</h3>
                        <p>To <strong>define business network</strong>, it is a group of interconnected individuals or
                            organizations
                            that collaborate to achieve mutual goals. A business network can be formal or informal, and
                            its success relies on the active participation of its members. Platforms like
                            <strong>Muslim Lynk</strong> emphasize the importance of creating
                            an <strong>elevated network</strong> that fosters professional
                            growth and innovation.
                        </p>
                        <h3>Elevated Network: Taking Professional Connections to the Next
                            Level</h3>
                        <p>An <strong>elevated network</strong> goes beyond basic connections. It involves cultivating
                            deep,
                            meaningful relationships that can drive significant impact. <strong>Muslim Lynk</strong>
                            exemplifies this concept by offering tools and resources that
                            enable its members to build impactful relationships and achieve their career
                            aspirations.</p>
                        <h3>How Muslim Lynk Stands Out Among Networking Groups</h3>
                        <p>Among various <strong>networking groups</strong>, <strong>Muslim Lynk</strong> is
                            distinguished by its focus on empowering Muslim professionals. By addressing the unique
                            challenges and opportunities faced by its members, the platform creates an environment where
                            individuals can thrive. Its emphasis on cultural understanding and shared values sets it
                            apart.</p>
                        <h3>Building a Global Community through Networking</h3>
                        <p>In the era of globalization, networking has taken on a new
                            dimension. <strong>Muslim Lynk</strong> is part of the larger
                            </span><strong>global leadership network</strong>, facilitating
                            connections between professionals from diverse backgrounds. This global approach ensures
                            that its members have access to a wealth of opportunities and perspectives.</p>
                        <h3>Professionals Network: A Path to Success</h3>
                        <p>A strong <strong>professionals network</strong> is often the key to success in any career.
                            Through
                            <strong>Muslim Lynk</strong>, members can access a vast network of
                            professionals who are eager to share their knowledge and expertise. This collaborative
                            approach fosters innovation and drives growth.
                        </p>
                        <h3>Networking Group: A Gateway to Opportunities</h3>
                        <p>Joining a <strong>networking group</strong> can be a transformative experience for
                            professionals.
                            Platforms
                            like <strong>Muslim Lynk</strong> provide a structured
                            environment where individuals can meet peers, mentors, and potential collaborators. These
                            connections often lead to new opportunities, from job offers to partnerships.</p>
                        <h3>The Significance of Network Groups in Professional
                            Growth</h3>
                        <p><strong>Network groups</strong> serve as a catalyst for professional
                            development. By participating in such groups, individuals can enhance their skills, gain
                            insights into industry trends, and expand their professional horizons. <strong>Muslim
                                Lynk</strong> offers these benefits and more, making it an
                            invaluable resource for Muslim professionals.</p>
                        <h3>Entrepreneurs Organization: Fostering Innovation and
                            Growth</h3>
                        <p>The <strong>entrepreneurs organization</strong> within <strong>Muslim Lynk</strong> is
                            designed
                            to support business leaders in their journey. By
                            providing access to resources, mentorship, and a vibrant community, the platform helps
                            entrepreneurs overcome challenges and achieve their goals.</p>
                        <h3>Global Leadership Network: Connecting Visionaries</h3>
                        <p>As part of the <strong>global leadership network</strong>, <strong>Muslim Lynk</strong>
                            brings together visionaries from around the world. This network is not just about making
                            connections; it is about creating a community of leaders who can inspire and support one
                            another in their endeavors.</p>
                        <h3>Professionals Network: The Power of Collaboration</h3>
                        <p>The strength of a <strong>professionals network</strong> lies in its ability to bring
                            together
                            diverse talents and
                            perspectives. Through <strong>Muslim Lynk</strong>, members
                            can collaborate on projects, share insights, and build relationships that drive success.
                            This spirit of collaboration is at the heart of the platform.</p>
                        <h3>Networking Groups: A Platform for Growth</h3>
                        <p>Participating in <strong>networking groups</strong> is one of the most effective ways to grow
                            professionally.
                            <strong>Muslim Lynk</strong> offers a platform where members can
                            connect with industry leaders, share their experiences, and gain valuable insights. These
                            interactions often lead to meaningful opportunities and collaborations.
                        </p>
                        <h3>Network Groups: Strength in Numbers</h3>
                        <p>The power of <strong>network groups</strong> lies in their ability to create a supportive
                            community.
                            <strong>Muslim Lynk</strong> leverages this concept to build a
                            network that empowers its members. By fostering connections and encouraging collaboration,
                            the platform helps professionals achieve their goals.
                        </p>
                        <h3>Conclusion: The Future of Networking for Muslim
                            Professionals</h3>
                        <p><strong>Muslim Lynk</strong> is more than just a platform; it is a
                            movement aimed at empowering Muslim professionals worldwide. By providing access to
                            <strong>business networking groups</strong>, a <strong>global
                                leadership network</strong>, and a vibrant
                            <strong>professionals network</strong>, it creates opportunities
                            for growth and success. Whether you are an entrepreneur, a business leader, or a
                            professional looking to expand your horizons, <strong>Muslim Lynk</strong> is your gateway
                            to an
                            <strong>elevated network</strong> of opportunities. Powered By <a
                                href="https://www.amcob.org/">AMCOB</a>
                        </p>

                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="home_banner_sec" style="background-image: url('{{ asset('assets/images/home_banner_bg.png') }}');">
        <div class="banner_container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="content">
                        <h1>Download the Muslim Lynk App</h1>
                        <p><span class="theme_color">Now available</span> on the App Store and Play Store</p>
                    </div>
                    <div class="btn_flex">
                        <a href="https://play.google.com/store/apps/details?id=com.MuslimLynk" target="_blank">
                            <img src="{{ asset('assets/images/home_banner_playstore.png') }}" alt="Playstore"
                                class="img-fluid">
                        </a>
                        <a href="https://apps.apple.com/pk/app/muslimlynk/id6746872077" target="_blank">
                            <img src="{{ asset('assets/images/home_banner_appstore.png') }}" alt="App Store"
                                class="img-fluid">
                        </a>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="banner_right_image">
                        <img src="{{ asset('assets/images/home_banner_right.png') }}" alt="Apps">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="footer">
        <p>© 2025 – Powered By AMCOB LLC. All Rights Reserved.</p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
        integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
        integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('toggleSwitch').addEventListener('change', function() {
                if (this.checked) {
                    document.getElementById('monthlyTab').classList.remove('active');
                    document.getElementById('yearlyTab').classList.add('active');
                } else {
                    document.getElementById('monthlyTab').classList.add('active');
                    document.getElementById('yearlyTab').classList.remove('active');
                }
            });
        });
    </script>
</body>

</html>
