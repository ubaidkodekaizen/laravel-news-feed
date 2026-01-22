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

    table#adsTable{
        overflow: hidden;
        border-radius: 10px 10px 0 0;
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

    table#adsTable {
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

    .card-header.card_header_flex {
        display: flex;
        justify-content: space-between;
        align-items: center;
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

    /* Toggle switches in table */
    table .toggle {
        display: inline-block;
        vertical-align: middle;
    }

    table .toggle__label {
        margin: 0 !important;
    }

    /* Status toggle - Green for Active, Red for Inactive */
    .toggle-status-input:not(:checked) + .toggle__label {
        background-color: #dc3545 !important; /* Red for Inactive */
    }

    .toggle-status-input:checked + .toggle__label {
        background-color: #28a745 !important; /* Green for Active */
    }

    /* Featured toggle - Green for Featured, Red for Not Featured */
    .toggle-featured-input:not(:checked) + .toggle__label {
        background-color: #dc3545 !important; /* Red for Not Featured */
    }

    .toggle-featured-input:checked + .toggle__label {
        background-color: #28a745 !important; /* Green for Featured */
    }

    div#adsTable_filter {
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
    table.dataTable td form button,
    table.dataTable td .btn {
        font-family: 'Poppins';
        font-size: 14px;
        padding: 8px 18px;
        border-radius: 14px;
    }

    div#adsTable_filter label::after {
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
    table.dataTable thead > tr > td.sorting_desc_disabled:after,
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

    .nav-tabs .nav-link {
        border: none;
        border-bottom: 3px solid transparent;
        transition: all 0.3s ease;
        text-transform: uppercase;
    }
    .nav-tabs .nav-link:hover:not(.active) {
        border-bottom-color: #37488E !important;
        color: #37488E !important;
    }
    .nav-tabs .nav-link.active {
        border-bottom-color: #37488E;
        color: #ffffff !important;
        border-radius: 12px;
        background: #273572!important;
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

    .ad-media-preview {
        max-width: 150px;
        max-height: 80px;
        object-fit: cover;
        border-radius: 8px;
    }

    .ad-media-preview-video {
        max-width: 150px;
        max-height: 80px;
        border-radius: 8px;
    }

    .toggle__label::after {
        height: 26px !important;
    }

    @media (max-width: 1241px) {
        div#adsTable_wrapper .col-sm-12.col-md-6 {
            width: 100%;
            justify-items: center;
            margin-top: 10px;
        }
    }

    @media (max-width: 768px) {
        div#adsTable_wrapper div.dataTables_filter label::after {
            top: 73% !important;
        }
        .toggle__label::after {
            height: 26px !important;
            top: 1.5px !important;  
        }
        }

    @media (max-width: 879px) {
        div#adsTable_wrapper div.dataTables_filter label::after {
            top: 73% !important;
        }
    }
