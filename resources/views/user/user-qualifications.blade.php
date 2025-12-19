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

    div#qualifications-table_length {
        display: none;
    }

    div#qualifications-table_filter {
        transform: translate(-226px, -78px);
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

    table#qualifications-table {
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

    .data_table_user #qualifications-table tbody td {
        vertical-align: middle;
        font-family: "Inter";
        font-size: 18.65px;
        font-weight: 400 !important;
    }

    .table_image {
        border-radius: 8px;
    }

    .data_table_user #qualifications-table tbody td.btn_flex {
        width: -webkit-fill-available;
        justify-self: center;
        position: relative;
        gap: 0 !important;
        background: #FFF !important;
        box-shadow: none  !important;
    }

    table.dataTable.display > tbody > tr.odd > .sorting_1, table.dataTable.order-column.stripe > tbody > tr.odd > .sorting_1,
    table.dataTable.display > tbody > tr:first-child > td {
         background: #FFF !important;
        box-shadow: none  !important;
    }

    .data_table_user #qualifications-table tbody td a,
    .data_table_user #qualifications-table tbody td form button {
        padding: 20px !important;
        background: transparent !important;
        border: none !important;
        position: relative;
    }

    .data_table_user #qualifications-table tbody td a::after{
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

    .data_table_user #qualifications-table tbody td form button::after{
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

    .data_table_user #qualifications-table tbody td a i,
    .data_table_user #qualifications-table tbody td form button i{
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

    a#qualifications-table_previous{
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

    a#qualifications-table_next {
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

   #qualifications-table thead > tr > th.sorting:before,
   #qualifications-table thead > tr > th.sorting_asc:before,
   #qualifications-table thead > tr > th.sorting_desc:before,
   #qualifications-table thead > tr > th.sorting_asc_disabled:before,
   #qualifications-table thead > tr > th.sorting_desc_disabled:before,
   #qualifications-table thead > tr > td.sorting:before,
   #qualifications-table thead > tr > td.sorting_asc:before,
   #qualifications-table thead > tr > td.sorting_desc:before,
   #qualifications-table thead > tr > td.sorting_asc_disabled:before,
   #qualifications-table thead > tr > td.sorting_desc_disabled:before{
        content: "" !important;
        width: 16px;
        height: 16px;
        background: url("{{ asset('assets/images/dashboard/productTableHeadUpChevronIcon.svg') }}") no-repeat center !important;
        background-size: contain;
    }

   #qualifications-table thead > tr > th.sorting:after,
   #qualifications-table thead > tr > th.sorting_asc:after,
   #qualifications-table thead > tr > th.sorting_desc:after,
   #qualifications-table thead > tr > th.sorting_asc_disabled:after,
   #qualifications-table thead > tr > th.sorting_desc_disabled:after,
   #qualifications-table thead > tr > td.sorting:after,
   #qualifications-table thead > tr > td.sorting_asc:after,
   #qualifications-table thead > tr > td.sorting_desc:after,
   #qualifications-table thead > tr > td.sorting_asc_disabled:after,
   #qualifications-table thead > tr > td.sorting_desc_disabled:after{
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

    @media (max-width: 1400px) {
        th.sorting,
        .data_table_user #qualifications-table tbody td {
            font-size: 14.65px;
        }

        table.dataTable thead > tr > th.sorting,
        table.dataTable thead > tr > th.sorting_asc,
        table.dataTable thead > tr > th.sorting_desc,
        table.dataTable thead > tr > th.sorting_asc_disabled,
        table.dataTable thead > tr > th.sorting_desc_disabled,
        table.dataTable thead > tr > td.sorting,
        table.dataTable thead > tr > td.sorting_asc,
        table.dataTable thead > tr > td.sorting_desc,
        table.dataTable thead > tr > td.sorting_asc_disabled,
        table.dataTable thead > tr > td.sorting_desc_disabled{
                width: 0% !important;
        }

    }


    @media (max-width: 1080px) {


    div#products-table_filter {
        transform: translate(0px, -10px);
        margin: auto !important;
        width: 100%;
    }

    div#products-table_filter label,
    .dataTables_wrapper .dataTables_filter input {
        width: 100% !important;
    }

    th.sorting,
    .data_table_user .table.dataTable tbody td {
        font-size: 14.65px;
    }

    /* .dataTables_filter::after {
        right: 28px;
    } */

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

}

@media (max-width: 1080px) {

    .section_heading_flex h2 {
        font-size: 24px;
    }

    .section_heading_flex a {
        font-size: 14px;
        padding: 10px 26px;
    }
    
    div#qualifications-table_filter label, 
    .dataTables_wrapper .dataTables_filter input {
        width: 99% !important;
        margin: 0 15px 0 0;
    }

    div#qualifications-table_filter {
        transform: translate(10px, -10px);
        margin: auto !important;
        width: 100%;
        
    }

    .dataTables_filter::after {
        right: 46px;
    }
}

@media (max-width: 768px) {
    .data_table_user #qualifications-table tbody td a::after {
        left: 19px;
    }

    div#qualifications-table_filter label {
        margin: 0 0 0 0;
    }
}

@media (max-width: 550px) {
    .data_table_user #qualifications-table tbody td form button::after {
        top: 35%;
    }
}

</style>

@section('dashboard-content')
    <div class="row">
        <div class="col-12">
            <div class="section_heading_flex">
                <h2>Qualifications</h2>
                <a href="{{ Route('user.add.qualifications') }}" class="btn btn-primary">Add Qualifications</a>
            </div>
            <div class="table-resposive data_table_user">
                <table id="qualifications-table" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name of College/University Attended</th>
                            <th>Degree/Diploma</th>
                            <th>Year Graduated</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($educations as $education)
                            <tr>
                                <td>{{ $education->id }}</td>
                                <td>{{ $education->college_university }}</td>
                                <td>{{ $education->degree_diploma }}</td>
                                <td>{{ $education->year }}</td>
                                <td class="btn_flex">
                                    <a href="{{ route('user.edit.qualifications', $education->id) }}"
                                        class="btn btn-primary">
                                        <i class="fa-solid fa-pen-to-square"></i>
                                    </a>
                                    <form action="{{ route('user.delete.qualifications', $education->id) }}" method="POST" class="mb-0">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger"
                                            onclick="return confirm('Are you sure?');">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center">No education records found.</td>
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
        $.fn.dataTable.ext.errMode = 'none'; // Suppress warnings
        $('#qualifications-table').DataTable({
            responsive: true,
            language: {
                emptyTable: "No qualifications available" // Custom message instead of warning
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
