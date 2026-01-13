@extends('admin.layouts.main')
<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
<style>
    body{
        background: #fafbff !important;
    }

    .card-header:first-child {
        background: #fafbff !important;
        border: none;
    }

    .card-body {
        background: #fafbff !important;
    }

    table.dataTable {
        margin: 0px !important;
    }

    div.dataTables_wrapper div.dt-row {
        padding: 10px;
    }

    .row.dt-row .col-sm-12{
        padding: 0 !important;
        overflow: scroll;
        overflow-y: hidden;
        border-radius: 15.99px 15.99px 0 0;
        border-top: 2px solid #F2F2F2;
        border-right: 2px solid #F2F2F2;
        border-bottom: 1px solid #F2F2F2 !important;
        border-left: 2px solid #F2F2F2;
    }

    .row.dt-row .col-sm-12::-webkit-scrollbar {
        width: 8px;
        height: 8px;
    }

    .row.dt-row .col-sm-12::-webkit-scrollbar-track {
        background: #f1f1f1;
    }

    .row.dt-row .col-sm-12::-webkit-scrollbar-thumb {
        background: #273572;
        border-radius: 10px;
    }


    table#usersTable {
        margin: 0 !important;
        /* border-radius: 15.99px 15.99px 0 0; */
        overflow: hidden;
        /* border-top: 2px solid #F2F2F2;
        border-right: 2px solid #F2F2F2;
        border-bottom: 1px solid #F2F2F2 !important;
        border-left: 2px solid #F2F2F2; */
    }

    .card-title {
        border-radius: 0;
        color: #333 !important;
        margin: 0;
        font-size: 28px;
        font-family: "Inter";
        font-weight: 500;
    }

    .card-header.card_header_flex a{
        background: var(--primary);
        border-color: var(--primary);
        font-family: "poppins";
        font-weight: 300;
        padding: 16px 30px;
        border-radius: 10.66px;
    }

    div.dataTables_wrapper div.dataTables_filter input {
        margin-left: .5em;
        display: inline-block;
        width: auto;
        font-size: 16px;
        font-weight: 400;
        font-family: 'Inter';
        border: 1px solid #E9EBF0 !important;
        border-radius: 10.66px !important;
        padding: 16px 15px !important;
        background-color: transparent;
        transition: background-color 0.2s ease;
    }

    div.dataTables_wrapper div.dataTables_filter input::-webkit-search-cancel-button {
        display: none;
    }

    div#usersTable_filter {
        position: relative;
    }

    div.dataTables_wrapper div.dataTables_filter label,
    div.dataTables_wrapper div.dataTables_length label {
        font-family: 'Poppins';
        font-size: 18px;
    }

    .col-sm-12.col-md-6 {
        align-content: center;
    }

    a.btn.btn-primary.btn-sm,
    a.btn.btn-warning.btn-sm,
    table.dataTable td form button {
        font-family: 'Poppins';
        font-size: 14px;
        padding: 8px 18px;
        border-radius: 14px;
    }

    div#usersTable_filter label::after {
        content: "";
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        background: url("/assets/images/dashboard/ProductSeachInputIcon.svg") no-repeat center;
        background-size: contain;
        pointer-events: none;
    }

    th,
    th.sorting, 
    th.sorting_disabled {
        color: #333333;
        font-family: "Inter";
        font-size: 18.65px;
        font-weight: 500 !important;
        background: #EDEEF4 !important;
        border: none !important;
        padding: 16px 16px !important;
        text-wrap-mode: nowrap;
        padding-right: 44px !important;
    }

    th:before,
    td:before,
    table.dataTable thead > tr > th.sorting:before,
    table.dataTable thead > tr > th.sorting_asc:before,
    table.dataTable thead > tr > th.sorting_desc:before,
    table.dataTable thead > tr > th.sorting_asc_disabled:before,
    table.dataTable thead > tr > th.sorting_desc_disabled:before,
    table.dataTable thead > tr > td.sorting:before,
    table.dataTable thead > tr > td.sorting_asc:before,
    table.dataTable thead > tr > td.sorting_desc:before,
    table.dataTable thead > tr > td.sorting_asc_disabled:before,
    table.dataTable thead > tr > td.sorting_desc_disabled:before {
        content: "" !important;
        width: 16px;
        height: 16px;
        background: url("/assets/images/dashboard/productTableHeadUpChevronIcon.svg") no-repeat center !important;
        background-size: contain;
    }

    th:after,
    td:after,
    table.dataTable thead > tr > th.sorting:after,
    table.dataTable thead > tr > th.sorting_asc:after,
    table.dataTable thead > tr > th.sorting_desc:after,
    table.dataTable thead > tr > th.sorting_asc_disabled:after,
    table.dataTable thead > tr > th.sorting_desc_disabled:after,
    table.dataTable thead > tr > td.sorting:after,
    table.dataTable thead > tr > td.sorting_asc:after,
    table.dataTable thead > tr > td.sorting_desc:after,
    table.dataTable thead > tr > td.sorting_asc_disabled:after,
    table.dataTable thead > tr > td.sorting_desc_disabled:after {
        content: "" !important;
        width: 16px;
        height: 16px;
        background: url("/assets/images/dashboard/productTableHeadDownChevronIcon.svg") no-repeat center !important;
        background-size: contain;
    }

    td,
    table.dataTable.table-striped>tbody>tr.odd>*,
    table.dataTable.table-striped>tbody>tr.even>* {
        vertical-align: middle;
        font-family: "Inter";
        font-size: 18.65px;
        font-weight: 400 !important;
        height: 100px;
        text-wrap-mode: nowrap;
        box-shadow: none !important;
    }

    /* Override DataTables striped rows for status-based highlighting */
    table.dataTable.table-striped>tbody>tr.subscription-active.odd>*,
    table.dataTable.table-striped>tbody>tr.subscription-active.even>* {
        background-color: transparent !important;
    }
    table.dataTable.table-striped>tbody>tr.subscription-cancelled.odd>*,
    table.dataTable.table-striped>tbody>tr.subscription-cancelled.even>*,
    table.dataTable.table-striped>tbody>tr.subscription-inactive.odd>*,
    table.dataTable.table-striped>tbody>tr.subscription-inactive.even>* {
        background-color: transparent !important;
    }

    div.dataTables_wrapper div.dataTables_info {
        font-size: 17.33px;
        color: #696969;
        font-family: "Inter", sans-serif;
        font-optical-sizing: auto;
        font-style: normal;
        font-weight: 300;
    }

    .pagination .page-item:first-child .page-link,
    .pagination .page-item:last-child .page-link {
        border: none;
        background: #FFFFFF;
    }

    .pagination .page-item.disabled .page-link {
        background-color: transparent;
        border-color: #dee2e6;
        color: #6c757d;
    }

    .pagination .page-link {
        color: #696969;
        padding: 10.83px 15.83px;
        margin: 0 2px;
        border-radius: 10.67px;
        font-family: Inter;
        font-weight: 400;
        font-size: 17.33px;
        line-height: 100%;
        border: 1.33px solid #E1E0E0;
    }

    .card {
        border: 0 !important;
    }

    .pagination .page-item.active .page-link {
        border: 1.33px solid #37488E;
        background: #37488E14;
        color:  #37488E;
    }

    /* Row highlighting based on subscription status */
    table#usersTable tbody tr.subscription-active,
    table#usersTable.table-striped tbody tr.subscription-active.odd,
    table#usersTable.table-striped tbody tr.subscription-active.even {
        background-color: #d4edda !important;
    }
    table#usersTable tbody tr.subscription-active:hover {
        background-color: #c3e6cb !important;
    }
    table#usersTable tbody tr.subscription-active td,
    table.dataTable.table-striped>tbody>tr.subscription-active.odd>td,
    table.dataTable.table-striped>tbody>tr.subscription-active.even>td {
        background-color: #d4edda !important;
    }
    table#usersTable tbody tr.subscription-active:hover td {
        background-color: #c3e6cb !important;
    }

    table#usersTable tbody tr.subscription-cancelled,
    table#usersTable tbody tr.subscription-inactive,
    table#usersTable.table-striped tbody tr.subscription-cancelled.odd,
    table#usersTable.table-striped tbody tr.subscription-cancelled.even,
    table#usersTable.table-striped tbody tr.subscription-inactive.odd,
    table#usersTable.table-striped tbody tr.subscription-inactive.even {
        background-color: #f8d7da !important;
    }
    table#usersTable tbody tr.subscription-cancelled:hover,
    table#usersTable tbody tr.subscription-inactive:hover {
        background-color: #f5c6cb !important;
    }
    table#usersTable tbody tr.subscription-cancelled td,
    table#usersTable tbody tr.subscription-inactive td,
    table.dataTable.table-striped>tbody>tr.subscription-cancelled.odd>td,
    table.dataTable.table-striped>tbody>tr.subscription-cancelled.even>td,
    table.dataTable.table-striped>tbody>tr.subscription-inactive.odd>td,
    table.dataTable.table-striped>tbody>tr.subscription-inactive.even>td {
        background-color: #f8d7da !important;
    }
    table#usersTable tbody tr.subscription-cancelled:hover td,
    table#usersTable tbody tr.subscription-inactive:hover td {
        background-color: #f5c6cb !important;
    }

    /* Free subscriptions - Yellow highlighting */
    table#usersTable tbody tr.subscription-free,
    table#usersTable.table-striped tbody tr.subscription-free.odd,
    table#usersTable.table-striped tbody tr.subscription-free.even {
        background-color: #fff3cd !important;
    }
    table#usersTable tbody tr.subscription-free:hover {
        background-color: #ffeaa7 !important;
    }
    table#usersTable tbody tr.subscription-free td,
    table.dataTable.table-striped>tbody>tr.subscription-free.odd>td,
    table.dataTable.table-striped>tbody>tr.subscription-free.even>td {
        background-color: #fff3cd !important;
    }
    table#usersTable tbody tr.subscription-free:hover td {
        background-color: #ffeaa7 !important;
    }
