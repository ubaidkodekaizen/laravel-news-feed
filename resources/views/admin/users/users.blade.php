@extends('admin.layouts.main')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
<style>
    body {
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

    .row.dt-row .col-sm-12 {
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
        margin-top: 0 !important;
        margin-bottom: 0 !important;
        /* border-radius: 15.99px 15.99px 0 0;
        overflow: hidden;
        border-top: 2px solid #F2F2F2;
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

    .card-header.card_header_flex a {
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
        width: 64.8%;
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

    form #sendResetLinkBtn {
        height: 58px;
        width: 48px;
        align-content: center;
        background: transparent !important;
        border: none !important;
        padding: 0 !important;
        position: relative;
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
    table.dataTable thead>tr>th.sorting:before,
    table.dataTable thead>tr>th.sorting_asc:before,
    table.dataTable thead>tr>th.sorting_desc:before,
    table.dataTable thead>tr>th.sorting_asc_disabled:before,
    table.dataTable thead>tr>th.sorting_desc_disabled:before,
    table.dataTable thead>tr>td.sorting:before,
    table.dataTable thead>tr>td.sorting_asc:before,
    table.dataTable thead>tr>td.sorting_desc:before,
    table.dataTable thead>tr>td.sorting_asc_disabled:before,
    table.dataTable thead>tr>td.sorting_desc_disabled:before {
        content: "" !important;
        width: 16px;
        height: 16px;
        background: url("/assets/images/dashboard/productTableHeadUpChevronIcon.svg") no-repeat center !important;
        background-size: contain;
    }

    th:after,
    td:after,
    table.dataTable thead>tr>th.sorting:after,
    table.dataTable thead>tr>th.sorting_asc:after,
    table.dataTable thead>tr>th.sorting_desc:after,
    table.dataTable thead>tr>th.sorting_asc_disabled:after,
    table.dataTable thead>tr>th.sorting_desc_disabled:after,
    table.dataTable thead>tr>td.sorting:after,
    table.dataTable thead>tr>td.sorting_asc:after,
    table.dataTable thead>tr>td.sorting_desc:after,
    table.dataTable thead>tr>td.sorting_asc_disabled:after,
    table.dataTable thead>tr>td.sorting_desc_disabled:after {
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

    .pagination .page-item.active .page-link {
        border: 1.33px solid #37488E;
        background: #37488E14;
        color: #37488E;
    }
</style>
@section('content')
    <main class="main-content">
        @php
            $filter = $filter ?? 'all';
            $counts = $counts ?? [];
            $user = Auth::user();
            $isAdmin = $user && $user->role_id == 1;
            $canCreate = $isAdmin || ($user && $user->hasPermission('users.create'));
            $canView =
                $isAdmin || (($user && $user->hasPermission('users.view')) || $user->hasPermission('users.profile'));
            $canEdit = $isAdmin || ($user && $user->hasPermission('users.edit'));
            $canDelete = $isAdmin || ($user && $user->hasPermission('users.delete'));
            $canRestore = $isAdmin || ($user && $user->hasPermission('users.restore'));
            $canSendReset = $isAdmin || ($user && $user->hasPermission('users.password.reset'));
        @endphp
        @if (!$canView)
            @php
                abort(403, 'Unauthorized action.');
            @endphp
        @endif
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="border: none;">
                        <div class="card-header card_header_flex">
                            <h4 class="card-title">User Management</h4>
                            @if ($canCreate)
                                <a href="{{ Route('admin.add.user') }}" class="btn btn-primary">Add User</a>
                            @endif
                        </div>
                        <div class="card-body">
                            <!-- Tabs Navigation -->
                            <ul class="nav nav-tabs mb-4 pb-3" id="userTabs" role="tablist"
                                style="border-bottom: 2px solid #F2F2F2;">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}"
                                        href="{{ route('admin.users', ['filter' => 'all']) }}"
                                        style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        All <span class="badge bg-secondary">{{ $counts['all'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'web' ? 'active' : '' }}"
                                        href="{{ route('admin.users', ['filter' => 'web']) }}"
                                        style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        WEB <span class="badge bg-secondary">{{ $counts['web'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'google' ? 'active' : '' }}"
                                        href="{{ route('admin.users', ['filter' => 'google']) }}"
                                        style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        GOOGLE <span class="badge bg-secondary">{{ $counts['google'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'apple' ? 'active' : '' }}"
                                        href="{{ route('admin.users', ['filter' => 'apple']) }}"
                                        style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        APPLE <span class="badge bg-secondary">{{ $counts['apple'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'amcob' ? 'active' : '' }}"
                                        href="{{ route('admin.users', ['filter' => 'amcob']) }}"
                                        style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        AMCOB <span class="badge bg-secondary">{{ $counts['amcob'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item delete" role="presentation">
                                    <a class="nav-link {{ $filter === 'deleted' ? 'active' : '' }}"
                                        href="{{ route('admin.users', ['filter' => 'deleted']) }}"
                                        style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        Deleted <span class="badge bg-danger">{{ $counts['deleted'] ?? 0 }}</span>
                                    </a>
                                </li>
                            </ul>
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

                                .nav-tabs .nav-item.delete .nav-link.active .badge {
                                    color: #ff0000ff !important;
                                }

                                .nav-tabs .badge {
                                    color: #000;
                                    margin: 0px 0px 0px 0px;
                                    font-size: 16px;
                                    font-family: "Inter";
                                    font-weight: 400;
                                    background: transparent !important;
                                }

                                .nav-tabs .nav-item.delete .badge {
                                    color: #ff0000ff;
                                }

                                @media (max-width: 768px) {
                                    ul#userTabs {
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
                                        <th>First Name</th>
                                        <th>Last Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($users as $user)
                                        <tr>
                                            <td>{{ $user->id }}</td>
                                            <td>{{ $user->first_name }}</td>
                                            <td>{{ $user->last_name }}</td>
                                            <td>{{ $user->email }}</td>
                                            <td>{{ $user->phone }}</td>

                                            <td>
                                                @if ($canView)
                                                    <a href="{{ route('admin.user.profile', ['id' => $user->id]) }}"
                                                        class="btn btn-primary btn-sm">
                                                        <svg width="30px" viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <title>View</title>
                                                            <path
                                                                d="M20.188 10.9343C20.5762 11.4056 20.7703 11.6412 20.7703 12C20.7703 12.3588 20.5762 12.5944 20.188 13.0657C18.7679 14.7899 15.6357 18 12 18C8.36427 18 5.23206 14.7899 3.81197 13.0657C3.42381 12.5944 3.22973 12.3588 3.22973 12C3.22973 11.6412 3.42381 11.4056 3.81197 10.9343C5.23206 9.21014 8.36427 6 12 6C15.6357 6 18.7679 9.21014 20.188 10.9343Z"
                                                                fill="#213bae" fill-opacity="0.14" />
                                                            <circle cx="12" cy="12" r="3" fill="#273572" />
                                                        </svg>
                                                    </a>
                                                @endif
                                                @if ($filter !== 'deleted')
                                                    @if ($canEdit)
                                                        <a id="edit" href="{{ route('admin.user.edit', $user->id) }}"
                                                            class="btn btn-warning btn-sm" title="Edit"></a>
                                                    @endif
                                                    @if ($canDelete)
                                                        <form action="{{ route('admin.delete.user', $user->id) }}"
                                                            method="POST" style="display:inline-block;"
                                                            class="delete-user-form">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button id="delete" type="submit"
                                                                class="btn btn-danger btn-sm" title="Delete"></button>
                                                        </form>
                                                    @endif
                                                @else
                                                    @if ($canRestore)
                                                        <form action="{{ route('admin.restore.user', $user->id) }}"
                                                            method="POST" style="display:inline-block;"
                                                            class="restore-user-form">
                                                            @csrf
                                                            <button id="restoreBtn" type="submit"
                                                                class="btn btn-success btn-sm" title="Restore">
                                                                <svg width="30px" height="30px" viewBox="0 0 40 40"
                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                    <path
                                                                        d="M10 16.682l5.69 5.685 1.408-1.407-3.283-3.28h10.131c1.147 0 2.19.467 2.943 1.222a4.157 4.157 0 011.225 2.946 4.18 4.18 0 01-4.168 4.168h-5.628V28h5.522c3.387 0 6.16-2.77 6.16-6.157a6.117 6.117 0 00-1.81-4.343 6.143 6.143 0 00-4.35-1.805H13.815l3.283-3.285L15.69 11 10 16.682z"
                                                                        fill="#273572" fill-rule="nonzero" />
                                                                </svg>
                                                            </button>
                                                        </form>
                                                    @endif
                                                @endif
                                                @if ($canSendReset)
                                                    <form action="{{ route('admin.reset.link') }}" method="POST"
                                                        style="display:inline-block;" class="reset-link-form">
                                                        @csrf
                                                        <input type="hidden" name="email" value="{{ $user->email }}">
                                                        <button id="sendResetLinkBtn" type="submit"
                                                            class="btn btn-info btn-sm" title="Send Reset Link">
                                                            <svg width="24px" height="24px" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M8 8c-2.248 0-4 1.752-4 4s1.752 4 4 4h2a1 1 0 1 1 0 2H8c-3.352 0-6-2.648-6-6s2.648-6 6-6h2a1 1 0 1 1 0 2H8zm5-1a1 1 0 0 1 1-1h2c3.352 0 6 2.648 6 6s-2.648 6-6 6h-2a1 1 0 1 1 0-2h2c2.248 0 4-1.752 4-4s-1.752-4-4-4h-2a1 1 0 0 1-1-1zm-6 5a1 1 0 0 1 1-1h8a1 1 0 1 1 0 2H8a1 1 0 0 1-1-1z"
                                                                    fill="#273572" />
                                                            </svg>
                                                        </button>
                                                    </form>
                                                @endif
                                            </td>
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
@section('scripts')
    <script>
        $(document).ready(function() {
            // Delete user confirmation
            $('.delete-user-form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.off('submit').submit();
                    }
                });
            });

            // Restore user confirmation
            $('.restore-user-form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                Swal.fire({
                    title: 'Restore User?',
                    text: "This user will be restored and become active again.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, restore it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.off('submit').submit();
                    }
                });
            });

            // Reset link confirmation
            $('.reset-link-form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                Swal.fire({
                    title: 'Send Reset Link?',
                    text: "A password reset link will be sent to the user's email.",
                    icon: 'info',
                    showCancelButton: true,
                    confirmButtonColor: '#17a2b8',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, send it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.off('submit').submit();
                    }
                });
            });

            // Show success message if exists
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 3000
                });
            @endif

            // Show error message if exists
            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true
                });
            @endif
        });
    </script>
@endsection
