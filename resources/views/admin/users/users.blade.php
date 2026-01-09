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

    form #sendResetLinkBtn{
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
</style>
@section('content')
<main class="main-content">

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card" style="border: none;">
                    <div class="card-header card_header_flex">
                        <h4 class="card-title">User Management</h4>
                        <a href="{{Route('admin.add.user')}}" class="btn btn-primary">Add User</a>
                    </div>
                    <div class="card-body">
                        <table id="usersTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fist Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->first_name}}</td>
                                    <td>{{$user->last_name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->phone}}</td>
                                    
                                    <td>
                                        <a href="{{ route('admin.user.profile', ['id' => $user->id]) }}" class="btn btn-warning btn-sm">
                                                    <svg width="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <title>View</title>
                                                    <path d="M20.188 10.9343C20.5762 11.4056 20.7703 11.6412 20.7703 12C20.7703 12.3588 20.5762 12.5944 20.188 13.0657C18.7679 14.7899 15.6357 18 12 18C8.36427 18 5.23206 14.7899 3.81197 13.0657C3.42381 12.5944 3.22973 12.3588 3.22973 12C3.22973 11.6412 3.42381 11.4056 3.81197 10.9343C5.23206 9.21014 8.36427 6 12 6C15.6357 6 18.7679 9.21014 20.188 10.9343Z" fill="#213bae" fill-opacity="0.14"/>
                                                    <circle cx="12" cy="12" r="3" fill="#273572"/>
                                                    </svg>
                                                </a>
                                        <!-- <a href="{{ route('admin.user.profile', ['id' => $user->id]) }}" class="btn btn-primary btn-sm">View</a> -->

                                        <a id="edit" href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-primary" title="Edit"></a>
                                        <!-- <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a> -->
                                        <form action="{{ route('admin.delete.user', $user->id) }}" method="POST"
                                            style="display:inline-block;" onsubmit="return confirmDelete();">
                                            
                                            @csrf
                                            @method('DELETE')
                                            <button id="delete" type="submit" class="btn btn-danger btn-sm" title="Delete">
                                            </button>
                                        </form>
                                      
                                        <form action="{{ route('admin.reset.link') }}" method="POST" style="display:inline-block;">
                                            @csrf
                                            <input type="hidden" name="email" value="{{ $user->email }}">
                                            <button id="sendResetLinkBtn" type="submit">
                                                <svg fill="#273572" width="24px" height="24px" viewBox="0 0 512 512" data-name="Layer 1" id="Layer_1" xmlns="http://www.w3.org/2000/svg">
                                                    <title>Send Reset Link</title>
                                                    <path d="M218.39,320.61,246.77,349H157a93,93,0,0,1,0-186h18V133H157a123,123,0,0,0,0,246h89.77l-28.38,28.38,21.22,21.23L304.22,364l-64.61-64.61Z"/><path d="M355,133H265.23l28.38-28.38L272.39,83.39,207.78,148l64.61,64.61,21.22-21.22L265.23,163H355a93,93,0,0,1,0,186H336.44v30H355a123,123,0,0,0,0-246Z"/></svg>
                                            </button>
                                        </form> 
                                        {{-- <a href="{{ route('admin.company.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit Company</a> --}}
                                        {{-- <a href="{{ route('users.destroy', $user->id) }}" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this user?');">Delete</a> --}}

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
        function confirmDelete() {
            // Show confirmation alert
            return confirm('Are you sure you want to delete this user?');
        }
    </script>
@endsection
