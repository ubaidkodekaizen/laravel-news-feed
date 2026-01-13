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

    table#schedulerLogsTable {
        margin-top: 0 !important;
        margin-bottom: 0 !important;
    }

    .card-title {
        border-radius: 0;
        color: #333 !important;
        margin: 0;
        font-size: 28px;
        font-family: "Inter";
        font-weight: 500;
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

    div#schedulerLogsTable_filter {
        position: relative;
    }

    div.dataTables_wrapper div.dataTables_filter label,
    div.dataTables_wrapper div.dataTables_length label {
        font-family: 'Poppins';
        font-size: 18px;
    }

    div#schedulerLogsTable_filter label::after {
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

    th.sorting, th.sorting_disabled {
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
        color:  #37488E;
    }

    .card {
        border: 0 !important;
    }

    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
    }
    .nav-tabs .nav-link:hover {
        border-bottom-color: #37488E;
        color: #37488E !important;
    }
    .nav-tabs .nav-link.active {
        border-bottom-color: #37488E;
        color: #37488E !important;
        background-color: transparent;
    }
    .nav-tabs .badge {
        margin-left: 5px;
        font-size: 12px;
        padding: 4px 8px;
    }

    .status-badge {
        padding: 6px 12px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        display: inline-block;
    }
    .status-success {
        background: #d4edda;
        color: #155724;
    }
    .status-failed {
        background: #f8d7da;
        color: #721c24;
    }
    .status-partial {
        background: #fff3cd;
        color: #856404;
    }

    a.btn.btn-primary.btn-sm,
    button.btn.btn-primary.btn-sm,
    button.btn.btn-danger.btn-sm,
    button.btn.btn-success.btn-sm,
    table.dataTable td form button {
        font-family: 'Poppins';
        font-size: 14px;
        padding: 8px 18px;
        border-radius: 14px;
        text-decoration: none;
        display: inline-block;
        margin-right: 5px;
        border: none;
        cursor: pointer;
        font-weight: 500;
        line-height: 1.5;
    }
    
    a.btn.btn-primary.btn-sm,
    button.btn.btn-primary.btn-sm {
        background-color: var(--primary);
        border-color: var(--primary);
        color: white;
    }
    a.btn.btn-primary.btn-sm:hover,
    button.btn.btn-primary.btn-sm:hover {
        background-color: #2d3a6b;
        border-color: #2d3a6b;
        color: white;
    }
    
    button.btn.btn-danger.btn-sm {
        background-color: #dc3545;
        color: white;
    }
    button.btn.btn-danger.btn-sm:hover {
        background-color: #c82333;
        color: white;
    }
    
    button.btn.btn-success.btn-sm {
        background-color: #28a745;
        color: white;
    }
    button.btn.btn-success.btn-sm:hover {
        background-color: #218838;
        color: white;
    }
</style>
@section('content')
    <main class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Scheduler Logs</h4>
                        </div>
                        <div class="card-body">
                            <!-- Tabs Navigation -->
                            <ul class="nav nav-tabs mb-4" id="schedulerLogsTabs" role="tablist" style="border-bottom: 2px solid #E1E0E0;">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}" 
                                       href="{{ route('admin.scheduler-logs', ['filter' => 'all']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        ALL <span class="badge bg-secondary">{{ $counts['all'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'deleted' ? 'active' : '' }}" 
                                       href="{{ route('admin.scheduler-logs', ['filter' => 'deleted']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        DELETED <span class="badge bg-danger">{{ $counts['deleted'] ?? 0 }}</span>
                                    </a>
                                </li>
                            </ul>
                            <style>
                                .nav-tabs .nav-link {
                                    border: none;
                                    border-bottom: 3px solid transparent;
                                    transition: all 0.3s ease;
                                }
                                .nav-tabs .nav-link:hover {
                                    border-bottom-color: #37488E;
                                    color: #37488E !important;
                                }
                                .nav-tabs .nav-link.active {
                                    border-bottom-color: #37488E;
                                    color: #37488E !important;
                                    background-color: transparent;
                                }
                                .nav-tabs .badge {
                                    margin-left: 5px;
                                    font-size: 12px;
                                    padding: 4px 8px;
                                }
                            </style>
                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                                </div>
                            @endif
                            <table id="schedulerLogsTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Scheduler</th>
                                        <th>Status</th>
                                        <th>Processed</th>
                                        <th>Updated</th>
                                        <th>Failed</th>
                                        <th>Ran At</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($logs as $log)
                                    <tr>
                                        <td>{{ $log->id }}</td>
                                        <td>{{ $log->scheduler }}</td>
                                        <td>
                                            <span class="status-badge status-{{ $log->status }}">
                                                {{ strtoupper($log->status) }}
                                            </span>
                                        </td>
                                        <td>{{ $log->records_processed ?? 0 }}</td>
                                        <td>{{ $log->records_updated ?? 0 }}</td>
                                        <td>{{ $log->records_failed ?? 0 }}</td>
                                        <td>{{ \Carbon\Carbon::parse($log->ran_at)->format('d M Y h:i A') }}</td>
                                        <td>
                                            <a href="{{ route('admin.scheduler-logs.show', $log->id) }}" class="btn btn-primary btn-sm" title="View Details">
                                                View
                                            </a>
                                            @if($filter !== 'deleted')
                                            <form action="{{ route('admin.scheduler-logs.destroy', $log->id) }}" method="POST" style="display:inline-block;" class="delete-log-form" data-id="{{ $log->id }}">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="btn btn-danger btn-sm delete-log-btn">Delete</button>
                                            </form>
                                            @else
                                            <form action="{{ route('admin.scheduler-logs.restore', $log->id) }}" method="POST" style="display:inline-block;" class="restore-log-form" data-id="{{ $log->id }}">
                                                @csrf
                                                <button type="button" class="btn btn-success btn-sm restore-log-btn">Restore</button>
                                            </form>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No scheduler logs found</td>
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
        // Initialize DataTable
        var table = $('#schedulerLogsTable').DataTable({
            "paging": true,
            "pageLength": 25,
            "searching": true,
            "ordering": true,
            "info": true,
            "order": [[0, "desc"]],
            "language": {
                "search": "",
                "searchPlaceholder": "Search logs...",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "Showing 0 to 0 of 0 entries",
                "infoFiltered": "(filtered from _MAX_ total entries)",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                }
            }
        });

        // Delete button click with SweetAlert
        $(document).on('click', '.delete-log-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const form = $(this).closest('form');
            
            Swal.fire({
                title: 'Are you sure?',
                text: 'You want to delete this scheduler log? This action cannot be undone!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Restore button click with SweetAlert
        $(document).on('click', '.restore-log-btn', function(e) {
            e.preventDefault();
            e.stopPropagation();
            const form = $(this).closest('form');
            
            Swal.fire({
                title: 'Restore Scheduler Log?',
                text: 'This scheduler log will be restored and become visible again.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#28a745',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, restore it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Show success message if exists
        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'Success!',
                text: '{{ session('success') }}',
                showConfirmButton: false,
                timer: 3000
            });
        @endif
    });
</script>
@endsection
