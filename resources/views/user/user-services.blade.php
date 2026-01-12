<!-- resources/views/user-Services.blade.php -->

@extends('layouts.dashboard-layout')

<style>

    .section_heading_flex a{
        background: var(--primary);
        border-color: var(--primary);
        font-family: "poppins";
        font-weight: 300;
        padding: 16px 30px;
        border-radius: 10.66px;
    }

    .data_table_user table tbody td.btn_flex {
        width: -webkit-fill-available;
        justify-self: center;
        flex-wrap: nowrap;
        position: relative;
        gap: 0 !important;
    }

    div#services-table_filter {
        transform: translate(-175px, -78px);
        font-family: "Inter";
        color: #696969 !important;
        font-size: 18.65px;
        position: relative;
    }

    div#services-table_filter input {
        border: 1px solid #E9EBF0 !important;
        border-radius: 10.66px !important;
        padding: 16px 15px !important;
        background-color: transparent;
        margin-left: -86px !important;
        transition: background-color 0.2s ease;
    }

    .dataTables_filter::after {
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

    .dataTables_wrapper .dataTables_filter input:focus {
        outline: none;
        background-color: #fafbff !important;
    }

    /* Active (when text exists) */
    .dataTables_wrapper .dataTables_filter input.has-text {
        background-color: #fafbff;
    }

    .dataTables_filter input::-webkit-search-cancel-button {
        -webkit-appearance: none;
        display: none;
    }

    /* Firefox */
    .dataTables_filter input[type="search"] {
        appearance: none;
    }

    .data_table_user table tbody td a::after {
        content: "";
        position: absolute;
        left: 24px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        background: url("{{ asset('assets/images/dashboard/productTableEditIcon.svg') }}") no-repeat center;
        background-size: contain;
    }

    .data_table_user table tbody td form button::after{
        content: "";
        position: absolute;
        /* right: 24px; */
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        background: url("{{ asset('assets/images/dashboard/productTableDeleteIcon.svg') }}") no-repeat center;
        background-size: contain;
    }

    .data_table_user table tbody td a i,
    .data_table_user .table tbody td form button i,
    .fa-solid.fa-trash,
    div#services-table_length{
        display: none;
    }

    .data_table_user table tbody td a,
    .data_table_user table tbody td form button {
        position: relative;
        padding: 20px !important;
        background: transparent !important;
        border: none !important;
    }

    table.dataTable.no-footer {
        border-radius: 15.99px 15.99px 0 0;
        overflow: hidden;
        border-top: 2px solid #F2F2F2;
        border-right: 2px solid #F2F2F2;
        border-bottom: 2px solid #F2F2F2 !important;
        border-left: 2px solid #F2F2F2;
        margin-bottom: 40px;
        /* overflow-x: scroll; */
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
    table.dataTable thead > tr > td.sorting_desc_disabled:before{
        content: "" !important;
        width: 16px;
        height: 16px;
        background: url("{{ asset('assets/images/dashboard/productTableHeadUpChevronIcon.svg') }}") no-repeat center !important;
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
    table.dataTable thead > tr > td.sorting_desc_disabled:after{
        content: "" !important;
        width: 16px;
        height: 16px;
        background: url("{{ asset('assets/images/dashboard/productTableHeadDownChevronIcon.svg') }}") no-repeat center !important;
        background-size: contain;
    }

    table.dataTable.dtr-inline.collapsed>tbody>tr>td.dtr-control:before, table.dataTable.dtr-inline.collapsed>tbody>tr>th.dtr-control:before{
        content: "" !important;
        width: 16px;
        height: 16px;
        background: url("/assets/images/dashboard/productTableHeadDownChevronIcon.svg") no-repeat center !important;
    }

     table.dataTable.dtr-inline.collapsed>tbody>tr.parent>td.dtr-control:before, table.dataTable.dtr-inline.collapsed>tbody>tr.parent>th.dtr-control:before{
        content: "" !important;
        width: 16px;
        height: 16px;
        background: url("/assets/images/dashboard/productTableHeadDownChevronIcon.svg") no-repeat center !important;
        rotate: -90deg;
    }

    /* .table-resposive.data_table_user::-webkit-scrollbar {
        height: 8px;
        box-shadow: none !important;
    }



    .table-resposive.data_table_user::-webkit-scrollbar-thumb {
        background: var(--primary);
        border-radius: 10px;
    } */

    .data_table_user .btn_flex {
        height: 100px !important;
        margin-bottom: -1px !important;
    }

    .table-resposive.data_table_user table{
        margin-bottom: 40px !important;
    }

    .table-resposive.data_table_user table thead tr th {
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

    table.dataTable tbody tr {
        background-color: transparent;
    }

    .data_table_user div#services-table_wrapper #services-table tbody tr td{
        vertical-align: middle;
        font-family: "Inter";
        font-size: 18.65px;
        font-weight: 400 !important;
        height: 100px;
        box-shadow: none;
        text-wrap-mode: nowrap;
    }

    .table_image {
        max-height: 80px !important;
        object-fit: cover;
        object-position: center;
        border-radius: 8px;
        max-width: 111px;
        width: 100%;
    }

    .dataTables_wrapper .dataTables_info {
        font-size: 17.33px !important;
        color: #696969 !important;
        font-family: "Inter", sans-serif;
        font-optical-sizing: auto;
        font-style: normal;
        font-weight: 300;
    }

    .dataTables_wrapper .dataTables_info {
        font-size: 17.33px !important;
        color: #696969 !important;
        font-family: "Inter", sans-serif;
        font-optical-sizing: auto;
        font-style: normal;
        font-weight: 300;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current{
        border: 1px solid #37488e !important;
        color: #37488e !important;
        background: #37488e03 !important;
        font-weight: 500 !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button{

        min-width: 40px !important;
        height: 40px !important;
        border: 1px solid #e2e2e2 !important;
        background: #fff !important;
        color: #333 !important;
        border-radius: 8px !important;
        font-size: 14px !important;
        cursor: pointer !important;
        transition: all 0.2s !important;
    }

    a#services-table_previous{
        color: #696969b5 !important;
        font-size: 17.33px !important;
        font-family: "Inter", sans-serif !important;
        font-optical-sizing: auto !important;
        font-style: normal !important;
        font-weight: 300 !important;
        letter-spacing: 0.5px !important;
        border: none !important;
        background: none !important;
    }

    a#services-table_next {
        color: #696969 !important;
        font-size: 17.33px !important;
        font-family: "Inter", sans-serif !important;
        font-optical-sizing: auto !important;
        font-style: normal !important;
        font-weight: 300 !important;
        letter-spacing: 0.5px !important;
        border: none !important;
        background: none !important;
    }

    @media (max-width: 1080px) {


    /* div#products-table_filter {
        transform: translate(0px, -10px);
        margin: auto !important;
        width: 100%;
    } */

    .section_heading_flex h2 {
        font-size: 24px;
    }

    .section_heading_flex a {
        font-size: 14px;
        padding: 10px 26px;
    }

    div#services-table_filter label,
    .dataTables_wrapper .dataTables_filter input {
        width: 100% !important;
    }

    div#services-table_filter {
        transform: translate(0px, -10px);
        margin: auto !important;
        width: 100%;
    }

    div#services-table_filter label,
    .dataTables_wrapper .dataTables_filter input {
        width: 99% !important;
        margin: 0 10px 0 15px;
        font-size: 16px;
    }

    table.dataTable>tbody>tr.child ul.dtr-details {
            width: 100%;
        }

        table.dataTable>tbody>tr.child ul.dtr-details>li:last-child {
            display: flex;
            align-items: center;
        }

        table.dataTable>tbody>tr.child ul.dtr-details>li:last-child .dtr-data {
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .data_table_user .table.dataTable tbody td a, .data_table_user .table.dataTable tbody td form button {
            position: relative;
        }

    .table-resposive.data_table_user table thead tr th,
    .data_table_user table tbody tr td {
        font-size: 14.65px !important;
    }

    /* .dataTables_filter::after {
        right: 28px;
    } */

    .data_table_user table tbody td.btn_flex a::after {
        left: 0px;
    }

}

@media (max-width: 768px) {
    .data_table_user table tbody td form button::after {
        top: 36%;
    }

    div#services-table_filter input {
        margin-left: -86px !important;
        margin: 0;
    } 
}



</style>



@section('dashboard-content')
    <div class="row">
        <div class="col-12">
            <div class="section_heading_flex">
                <h2>Services</h2>
                <a href="{{ Route('user.add.service') }}" class="btn btn-primary">Add Service</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-resposive data_table_user">
                <table id="services-table" class="display" style="width:100%">

                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Category</th>
                                <th>Price</th>
                                <th>Subscription</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $service)
                                <tr>
                                    <td>{{ $service->id }}</td>
                                    <td>
                                        <img src="{{ $service->service_image ? getImageUrl($service->service_image) : asset('assets/images/logo_bg.png') }}"
                                            alt="Service Image" class="table_image">
                                    </td>
                                    <td>{{ $service->title }}</td>
                                    <td>{{ $service->category ?? 'N/A' }}</td>
                                    <td>
                                        ${{ number_format($service->original_price, 2) }}
                                    </td>
                                    <td>{{ $service->duration }}</td>

                                    <td class="btn_flex">

                                        <a href="{{ route('user.edit.service', $service->id) }}" class="btn btn-primary">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('user.delete.service', $service->id) }}" method="POST" class="mb-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center">No services found.</td>
                                </tr>
                            @endforelse
                        </tbody>


                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
   <script>
    jQuery(document).ready(function($) {
        $.fn.dataTable.ext.errMode = 'none'; // Suppress DataTable warnings
        $('#services-table').DataTable({
            responsive: true,
            language: {
                emptyTable: "No services available" // Custom message instead of warning
            }
        });
    });
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const observer = new MutationObserver(() => {
        const searchInput = document.querySelector(
            '.dataTables_wrapper .dataTables_filter input'
        );

        if (searchInput) {
            // Avoid duplicate listeners
            if (searchInput.dataset.listenerAdded) return;
            searchInput.dataset.listenerAdded = "true";

            searchInput.addEventListener('input', function () {
                if (this.value.trim() !== '') {
                    this.classList.add('has-text');
                } else {
                    this.classList.remove('has-text');
                }
            });
        }
    });

    observer.observe(document.body, { childList: true, subtree: true });
});
</script>

@endsection
