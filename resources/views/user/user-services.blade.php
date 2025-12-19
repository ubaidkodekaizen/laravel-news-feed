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
    
    .data_table_user table tbody td.btn_flex a::after {
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
    .data_table_user .table tbody td form button i{
        display: none;
    }

    .data_table_user table tbody td a, 
    .data_table_user table tbody td form button {
        padding: 20px !important;
        background: transparent !important;
        border: none !important;
    }

    .table-resposive.data_table_user {
        border-radius: 15.99px 15.99px 0 0;
        overflow: hidden;
        border-top: 2px solid #F2F2F2;
        border-right: 2px solid #F2F2F2;
        border-bottom: 1px solid #F2F2F2;
        border-left: 2px solid #F2F2F2;
        margin-bottom: 40px;
        /* overflow-x: scroll; */
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
        margin-bottom: 0 !important;
    }

    .table-resposive.data_table_user table thead tr th {
        color: #333333;
        font-family: "Inter";
        font-size: 18.65px;
        font-weight: 500 !important;
        background: #EDEEF4 !important;
        border: none !important;
        padding: 16px 16px !important;
    }

    .data_table_user table tbody td {
        vertical-align: middle;
        font-family: "Inter";
        font-size: 18.65px;
        font-weight: 400 !important;
    }

    .data_table_user table tbody td{
        height: 100px;
    }

    .table_image {
        border-radius: 8px;
        max-width: 100px;
        width: 100%;
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

    div#products-table_filter label,
    .dataTables_wrapper .dataTables_filter input {
        width: 100% !important;
    }

    .table-resposive.data_table_user table thead tr th,
    .data_table_user table tbody td {
        font-size: 14.65px; 
    }

    /* .dataTables_filter::after {
        right: 28px;
    } */

    .data_table_user table tbody td.btn_flex a::after {
        left: 0px;
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
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Discounted Price</th>
                                <th>Subscription</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $service)
                                <tr>
                                    <td>{{ $service->id }}</td>
                                    <td>
                                        <img src="{{ $service->service_image ? asset('storage/' . $service->service_image) : asset('assets/images/logo_bg.png') }}"
                                            alt="Service Image" class="table_image">
                                    </td>
                                    <td>{{ $service->title }}</td>
                                    <td>
                                        ${{ number_format($service->original_price, 2) }}
                                    </td>
                                    <td>{{ $service->discounted_price ? '$' . number_format($service->discounted_price, 2) : 'N/A' }}</td>

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
                                    <td colspan="7" class="text-center">No services found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

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

@endsection
