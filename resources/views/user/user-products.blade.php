<!-- resources/views/user-products.blade.php -->

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

    div#products-table_length {
        display: none;
    }

    div#products-table_filter {
        transform: translate(-190px, -78px);
        font-family: "Inter";
        color: #696969 !important;
        font-size: 18.65px;
        position: relative;
    }

    .dataTables_wrapper .dataTables_filter input {
        border: 1px solid #E9EBF0 !important;
        border-radius: 10.66px !important;
        padding: 16px 15px !important;
        background-color: transparent;
        margin-left: -86px !important;
        transition: background-color 0.2s ease;
    }

    .dataTables_wrapper .dataTables_filter input:focus {
        outline: none;
        background-color: #fafbff;
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


    .dataTables_filter::after {
        content: "";
        position: absolute;
        right: 18px;
        top: 50%;
        transform: translateY(-50%);
        width: 16px;
        height: 16px;
        background: url("{{ asset('assets/images/dashboard/ProductSeachInputIcon.svg') }}") no-repeat center;
        background-size: contain;
        pointer-events: none; /* allows typing */
    }

    /* IE / Edge (legacy) */
    .dataTables_filter input::-ms-clear {
        display: none;
    }

    table#products-table {
        border-radius: 15.99px 15.99px 0 0;
        overflow: hidden;
        border-top: 2px solid #F2F2F2;
        border-right: 2px solid #F2F2F2;
        border-bottom: 1px solid #F2F2F2;
        border-left: 2px solid #F2F2F2;
        margin-bottom: 40px;
    }

    th.sorting {
        color: #333333;
        font-family: "Inter";
        font-size: 18.65px;
        font-weight: 500 !important;
        background: #EDEEF4 !important;
        border: none !important;
        padding: 16px 16px !important;
    }

    .data_table_user .table.dataTable tbody td {
        vertical-align: middle;
        font-family: "Inter";
        font-size: 18.65px;
        font-weight: 400 !important;
    }

    .table_image {
        border-radius: 8px;
    }

    .data_table_user .table.dataTable tbody td.btn_flex {
        width: -webkit-fill-available;
        justify-self: center;
        position: relative;
        gap: 0 !important;
    }

    .data_table_user .table.dataTable tbody td a,
    .data_table_user .table.dataTable tbody td form button {
        padding: 20px !important;
        background: transparent !important;
        border: none !important;
    }

    .data_table_user .table.dataTable tbody td a::after{
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

    .data_table_user .table.dataTable tbody td form button::after{
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

    .data_table_user .table.dataTable tbody td a i,
    .data_table_user .table.dataTable tbody td form button i{
        display: none;
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

    a#products-table_previous{
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

    a#products-table_next {
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

</style>

@section('dashboard-content')
    <div class="row">
        <div class="col-12">
            <div class="section_heading_flex">
                <h2>Products</h2>
                <a href="{{ Route('user.add.product') }}" class="btn btn-primary">Add Product</a>
            </div>
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            <div class="table-resposive data_table_user">
                <table id="products-table" class="table" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Price</th>
                            <th>Discounted Price</th>
                            <th>Quantity/Unit</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $key => $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <img src="{{ $product->product_image ? asset('storage/' . $product->product_image) : asset('assets/images/logo_bg.png') }}"
                                        alt="" class="table_image">
                                </td>
                                <td>{{ $product->title }}</td>
                                <td>${{ number_format($product->original_price, 2) }}</td>
                                <td>{{ $product->discounted_price ? '$' . number_format($product->discounted_price, 2) : 'N/A' }}
                                </td>
                                <td>{{ $product->quantity }}/{{ $product->unit_of_quantity }}</td>
                                <td class="btn_flex">

                                    <a href="{{ route('user.edit.product', $product->id) }}" class="btn btn-primary"><i
                                            class="fa-solid fa-pen-to-square"></i></a>
                                    <form action="{{ route('user.delete.product', $product->id) }}" method="POST"
                                        onsubmit="return confirm('Are you sure?');" class="mb-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"><i
                                                class="fa-solid fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center">No products available.</td>
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
        $('#products-table').DataTable({
            language: {
                emptyTable: "No products available" // Custom message instead of warning
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
