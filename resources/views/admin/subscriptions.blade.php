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

    /* Make table rows clickable */
    table#usersTable tbody tr {
        cursor: pointer;
    }

    /* Modal Styles */
    .subscription-modal .modal-content {
        border-radius: 15px;
        border: none;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
    }

    .subscription-modal .modal-header {
        background: #273572;
        color: white;
        border-radius: 15px 15px 0 0;
        padding: 20px 30px;
        border-bottom: none;
    }

    .subscription-modal .modal-header .btn-close {
        filter: brightness(0) invert(1);
    }

    .subscription-modal .modal-title {
        font-family: 'Inter';
        font-weight: 600;
        font-size: 24px;
    }

    .subscription-modal .modal-body {
        padding: 30px;
        font-family: 'Inter';
    }

    .subscription-detail-row {
        display: flex;
        padding: 15px 0;
        border-bottom: 1px solid #E9EBF0;
    }

    .subscription-detail-row:last-child {
        border-bottom: none;
    }

    .subscription-detail-label {
        font-weight: 600;
        color: #333;
        width: 200px;
        flex-shrink: 0;
        font-size: 16px;
    }

    .subscription-detail-value {
        color: #666;
        flex: 1;
        font-size: 16px;
        word-break: break-word;
    }

    .subscription-detail-section {
        margin-bottom: 30px;
    }

    .subscription-detail-section-title {
        font-family: 'Inter';
        font-weight: 600;
        font-size: 20px;
        color: #273572;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #E9EBF0;
    }

    .status-badge {
        display: inline-block;
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
    }

    .status-active {
        background-color: #d4edda;
        color: #155724;
    }

    .status-cancelled,
    .status-inactive {
        background-color: #f8d7da;
        color: #721c24;
    }

    .status-free {
        background-color: #fff3cd;
        color: #856404;
    }

    .loading-spinner {
        text-align: center;
        padding: 40px;
    }

    .spinner-border {
        width: 3rem;
        height: 3rem;
        border-width: 0.3em;
    }