</style>
@section('content')
    <main class="main-content">
        @php
            $filter = $filter ?? 'all';
            $counts = $counts ?? [];
            $user = Auth::user();
            $isAdmin = $user && $user->role_id == 1;
            $canView = $isAdmin || ($user && $user->hasPermission('subscriptions.view'));
            $canFilter = $isAdmin || ($user && $user->hasPermission('subscriptions.filter'));
        @endphp
        @if(!$canView && !$canFilter)
            @php
                abort(403, 'Unauthorized action.');
            @endphp
        @endif
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Subscriptions</h4>
                        </div>
                        <div class="card-body">
                            <!-- Tabs Navigation - Only show if user has filter permission -->
                            @if($canFilter)
                            <ul class="nav nav-tabs mb-4 pb-3" id="subscriptionTabs" role="tablist" style="border-bottom: 2px solid #E1E0E0;">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}"
                                       href="{{ route('admin.subscriptions', ['filter' => 'all']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        ALL <span class="badge bg-secondary">{{ $counts['all'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'active' ? 'active' : '' }}"
                                       href="{{ route('admin.subscriptions', ['filter' => 'active']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        ACTIVE <span class="badge bg-secondary">{{ $counts['active'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'inactive' ? 'active' : '' }}"
                                       href="{{ route('admin.subscriptions', ['filter' => 'inactive']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        INACTIVE <span class="badge bg-secondary">{{ $counts['inactive'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'web' ? 'active' : '' }}"
                                       href="{{ route('admin.subscriptions', ['filter' => 'web']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        WEB <span class="badge bg-secondary">{{ $counts['web'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'google' ? 'active' : '' }}"
                                       href="{{ route('admin.subscriptions', ['filter' => 'google']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        GOOGLE <span class="badge bg-secondary">{{ $counts['google'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'apple' ? 'active' : '' }}"
                                       href="{{ route('admin.subscriptions', ['filter' => 'apple']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        APPLE <span class="badge bg-secondary">{{ $counts['apple'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'amcob' ? 'active' : '' }}"
                                       href="{{ route('admin.subscriptions', ['filter' => 'amcob']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        AMCOB <span class="badge bg-secondary">{{ $counts['amcob'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'monthly' ? 'active' : '' }}"
                                       href="{{ route('admin.subscriptions', ['filter' => 'monthly']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        MONTHLY <span class="badge bg-secondary">{{ $counts['monthly'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'annual' ? 'active' : '' }}"
                                       href="{{ route('admin.subscriptions', ['filter' => 'annual']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        ANNUAL <span class="badge bg-secondary">{{ $counts['annual'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'free' ? 'active' : '' }}"
                                       href="{{ route('admin.subscriptions', ['filter' => 'free']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        FREE <span class="badge bg-secondary">{{ $counts['free'] ?? 0 }}</span>
                                    </a>
                                </li>
                            </ul>
                            @else
                            {{-- Show message if user can view but not filter --}}
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle"></i> You have view-only access. Filter tabs are not available.
                            </div>
                            @endif
                            <style>
                            .nav-tabs .nav-link {
                                border: none;
                                border-bottom: 3px solid transparent;
                                transition: all 0.3s ease;
                                text-transform: uppercase;
                            }
                            .nav-tabs .nav-link:hover {
                                border-bottom-color: #37488E;
                                color: #37488E !important;
                            }
                            .nav-tabs .nav-link.active {
                                border-bottom-color: #37488E;
                                color: #ffffff !important;
                                border-radius: 12px;
                                background: #273572;
                            }

                            .nav-tabs .nav-link.active .badge {
                                color: #ffffff !important;
                            }
                            .nav-tabs .badge {
                                color: #000;
                                margin: 0px 0px 0px 0px;
                                font-size: 16px;
                                font-family: "Inter";
                                font-weight: 400;
                                background: transparent !important;
                            }
                            @media (max-width: 768px) {
                                    ul#subscriptionTabs {
                                        justify-content: center;
                                    }

                                    .nav-tabs .nav-link {
                                        font-size: 12px;
                                        padding: 5px 12px !important;
                                    }

                                    .nav-tabs .badge {
                                        font-size: 12px;
                                    }
                                }
                        </style>
                            <table id="usersTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Subscription ID</th>
                                        <th>Type</th>
                                        <th>Amount</th>
                                        <th>Start Date</th>
                                        <th>Renewal Date</th>
                                        <th>Status</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($subscriptions as $subscription)
                                        @php
                                            $statusClass = '';
                                            // Check if it's a free subscription
                                            if (strtolower($subscription->subscription_type ?? '') === 'free' || 
                                                ($subscription->subscription_amount ?? 0) == 0) {
                                                $statusClass = 'subscription-free';
                                            } elseif (strtolower($subscription->status) === 'active') {
                                                $statusClass = 'subscription-active';
                                            } elseif (strtolower($subscription->status) === 'cancelled' || strtolower($subscription->status) === 'inactive') {
                                                $statusClass = 'subscription-cancelled subscription-inactive';
                                            }
                                        @endphp
                                        <tr class="{{ $statusClass }}">
                                            <td>{{ $subscription->id }}</td>
                                            <td>
                                                @if($subscription->user)
                                                    {{ trim($subscription->user->first_name . ' ' . $subscription->user->last_name) ?: 'N/A' }}
                                                @else
                                                    <span style="color: #999;">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($subscription->user && $subscription->user->email)
                                                    {{ $subscription->user->email }}
                                                @else
                                                    <span style="color: #999;">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($subscription->user && $subscription->user->phone)
                                                    {{ $subscription->user->phone }}
                                                @else
                                                    <span style="color: #999;">N/A</span>
                                                @endif
                                            </td>
                                            <td>{{ $subscription->transaction_id ?: 'N/A' }}</td>
                                            <td>{{ $subscription->subscription_type }}</td>
                                            <td>${{ number_format($subscription->subscription_amount, 2) }}</td>
                                            <td>
                                                @if($subscription->start_date)
                                                    {{ \Carbon\Carbon::parse($subscription->start_date)->format('m/d/Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>
                                                @if($subscription->renewal_date)
                                                    {{ \Carbon\Carbon::parse($subscription->renewal_date)->format('m/d/Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </td>
                                            <td>{{ $subscription->status }}</td>

                                            {{-- <td>
                                        <a href="{{ route('admin.user.profile', ['id' => $user->id]) }}" class="btn btn-primary btn-sm">View</a>
                                        <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="{{ route('admin.company.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit Company</a>
                                        <a href="{{ route('users.destroy', $user->id) }}" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>

                                    </td> --}}
                                        </tr>
                                    @empty
                                        <tr>
                                            <td>No Users</td>

                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection
