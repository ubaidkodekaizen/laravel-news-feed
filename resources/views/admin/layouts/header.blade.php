<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="icon" href="{{ asset('assets/images/logo_bg.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css?v1') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/style.css?v1') }}">

<style>
    .header {
        background: linear-gradient(180deg, #0e1948, #213bae);
        color: #fff;
    }

    .header-flex {
        justify-content: space-between;
        padding: 0px 20px;
    }

    img:not(.logo img, .user_company_profile .company_logo img, .user_company_profile .profile_pic img) {
        border: 2px solid var(--primary);
        width: 50px;
        height: 50px;
        border-radius: 50px;
        margin-right: 10px;
    }

    a.d-block.text-light.text-decoration-none.dropdown-toggle {
        font-family: "Inter", sans-serif;
        font-optical-sizing: auto;
        font-style: normal;
        font-weight: 400;
        font-size: 16px;
        color: #ffffff;
    }

    #userProfileDropdown {
        width: 18px;
        height: 12px;
        border: none;
    }

    .sidebar {
        padding: 20px 10px;
        width: 300px;
        top: 110px;
        background-color: #F4F5FB;
        border-right: 1px solid #E9EBF0;
    }

    .sidebar-menu li a{
        padding: 1rem 0.8rem;
        font-size: 1.13rem;
        font-weight: 500;
        font-family: "Inter";
        text-transform: capitalize;
        color: #333;
        border: none;
    }

    .sidebar-menu li a:hover, 
    .sidebar-menu li a.active {
        background-color: #273572;
        color: #fff;
    }

    .sidebar-menu svg{
        color: #333 !important;
        margin-right: 6px;
        margin-top: -4.5px;
    }

    .sidebar-menu li a:hover svg,
    .sidebar-menu li a.active svg,
    .sidebar-menu li a:hover svg path,
    .sidebar-menu li a.active svg path {
        fill: #fff;
        color: #fff;
    }
</style>
</head>

