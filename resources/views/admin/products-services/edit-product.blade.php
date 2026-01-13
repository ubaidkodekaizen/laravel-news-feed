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
    .card-title {
        border-radius: 0;
        color: #333 !important;
        margin: 0;
        font-size: 28px;
        font-family: "Inter";
        font-weight: 500;
    }
    .form-label {
        font-family: "Inter";
        font-weight: 500;
        color: #333;
        margin-bottom: 8px;
    }
    .form-control {
        font-family: "Inter";
        border: 1px solid #E9EBF0;
        border-radius: 10px;
        padding: 12px 15px;
    }

    .card .card-header .card-title a img {
        width: 14px !important;
        margin-top: -6px;
        margin-right: 16px;
        border: none !important;
    }

    .btn {
        border-radius: 9.77px !important;
        padding: 15px 56px !important;
        font-family: "Poppins", sans-serif !important;
        font-weight: 500 !important;
        font-size: 22px !important;
        line-height: 100% !important;
        letter-spacing: 0px !important;
        text-align: center !important;
        /* margin: 0 0 0 0; */
    }
</style>
@section('content')
<main class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card" style="border: none;">
                    <div class="card-header">
                        <h4 class="card-title">
                        <a href="{{ route('admin.products-services', ['filter' => 'products']) }}">
                                    <img src="{{ asset('assets/images/dashboard/dashboardBackChevron.svg') }}" alt="back"></a>      
                        Edit Product</h4>
                    </div>
                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('admin.update.product', $product->id) }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="title" class="form-label">Title <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="title" name="title" 
                                        value="{{ old('title', $product->title) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="original_price" class="form-label">Original Price <span class="text-danger">*</span></label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="original_price" name="original_price" 
                                        value="{{ old('original_price', $product->original_price) }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="discounted_price" class="form-label">Discounted Price</label>
                                    <input type="number" step="0.01" min="0" class="form-control" id="discounted_price" name="discounted_price" 
                                        value="{{ old('discounted_price', $product->discounted_price) }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="quantity" class="form-label">Quantity <span class="text-danger">*</span></label>
                                    <input type="number" min="0" class="form-control" id="quantity" name="quantity" 
                                        value="{{ old('quantity', $product->quantity) }}" required>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="unit_of_quantity" class="form-label">Unit of Quantity <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="unit_of_quantity" name="unit_of_quantity" 
                                        value="{{ old('unit_of_quantity', $product->unit_of_quantity) }}" required>
                                </div>
                                <div class="col-md-6">
                                    <label for="product_image" class="form-label">Product Image</label>
                                    <input type="file" class="form-control" id="product_image" name="product_image" accept="image/*">
                                    @if($product->product_image)
                                        <small class="text-muted">Current image: <a href="{{ $product->product_image }}" target="_blank">View</a></small>
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="short_description" class="form-label">Short Description</label>
                                    <textarea class="form-control" id="short_description" name="short_description" rows="4">{{ old('short_description', $product->short_description) }}</textarea>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary">Update Product</button>
                                    <a href="{{ route('admin.products-services', ['filter' => 'products']) }}" class="btn btn-secondary">Cancel</a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

