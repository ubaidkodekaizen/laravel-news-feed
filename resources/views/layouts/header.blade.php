<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MuslimLynk | Empowering Connections, Amplifying Impact</title>
    <meta name="description"
        content="Join MuslimLynk to connect, collaborate, and grow. A dynamic network for Muslim professionals and entrepreneurs, driving success and community impact.">
    <meta property="og:type" content="website">
    <meta property="og:title" content="MuslimLynk – Where Connections Create Impact">
    <meta property="og:description"
        content="Discover opportunities, build powerful networks, and strengthen our community’s economic future. Join the movement and let’s grow together!">
    <meta property="og:url" content="{{ url('/') }}">
    <meta property="og:image" content="{{ asset('assets/images/logo_bg.png') }}">
    <meta property="og:site_name" content="{{ config('app.name') }}">
    {{-- <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="MuslimLynk – Where Connections Create Impact">
    <meta name="twitter:description" content="Discover opportunities, build powerful networks, and strengthen our community’s economic future. Join the movement and let’s grow together!">
    <meta name="twitter:image" content="{{ asset('assets/images/logo_bg.png') }}">
    <meta name="twitter:site" content="@yourtwitterhandle"> --}}

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link
        href="https://fonts.googleapis.com/css2?
  family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&
  family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;
  1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&
  family=Plus+Jakarta+Sans:ital,wght@0,200..800;1,200..800&
  display=swap"
        rel="stylesheet">


    <link rel="icon" href="{{ asset('assets/images/logo_bg.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">


    <link rel="stylesheet" href="{{ asset('assets/css/style.css?v2') }}">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- Load built assets directly --}}
    @php
        $manifestPath = public_path('build/manifest.json');

        $appCss = $chatCss = $appJs = null;

        if (file_exists($manifestPath)) {
            $manifest = json_decode(file_get_contents($manifestPath), true);

            $appCss = $manifest['resources/css/app.css']['file'] ?? null;
            $chatCss = $manifest['resources/css/chat.css']['file'] ?? null;
            $appJs = $manifest['resources/js/App.jsx']['file'] ?? null;
        }
    @endphp

    @if ($appCss)
        <link rel="stylesheet" href="{{ asset('build/' . $appCss) }}">
    @endif

    @if (!request()->routeIs('inbox') && $chatCss)
        <link rel="stylesheet" href="{{ asset('build/' . $chatCss) }}">
    @endif

    @if ($appJs)
        <script type="module" src="{{ asset('build/' . $appJs) }}"></script>
    @endif

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
            border-radius: 10px 0 0 10px;
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

        /* Notification Dropdown Styles */
        .notification-dropdown {
            padding: 0;
        }

        .notification-item {
            padding: 10px 15px;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            transition: background-color 0.2s;
            position: relative;
        }

        .notification-item:hover {
            background-color: #f8f9fa;
        }


        .notification-item.unread {
            background-color: #b8c035;
        }

        .notification-item.unread:hover {
            background-color: #b9c03586;
        }

        .notification-item:last-child {
            border-bottom: none;
        }

        .notification-title {
            font-weight: 600;
            font-size: 14px;
            color: #273572;
            margin-bottom: 0;
            font-family: "Inter", sans-serif;
        }

        .notification-message {
            font-size: 13px;
            font-family: "Inter", sans-serif;
            font-weight: 400;
            color: #333333;
            margin-bottom: 0;
            line-height: 1.4;
        }

        .notification-time {
            font-size: 11px;
            font-family: "Inter", sans-serif;
            font-weight: 400;
            color: #333;
            position: absolute;
            bottom: 10px;
            right: 10px;
        }

        .notification-empty {
            text-align: center;
            padding: 30px 15px;
            color: #6c757d;
            font-size: 16px;
            font-weight: 400;
            font-family: 'Inter', sans-serif;
        }

        #notificationBadge {
            font-size: 10px;
            padding: 2px 6px;
        }

        /* Ensure dropdown is hidden by default */
        .notification-dropdown {
            display: none !important;
        }

        .notification-dropdown.show {
            display: block !important;
            padding: 0;
            overflow: hidden;
        }

        /* View All Notifications button styling - make it always visible */
        #viewAllNotifications {
            background-color: #ffffff !important;
            border-top: 0;
            font-weight: 500 !important;
            color: #333 !important;
            text-decoration: none !important;
            display: block !important;
            opacity: 1 !important;
            visibility: visible !important;
            width: 100%;
            border-radius: 0;
            margin: 0;
            font-size: 16px;
            font-family: "Inter", sans-serif;
            height: 36px;
            border: none;
        }

        #viewAllNotifications:hover {
            background-color: #b8c035 !important;
            color: #273572 !important;
        }

        /* All Notifications Modal Styles */
        .all-notifications-list {
            max-height: 500px;
            overflow-y: auto;
        }

        .notification-item-modal {
            padding: 15px;
            border-bottom: 1px solid #e9ecef;
            cursor: pointer;
            position: relative;
            border-radius: 10px;
            transition: background-color 0.2s;
        }

        .notification-item-modal:hover {
            background-color: #f8f9fa;
        }

        .notification-item-modal.unread {
            background: #b8c035;
            border-left: 3px solid #273572;
        }

        .notification-item-modal.unread:hover {
            background-color: #b9c03586;
        }

        .notification-item-modal .badge {
            background-color: #273572 !important;
        }

        .notification-item-modal:last-child {
            border-bottom: none;
        }

        /* Ensure modals are centered and above everything */
        #allNotificationsModal,
        #notificationModal {
            z-index: 9999 !important;
        }

        #allNotificationsModal .modal-dialog,
        #notificationModal .modal-dialog {
            margin: 1.75rem auto !important;
        }

        #allNotificationsModal .modal-content {
            border-radius: 28.54px;
        }

        #allNotificationsModal .modal-header {
            padding: 25px 26px 19px 26px;
        }

        #allNotificationsModal .modal-title {
            font-family: "Inter", sans-serif;
            font-weight: 600;
            font-size: 18px;
            line-height: 100%;
            color: #333333;
            display: flex;
            align-items: center;
            justify-content: start;
        }


        #allNotificationsModal .modal-header .btn-close {
            position: relative;
            z-index: 2;
            background: #b8b9a53b;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #2d3b68;
            opacity: 1;
            cursor: pointer;
            transition: 0.3s;
        }

        #allNotificationsModal .modal-footer {
            border-top: 1px solid #e5e7eb;
            padding: 20px 24px;
            border-radius: 0 0 12px 12px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 10px;
            background: #d9d9d912;
        }

        #allNotificationsModal .modal-footer button {
            border: 0.75px solid #2d3b68;
            font-family: "Inter", sans-serif;
            font-weight: 600;
            font-size: 12.06px;
            line-height: 100%;
            color: #2d3b68;
            padding: 15px 35px;
            border-radius: 24px;
            background: transparent;
            margin: 0;
        }

        .notification-item-modal .notification-time {
            bottom: 10px;
            right: 15px;
        }

        /* Fix modal backdrop z-index */
        /* .modal-backdrop {
            z-index: 9998 !important;
        } */
    </style>

    @yield('styles')
