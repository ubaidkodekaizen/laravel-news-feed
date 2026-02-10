<footer id="footer">
    <div class="footerInner">
        <p>&copy; {{ date('Y') }} {{ config('app.name', 'NewsFeed') }}. All Rights Reserved.</p>
    </div>
</footer>

@yield('scripts')
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
