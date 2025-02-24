<!-- resources/views/partials/dashboard-sidebar.blade.php -->

<div class="sidebar" id="dashboardSidebar">
    <h5 class="filter_heading">Dashboard Menu</h5>
    <div id="dashboardMenu">
        <!-- Dashboard Menu Items -->
        <ul class="nav-list">
            <li class="nav-items">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-home"></i>
                    Dashboard
                </a>
            </li>
            <li class="nav-items">
                <a href="{{ route('user.details.show') }}" class="{{ request()->routeIs('user.details.show') ? 'active' : '' }}">
                    <i class="fa-solid fa-user"></i>
                    My Profile
                </a>
            </li> 
            <li class="nav-items">
                <a href="{{ route('user.products') }}" class="{{ request()->routeIs('user.products') ? 'active' : '' }}">
                    <i class="fa-solid fa-diagram-project"></i>
                    Products
                </a>
            </li>
            <li class="nav-items">
                <a href="{{ route('user.services') }}" class="{{ request()->routeIs('user.services') ? 'active' : '' }}">
                    <i class="fa-brands fa-servicestack"></i>
                    Services
                </a>
            </li>
            <li class="nav-items">
                <a href="{{ route('user.qualifications') }}" class="{{ request()->routeIs('user.qualifications') ? 'active' : '' }}">
                    <i class="fa-solid fa-user-graduate"></i>
                    Qualifications
                </a>
            </li>
            {{-- <li class="nav-items">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-money-bill-wheat"></i>
                    Subscriptions
                </a>
            </li> --}}
           
        </ul>
    </div>
</div>
