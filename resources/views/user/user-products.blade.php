<!-- resources/views/user-products.blade.php -->

@extends('layouts.dashboard-layout')

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
                                        onsubmit="return confirm('Are you sure?');" style="display:inline;">
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
        $(document).ready(function() {
            $('#products-table').DataTable();
        });
    </script>
@endsection
