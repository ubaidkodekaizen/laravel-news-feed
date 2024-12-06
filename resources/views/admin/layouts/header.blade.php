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
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/css/style.css') }}">


</head>

<body>

    <div class="header">
        <div class="container-fluid">
            <div class="header-flex">

                <div class="profile_dropdown">
                    <div class="flex-shrink-0 dropdown">
                        <a href="#" class="d-block text-light text-decoration-none dropdown-toggle"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
                                alt="">
                            {{ Auth::user()->first_name }}
                        </a>
                        <ul class="dropdown-menu text-small shadow">
                            {{-- <li><a class="dropdown-item" href="#">Profile</a></li>
                            <li> --}}
                            <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
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
            <div class="sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="logo">
                    <img src="{{ asset('assets/images/logo_bg.png') }}" alt="">
                </a>

            </div>
            <ul class="sidebar-menu">
                <li>
                    <a href="{{ route('admin.dashboard') }}"
                        class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        Dashboard
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.users') }}"
                        class="{{ request()->routeIs('admin.users') ? 'active' : '' }}">
                        Users
                    </a>
                </li>
                <li>
                    <a href="{{ route('admin.subscriptions') }}"
                        class="{{ request()->routeIs('admin.subscriptions') ? 'active' : '' }}">
                        Subscriptions
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
