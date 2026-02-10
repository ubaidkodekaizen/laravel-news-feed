<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel') | {{ config('app.name', 'NewsFeed') }}</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/design-system.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <style>
        :root {
            --sidebar-width: 260px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            background: var(--color-bg-secondary);
            color: var(--color-text-primary);
            -webkit-font-smoothing: antialiased;
        }
        
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        .admin-sidebar {
            width: var(--sidebar-width);
            background: var(--color-bg-primary);
            border-right: 1px solid var(--color-border);
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            box-shadow: var(--shadow-md);
            z-index: 100;
        }
        
        .admin-header {
            background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
            color: white;
            padding: var(--spacing-md) var(--spacing-lg);
            display: flex;
            align-items: center;
            justify-content: space-between;
            box-shadow: var(--shadow-md);
            position: sticky;
            top: 0;
            z-index: 50;
        }
        
        .admin-logo {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            font-size: var(--font-size-xl);
            font-weight: var(--font-weight-bold);
        }
        
        .admin-logo i {
            font-size: var(--font-size-2xl);
        }
        
        .admin-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            min-height: 100vh;
        }
        
        .admin-main {
            padding: var(--spacing-xl);
            max-width: 1400px;
            margin: 0 auto;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: var(--spacing-lg) 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 4px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            padding: var(--spacing-md) var(--spacing-lg);
            color: var(--color-text-secondary);
            text-decoration: none;
            font-weight: var(--font-weight-medium);
            font-size: var(--font-size-base);
            transition: all var(--transition-base);
            border-left: 3px solid transparent;
        }
        
        .sidebar-menu a:hover {
            background: var(--color-bg-tertiary);
            color: var(--color-primary);
            border-left-color: var(--color-primary);
        }
        
        .sidebar-menu a.active {
            background: var(--color-primary-50);
            color: var(--color-primary);
            border-left-color: var(--color-primary);
        }
        
        .sidebar-menu a i {
            width: 20px;
            text-align: center;
        }
        
        .user-menu {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255, 255, 255, 0.3);
        }
        
        .avatar-initials {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: var(--font-weight-semibold);
            font-size: var(--font-size-sm);
        }
        
        .card-modern {
            background: var(--color-bg-primary);
            border-radius: var(--radius-lg);
            border: 1px solid var(--color-border);
            box-shadow: var(--shadow-sm);
            padding: var(--spacing-lg);
            margin-bottom: var(--spacing-lg);
        }
        
        .page-header {
            margin-bottom: var(--spacing-xl);
        }
        
        .page-header h1 {
            font-size: var(--font-size-3xl);
            font-weight: var(--font-weight-bold);
            color: var(--color-text-primary);
            margin-bottom: var(--spacing-sm);
        }
        
        .page-header p {
            color: var(--color-text-secondary);
            font-size: var(--font-size-base);
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-100%);
                transition: transform var(--transition-base);
            }
            
            .admin-sidebar.open {
                transform: translateX(0);
            }
            
            .admin-content {
                margin-left: 0;
            }
        }
    </style>
    
    @yield('styles')
</head>
<body>
    <div class="admin-wrapper">
        @include('admin.layouts.sidebar')
        
        <div class="admin-content">
            @include('admin.layouts.header')
            
            <div class="admin-main">
                @yield('content')
            </div>
        </div>
    </div>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    
    @yield('scripts')
</body>
</html>