</style>
@section('content')
    <main class="main-content">
        @php
            $statusFilter = $statusFilter ?? 'all';
            $platformFilter = $platformFilter ?? 'all';
            $typeFilter = $typeFilter ?? 'all';
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
                            <!-- Filter Dropdowns - Only show if user has filter permission -->
                            @if($canFilter)
                            <div class="row mb-4">
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <label for="statusFilter" class="form-label" style="font-family: 'Inter'; font-weight: 600; font-size: 16px; color: #333; margin-bottom: 8px;">Status</label>
                                    <select class="form-select subscription-filter" id="statusFilter" name="status">
                                        <option value="all" {{ $statusFilter === 'all' ? 'selected' : '' }}>ALL ({{ $counts['all'] ?? 0 }})</option>
                                        <option value="active" {{ $statusFilter === 'active' ? 'selected' : '' }}>ACTIVE ({{ $counts['active'] ?? 0 }})</option>
                                        <option value="inactive" {{ $statusFilter === 'inactive' ? 'selected' : '' }}>INACTIVE ({{ $counts['inactive'] ?? 0 }})</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3 mb-md-0">
                                    <label for="platformFilter" class="form-label" style="font-family: 'Inter'; font-weight: 600; font-size: 16px; color: #333; margin-bottom: 8px;">Platform</label>
                                    <select class="form-select subscription-filter" id="platformFilter" name="platform">
                                        <option value="all" {{ $platformFilter === 'all' ? 'selected' : '' }}>ALL</option>
                                        <option value="web" {{ $platformFilter === 'web' ? 'selected' : '' }}>WEB ({{ $counts['web'] ?? 0 }})</option>
                                        <option value="google" {{ $platformFilter === 'google' ? 'selected' : '' }}>GOOGLE ({{ $counts['google'] ?? 0 }})</option>
                                        <option value="apple" {{ $platformFilter === 'apple' ? 'selected' : '' }}>APPLE ({{ $counts['apple'] ?? 0 }})</option>
                                        <option value="amcob" {{ $platformFilter === 'amcob' ? 'selected' : '' }}>AMCOB ({{ $counts['amcob'] ?? 0 }})</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="typeFilter" class="form-label" style="font-family: 'Inter'; font-weight: 600; font-size: 16px; color: #333; margin-bottom: 8px;">Type</label>
                                    <select class="form-select subscription-filter" id="typeFilter" name="type">
                                        <option value="all" {{ $typeFilter === 'all' ? 'selected' : '' }}>ALL</option>
                                        <option value="monthly" {{ $typeFilter === 'monthly' ? 'selected' : '' }}>MONTHLY ({{ $counts['monthly'] ?? 0 }})</option>
                                        <option value="annual" {{ $typeFilter === 'annual' ? 'selected' : '' }}>ANNUAL ({{ $counts['annual'] ?? 0 }})</option>
                                        <option value="free" {{ $typeFilter === 'free' ? 'selected' : '' }}>FREE ({{ $counts['free'] ?? 0 }})</option>
                                    </select>
                                </div>
                            </div>
                            @else
                            {{-- Show message if user can view but not filter --}}
                            <div class="alert alert-info mb-4">
                                <i class="fas fa-info-circle"></i> You have view-only access. Filters are not available.
                            </div>
                            @endif
                            <style>
                            .subscription-filter {
                                font-family: 'Inter';
                                font-size: 16px;
                                font-weight: 500;
                                padding: 12px 16px;
                                border: 1.5px solid #E1E0E0;
                                border-radius: 10px;
                                background-color: #fff;
                                color: #333;
                                transition: all 0.3s ease;
                            }

                            .subscription-filter:hover {
                                border-color: #37488E;
                            }

                            .subscription-filter:focus {
                                border-color: #273572;
                                box-shadow: 0 0 0 0.2rem rgba(39, 53, 114, 0.25);
                                outline: none;
                            }

                            .form-label {
                                display: block;
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
                                        <tr class="{{ $statusClass }}" data-subscription-id="{{ $subscription->id }}">
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

    <!-- Subscription Detail Modal -->
    <div class="modal fade subscription-modal" id="subscriptionDetailModal" tabindex="-1" aria-labelledby="subscriptionDetailModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="subscriptionDetailModalLabel">Subscription Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="subscriptionDetailContent">
                    <div class="loading-spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const modal = new bootstrap.Modal(document.getElementById('subscriptionDetailModal'));
            const modalContent = document.getElementById('subscriptionDetailContent');
            const modalTitle = document.getElementById('subscriptionDetailModalLabel');

            // Add click event to table rows
            document.querySelectorAll('table#usersTable tbody tr[data-subscription-id]').forEach(row => {
                row.addEventListener('click', function(e) {
                    // Don't trigger if clicking on a link or button
                    if (e.target.tagName === 'A' || e.target.tagName === 'BUTTON' || e.target.closest('a') || e.target.closest('button')) {
                        return;
                    }

                    const subscriptionId = this.getAttribute('data-subscription-id');
                    loadSubscriptionDetails(subscriptionId);
                });
            });

            function loadSubscriptionDetails(id) {
                // Show loading spinner
                modalContent.innerHTML = `
                    <div class="loading-spinner">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                `;
                modal.show();

                fetch(`{{ route('admin.subscriptions.show', '') }}/${id}`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Failed to load subscription details');
                    }
                    return response.json();
                })
                .then(data => {
                    renderSubscriptionDetails(data);
                })
                .catch(error => {
                    modalContent.innerHTML = `
                        <div class="alert alert-danger">
                            <strong>Error:</strong> ${error.message}
                        </div>
                    `;
                });
            }

            function renderSubscriptionDetails(data) {
                const statusClass = data.status.toLowerCase();
                let statusBadgeClass = 'status-active';
                if (statusClass.includes('cancelled') || statusClass.includes('inactive')) {
                    statusBadgeClass = 'status-cancelled';
                } else if (data.subscription_type && data.subscription_type.toLowerCase() === 'free') {
                    statusBadgeClass = 'status-free';
                }

                let receiptDataHtml = '';
                if (data.receipt_data && typeof data.receipt_data === 'object') {
                    receiptDataHtml = '<pre style="background: #f5f5f5; padding: 15px; border-radius: 8px; overflow-x: auto; font-size: 12px;">' + JSON.stringify(data.receipt_data, null, 2) + '</pre>';
                } else if (data.receipt_data) {
                    receiptDataHtml = '<pre style="background: #f5f5f5; padding: 15px; border-radius: 8px; overflow-x: auto; font-size: 12px;">' + data.receipt_data + '</pre>';
                } else {
                    receiptDataHtml = 'N/A';
                }

                modalContent.innerHTML = `
                    <div class="subscription-detail-section">
                        <div class="subscription-detail-section-title">User Information</div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">User ID:</div>
                            <div class="subscription-detail-value">${data.user.id || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Name:</div>
                            <div class="subscription-detail-value">${data.user.name || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Email:</div>
                            <div class="subscription-detail-value">${data.user.email || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Phone:</div>
                            <div class="subscription-detail-value">${data.user.phone || 'N/A'}</div>
                        </div>
                    </div>

                    <div class="subscription-detail-section">
                        <div class="subscription-detail-section-title">Subscription Information</div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Subscription ID:</div>
                            <div class="subscription-detail-value">${data.id || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Plan:</div>
                            <div class="subscription-detail-value">${data.plan.name || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Type:</div>
                            <div class="subscription-detail-value">${data.subscription_type || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Amount:</div>
                            <div class="subscription-detail-value">${data.subscription_amount || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Platform:</div>
                            <div class="subscription-detail-value">${data.platform || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Status:</div>
                            <div class="subscription-detail-value">
                                <span class="status-badge ${statusBadgeClass}">${data.status || 'N/A'}</span>
                            </div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Transaction ID:</div>
                            <div class="subscription-detail-value">${data.transaction_id || 'N/A'}</div>
                        </div>
                    </div>

                    <div class="subscription-detail-section">
                        <div class="subscription-detail-section-title">Dates & Timeline</div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Start Date:</div>
                            <div class="subscription-detail-value">${data.start_date || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Renewal Date:</div>
                            <div class="subscription-detail-value">${data.renewal_date || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Expires At:</div>
                            <div class="subscription-detail-value">${data.expires_at || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Last Renewed:</div>
                            <div class="subscription-detail-value">${data.last_renewed_at || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Renewal Count:</div>
                            <div class="subscription-detail-value">${data.renewal_count || 0}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Cancelled At:</div>
                            <div class="subscription-detail-value">${data.cancelled_at || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Grace Period Ends:</div>
                            <div class="subscription-detail-value">${data.grace_period_ends_at || 'N/A'}</div>
                        </div>
                    </div>

                    <div class="subscription-detail-section">
                        <div class="subscription-detail-section-title">Additional Information</div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Auto Renewing:</div>
                            <div class="subscription-detail-value">${data.auto_renewing || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Payment State:</div>
                            <div class="subscription-detail-value">${data.payment_state || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Last Checked:</div>
                            <div class="subscription-detail-value">${data.last_checked_at || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Renewal Reminder Sent:</div>
                            <div class="subscription-detail-value">${data.renewal_reminder_sent_at || 'N/A'}</div>
                        </div>
                    </div>

                    <div class="subscription-detail-section">
                        <div class="subscription-detail-section-title">System Information</div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Created At:</div>
                            <div class="subscription-detail-value">${data.created_at || 'N/A'}</div>
                        </div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label">Updated At:</div>
                            <div class="subscription-detail-value">${data.updated_at || 'N/A'}</div>
                        </div>
                    </div>

                    <div class="subscription-detail-section">
                        <div class="subscription-detail-section-title">Receipt Data</div>
                        <div class="subscription-detail-row">
                            <div class="subscription-detail-label" style="width: 100%; margin-bottom: 10px;">Receipt Information:</div>
                            <div class="subscription-detail-value" style="width: 100%;">${receiptDataHtml}</div>
                        </div>
                    </div>
                `;
            }
        });

        // Handle filter dropdown changes
        document.querySelectorAll('.subscription-filter').forEach(select => {
            select.addEventListener('change', function() {
                const status = document.getElementById('statusFilter').value;
                const platform = document.getElementById('platformFilter').value;
                const type = document.getElementById('typeFilter').value;

                // Build URL with all filter parameters
                const params = new URLSearchParams();
                if (status !== 'all') params.append('status', status);
                if (platform !== 'all') params.append('platform', platform);
                if (type !== 'all') params.append('type', type);

                // Redirect to filtered URL
                const url = '{{ route("admin.subscriptions") }}' + (params.toString() ? '?' + params.toString() : '');
                window.location.href = url;
            });
        });
    </script>
@endsection
