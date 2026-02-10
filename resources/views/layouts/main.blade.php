<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>@yield('title', config('app.name', 'NewsFeed') . ' | Connect, Share, Engage')</title>
    <meta name="description" content="@yield('description', 'A modern social newsfeed platform for meaningful connections.')">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Design System -->
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    
    @yield('styles')
</head>
<body>
    @include('layouts.header')
    
    <main style="flex: 1; padding-top: 0;">
        @yield('content')
    </main>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    
    <script>
        // Auth user data for feed
        @auth
        window.authUserId = {{ Auth::id() }};
        window.authUserAvatar = @json($authUserData['photo'] ?? '');
        window.authUserInitials = @json($authUserData['user_initials'] ?? 'U');
        window.authUserHasPhoto = {{ $authUserData['user_has_photo'] ?? false ? 'true' : 'false' }};
        @endauth
        
        // User data
        @auth
        window.userId = {{ auth()->id() ?? 'null' }};
        window.userFirstName = "{{ auth()->user()->first_name }}";
        window.userLastName = "{{ auth()->user()->last_name }}";
        window.userEmail = "{{ auth()->user()->email }}";
        @php
            $userPhoto = auth()->user()->photo;
            $photoUrl = getImageUrl($userPhoto) ?? '';
        @endphp
        window.userPhoto = "{{ $photoUrl }}";
        window.userSlug = "{{ auth()->user()->slug }}";
        window.userInitials = "{{ strtoupper(substr(auth()->user()->first_name, 0, 1) . substr(auth()->user()->last_name, 0, 1)) }}";
        @endauth
        
        // Logout handler
        document.querySelectorAll('.logoutBtn, a[href="{{ route('logout') }}"]').forEach(btn => {
            btn.addEventListener('click', function() {
                localStorage.setItem("sanctum-token", "");
            });
        });
    </script>
    
    @yield('scripts')
</body>
</html>
