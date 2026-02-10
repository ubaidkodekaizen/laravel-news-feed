<header class="admin-header">
    <div class="admin-logo">
        <i class="fas fa-shield-alt"></i>
        <span>Admin Panel</span>
    </div>
    
    <div class="user-menu">
        @auth
        @php
            $user = Auth::user();
            $photo = $user->photo;
            $initials = strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1));
        @endphp
        
        @if($photo)
            <img src="{{ getImageUrl($photo) }}" alt="{{ $user->first_name }}" class="user-avatar">
        @else
            <div class="avatar-initials">{{ $initials }}</div>
        @endif
        
        <div class="dropdown">
            <button class="btn btn-link text-white text-decoration-none dropdown-toggle p-0" type="button" data-bs-toggle="dropdown">
                {{ $user->first_name }} {{ $user->last_name }}
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="{{ route('admin.dashboard') }}"><i class="fas fa-tachometer-alt me-2"></i> Dashboard</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item text-danger" href="{{ route('admin.logout') }}"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
            </ul>
        </div>
        @endauth
    </div>
</header>
