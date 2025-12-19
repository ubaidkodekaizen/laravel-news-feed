<!-- resources/views/user-products.blade.php -->

@extends('layouts.dashboard-layout')

<style>
    .form-label,
    .col-lg-12 label {
        margin-bottom: .5rem;
        font-family: "inter";
        font-weight: 400;
        font-size: 18px;
    }

    div#upload-area h4{
        font-family: "inter";
        font-weight: 400;
        font-size: 16px;
    }

    div#upload-area span{
        font-family: "inter";
        font-weight: 400;
        font-size: 14px;
        color: #898F9B;
    }

    .add_form .form-control {
        font-family: "inter";
        font-weight: 400 !important;
        font-size: 18px !important;
        padding: 14.5px .75rem !important;
        background-color: #F6F7FC !important;
        border: 2px solid #E9EBF0 !important;
        border-radius: 9.77px !important;
    }

    .add_form button.btn.btn-primary {
        font-family: "poppins";
        font-weight: 500;
        font-size: 18px;
        padding: 15px 66px;
        border-radius: 9.77px;
    }

    .section_heading_flex h2 a img{
        width: 14px;
        margin-top: -6px;
        margin-right: 16px;
    }

    .add_form #remove-image {
        margin-top: 14px;
        display: block;
        text-align: center;
        justify-self: center;
        font-family: "poppins";
        font-weight: 500;
        font-size: 14px;
        padding: 9px 71px;
        border-radius: 9.77px;
    }

    @media (max-width: 1080px) {
    .main-content {
        width: 100% !important;
        height: 100%;
        padding: 50px 14px 20px 14px !important;
    }
    .section_heading_flex h2 {
        font-size: 24px;
        margin-bottom: 22px;    
    }
}

@media (max-width: 786px){
    .add_form button.btn.btn-primary {
        font-size: 16px;
    }
}
</style>
  
@section('dashboard-content')
    <div class="row">
        <div class="col-12">
            <div class="section_heading_flex">
                <h2><a href="{{ route('user.products')}}"><img src="{{ asset('assets/images/dashboard/dashboardBackChevron.svg')}}" alt=""></a> Add Product</h2>
            </div>
            <div class="add_form">
                <form action="{{ route('user.store.product', $product->id ?? '') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="product_image" class="form-label">Product Image</label>
                            <div class="image-uploader">
                                <input type="file" name="product_image" id="product_image" class="form-control"
                                    accept="image/*" style="display: none;">
                                <div class="upload-area" id="upload-area">
                                    <img src="{{ asset('assets/images/dashboard/ProductUploadImageIcon.svg')}}" alt="">
                                    <h4>Click to upload or drag and drop</h4>
                                    <span>Support PNG,JPEG, WEBP</span>
                                </div>
                                <div class="image-preview" id="image-preview">
                                    @if (isset($product->product_image) && $product->product_image)
                                        <img id="preview-image" src="{{ asset('storage/' . $product->product_image) }}"
                                            alt="Preview">
                                        <button type="button" id="remove-image"
                                            class="btn btn-danger btn-sm">Remove</button>
                                    @else
                                        <img id="preview-image" src="#" alt="Preview" style="display: none;">
                                        <button type="button" id="remove-image" class="btn btn-danger btn-sm"
                                            style="display: none;">Remove</button>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="title" class="form-label">Title:</label>
                            <input type="text" name="title" id="title" class="form-control"
                                value="{{ old('title', $product->title ?? '') }}" required>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <label for="short_description">Short Description</label>
                            <textarea name="short_description" id="short_description" rows="4" class="form-control">{{ old('short_description', $product->short_description ?? '') }}</textarea>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="original_price" class="form-label">Original Price:</label>
                            <div class="input-group">
                                <!-- <span class="input-group-text">$</span> -->
                                <input type="number" name="original_price" class="form-control"
                                    value="{{ old('original_price', $product->original_price ?? '') }}" required>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="discounted_price" class="form-label">Discounted Price:</label>
                            <div class="input-group">
                                <!-- <span class="input-group-text">$</span> -->
                                <input type="number" name="discounted_price" class="form-control"
                                    value="{{ old('discounted_price', $product->discounted_price ?? '') }}">
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="quantity" class="form-label">Quantity:</label>
                            <input type="number" name="quantity" id="quantity" class="form-control"
                                value="{{ old('quantity', $product->quantity ?? '') }}" required>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="unit_of_quantity" class="form-label">Unit of quantity:</label>
                            <input type="text" name="unit_of_quantity" class="form-control"
                                value="{{ old('unit_of_quantity', $product->unit_of_quantity ?? '') }}"
                                placeholder="KG/Unit/L/ Etc." required>
                        </div>
                        <div class="col-12">
                            <button class="btn btn-primary" type="submit">Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Flag to ensure event listeners are only attached once
        let isInitialized = false;

        document.addEventListener('DOMContentLoaded', function() {
            if (isInitialized) return; // Prevent duplicate initialization
            isInitialized = true;

            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('product_image');
            const previewImage = document.getElementById('preview-image');
            const removeImageButton = document.getElementById('remove-image');
            const imagePreviewContainer = document.getElementById('image-preview');

            // Open file dialog when upload area is clicked
            uploadArea.addEventListener('click', function() {
                console.log('Upload area clicked');
                fileInput.click();
            });

            // Handle file selection
            fileInput.addEventListener('change', function(event) {
                console.log('File input changed');
                const file = event.target.files[0];
                if (file && file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = 'block';
                        removeImageButton.style.display = 'block';
                        uploadArea.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Handle drag and drop
            uploadArea.addEventListener('dragover', function(event) {
                event.preventDefault();
                uploadArea.classList.add('dragover');
            });

            uploadArea.addEventListener('dragleave', function(event) {
                event.preventDefault();
                uploadArea.classList.remove('dragover');
            });

            uploadArea.addEventListener('drop', function(event) {
                event.preventDefault();
                uploadArea.classList.remove('dragover');
                const file = event.dataTransfer.files[0];
                if (file && file.type.startsWith('image/')) {
                    fileInput.files = event.dataTransfer.files;
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        previewImage.src = e.target.result;
                        previewImage.style.display = 'block';
                        removeImageButton.style.display = 'block';
                        uploadArea.style.display = 'none';
                    };
                    reader.readAsDataURL(file);
                }
            });

            // Remove image
            removeImageButton.addEventListener('click', function() {
                console.log('Remove image clicked');
                previewImage.src = '#';
                previewImage.style.display = 'none';
                removeImageButton.style.display = 'none';
                uploadArea.style.display = 'block';
                fileInput.value = ''; // Reset the file input
            });
        });
    </script>
@endsection
