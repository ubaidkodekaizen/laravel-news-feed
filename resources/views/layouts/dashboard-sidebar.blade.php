<style>
    .sidebar {
        width: 16% !important;
        background-color: var(--color-bg-secondary) !important;
        border-right: 1px solid var(--color-border) !important;
        height: -webkit-fill-available !important;
        overflow: visible;
    }

    div#dashboardMenu {
        position: relative;
    }

    div#dashboardMenu .dashboardMenuCollapseBtn {
        position: absolute;
        top: 52px;
        right: -13px;
        outline: none;
        border: none;
        background: transparent;
        rotate: 0deg;
        padding: 0;
        border-radius: 50%;
        color: var(--color-text-secondary);
        cursor: pointer;
    }

    #dashboardSidebar.collapsed .dashboardMenuCollapseBtn {
        rotate: 180deg;
    }

    body {
        overflow-y: unset !important;
        background: var(--color-bg-secondary);
    }

    .header {
        position: fixed !important;
        width: 100%;
    }

    .navbar_d_flex {
        display: flex;
        height: 100vh;
        align-items: unset;
    }

    #dashboardSidebar {
        display: flex;
        width: 100% !important;
        max-width: 277px;
        transition: width var(--transition-base);
        flex-direction: column;
        justify-content: space-between;
        position: fixed;
        left: 0;
        top: 0;
        height: 100% !important;
        z-index: 2;
        margin-top: 109px;
    }

    .main-content {
        width: 100% !important;
        max-width: calc(100% - 277px) !important;
        height: max-content !important;
        overflow-x: hidden !important;
        overflow-y: hidden !important;
        margin-left: auto;
        flex: unset !important;
        margin-top: 109px;
    }

    #dashboardSidebar.collapsed~.main-content {
        width: 100% !important;
        max-width: calc(100% - 90px) !important;
    }

    #dashboardSidebar.collapsed {
        max-width: 90px;
        width: 100% !important;
    }

    #dashboardSidebar.collapsed .menu-text {
        display: inline;
        transition: display var(--transition-base);
    }

    #dashboardSidebar.collapsed .menu-text {
        display: none;
    }

    #dashboardSidebar.collapsed .nav-list .nav-items a {
        padding: 12px 12px;
    }

    .nav-items a {
        transition: all var(--transition-base);
        padding: 12px 20px;
        display: flex;
        align-items: center;
        gap: var(--spacing-md);
        text-decoration: none;
        color: var(--color-text-secondary);
        border-radius: var(--radius-md);
        margin: var(--spacing-xs) var(--spacing-md);
    }
    
    .nav-items a:hover {
        background: var(--color-primary-50);
        color: var(--color-primary);
    }
    
    .nav-items a.active {
        background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);
        color: white;
    }
    
    .nav-items a i {
        width: 20px;
        text-align: center;
    }

    .appSection {
        padding: var(--spacing-lg);
    }

    .appSection .appSecInner {
        background: var(--color-primary);
        padding: var(--spacing-xl) var(--spacing-lg);
        border-radius: var(--radius-lg);
        font-family: var(--font-family);
        color: white;
    }

    .appSection .appSecInner h3 {
        font-size: var(--font-size-2xl);
        font-weight: var(--font-weight-semibold);
    }

    .appSection .appSecInner button {
        width: 100%;
        border: none;
        outline: none;
        border-radius: var(--radius-md);
    }

    .appSection .appSecInner button a {
        text-decoration: none;
        color: white;
        font-size: var(--font-size-lg);
        font-weight: var(--font-weight-medium);
        line-height: 55px;
    }

    @media (max-width: 1400px) {
        #dashboardSidebar {
            width: 100% !important;
            max-width: 277px;
        }

        body {
            overflow: unset !important;
        }
    }

    @media (max-width: 1080px) {
        #dashboardSidebar {
            left: -100%;
            width: 50% !important;
            transition: left var(--transition-base);
        }

        #dashboardSidebar.open {
            left: 0%;
            transition: left var(--transition-base);
        }

        body {
            overflow: auto !important;
        }

        .main-content {
            width: 100% !important;
            max-width: 100% !important;
            height: 100%;
            padding: 50px 14px 20px 14px !important;
        }

        div#dashboardMenu .dashboardMenuCollapseBtn {
            display: none;
        }

        #dashboardSidebar.collapsed~.main-content {
            width: 100% !important;
            max-width: 100% !important;
        }
    }

    @media (max-width: 768px) {
        #dashboardSidebar {
            width: 80% !important;
        }

        #dashboardSidebar.collapsed {
            width: 14.7% !important;
        }
    }
</style>

<div class="sidebar" id="dashboardSidebar">
    <div id="dashboardMenu">
        <button class="dashboardMenuCollapseBtn"><i class="fas fa-chevron-left"></i></button>
        <ul class="nav-list">
            <li class="nav-items">
                <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
                    <i class="fas fa-chart-line"></i>
                    <span class="menu-text">Dashboard</span>
                </a>
            </li>
            <li class="nav-items">
                <a href="{{ route('user.details.show') }}"
                    class="{{ request()->routeIs('user.details.show') ? 'active' : '' }}">
                    <i class="fas fa-user"></i>
                    <span class="menu-text">My Profile</span>
                </a>
            </li>
            <!-- Products, Services, and Education removed - not part of newsfeed boilerplate -->
        </ul>
    </div>
</div>

<script>
    document.querySelector('.dashboardMenuCollapseBtn')
        .addEventListener('click', function() {
            document.getElementById('dashboardSidebar')
                .classList.toggle('collapsed');
        });
</script>
