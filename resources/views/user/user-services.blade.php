<!-- resources/views/user-Services.blade.php -->

@extends('layouts.dashboard-layout')

@section('dashboard-content')
    <div class="row">
        <div class="col-12">
            <div class="section_heading_flex">
                <h2>Services</h2>
                <a href="{{Route('user.add.service')}}" class="btn btn-primary">Add Service</a>
            </div>
            <div class="table-resposive data_table_user">
                <table id="services-table" class="display" style="width:100%">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Service Name</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>1</td>
                            <td><img src="{{ asset('assets/images/logo_bg.png') }}" alt="" class="table_image"></td>
                            <td>Service A</td>
                            <td>Category 1</td>
                            <td>This is Description</td>
                            <td>$100</td>
                            <td class="btn_flex">
                                <button class="btn btn-success"><i class="fa-solid fa-power-off"></i></button>
                                <button class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>2</td>
                            <td><img src="{{ asset('assets/images/logo_bg.png') }}" alt="" class="table_image"></td>
                            <td>Service B</td>
                            <td>Category 2</td>
                            <td>This is Description</td>
                            <td>$200</td>
                            <td class="btn_flex">
                                <button class="btn btn-success"><i class="fa-solid fa-power-off"></i></button>
                                <button class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>3</td>
                            <td><img src="{{ asset('assets/images/logo_bg.png') }}" alt="" class="table_image"></td>
                            <td>Service C</td>
                            <td>Category 3</td>
                            <td>This is Description</td>
                            <td>$300</td>
                            <td class="btn_flex">
                                <button class="btn btn-success"><i class="fa-solid fa-power-off"></i></button>
                                <button class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                        <tr>
                            <td>4</td>
                            <td><img src="{{ asset('assets/images/logo_bg.png') }}" alt="" class="table_image"></td>
                            <td>Service D</td>
                            <td>Category 4</td>
                            <td>This is Description</td>
                            <td>$400</td>
                            <td class="btn_flex">
                                <button class="btn btn-success"><i class="fa-solid fa-power-off"></i></button>
                                <button class="btn btn-primary"><i class="fa-solid fa-pen-to-square"></i></button>
                                <button class="btn btn-danger"><i class="fa-solid fa-trash"></i></button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('#services-table').DataTable();
    });
</script>
@endsection
