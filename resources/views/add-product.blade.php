<!-- resources/views/user-products.blade.php -->

@extends('layouts.dashboard-layout')

@section('dashboard-content')
    <div class="row">
        <div class="col-12">
            <div class="section_heading">
                <h2>Products</h2>
            </div>
            <table id="products-table" class="display" style="width:100%">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Product Name</th>
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
                        <td>Product A</td>
                        <td>Category 1</td>
                        <td>This is Description</td>
                        <td>$100</td>
                        <td>Active</td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td><img src="{{ asset('assets/images/logo_bg.png') }}" alt="" class="table_image"></td>
                        <td>Product B</td>
                        <td>Category 2</td>
                        <td>This is Description</td>
                        <td>$200</td>
                        <td>Inactive</td>
                    </tr>
                    <tr>
                        <td>3</td>
                        <td><img src="{{ asset('assets/images/logo_bg.png') }}" alt="" class="table_image"></td>
                        <td>Product C</td>
                        <td>Category 3</td>
                        <td>This is Description</td>
                        <td>$300</td>
                        <td>Active</td>
                    </tr>
                    <tr>
                        <td>4</td>
                        <td><img src="{{ asset('assets/images/logo_bg.png') }}" alt="" class="table_image"></td>
                        <td>Product D</td>
                        <td>Category 4</td>
                        <td>This is Description</td>
                        <td>$400</td>
                        <td>Inactive</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('scripts')
<script>
    $(document).ready(function() {
        $('#products-table').DataTable();
    });
</script>
@endsection
