<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muslim Lynk</title>
    <link rel="icon" href="{{ asset('assets/images/logo_bg.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper/swiper-bundle.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.css">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css?v2') }}">

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

        .suggestion_search {
            position: relative;
        }
    </style>

</head>

<body>

    <div class="header">
        <div class="container-fluid">
            <div class="header_flex">
                <div class="header_left">
                    <div class="logo">
                        <a href="{{ route('search') }}">
                            <img src="{{ asset('assets/images/logo_bg.png') }}" alt="" class="img-fluid">
                        </a>
                    </div>
                    <div class="header-mid mobile_hide">
                        <form method="GET" action="{{ route('search') }}" id="search_form">
                            <div class="search_area">
                                <div class="suggestion_search w-50">
                                    <input type="text" id="header_search" autocomplete="off"
                                        placeholder="Product, Service or Industry" class="form-control">
                                    <div id="suggestion_box" class="suggestion-box" style="display: none;">
                                    </div>
                                </div>

                                {!! \App\Helpers\DropDownHelper::countryDropdown() !!}

                                <input type="hidden" name="name" id="first_name1">
                                <input type="hidden" name="product_service_name" id="product_service_name1">
                                <input type="hidden" name="company_industry" id="company_industry1">


                                <button class="btn btn-primary search_btn">Search</button>

                            </div>
                        </form>
                    </div>

                </div>

                <div class="header_right">
                    <div class="profile">
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
                            alt="">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="profile_name_dd dropdown-toggle"
                                data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->first_name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a>
                                </li>
                                {{-- <li><a class="dropdown-item" href="{{route('user.company.details')}}">User Company</a></li> --}}
                                <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