</style>
@section('content')
    <main class="main-content">
        @php
            $filter = $filter ?? 'all';
            $counts = $counts ?? [];
            $user = Auth::user();
            $isAdmin = $user && $user->role_id == 1;
            $canCreate = $isAdmin || ($user && $user->hasPermission('ads.create'));
            $canView = $isAdmin || ($user && $user->hasPermission('ads.view'));
            $canEdit = $isAdmin || ($user && $user->hasPermission('ads.edit'));
            $canDelete = $isAdmin || ($user && $user->hasPermission('ads.delete'));
            $canRestore = $isAdmin || ($user && $user->hasPermission('ads.restore'));
        @endphp
        @if(!$canView)
            @php
                abort(403, 'Unauthorized action.');
            @endphp
        @endif
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card" style="border: none;">
                        <div class="card-header card_header_flex">
                            <h4 class="card-title">Ads</h4>
                            @if($canCreate)
                            <a href="{{ route('admin.add.ad') }}" class="btn btn-primary">Add Ad</a>
                            @endif
                        </div>
                        <div class="card-body">
                            <!-- Tabs Navigation -->
                            <ul class="nav nav-tabs mb-4 pb-3" id="adTabs" role="tablist" style="border-bottom: 2px solid #E1E0E0;">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'active' ? 'active' : '' }}" 
                                       href="{{ route('admin.ads', ['filter' => 'active']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        Active <span class="badge bg-success">{{ $counts['active'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'inactive' ? 'active' : '' }}" 
                                       href="{{ route('admin.ads', ['filter' => 'inactive']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        Inactive <span class="badge bg-secondary">{{ $counts['inactive'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'featured' ? 'active' : '' }}" 
                                       href="{{ route('admin.ads', ['filter' => 'featured']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        Featured <span class="badge bg-warning">{{ $counts['featured'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item delete" role="presentation">
                                    <a class="nav-link {{ $filter === 'deleted' ? 'active' : '' }}" 
                                       href="{{ route('admin.ads', ['filter' => 'deleted']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        Deleted <span class="badge bg-danger">{{ $counts['deleted'] ?? 0 }}</span>
                                    </a>
                                </li>
                            </ul>
                            <style>
                                .nav-tabs .nav-link:hover:not(.active) {
                                    border-bottom-color: #37488E !important;
                                    color: #37488E !important;
                                }
                                
                                @media (max-width: 768px) {
                                    ul#adTabs {
                                        justify-content: center !important;
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
                            <table id="adsTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Media</th>
                                        <th>URL</th>
                                        <th>Featured</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($ads as $ad)
                                        <tr>
                                            <td>{{ $ad->id }}</td>
                                            <td>
                                                @if($ad->media)
                                                    @php
                                                        $mediaUrl = getImageUrl($ad->media);
                                                        $extension = strtolower(pathinfo(parse_url($mediaUrl, PHP_URL_PATH), PATHINFO_EXTENSION));
                                                        $isVideo = in_array($extension, ['mp4', 'mov', 'avi', 'webm']);
                                                    @endphp
                                                    @if($isVideo)
                                                        <video src="{{ $mediaUrl }}" class="ad-media-preview-video" controls>
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    @else
                                                        <img src="{{ $mediaUrl }}" alt="Ad Media" class="ad-media-preview">
                                                    @endif
                                                @else
                                                    <span class="text-muted">No media</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($ad->url)
                                                    <a href="{{ $ad->url }}" target="_blank" rel="noopener noreferrer">
                                                        {{ Str::limit($ad->url, 50) }}
                                                    </a>
                                                @else
                                                    <span class="text-muted">No URL</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($filter !== 'deleted')
                                                    @if($canEdit)
                                                        <div class="toggle">
                                                            <input type="checkbox" 
                                                                id="featured_{{ $ad->id }}" 
                                                                class="toggle__input toggle-featured-input" 
                                                                data-id="{{ $ad->id }}"
                                                                data-url="{{ route('admin.toggle.featured', $ad->id) }}"
                                                                {{ $ad->featured ? 'checked' : '' }}>
                                                            <label for="featured_{{ $ad->id }}" class="toggle__label mt-0">
                                                                <span>No</span>
                                                                <span>Yes</span>
                                                            </label>
                                                        </div>
                                                    @else
                                                        @if($ad->featured)
                                                            <span class="badge bg-warning">Yes</span>
                                                        @else
                                                            <span class="badge bg-secondary">No</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if($ad->featured)
                                                        <span class="badge bg-warning">Yes</span>
                                                    @else
                                                        <span class="badge bg-secondary">No</span>
                                                    @endif
                                                @endif
                                            </td>
                                            <td>
                                                @if($filter !== 'deleted')
                                                    @if($canEdit)
                                                        <div class="toggle">
                                                            <input type="checkbox" 
                                                                id="status_{{ $ad->id }}" 
                                                                class="toggle__input toggle-status-input" 
                                                                data-id="{{ $ad->id }}"
                                                                data-url="{{ route('admin.toggle.status', $ad->id) }}"
                                                                {{ $ad->status === 'Active' ? 'checked' : '' }}>
                                                            <label for="status_{{ $ad->id }}" class="toggle__label mt-0">
                                                                <span>Inactive</span>
                                                                <span>Active</span>
                                                            </label>
                                                        </div>
                                                    @else
                                                        @if($ad->status === 'Active')
                                                            <span class="badge bg-success">{{ $ad->status }}</span>
                                                        @else
                                                            <span class="badge bg-secondary">{{ $ad->status }}</span>
                                                        @endif
                                                    @endif
                                                @else
                                                    @if($ad->status === 'Active')
                                                        <span class="badge bg-success">{{ $ad->status }}</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ $ad->status }}</span>
                                                    @endif
                                                @endif
                                            </td>
                                            
                                            <td>
                                                @if($filter !== 'deleted')
                                                    @if($canEdit)
                                                    <a id="edit" href="{{ route('admin.edit.ad', $ad->id) }}"
                                                        class="btn btn-primary btn-sm"></a>
                                                    @endif
                                                    @if($canDelete)
                                                    <form action="{{ route('admin.delete.ad', $ad->id) }}" method="POST"
                                                        style="display:inline-block;" class="delete-ad-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-danger btn-sm" id="delete"></button>
                                                    </form>
                                                    @endif
                                                @else
                                                    @if($canRestore)
                                                    <form action="{{ route('admin.restore.ad', $ad->id) }}" method="POST"
                                                        style="display:inline-block;" class="restore-ad-form">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm" id="restoreBtn">
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
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="6" class="text-center">No ads found</td>
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
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTable
            $('#adsTable').DataTable({
                "order": [[0, "desc"]],
                "pageLength": 10,
                "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "All"]]
            });

            // Delete ad confirmation
            $('.delete-ad-form').on('submit', function(e) {
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

            // Restore ad confirmation
            $('.restore-ad-form').on('submit', function(e) {
                e.preventDefault();
                const form = $(this);
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This ad will be restored!",
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

            // Show error message if exists
            @if(session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error!',
                    text: '{{ session('error') }}',
                    showConfirmButton: true
                });
            @endif

            // Toggle featured status
            $(document).on('change', '.toggle-featured-input', function() {
                const checkbox = $(this);
                const url = checkbox.data('url');
                const isChecked = checkbox.is(':checked');
                const token = $('input[name="_token"]').first().val() || $('meta[name="csrf-token"]').attr('content');
                
                $.ajax({
                    url: url,
                    method: 'PATCH',
                    data: {
                        _token: token,
                        _method: 'PATCH'
                    },
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Featured status updated successfully!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        // Reload page after 2 seconds to update counts
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    },
                    error: function(xhr) {
                        // Revert checkbox state on error
                        checkbox.prop('checked', !isChecked);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Failed to update featured status',
                            showConfirmButton: true
                        });
                    }
                });
            });

            // Toggle status
            $(document).on('change', '.toggle-status-input', function() {
                const checkbox = $(this);
                const url = checkbox.data('url');
                const isChecked = checkbox.is(':checked');
                const token = $('input[name="_token"]').first().val() || $('meta[name="csrf-token"]').attr('content');
                
                $.ajax({
                    url: url,
                    method: 'PATCH',
                    data: {
                        _token: token,
                        _method: 'PATCH'
                    },
                    headers: {
                        'X-CSRF-TOKEN': token
                    },
                    success: function(response) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: 'Status updated successfully!',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        // Reload page after 2 seconds to update counts
                        setTimeout(function() {
                            location.reload();
                        }, 2000);
                    },
                    error: function(xhr) {
                        // Revert checkbox state on error
                        checkbox.prop('checked', !isChecked);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error!',
                            text: xhr.responseJSON?.message || 'Failed to update status',
                            showConfirmButton: true
                        });
                    }
                });
            });
        });
    </script>
@endsection