</head>

<body>

    <div class="header position-relative">

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


                            {{-- {!! \App\Helpers\DropDownHelper::countryDropdown() !!} --}}

                            <div class="suggestion_search">
                                <input type="text" id="header_search" autocomplete="off"
                                    placeholder="Search People, Product, Service or Industry" class="form-control">
                                <div id="suggestion_box" class="suggestion-box" style="display: none;"></div>
                            </div>

                            <input type="hidden" name="name" id="first_name1">
                            <input type="hidden" name="product" id="product1">
                            <input type="hidden" name="service" id="service1">
                            <input type="hidden" name="company_industry" id="company_industry1">

                            <button type="submit" class="btn btn-primary search_btn">
                                <img src="{{ asset('assets/images/fe_search.svg') }}" alt="Search">
                            </button>
                        </div>
                    </form>
                </div>


            </div>

            <div class="header_right">
                <div class="top_header_links mobile_hide">
                    <ul>
                        <li>
                            <a href="{{ route('news-feed') }}" class="btn btn-primary" data-toggle="tooltip"
                                data-placement="bottom" title="News Feed">

                                <i class="fa-solid fa-newspaper"></i>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('smart.suggestion') }}" class="btn btn-primary" data-toggle="tooltip"
                                data-placement="bottom" title="Smart Suggestions">

                                <img src="{{ asset('assets/images/suggestion.svg') }}" alt="Suggestions">
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('our.community') }}" class="btn btn-primary" data-toggle="tooltip"
                                data-placement="bottom" title="Our Community">
                                <img src="{{ asset('assets/images/Vector.svg') }}" alt="community">
                            </a>
                        </li>

                        <li>
                            <a href="{{ route('inbox') }}" class="btn btn-primary position-relative"
                                data-toggle="tooltip" data-placement="bottom" title="Inbox">
                                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25"
                                    viewBox="0 0 24 24" style="fill: rgba(255, 255, 255, 1);transform: ;msFilter:;">
                                    <circle cx="9.5" cy="9.5" r="1.5"></circle>
                                    <circle cx="14.5" cy="9.5" r="1.5"></circle>
                                    <path
                                        d="M12 2C6.486 2 2 5.589 2 10c0 2.908 1.897 5.515 5 6.934V22l5.34-4.004C17.697 17.852 22 14.32 22 10c0-4.411-4.486-8-10-8zm0 14h-.333L9 18v-2.417l-.641-.247C5.671 14.301 4 12.256 4 10c0-3.309 3.589-6 8-6s8 2.691 8 6-3.589 6-8 6z">
                                    </path>
                                </svg>
                                <div id="inbox-unread-badge"></div>
                            </a>
                        </li>

                        <li>
                            <div class="dropdown position-relative">
                                <button class="btn btn-primary position-relative" type="button"
                                    id="notificationDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false"
                                    data-toggle="tooltip" data-placement="bottom" title="Notifications">
                                    <i class="fas fa-bell"></i>
                                    <span
                                        class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                        id="notificationBadge" style="display: none;">
                                        0
                                    </span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end notification-dropdown"
                                    id="notificationDropdown" style="">
                                    <li>
                                        <h6 class="dropdown-header">
                                            <span>Notifications</span>
                                            <button class="btn btn-sm btn-link text-decoration-none p-0"
                                                id="markAllReadBtn" style="font-size: 12px;">Mark all read</button>
                                        </h6>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <div id="notificationList">
                                            <div class="text-center py-3">
                                                <div class="spinner-border spinner-border-sm text-primary"
                                                    role="status">
                                                    <span class="visually-hidden">Loading...</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li>
                                        <a class="dropdown-item text-center fw-bold" href="#"
                                            id="viewAllNotifications">
                                            View All Notifications
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </li>

                    </ul>
                </div>
                <div class="profile">
                    @php
                        $currentUser = Auth::user();
                        $photoPath = $currentUser->photo ?? null;
                        // Check if photo exists - handle both S3 URLs and local storage
                        $hasPhoto = false;
                        if ($photoPath) {
                            if (str_starts_with($photoPath, 'http')) {
                                // S3 URL - assume it exists
                                $hasPhoto = true;
                            } else {
                                // Local storage - check file existence
                                $hasPhoto = \Illuminate\Support\Facades\Storage::disk('public')->exists($photoPath);
                            }
                        }
                        $initials = strtoupper(
                            substr($currentUser->first_name ?? '', 0, 1) . substr($currentUser->last_name ?? '', 0, 1),
                        );
                    @endphp

                    @if ($hasPhoto)
                        <img src="{{ getImageUrl($currentUser->photo) }}" alt="{{ $currentUser->first_name }}">
                    @else
                        <div class="avatar-initials-header">
                            {{ $initials }}
                        </div>
                    @endif
                    <div class="dropdown">
                        <a href="javascript:void(0);" class="profile_name_dd dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="user_profile_name_h"> {{ Auth::user()->first_name }} </span>
                            <img id="userProfileDropdown" src="{{ asset('assets/images/whiteChevron.svg') }}"
                                alt="DropDown">
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                            <li><a class="dropdown-item logoutBtn" href="{{ route('logout') }}">Logout</a></li>
                        </ul>
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
                                placeholder="Search People, Product, Service or Industry" class="form-control">
                        </div>

                        {{-- {!! \App\Helpers\DropDownHelper::countryDropdown() !!} --}}

                        <input type="hidden" name="name" id="mobile_first_name1">
                        <input type="hidden" name="product" id="mobile_product1">
                        <input type="hidden" name="service" id="mobile_service1">
                        <input type="hidden" name="company_industry" id="mobile_company_industry1">

                        <button type="submit" class="btn btn-primary search_btn mt-2">Search</button>
                    </div>
                </form>

                <a href="{{ route('news-feed') }}" class="btn btn-primary mt-2 w-100">News Feed</a>
                <a href="{{ route('smart.suggestion') }}" class="btn btn-primary mt-2 w-100">Smart Suggestion</a>
                <a href="{{ route('our.community') }}" class="btn btn-primary mt-4 w-100">Our Community</a>
                <a href="{{ route('inbox') }}" class="btn btn-primary mt-2 w-100">Inbox</a>



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
                    const text = trigger.getAttribute('title') || trigger.getAttribute(
                        'data-original-title');

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

                    const left = triggerRect.left + (triggerRect.width / 2) - (tooltipRect.width /
                        2) + window.scrollX;
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

    <!-- Notification Details Modal -->
    <div class="modal fade" id="notificationModal" tabindex="-1" aria-labelledby="notificationModalLabel"
        aria-hidden="true" style="z-index: 10000;">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="notificationModalLabel">Notification Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body" id="notificationModalBody">
                    <!-- Notification details will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- All Notifications Modal -->
    <div class="modal fade" id="allNotificationsModal" tabindex="-1" aria-labelledby="allNotificationsModalLabel"
        aria-hidden="true" style="z-index: 9999;">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="allNotificationsModalLabel">All Notifications</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body" id="allNotificationsModalBody">
                    <div class="text-center py-3">
                        <div class="spinner-border spinner-border-sm text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Notification Dropdown Functionality
        (function() {
            let notifications = [];
            let unreadCount = 0;

            // Fetch notifications (for dropdown - latest 5)
            function fetchNotifications() {
                const token = localStorage.getItem('sanctum-token');
                if (!token) {
                    $('#notificationList').html(
                        '<div class="notification-empty">Please login to see notifications</div>');
                    return;
                }

                // Show loading state
                $('#notificationList').html(
                    '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                );

                $.ajax({
                    url: '/notifications?per_page=5&unread_only=false',
                    method: 'GET',
                    headers: {
                        'Authorization': token,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    success: function(response) {
                        console.log('API Success Response:', response);
                        if (response.status && response.notifications) {
                            notifications = response.notifications.data || [];
                            unreadCount = response.unread_count || 0;
                            console.log('Notifications loaded:', notifications.length, 'Unread:',
                                unreadCount);
                            updateNotificationBadge();
                            renderNotifications();
                        } else {
                            console.error('Invalid response format:', response);
                            $('#notificationList').html(
                                '<div class="notification-empty">No notifications found</div>');
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error('API Error:', {
                            status: xhr.status,
                            statusText: xhr.statusText,
                            responseText: xhr.responseText,
                            error: error
                        });
                        if (xhr.status === 401) {
                            $('#notificationList').html(
                                '<div class="notification-empty">Please login to see notifications</div>'
                            );
                        } else {
                            $('#notificationList').html(
                                '<div class="notification-empty">Failed to load notifications. Status: ' +
                                xhr.status + '</div>');
                        }
                    }
                });
            }

            // Fetch all notifications (for View All modal)
            function fetchAllNotifications(page = 1) {
                const token = localStorage.getItem('sanctum-token');
                if (!token) {
                    $('#allNotificationsModalBody').html(
                        '<div class="text-center py-5"><p class="text-muted">Please login to see notifications</p></div>'
                    );
                    return;
                }

                // Show loading
                $('#allNotificationsModalBody').html(
                    '<div class="text-center py-3"><div class="spinner-border spinner-border-sm text-primary" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                );

                $.ajax({
                    url: `/notifications?per_page=20&page=${page}&unread_only=false`,
                    method: 'GET',
                    headers: {
                        'Authorization': token,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.status && response.notifications) {
                            renderAllNotifications(response.notifications);
                        } else {
                            $('#allNotificationsModalBody').html(
                                '<div class="text-center py-5"><p class="text-muted">Failed to load notifications</p></div>'
                            );
                        }
                    },
                    error: function(xhr) {
                        $('#allNotificationsModalBody').html(
                            '<div class="text-center py-5"><p class="text-muted">Failed to load notifications</p></div>'
                        );
                    }
                });
            }

            // Update notification badge
            function updateNotificationBadge() {
                const badge = $('#notificationBadge');
                if (unreadCount > 0) {
                    badge.text(unreadCount > 99 ? '99+' : unreadCount).show();
                } else {
                    badge.hide();
                }
            }

            // Render notifications in dropdown (latest 5)
            function renderNotifications() {
                const list = $('#notificationList');

                if (notifications.length === 0) {
                    list.html('<div class="notification-empty">No notifications yet</div>');
                    return;
                }

                // Show only latest 5 (API already returns 5, but ensure)
                const latestNotifications = notifications.slice(0, 5);

                let html = '';
                latestNotifications.forEach(function(notif) {
                    const isUnread = !notif.read_at;
                    const timeAgo = getTimeAgo(notif.created_at);
                    // Store full notification data as JSON in data attribute (escape for HTML)
                    const notifData = escapeHtml(JSON.stringify(notif));

                    html += `
                        <div class="notification-item ${isUnread ? 'unread' : ''}"
                             data-id="${notif.id}"
                             data-type="${notif.type || ''}"
                             data-notification="${notifData}">
                            <div class="notification-title">${escapeHtml(notif.title || 'Notification')}</div>
                            <div class="notification-message">${escapeHtml(notif.message || '')}</div>
                            <div class="notification-time">${timeAgo}</div>
                        </div>
                    `;
                });

                list.html(html);
            }

            // Render all notifications in modal
            function renderAllNotifications(notificationsData) {
                const modalBody = $('#allNotificationsModalBody');
                const notifications = notificationsData.data || [];
                const currentPage = notificationsData.current_page || 1;
                const lastPage = notificationsData.last_page || 1;

                if (notifications.length === 0) {
                    modalBody.html(
                        '<div class="text-center py-5"><p class="text-muted">No notifications yet</p></div>');
                    return;
                }

                let html = '<div class="all-notifications-list">';

                notifications.forEach(function(notif) {
                    const isUnread = !notif.read_at;
                    const timeAgo = getTimeAgo(notif.created_at);
                    // Store full notification data as JSON in data attribute (escape for HTML)
                    const notifData = escapeHtml(JSON.stringify(notif));

                    html += `
                        <div class="notification-item-modal ${isUnread ? 'unread' : ''}"
                             data-id="${notif.id}"
                             data-type="${notif.type}"
                             data-notification="${notifData}">
                            <div class="d-flex justify-content-between align-items-start">
                                <div class="flex-grow-1">
                                    <div class="notification-title">${escapeHtml(notif.title)}</div>
                                    <div class="notification-message">${escapeHtml(notif.message)}</div>
                                    <div class="notification-time">${timeAgo}</div>
                                </div>
                                ${isUnread ? '<span class="badge bg-primary ms-2">New</span>' : ''}
                            </div>
                        </div>
                    `;
                });

                html += '</div>';

                // Add pagination if needed
                if (lastPage > 1) {
                    html += '<div class="d-flex justify-content-center mt-3">';
                    if (currentPage > 1) {
                        html +=
                            `<button class="btn btn-sm btn-outline-primary me-2" onclick="window.fetchAllNotificationsPage(${currentPage - 1})">Previous</button>`;
                    }
                    html += `<span class="align-self-center me-2">Page ${currentPage} of ${lastPage}</span>`;
                    if (currentPage < lastPage) {
                        html +=
                            `<button class="btn btn-sm btn-outline-primary" onclick="window.fetchAllNotificationsPage(${currentPage + 1})">Next</button>`;
                    }
                    html += '</div>';
                }

                modalBody.html(html);
            }

            // Get time ago
            function getTimeAgo(dateString) {
                const date = new Date(dateString);
                const now = new Date();
                const diffInSeconds = Math.floor((now - date) / 1000);

                if (diffInSeconds < 60) return 'Just now';
                if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + ' minutes ago';
                if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + ' hours ago';
                if (diffInSeconds < 604800) return Math.floor(diffInSeconds / 86400) + ' days ago';
                return date.toLocaleDateString();
            }

            // Escape HTML
            function escapeHtml(text) {
                const map = {
                    '&': '&amp;',
                    '<': '&lt;',
                    '>': '&gt;',
                    '"': '&quot;',
                    "'": '&#039;'
                };
                return text.replace(/[&<>"']/g, m => map[m]);
            }

            // Get redirect URL based on notification type
            function getNotificationRedirectUrl(notification) {
                const data = notification.data || {};

                switch (notification.type) {
                    case 'new_message':
                    case 'message_reaction':
                        if (data.conversation_id) {
                            return `/inbox?conversation=${data.conversation_id}`;
                        }
                        return '/inbox';

                    case 'post_reaction':
                    case 'post_share':
                        if (data.post_slug) {
                            return `/news-feed/posts/${data.post_slug}`;
                        }
                        return '/news-feed';

                    case 'post_comment':
                        if (data.post_slug) {
                            // Redirect to post with comment ID if available
                            let url = `/news-feed/posts/${data.post_slug}`;
                            if (data.comment_id) {
                                url += `#comment-${data.comment_id}`;
                            }
                            return url;
                        }
                        return '/news-feed';

                    case 'comment_reply':
                        if (data.post_slug) {
                            // Redirect to post with parent comment ID if available
                            let url = `/news-feed/posts/${data.post_slug}`;
                            if (data.parent_comment_id) {
                                url += `#comment-${data.parent_comment_id}`;
                            } else if (data.comment_id) {
                                url += `#comment-${data.comment_id}`;
                            }
                            return url;
                        }
                        return '/news-feed';

                    case 'new_service':
                        if (data.service_id) {
                            return `/services#service-${data.service_id}`;
                        }
                        return '/services';

                    case 'new_product':
                        if (data.product_id) {
                            return `/products#product-${data.product_id}`;
                        }
                        return '/products';

                    case 'profile_view':
                        if (data.viewer_id) {
                            return `/user/profile/${data.viewer_slug || data.viewer_id}`;
                        }
                        return '/news-feed';

                    case 'new_follower':
                        if (data.follower_id) {
                            return `/user/profile/${data.follower_slug || data.follower_id}`;
                        }
                        return '/news-feed';

                    default:
                        return '/news-feed';
                }
            }

            // Show notification details in modal (optional - for viewing details before redirect)
            function showNotificationDetails(notification) {
                const modalBody = $('#notificationModalBody');
                const data = notification.data || {};
                const redirectUrl = getNotificationRedirectUrl(notification);

                let content = `
                    <div class="mb-3">
                        <h6 class="text-primary">${escapeHtml(notification.title)}</h6>
                        <p class="mb-2">${escapeHtml(notification.message)}</p>
                        <small class="text-muted">${getTimeAgo(notification.created_at)}</small>
                    </div>
                `;

                // Add action button
                content += `<a href="${redirectUrl}" class="btn btn-primary btn-sm">View Details</a>`;

                modalBody.html(content);
                new bootstrap.Modal(document.getElementById('notificationModal')).show();
            }

            // Mark notification as read
            function markAsRead(notificationId) {
                const token = localStorage.getItem('sanctum-token');
                if (!token) return;

                $.ajax({
                    url: `/notifications/${notificationId}/read`,
                    method: 'POST',
                    headers: {
                        'Authorization': token,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        // Update local state
                        const notif = notifications.find(n => n.id === notificationId);
                        if (notif) {
                            notif.read_at = new Date().toISOString();
                            unreadCount = Math.max(0, unreadCount - 1);
                            updateNotificationBadge();
                            renderNotifications();
                        }
                    }
                });
            }

            // Mark all as read
            function markAllAsRead() {
                const token = localStorage.getItem('sanctum-token');
                if (!token) return;

                $.ajax({
                    url: '/notifications/read-all',
                    method: 'POST',
                    headers: {
                        'Authorization': token,
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function() {
                        // Re-fetch notifications to get updated read status from server
                        fetchNotifications();
                    }
                });
            }

            // Event handlers - Click on notification in dropdown
            $(document).on('click', '.notification-item', function(e) {
                e.preventDefault();
                const $item = $(this);
                const notificationId = $item.data('id');

                // Get notification from stored data or from array
                let notification;
                try {
                    const notifData = $item.attr('data-notification');
                    if (notifData) {
                        notification = JSON.parse(notifData);
                    }
                } catch (e) {
                    // Fallback to finding in array
                    notification = notifications.find(n => n.id === notificationId);
                }

                if (notification) {
                    // Mark as read if unread
                    if (!notification.read_at) {
                        markAsRead(notificationId);
                    }
                    // Close dropdown
                    bootstrap.Dropdown.getInstance(document.getElementById('notificationDropdownBtn'))?.hide();
                    // Redirect to relevant page
                    const redirectUrl = getNotificationRedirectUrl(notification);
                    window.location.href = redirectUrl;
                }
            });

            $('#markAllReadBtn').on('click', function(e) {
                e.stopPropagation();
                markAllAsRead();
            });

            // View All Notifications button
            $('#viewAllNotifications').on('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                // Close dropdown
                bootstrap.Dropdown.getInstance(document.getElementById('notificationDropdownBtn'))?.hide();
                // Open modal and fetch all notifications
                fetchAllNotifications(1);
                new bootstrap.Modal(document.getElementById('allNotificationsModal')).show();
            });

            // Make fetchAllNotificationsPage available globally for pagination
            window.fetchAllNotificationsPage = function(page) {
                fetchAllNotifications(page);
            };

            // Handle notification click in "View All" modal
            $(document).on('click', '.notification-item-modal', function(e) {
                e.preventDefault();
                const $item = $(this);
                const notificationId = $item.data('id');

                // Get notification from stored data
                let notification;
                try {
                    const notifData = $item.attr('data-notification');
                    if (notifData) {
                        notification = JSON.parse(notifData);
                    }
                } catch (e) {
                    console.error('Failed to parse notification data:', e);
                    return;
                }

                if (notification) {
                    // Mark as read if unread
                    if (!notification.read_at) {
                        markAsRead(notificationId);
                    }
                    // Close modal
                    bootstrap.Modal.getInstance(document.getElementById('allNotificationsModal'))?.hide();
                    // Redirect to relevant page
                    const redirectUrl = getNotificationRedirectUrl(notification);
                    window.location.href = redirectUrl;
                }
            });

            // Load notifications when dropdown is opened
            $(document).ready(function() {
                // Click handler
                $('#notificationDropdownBtn').on('click', function(e) {
                    console.log('Bell icon clicked');
                    setTimeout(function() {
                        fetchNotifications();
                    }, 100);
                });

                // Bootstrap dropdown show event
                const dropdownElement = document.getElementById('notificationDropdown');
                const dropdownBtn = document.getElementById('notificationDropdownBtn');

                if (dropdownElement && dropdownBtn) {
                    dropdownElement.addEventListener('show.bs.dropdown', function() {
                        console.log('Dropdown opening event triggered');
                        fetchNotifications();
                    });
                }

                // Also try to fetch on page load if dropdown might be open
                console.log('Notification system initialized');
            });


            // Initial load (only badge count, not full list)
            // Fetch minimal data for badge count
            const token = localStorage.getItem('sanctum-token');
            if (token) {
                $.ajax({
                    url: '/api/notifications?per_page=1&unread_only=true',
                    method: 'GET',
                    headers: {
                        'Authorization': token,
                        'Accept': 'application/json'
                    },
                    success: function(response) {
                        if (response.status) {
                            unreadCount = response.unread_count || 0;
                            updateNotificationBadge();
                        }
                    }
                });
            }

            // Refresh every 30 seconds (only update badge if dropdown is closed)
            setInterval(function() {
                const dropdown = document.getElementById('notificationDropdown');
                if (!dropdown || !dropdown.classList.contains('show')) {
                    // Only fetch to update badge count if dropdown is closed
                    const token = localStorage.getItem('sanctum-token');
                    if (token) {
                        $.ajax({
                            url: '/notifications?per_page=1&unread_only=true',
                            method: 'GET',
                            headers: {
                                'Authorization': token,
                                'Accept': 'application/json'
                            },
                            success: function(response) {
                                if (response.status) {
                                    unreadCount = response.unread_count || 0;
                                    updateNotificationBadge();
                                }
                            }
                        });
                    }
                } else {
                    // If dropdown is open, refresh full list
                    fetchNotifications();
                }
            }, 30000);
        })();
    </script>

</body>

</html>
