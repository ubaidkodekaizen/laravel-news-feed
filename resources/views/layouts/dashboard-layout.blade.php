<!-- resources/views/layouts/dashboard-layout.blade.php -->

@extends('layouts.main')

<style>
    .main-content {
        padding: 46px 40px !important;
        background: #fafbff;
    }
    .col-lg-6.dashboardBtns {
        display: flex;
        justify-content: end;
        gap: 10px;
    }
    
</style>

@section('content')
    <div class="navbar_d_flex">
        <!-- Toggler Button for Dashboard Sidebar -->
        <button class="sidebar-toggler btn btn-primary d-lg-none" type="button" onclick="toggleDashboardSidebar()">
            Dashboard Menu
        </button>

        <!-- Dashboard Sidebar -->
        @include('layouts.dashboard-sidebar')

        <!-- Main Content Area -->
        <div class="main-content">
            <!-- Dashboard Content -->
            @yield('dashboard-content')
        </div>
    </div>
    @yield('script')
@endsection

<script>
    function toggleDashboardSidebar() {
        const sidebar = document.getElementById("dashboardSidebar");
        if (sidebar) {
            sidebar.classList.toggle("open");
        } else {
            console.error("Dashboard Sidebar element not found!");
        }
    }
</script>
