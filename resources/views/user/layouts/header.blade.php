<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muslim Linker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link rel="stylesheet" href="{{asset('assets/css/style.css')}}">
</head>
<body>
    
    <div class="header">
        <div class="container-fluid">
            <div class="header_flex">
                <div class="header_left">
                    <div class="logo">
                        MUSLIM LINK
                    </div>
                    <div class="left-nav">
                        <ul class="nav">
                            {{-- <li><a href="javascript:void(0);">Dashboard</a></li> --}}
                            <li><a href="{{route('search')}}">Search</a></li>
                        </ul>
                    </div>
                </div>
                <div class="header_right">
                    <div class="profile">
                        <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}" alt="">
                        <div class="dropdown">
                            <a href="javascript:void(0);" class="profile_name_dd dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                {{Auth::user()->first_name}}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="{{route('user.details.show')}}">User Profile</a></li>
                                <li><a class="dropdown-item" href="{{route('user.company.details')}}">User Company</a></li>
                                <li><a class="dropdown-item" href="{{route('logout')}}">Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>