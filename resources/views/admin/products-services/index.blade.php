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


    table#productsServicesTable {
        margin: 0 !important;
        overflow: hidden;
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

    div#productsServicesTable_filter {
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

    div#productsServicesTable_filter label::after {
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

    span.badge {
        font-family: "Inter";
        font-size: 18.65px;
        font-weight: 400 !important;
        background: #e4e7f18c !important;
        color: #000;
    }

    @media (max-width: 1241px) {
        div#productsServicesTable_wrapper .col-sm-12.col-md-6 {
            width: 100%;
            justify-items: center;
            margin-top: 10px;
        }
    }

    @media (max-width: 768px) {
        div#productsServicesTable_wrapper div.dataTables_filter label::after {
            top: 73% !important;
        }
        .card-title {
            text-align: center; 
        }
        }

    @media (max-width: 879px) {
        div#productsServicesTable_wrapper div.dataTables_filter label::after {
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
            $canView = $isAdmin || ($user && $user->hasPermission('products-services.view'));
            $canViewProduct = $isAdmin || ($user && $user->hasPermission('products.view'));
            $canViewService = $isAdmin || ($user && $user->hasPermission('services.view'));
            $canEditProduct = $isAdmin || ($user && $user->hasPermission('products.edit'));
            $canEditService = $isAdmin || ($user && $user->hasPermission('services.edit'));
            $canDeleteProduct = $isAdmin || ($user && $user->hasPermission('products.delete'));
            $canDeleteService = $isAdmin || ($user && $user->hasPermission('services.delete'));
            $canRestoreProduct = $isAdmin || ($user && $user->hasPermission('products.restore'));
            $canRestoreService = $isAdmin || ($user && $user->hasPermission('services.restore'));
        @endphp
        @if(!$canView)
            @php
                abort(403, 'Unauthorized action.');
            @endphp
        @endif
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Products/Services</h4>
                        </div>
                        <div class="card-body">
                            <!-- Tabs Navigation -->
                            <ul class="nav nav-tabs mb-4 pb-3" id="productsServicesTabs" role="tablist" style="border-bottom: 2px solid #E1E0E0;">
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'all' ? 'active' : '' }}" 
                                       href="{{ route('admin.products-services', ['filter' => 'all']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        All <span class="badge bg-secondary">{{ $counts['all'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'products' ? 'active' : '' }}" 
                                       href="{{ route('admin.products-services', ['filter' => 'products']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        Products <span class="badge bg-secondary">{{ $counts['products'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <a class="nav-link {{ $filter === 'services' ? 'active' : '' }}" 
                                       href="{{ route('admin.products-services', ['filter' => 'services']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        Services <span class="badge bg-secondary">{{ $counts['services'] ?? 0 }}</span>
                                    </a>
                                </li>
                                <li class="nav-item delete" role="presentation">
                                    <a class="nav-link {{ $filter === 'deleted' ? 'active' : '' }}" 
                                       href="{{ route('admin.products-services', ['filter' => 'deleted']) }}"
                                       style="color: #333; font-family: 'Inter'; font-weight: 500; padding: 12px 20px; border: none;">
                                        Deleted <span class="badge bg-danger redBadge">{{ $counts['deleted'] ?? 0 }}</span>
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

                            .nav-tabs .nav-item.delete .badge{
                                color: #ff0000ff;
                            }
                            @media (max-width: 768px) {
                                    ul#productsServicesTabs {
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
                            <table id="productsServicesTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Type</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Owner</th>
                                        <th>Email</th>
                                        <th>Price</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($items as $item)
                                        <tr>
                                            <td>{{ $item->id }}</td>
                                            <td>
                                                @php
                                                    // Determine type: if item_type is set use it, otherwise check if it has duration (service) or quantity (product)
                                                    $itemType = $item->item_type ?? (isset($item->duration) ? 'service' : 'product');
                                                @endphp
                                                <span class="badge bg-{{ $itemType === 'service' ? 'info' : 'success' }}">
                                                    {{ ucfirst($itemType) }}
                                                </span>
                                            </td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->category ?? 'N/A' }}</td>
                                            <td>
                                                @if($item->user)
                                                    {{ trim($item->user->first_name . ' ' . $item->user->last_name) ?: 'N/A' }}
                                                @else
                                                    <span style="color: #999;">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($item->user && $item->user->email)
                                                    {{ $item->user->email }}
                                                @else
                                                    <span style="color: #999;">N/A</span>
                                                @endif
                                            </td>
                                            <td>
                                                ${{ number_format($item->original_price, 2) }}
                                            </td>
                                            <td>
                                                @php
                                                    // Determine type: if item_type is set use it, otherwise check if it has duration (service) or quantity (product)
                                                    $itemType = $item->item_type ?? (isset($item->duration) ? 'service' : 'product');
                                                    $canView = ($itemType === 'service' && $canViewService) || ($itemType === 'product' && $canViewProduct);
                                                    $canEdit = ($itemType === 'service' && $canEditService) || ($itemType === 'product' && $canEditProduct);
                                                    $canDelete = ($itemType === 'service' && $canDeleteService) || ($itemType === 'product' && $canDeleteProduct);
                                                    $canRestore = ($itemType === 'service' && $canRestoreService) || ($itemType === 'product' && $canRestoreProduct);
                                                @endphp
                                                @if($filter === 'deleted')
                                                    @if($canRestore)
                                                    <form action="{{ $itemType === 'service' ? route('admin.restore.service', $item->id) : route('admin.restore.product', $item->id) }}" 
                                                          method="POST" 
                                                          style="display:inline-block;" 
                                                          class="restore-item-form">
                                                        @csrf
                                                        <button type="submit" class="btn btn-success btn-sm">Restore</button>
                                                    </form>
                                                    @endif
                                                @else
                                                    @if($canView)
                                                    <a href="{{ $itemType === 'service' ? route('admin.view.service', $item->id) : route('admin.view.product', $item->id) }}" 
                                                       class="btn btn-primary btn-sm">
                                                        <svg width="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <title>View</title>
                                                    <path d="M20.188 10.9343C20.5762 11.4056 20.7703 11.6412 20.7703 12C20.7703 12.3588 20.5762 12.5944 20.188 13.0657C18.7679 14.7899 15.6357 18 12 18C8.36427 18 5.23206 14.7899 3.81197 13.0657C3.42381 12.5944 3.22973 12.3588 3.22973 12C3.22973 11.6412 3.42381 11.4056 3.81197 10.9343C5.23206 9.21014 8.36427 6 12 6C15.6357 6 18.7679 9.21014 20.188 10.9343Z" fill="#213bae" fill-opacity="0.14"/>
                                                    <circle cx="12" cy="12" r="3" fill="#273572"/>
                                                    </svg>
                                                    </a>
                                                    @endif
                                                    @if($canEdit)
                                                    <a id="edit" href="{{ $itemType === 'service' ? route('admin.edit.service', $item->id) : route('admin.edit.product', $item->id) }}" 
                                                       class="btn btn-warning btn-sm"></a>
                                                    @endif
                                                    @if($canDelete)
                                                    <form action="{{ $itemType === 'service' ? route('admin.delete.service', $item->id) : route('admin.delete.product', $item->id) }}" 
                                                          method="POST" 
                                                          style="display:inline-block;" 
                                                          class="delete-item-form">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button id="delete" type="submit" class="btn btn-danger btn-sm"></button>
                                                    </form>
                                                    @endif
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No Products/Services found</td>
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
        $('#productsServicesTable').DataTable();

        // Delete confirmation with SweetAlert
        $('.delete-item-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const itemType = form.closest('tr').find('.badge').text().trim();
            
            Swal.fire({
                title: 'Are you sure?',
                text: `You want to delete this ${itemType}? This action cannot be undone!`,
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

        // Restore confirmation with SweetAlert
        $('.restore-item-form').on('submit', function(e) {
            e.preventDefault();
            const form = $(this);
            const itemType = form.closest('tr').find('.badge').text().trim();
            
            Swal.fire({
                title: 'Restore ' + itemType + '?',
                text: `This ${itemType} will be restored and become active again.`,
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
    });
</script>
@endsection
