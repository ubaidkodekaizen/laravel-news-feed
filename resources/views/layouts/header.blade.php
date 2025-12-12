<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muslim Lynk | Empowering Connections, Amplifying Impact</title>
    <meta name="description" content="Join Muslim Lynk to connect, collaborate, and grow. A dynamic network for Muslim professionals and entrepreneurs, driving success and community impact.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="Muslim Lynk – Where Connections Create Impact">
    <meta property="og:description" content="Discover opportunities, build powerful networks, and strengthen our community’s economic future. Join the movement and let’s grow together!">
    <meta property="og:url" content="{{url('/')}}">
    <meta property="og:image" content="{{ asset('assets/images/logo_bg.png') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    {{-- <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Muslim Lynk – Where Connections Create Impact">
    <meta name="twitter:description" content="Discover opportunities, build powerful networks, and strengthen our community’s economic future. Join the movement and let’s grow together!">
    <meta name="twitter:image" content="{{ asset('assets/images/logo_bg.png') }}">
    <meta name="twitter:site" content="@yourtwitterhandle"> --}}

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">


    <link rel="icon" href="{{ asset('assets/images/logo_bg.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">
    @yield('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/style.css?v2') }}">
    <link rel="stylesheet" href="{{ asset('build/assets/App-C02OVGiM.css') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="{{ asset('build/assets/App-Dn0-_m-T.js') }}" defer></script>
    <style>
        .suggestion-box {
            position: absolute;
            background-color: var(--white);
            border: 1px solid var(--primary);
            width: 100%;
            max-height: 200px;
            overflow-y: auto;
            z-index: 100;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            margin-top: 5px;
            display: none;
            border-radius: 7px;
            color: var(--black);
        }

        .suggestion-item {
            padding: 8px;
            cursor: pointer;
        }

        input#header_search {
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
            color: #273572;
        }

        input#header_search::placeholder {
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            font-weight: 400;
            font-size: 16px;
            color: #273572;
        }

        .custom-tooltip {
            position: absolute;
            background: linear-gradient(180deg, #0e1948, #213bae);
            color: #fff;
            padding: 10px 15px;
            border-radius: 6px;
            font-size: 14px;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            font-weight: 400;
            pointer-events: none;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
            z-index: 99999999;
            white-space: nowrap;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        }

        .custom-tooltip.show {
            opacity: 1;
            visibility: visible;
        }

        .custom-tooltip::before {
            content: '';
            position: absolute;
            bottom: 100%;
            left: 50%;
            transform: translateX(-50%);
            border: 7px solid transparent;
            border-bottom-color: #0e1948;
        }
    </style>


</head>

<body>

    <div class="header position-relative">
        <div class="container-fluid">
            <div class="header_flex">
                <!-- Mobile Toggle Button -->
                <div class="mobile_toggle">
                    <i class="fas fa-bars" id="toggleDrawerBtn"></i>
                </div>
                <div class="header_left">
                    <div class="logo">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('assets/images/greenAndWhiteLogo.png') }}" alt="" class="img-fluid">
                        </a>
                    </div>

                    <div class="header-mid mobile_hide">
                        <form method="GET" action="{{ route('search') }}" class="mb-0" id="search_form">
                            <div class="search_area">


                                {!! \App\Helpers\DropDownHelper::countryDropdown() !!}

                                <div class="suggestion_search">
                                    <input type="text" id="header_search" autocomplete="off"
                                        placeholder="Product, Service or Industry" class="form-control">
                                    <div id="suggestion_box" class="suggestion-box" style="display: none;"></div>
                                </div>

                                <input type="hidden" name="name" id="first_name1">
                                <input type="hidden" name="product" id="product1">
                                <input type="hidden" name="service" id="service1">
                                <input type="hidden" name="company_industry" id="company_industry1">

                                <button class="btn btn-primary search_btn">
                                    <img src="{{asset('assets/images/fe_search.svg')}}" alt="Search">
                                </button>
                            </div>
                        </form>
                    </div>


                </div>

                <div class="header_right">
                    <div class="top_header_links mobile_hide">
                        <ul>
                            <li>
                                <a href="{{ route('our.community') }}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Our Community">
                                    <img src="{{asset('assets/images/Vector.svg')}}" alt="community">
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('smart.suggestion') }}" class="btn btn-primary" data-toggle="tooltip" data-placement="bottom" title="Smart Suggestions">

                                    <img src="{{asset('assets/images/suggestion.svg')}}" alt="Suggestions">
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="profile">
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
                            alt="">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="profile_name_dd dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="user_profile_name_h"> {{ Auth::user()->first_name }} </span>
                                <img id="userProfileDropdown" src="{{asset('assets/images/whiteChevron.svg')}}" alt="DropDown">
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li><a class="dropdown-item logoutBtn" href="{{ route('logout') }}">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Side Drawer -->
        <div class="side_drawer" id="sideDrawer">
            <div class="drawer_header">
                <i class="fas fa-times" id="closeDrawerBtn"></i>
            </div>
            <div class="drawer_content">
                <form method="GET" action="{{ route('search') }}" id="mobile_search_form">
                    <div class="search_area flex-column">
                        <div class="suggestion_search w-100">
                            <input type="text" id="mobile_header_search" autocomplete="off"
                                placeholder="Product, Service or Industry" class="form-control">
                        </div>

                        {!! \App\Helpers\DropDownHelper::countryDropdown() !!}

                        <input type="hidden" name="name" id="mobile_first_name1">
                        <input type="hidden" name="product" id="mobile_product1">
                        <input type="hidden" name="service" id="mobile_service1">
                        <input type="hidden" name="company_industry" id="mobile_company_industry1">

                        <button class="btn btn-primary search_btn mt-2">Search</button>
                    </div>
                </form>

                <a href="{{ route('our.community') }}" class="btn btn-primary mt-4 w-100">Our Community</a>
                <a href="{{ route('smart.suggestion') }}" class="btn btn-primary mt-2 w-100">Smart Suggestion</a>
                <!-- Add more mobile links here -->
                <!-- <ul class="drawer_links mt-3">
                <li><a href="{{ route('our.community') }}">Our Community</a></li>
                <li><a href="#">Link Two</a></li>
            </ul> -->
            </div>
        </div>
    </div>




    <script>
        // ===== FRESH TOOLTIP JS - No Bugs =====
        document.addEventListener('DOMContentLoaded', function() {
            const triggers = document.querySelectorAll('[data-toggle="tooltip"]');
            let activeTooltip = null;

            triggers.forEach(function(trigger) {
                trigger.addEventListener('mouseenter', function() {
                    const text = trigger.getAttribute('title') || trigger.getAttribute('data-original-title');

                    if (!text) return;

                    if (!trigger.getAttribute('data-original-title')) {
                        trigger.setAttribute('data-original-title', text);
                        trigger.removeAttribute('title');
                    }

                    if (activeTooltip) {
                        activeTooltip.remove();
                    }

                    activeTooltip = document.createElement('div');
                    activeTooltip.className = 'custom-tooltip';
                    activeTooltip.textContent = text;
                    document.body.appendChild(activeTooltip);

                    const triggerRect = trigger.getBoundingClientRect();
                    const tooltipRect = activeTooltip.getBoundingClientRect();

                    const left = triggerRect.left + (triggerRect.width / 2) - (tooltipRect.width / 2) + window.scrollX;
                    const top = triggerRect.bottom + 10 + window.scrollY;

                    activeTooltip.style.left = left + 'px';
                    activeTooltip.style.top = top + 'px';

                    setTimeout(function() {
                        if (activeTooltip) {
                            activeTooltip.classList.add('show');
                        }
                    }, 10);
                });

                trigger.addEventListener('mouseleave', function() {
                    if (activeTooltip) {
                        activeTooltip.classList.remove('show');
                        const tooltipToRemove = activeTooltip;
                        setTimeout(function() {
                            tooltipToRemove.remove();
                        }, 300);
                        activeTooltip = null;
                    }
                });
            });
        });
    </script>

</body>

</html>