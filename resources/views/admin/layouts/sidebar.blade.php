<aside class="admin-sidebar">
    <ul class="sidebar-menu">
        <li>
            <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fas fa-tachometer-alt"></i>
                <span>Dashboard</span>
            </a>
        </li>
        
        <li>
            <a href="{{ route('admin.users') }}" class="{{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="fas fa-users"></i>
                <span>Users</span>
            </a>
        </li>
        
        <li>
            <a href="{{ route('admin.feed') }}" class="{{ request()->routeIs('admin.feed*') ? 'active' : '' }}">
                <i class="fas fa-newspaper"></i>
                <span>Feed Management</span>
            </a>
        </li>
    </ul>
</aside>
