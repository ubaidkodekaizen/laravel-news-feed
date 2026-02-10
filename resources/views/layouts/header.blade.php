<header class="modern-header">
    <style>
        .modern-header {
            background: var(--bg-primary);
            border-bottom: 1px solid var(--border-color);
            box-shadow: var(--shadow-sm);
            position: sticky;
            top: 0;
            z-index: 1000;
            padding: 12px 0;
        }
        
        .header-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 0 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }
        
        .header-logo {
            display: flex;
            align-items: center;
            text-decoration: none;
            font-size: 24px;
            font-weight: 700;
            color: var(--primary);
            gap: 12px;
        }
        
        .header-logo img {
            height: 40px;
            width: auto;
        }
        
        .header-nav {
            display: flex;
            align-items: center;
            gap: 8px;
            flex: 1;
            justify-content: center;
        }
        
        .header-nav a {
            padding: 10px 20px;
            text-decoration: none;
            color: var(--text-secondary);
            font-weight: 500;
            font-size: 15px;
            border-radius: var(--border-radius-sm);
            transition: all 0.2s ease;
        }
        
        .header-nav a:hover,
        .header-nav a.active {
            color: var(--primary);
            background: rgba(99, 102, 241, 0.1);
        }
        
        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .header-search {
            position: relative;
            max-width: 400px;
            width: 100%;
        }
        
        .header-search input {
            width: 100%;
            padding: 10px 16px 10px 44px;
            border: 1px solid var(--border-color);
            border-radius: var(--border-radius-lg);
            font-size: 14px;
            transition: all 0.3s ease;
        }
        
        .header-search input:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
            outline: none;
        }
        
        .header-search i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--text-tertiary);
        }
        
        .user-profile-menu {
            display: flex;
            align-items: center;
            gap: 12px;
            position: relative;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid var(--border-color);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .user-avatar:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }
        
        .avatar-initials {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 14px;
            border: 2px solid var(--border-color);
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .avatar-initials:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-md);
        }
        
        /* Notification styles removed - not part of newsfeed boilerplate */
        .notification-badge {
            position: absolute;
            top: 4px;
            right: 4px;
            background: var(--danger);
            color: white;
            font-size: 10px;
            font-weight: 600;
            padding: 2px 6px;
            border-radius: 10px;
            min-width: 18px;
            text-align: center;
        }
        
        @media (max-width: 768px) {
            .header-nav {
                display: none;
            }
            
            .header-search {
                max-width: 200px;
            }
        }
    </style>
    
    <div class="header-container">
        <a href="{{ route('home') }}" class="header-logo">
            <span>{{ config('app.name', 'NewsFeed') }}</span>
        </a>
        
        @auth
        <nav class="header-nav">
            <a href="{{ route('news-feed') }}" class="{{ request()->routeIs('news-feed*') ? 'active' : '' }}">
                <i class="fas fa-home"></i> Feed
            </a>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                <i class="fas fa-chart-line"></i> Dashboard
            </a>
        </nav>
        
        <div class="header-actions">
            <div class="header-search">
                <i class="fas fa-search"></i>
                <input type="text" placeholder="Search..." id="headerSearch">
            </div>
            
                  <!-- Notification button removed - not part of newsfeed boilerplate -->
            
            <div class="user-profile-menu">
                @php
                    $user = Auth::user();
                    $photo = $user->photo;
                    $initials = strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1));
                @endphp
                
                @if($photo)
                    <img src="{{ getImageUrl($photo) }}" alt="{{ $user->first_name }}" class="user-avatar" id="userAvatar">
                @else
                    <div class="avatar-initials">{{ $initials }}</div>
                @endif
                
                <div class="dropdown">
                    <button class="btn btn-link text-decoration-none text-dark p-0" type="button" data-bs-toggle="dropdown">
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
        <div class="header-actions">
            <a href="{{ route('login.form') }}" class="btn btn-outline-primary">Login</a>
            <a href="{{ route('register.form') }}" class="btn btn-primary">Sign Up</a>
        </div>
        @endauth
    </div>
</header>
