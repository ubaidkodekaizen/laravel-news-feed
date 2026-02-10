<header style="background: var(--color-bg-primary); border-bottom: 1px solid var(--color-border); box-shadow: var(--shadow-sm); position: sticky; top: 0; z-index: var(--z-sticky);">
    <div class="container" style="display: flex; align-items: center; justify-content: space-between; padding: 12px var(--spacing-lg); gap: var(--spacing-lg);">
        <a href="{{ route('home') }}" style="display: flex; align-items: center; text-decoration: none; font-size: var(--font-size-xl); font-weight: var(--font-weight-bold); color: var(--color-primary); gap: var(--spacing-sm);">
            <span>{{ config('app.name', 'NewsFeed') }}</span>
        </a>
        
        @auth
        <nav style="display: flex; align-items: center; gap: var(--spacing-sm); flex: 1; justify-content: center;">
            <a href="{{ route('news-feed') }}" 
               style="padding: 10px 20px; text-decoration: none; color: {{ request()->routeIs('news-feed*') ? 'var(--color-primary)' : 'var(--color-text-secondary)' }}; font-weight: var(--font-weight-medium); font-size: var(--font-size-sm); border-radius: var(--radius-md); transition: all var(--transition-base); background: {{ request()->routeIs('news-feed*') ? 'var(--color-primary-50)' : 'transparent' }};">
                <i class="fas fa-home"></i> Feed
            </a>
            <a href="{{ route('dashboard') }}" 
               style="padding: 10px 20px; text-decoration: none; color: {{ request()->routeIs('dashboard') ? 'var(--color-primary)' : 'var(--color-text-secondary)' }}; font-weight: var(--font-weight-medium); font-size: var(--font-size-sm); border-radius: var(--radius-md); transition: all var(--transition-base); background: {{ request()->routeIs('dashboard') ? 'var(--color-primary-50)' : 'transparent' }};">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
        </nav>
        
        <div style="display: flex; align-items: center; gap: var(--spacing-md);">
            <div style="position: relative; max-width: 400px; width: 100%;">
                <i class="fas fa-search" style="position: absolute; left: 16px; top: 50%; transform: translateY(-50%); color: var(--color-text-tertiary);"></i>
                <input type="text" placeholder="Search..." id="headerSearch" 
                       style="width: 100%; padding: 10px 16px 10px 44px; border: 1px solid var(--color-border); border-radius: var(--radius-lg); font-size: var(--font-size-sm); transition: all var(--transition-base);">
            </div>
            
            <div style="display: flex; align-items: center; gap: var(--spacing-sm); position: relative;">
                @php
                    $user = Auth::user();
                    $photo = $user->photo;
                    $initials = strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1));
                @endphp
                
                @if($photo)
                    <img src="{{ getImageUrl($photo) }}" alt="{{ $user->first_name }}" 
                         style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover; border: 2px solid var(--color-border); cursor: pointer; transition: all var(--transition-base);">
                @else
                    <div style="width: 40px; height: 40px; border-radius: 50%; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); display: flex; align-items: center; justify-content: center; color: white; font-weight: var(--font-weight-semibold); font-size: var(--font-size-sm); border: 2px solid var(--color-border); cursor: pointer;">
                        {{ $initials }}
                    </div>
                @endif
                
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none p-0" type="button" data-bs-toggle="dropdown" style="color: var(--color-text-secondary);">
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="{{ route('user.profile', $user->slug) }}"><i class="fas fa-user me-2"></i> Profile</a></li>
                        <li><a class="dropdown-item" href="{{ route('user.details.show') }}"><i class="fas fa-cog me-2"></i> Settings</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="{{ route('logout') }}"><i class="fas fa-sign-out-alt me-2"></i> Logout</a></li>
                    </ul>
                </div>
            </div>
        </div>
        @else
        <div style="display: flex; align-items: center; gap: var(--spacing-sm);">
            <a href="{{ route('login.form') }}" class="btn btn-outline-primary">Login</a>
            <a href="{{ route('register.form') }}" class="btn btn-primary">Sign Up</a>
        </div>
        @endauth
    </div>
</header>

<style>
    @media (max-width: 768px) {
        header nav {
            display: none !important;
        }
        
        header input[type="text"] {
            max-width: 200px;
        }
    }
</style>
