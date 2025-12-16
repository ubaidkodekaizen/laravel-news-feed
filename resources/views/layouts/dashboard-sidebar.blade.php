<!-- resources/views/partials/dashboard-sidebar.blade.php -->

<style>
    .sidebar {
        width: 16% !important;
        background-color: #F4F5FB !important;
        border-right: 1px solid #E9EBF0 !important;
        height: -webkit-fill-available !important;
    }
</style>

<div class="sidebar" id="dashboardSidebar">
    <div id="dashboardMenu">
        <!-- Dashboard Menu Items -->
        <ul class="nav-list">
            <li class="nav-items">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <!-- <i class="fa-solid fa-home"></i> -->
                     <img src="assets/images/dashboard/sidebarDashboardIcon.svg" alt="">
                    Dashboard
                </a>
            </li>
            <li class="nav-items">
                <a href="{{ route('user.details.show') }}" class="{{ request()->routeIs('user.details.show') ? 'active' : '' }}">
                    <!-- <i class="fa-solid fa-user"></i> -->
                     <img src="assets/images/dashboard/sidebarMyProfileIcon.svg" alt="">
                    My Profile
                </a>
            </li> 
            <li class="nav-items">
                <a href="{{ route('user.products') }}" class="{{ request()->routeIs('user.products') ? 'active' : '' }}">
                    <!-- <i class="fa-solid fa-diagram-project"></i> -->
                     <img src="assets/images/dashboard/sidebarProductIcon.svg" alt="">
                    Products
                </a>
            </li>
            <li class="nav-items">
                <a href="{{ route('user.services') }}" class="{{ request()->routeIs('user.services') ? 'active' : '' }}">
                    <!-- <i class="fa-brands fa-servicestack"></i> -->
                     <img src="assets/images/dashboard/sidebarServiceIcon.svg" alt="">
                    Services
                </a>
            </li>
            <li class="nav-items">
                <a href="{{ route('user.qualifications') }}" class="{{ request()->routeIs('user.qualifications') ? 'active' : '' }}">
                    <!-- <i class="fa-solid fa-user-graduate"></i> -->
                     <img src="assets/images/dashboard/sidebarQualificationIcon.svg" alt="">
                    Qualifications
                </a>
            </li>
            <!-- {{-- <li class="nav-items">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fa-solid fa-money-bill-wheat"></i> 
                    Subscriptions
                </a>
            </li> --}} -->
           
        </ul>
    </div>
    <div class="appSection">
        <div class="appSecInner">
            <h3></h3>
            <p></p>
            <a href=""></a>
        </div>
    </div>
</div>
