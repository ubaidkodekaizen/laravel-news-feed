<!-- resources/views/partials/dashboard-sidebar.blade.php -->

<style>
    body{
        overflow: hidden;
    }
    .sidebar {
        width: 16% !important;
        background-color: #F4F5FB !important;
        border-right: 1px solid #E9EBF0 !important;
        height: -webkit-fill-available !important;
        overflow: visible;
    }

    div#dashboardMenu {
        position: relative;
    }

    div#dashboardMenu .dashboardMenuCollapseBtn{
        position: absolute;
        top: 52px;
        right: -13px;
        outline: none;
        border: none;
        background: #00000000;
        rotate: 0deg;
        padding: 0;
        border-radius: 50%;
    }

    #dashboardSidebar.collapsed .dashboardMenuCollapseBtn{
        rotate: 180deg;
    }

    #dashboardSidebar {
        display: flex;
        width: 18%;
        transition: width 0.3s ease;
        flex-direction: column;
        justify-content: space-between;
    }

    .main-content {
        width: 84% !important;
        transition: width 0.3s ease;
    }

    #dashboardSidebar.collapsed ~ .main-content {
        width: 96% !important;
    }

    #dashboardSidebar.collapsed {
        width: 4.7% !important;
    }

    #dashboardSidebar.collapsed .menu-text {
        display: inline;
        transition: display 0.3s ease;
    }

    #dashboardSidebar.collapsed .menu-text {
        display: none;
    }

    #dashboardSidebar.collapsed .nav-list .nav-items a{
        padding: 12px 12px;
    }

    .nav-items a {
        transition: all 0.3s ease;
    }

    .appSection {
        padding: 20px;
    }

    .appSection .appSecInner{
        background: #b8c034;
        padding: 30px 20px;
        border-radius: 10px;
        font-family: "Inter";
        color: #333;
    }

    .appSection .appSecInner h3{
        font-size: 26px;
        font-weight: 600;
    }

    .appSection .appSecInner button {
        width: 100%;
        border: none;
        outline: none;
        border-radius: 8px;
    }

    .appSection .appSecInner button a{
        text-decoration: none;
        color: #333;
        font-size: 18px;
        font-weight: 500;
        line-height: 55px;
    }

    
    


@media (max-width: 1080px) {

    body{
        overflow: auto !important;
    }

    .main-content {
        width: 100% !important;
        height: 100%;
        padding: 50px 14px 20px 14px !important;
    }

    div#dashboardMenu .dashboardMenuCollapseBtn {
        display: none;
    }

   
}

@media (max-width: 768px) {
    .sidebar {
        width: 80% !important;
        height: 100vh !important;
    }

    
}



</style>

<div class="sidebar" id="dashboardSidebar">
    <div id="dashboardMenu">
        <button class="dashboardMenuCollapseBtn"><img src="{{ asset('assets/images/dashboard/sidebarCollapseIcon.svg') }}" alt=""></button>
        <!-- Dashboard Menu Items -->
        <ul class="nav-list">
            <li class="nav-items">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <!-- <i class="fa-solid fa-home"></i> -->
                     <!-- <img src="assets/images/dashboard/sidebarDashboardIcon.svg" alt=""> -->
                     <img src="{{ asset('assets/images/dashboard/sidebarDashboardIcon.svg') }}" alt="">
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-items">
                <a href="{{ route('user.details.show') }}" class="{{ request()->routeIs('user.details.show') ? 'active' : '' }}">
                    <!-- <i class="fa-solid fa-user"></i> -->
                     <img src="{{ asset('assets/images/dashboard/sidebarMyProfileIcon.svg') }}" alt="">
                    <!-- My Profile -->
                     <span class="menu-text">My Profile</span>
                </a>
            </li> 
            <li class="nav-items">
                <a href="{{ route('user.products') }}" class="{{ request()->routeIs('user.products') ? 'active' : '' }}">
                    <!-- <i class="fa-solid fa-diagram-project"></i> -->
                     <img src="{{ asset('assets/images/dashboard/sidebarProductIcon.svg') }}" alt="">

                    <span class="menu-text">Products</span>
                </a>
            </li>
            <li class="nav-items">
                <a href="{{ route('user.services') }}" class="{{ request()->routeIs('user.services') ? 'active' : '' }}">
                    <!-- <i class="fa-brands fa-servicestack"></i> -->
                     <img src="{{ asset('assets/images/dashboard/sidebarServiceIcon.svg') }}" alt="">

                    
                    <span class="menu-text">Services</span>
                </a>
            </li>
            <li class="nav-items">
                <a href="{{ route('user.qualifications') }}" class="{{ request()->routeIs('user.qualifications') ? 'active' : '' }}">
                    <!-- <i class="fa-solid fa-user-graduate"></i> -->
                     <img src="{{ asset('assets/images/dashboard/sidebarQualificationIcon.svg') }}" alt="">

                    <span class="menu-text">Qualifications</span>
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
    <!-- <div class="appSection">
        <div class="appSecInner">
            <h3>Download our Mobile App</h3>
            <p>Get easy in another way</p>
            <button>
                <a href="#">Download</a>
            </button>
        </div>
    </div> -->
</div>

<script>
    document.querySelector('.dashboardMenuCollapseBtn')
        .addEventListener('click', function () {
            document.getElementById('dashboardSidebar')
                .classList.toggle('collapsed');
        });
</script>