<body>

    <div class="header">
        <div class="container-fluid">
            <div class="header-flex">
                <a href="{{ route('admin.dashboard') }}" class="logo">
                    <img src="{{ asset('assets/images/greenAndWhiteLogo.png') }}" alt="">
                </a>

                <div class="profile_dropdown">
                    <div class="flex-shrink-0 dropdown">
                        <a href="#" class="d-block text-light text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
                                alt="">
                            {{ Auth::user()->first_name }}
                            <img id="userProfileDropdown" src="http://127.0.0.1:8000/assets/images/whiteChevron.svg" alt="DropDown">
                        </a>
                        <ul class="dropdown-menu text-small shadow">
                            {{-- <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li> --}}
                            <!-- <hr class="dropdown-divider"> -->
                            </li>
                            <li><a class="dropdown-item" href="{{ route('admin.logout') }}">Logout</a></li>
                        </ul>
                    </div>
                </div>
                <button class="hamburger" id="hamburger">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>
            </div>
        </div>
    </div>
    <div class="admin-panel">
        <aside class="sidebar">
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg fill="#333"
                            xmlns:dc="http://purl.org/dc/elements/1.1/"
                            xmlns:cc="http://creativecommons.org/ns#"
                            xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
                            xmlns:svg="http://www.w3.org/2000/svg"
                            xmlns="http://www.w3.org/2000/svg"
                            xmlns:sodipodi="http://sodipodi.sourceforge.net/DTD/sodipodi-0.dtd"
                            xmlns:inkscape="http://www.inkscape.org/namespaces/inkscape"
                            width="20"
                            height="20"
                            viewBox="0 0 448 448"
                            id="svg2"
                            version="1.1"
                            inkscape:version="0.91 r13725"
                            sodipodi:docname="dashboard.svg">
                            <title
                                id="title3342">dashboard</title>
                            <defs
                                id="defs4" />
                            <sodipodi:namedview
                                id="base"
                                pagecolor="#ffffff"
                                bordercolor="#666666"
                                borderopacity="1.0"
                                inkscape:pageopacity="0.0"
                                inkscape:pageshadow="2"
                                inkscape:zoom="0.98994949"
                                inkscape:cx="415.72893"
                                inkscape:cy="228.50386"
                                inkscape:document-units="px"
                                inkscape:current-layer="layer1"
                                showgrid="true"
                                fit-margin-top="448"
                                fit-margin-right="384"
                                fit-margin-left="0"
                                fit-margin-bottom="0"
                                units="px"
                                inkscape:window-width="1196"
                                inkscape:window-height="852"
                                inkscape:window-x="1102"
                                inkscape:window-y="413"
                                inkscape:window-maximized="0"
                                inkscape:snap-bbox="true"
                                inkscape:bbox-nodes="true">
                                <inkscape:grid
                                type="xygrid"
                                id="grid3347"
                                spacingx="16"
                                spacingy="16"
                                empspacing="2"
                                originx="0"
                                originy="-1.7498462e-005" />
                            </sodipodi:namedview>
                            <metadata
                                id="metadata7">
                                <rdf:RDF>
                                <cc:Work
                                    rdf:about="">
                                    <dc:format>image/svg+xml</dc:format>
                                    <dc:type
                                    rdf:resource="http://purl.org/dc/dcmitype/StillImage" />
                                    <dc:title>dashboard</dc:title>
                                </cc:Work>
                                </rdf:RDF>
                            </metadata>
                            <g
                                inkscape:label="Layer 1"
                                inkscape:groupmode="layer"
                                id="layer1"
                                transform="translate(0,-604.36224)">
                                <rect
                                style="fill-opacity:1;stroke:none;stroke-opacity:1"
                                id="rect3334"
                                width="192"
                                height="256"
                                x="0"
                                y="604.36224" />
                                <rect
                                y="796.36224"
                                x="256"
                                height="255.99995"
                                width="192"
                                id="rect3336"
                                style="fill-opacity:1;stroke:none;stroke-opacity:1" />
                                <rect
                                y="924.36224"
                                x="0"
                                height="127.99995"
                                width="192"
                                id="rect3338"
                                style="fill-opacity:1;stroke:none;stroke-opacity:1" />
                                <rect
                                style="fill-opacity:1;stroke:none;stroke-opacity:1"
                                id="rect3340"
                                width="192"
                                height="128.00002"
                                x="256"
                                y="604.36224" />
                            </g>
                            </svg>
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users') }}"
                        class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        <svg fill="#333" width="20px" height="20px" viewBox="0 0 24 24" id="user" data-name="Flat Color" xmlns="http://www.w3.org/2000/svg" class="icon flat-color">
                            <title>Users</title>
                            <path id="primary" d="M21,20a2,2,0,0,1-2,2H5a2,2,0,0,1-2-2,6,6,0,0,1,6-6h6A6,6,0,0,1,21,20Zm-9-8A5,5,0,1,0,7,7,5,5,0,0,0,12,12Z" ></path>
                        </svg>
                        Users
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.subscriptions') }}"
                        class="{{ request()->routeIs('admin.subscriptions') ? 'active' : '' }}">
                        <svg width="20px" height="20px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                            <title>Subscriptions</title>
                            <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                <g id="Media" transform="translate(-720.000000, -48.000000)">
                                    <g id="notification_fill" transform="translate(720.000000, 48.000000)">
                                        <path style="fill: transparent;" d="M24,0 L24,24 L0,24 L0,0 L24,0 Z M12.5934901,23.257841 L12.5819402,23.2595131 L12.5108777,23.2950439 L12.4918791,23.2987469 L12.4918791,23.2987469 L12.4767152,23.2950439 L12.4056548,23.2595131 C12.3958229,23.2563662 12.3870493,23.2590235 12.3821421,23.2649074 L12.3780323,23.275831 L12.360941,23.7031097 L12.3658947,23.7234994 L12.3769048,23.7357139 L12.4804777,23.8096931 L12.4953491,23.8136134 L12.4953491,23.8136134 L12.5071152,23.8096931 L12.6106902,23.7357139 L12.6232938,23.7196733 L12.6232938,23.7196733 L12.6266527,23.7031097 L12.609561,23.275831 C12.6075724,23.2657013 12.6010112,23.2592993 12.5934901,23.257841 L12.5934901,23.257841 Z M12.8583906,23.1452862 L12.8445485,23.1473072 L12.6598443,23.2396597 L12.6498822,23.2499052 L12.6498822,23.2499052 L12.6471943,23.2611114 L12.6650943,23.6906389 L12.6699349,23.7034178 L12.6699349,23.7034178 L12.678386,23.7104931 L12.8793402,23.8032389 C12.8914285,23.8068999 12.9022333,23.8029875 12.9078286,23.7952264 L12.9118235,23.7811639 L12.8776777,23.1665331 C12.8752882,23.1545897 12.8674102,23.1470016 12.8583906,23.1452862 L12.8583906,23.1452862 Z M12.1430473,23.1473072 C12.1332178,23.1423925 12.1221763,23.1452606 12.1156365,23.1525954 L12.1099173,23.1665331 L12.0757714,23.7811639 C12.0751323,23.7926639 12.0828099,23.8018602 12.0926481,23.8045676 L12.108256,23.8032389 L12.3092106,23.7104931 L12.3186497,23.7024347 L12.3186497,23.7024347 L12.3225043,23.6906389 L12.340401,23.2611114 L12.337245,23.2485176 L12.337245,23.2485176 L12.3277531,23.2396597 L12.1430473,23.1473072 Z" id="MingCute" fill-rule="nonzero">
                                        </path>
                                        <path d="M15,19 C15,20.0543909 14.18415,20.9181678 13.1492661,20.9945144 L13,21 L11,21 C9.94563773,21 9.08183483,20.18415 9.00548573,19.1492661 L9,19 L15,19 Z M12.0002,2 C15.7856583,2 18.869299,5.0047865 18.996141,8.75935044 L19.0002,9 L19.0002,12.7639 L20.8222,16.4081 C21.1704857,17.1046714 20.7047125,17.9183404 19.9532033,17.9942531 L19.8384,18 L4.16197,18 C3.38318905,18 2.86370061,17.2195011 3.13189688,16.5133571 L3.1781,16.4081 L5.00016,12.7639 L5.00016,9 C5.00016,5.13401 8.13417,2 12.0002,2 Z"  fill="#333">
                                        </path>
                                    </g>
                                </g>
                            </g>
                        </svg>
                        Subscriptions
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.blogs') }}"
                        class="{{ request()->routeIs('admin.blogs') ? 'active' : '' }}">
                        <svg width="20px" height="20px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <title>Blogs</title>
                        <g id="style=fill">
                        <g id="document">
                        <path id="Subtract" fill-rule="evenodd" clip-rule="evenodd" d="M8 1.25C4.82436 1.25 2.25 3.82436 2.25 7V17C2.25 20.1756 4.82436 22.75 8 22.75H16C19.1756 22.75 21.75 20.1756 21.75 17V7C21.75 3.82436 19.1756 1.25 16 1.25H8ZM8 7.44995C7.58579 7.44995 7.25 7.78574 7.25 8.19995C7.25 8.61416 7.58579 8.94995 8 8.94995H16C16.4142 8.94995 16.75 8.61416 16.75 8.19995C16.75 7.78574 16.4142 7.44995 16 7.44995H8ZM7.25 12.2C7.25 11.7857 7.58579 11.45 8 11.45H16C16.4142 11.45 16.75 11.7857 16.75 12.2C16.75 12.6142 16.4142 12.95 16 12.95H8C7.58579 12.95 7.25 12.6142 7.25 12.2ZM9 15.45C8.58579 15.45 8.25 15.7857 8.25 16.2C8.25 16.6142 8.58579 16.95 9 16.95H15C15.4142 16.95 15.75 16.6142 15.75 16.2C15.75 15.7857 15.4142 15.45 15 15.45H9Z" fill="#333"/>
                        </g>
                        </g>
                        </svg>
                        Blogs
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.events') }}"
                        class="{{ request()->routeIs('admin.events') ? 'active' : '' }}">
                        <svg style="margin-left: -5px; margin-right: 0px;" fill="#333" xmlns="http://www.w3.org/2000/svg"  width="30px" height="30px"
                            viewBox="0 0 100 100" xml:space="preserve">
                            <title>Events</title>

                        <g>
                            <g>
                                <path d="M76,42H24c-1.1,0-2,0.9-2,2v30c0,3.3,2.7,6,6,6h44c3.3,0,6-2.7,6-6V44C78,42.9,77.1,42,76,42z M40,70
                                    c0,1.1-0.9,2-2,2h-4c-1.1,0-2-0.9-2-2v-4c0-1.1,0.9-2,2-2h4c1.1,0,2,0.9,2,2V70z M40,56c0,1.1-0.9,2-2,2h-4c-1.1,0-2-0.9-2-2v-4
                                    c0-1.1,0.9-2,2-2h4c1.1,0,2,0.9,2,2V56z M54,70c0,1.1-0.9,2-2,2h-4c-1.1,0-2-0.9-2-2v-4c0-1.1,0.9-2,2-2h4c1.1,0,2,0.9,2,2V70z
                                    M54,56c0,1.1-0.9,2-2,2h-4c-1.1,0-2-0.9-2-2v-4c0-1.1,0.9-2,2-2h4c1.1,0,2,0.9,2,2V56z M68,70c0,1.1-0.9,2-2,2h-4
                                    c-1.1,0-2-0.9-2-2v-4c0-1.1,0.9-2,2-2h4c1.1,0,2,0.9,2,2V70z M68,56c0,1.1-0.9,2-2,2h-4c-1.1,0-2-0.9-2-2v-4c0-1.1,0.9-2,2-2h4
                                    c1.1,0,2,0.9,2,2V56z"/>
                            </g>
                            <g>
                                <path d="M72,26h-5v-2c0-2.2-1.8-4-4-4s-4,1.8-4,4v2H41v-2c0-2.2-1.8-4-4-4s-4,1.8-4,4v2h-5c-3.3,0-6,2.7-6,6v2
                                    c0,1.1,0.9,2,2,2h52c1.1,0,2-0.9,2-2v-2C78,28.7,75.3,26,72,26z"/>
                            </g>
                        </g>
                        </svg>
                        Events
                    </a>
                </li>
                {{-- <li>
                    <a href="{{ route('admin.companies') }}" 
                       class="{{ request()->routeIs('admin.companies') ? 'active' : '' }}">
                       Companies
                    </a>
                </li> --}}
            </ul>

        </aside>
