<!-- resources/views/user-products.blade.php -->

@extends('layouts.dashboard-layout')

@section('dashboard-content')
    <div class="row">
        <div class="col-12">
            <div class="section_heading_flex">
                <h2>Add Product</h2>
            </div>
            <div class="add_form">
                <form action="">
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <label for="product_name" class="form-label">Product Name:</label>
                            <input type="text" name="product_name" id="product_name" class="form-control" required>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="product_category" class="form-label">Product Category:</label>
                            <select name="product_category" id="product_category" class="form-select" required>
                                <option value="">Select Product Category</option>
                            </select>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="product_price" class="form-label">Product Price:</label>
                            <input type="text" name="product_price" id="product_price" class="form-control" required>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <label for="product_price" class="form-label">Price Type:</label>
                            <select name="product_price" id="product_price" class="form-select" required>
                                <option value="">Select Price Type</option>
                                <option value="Monthly">Monthly</option>
                                <option value="Yearly">Yearly</option>
                            </select>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <label for="product_description" class="form-label">Product Description:</label>
                            <textarea name="product_description" id="product_description" class="form-control" rows="4"></textarea>
                        </div>
                        <div class="col-12 mb-3">
                            <label for="product_image" class="form-label">Product Image:</label>
                            <div class="image-uploader">
                                <input type="file" name="product_image" id="product_image" class="form-control" required accept="image/*" style="display: none;">
                                <div class="upload-area" id="upload-area">
                                    <span>Click to upload or drag and drop</span>
                                </div>
                                <div class="image-preview" id="image-preview">
                                    <img id="preview-image" src="#" alt="Preview" style="display: none;">
                                    <button type="button" id="remove-image" class="btn btn-danger btn-sm" style="display: none;">Remove</button>
                                </div>
                            </div>
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
    document.addEventListener('DOMContentLoaded', function() {
        const uploadArea = document.getElementById('upload-area');
        const fileInput = document.getElementById('product_image');
        const previewImage = document.getElementById('preview-image');
        const removeImageButton = document.getElementById('remove-image');
        const imagePreviewContainer = document.getElementById('image-preview');

        // Open file dialog when upload area is clicked
        uploadArea.addEventListener('click', function() {
            fileInput.click();
        });

        // Handle file selection
        fileInput.addEventListener('change', function(event) {
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
            previewImage.src = '#';
            previewImage.style.display = 'none';
            removeImageButton.style.display = 'none';
            uploadArea.style.display = 'block';
            fileInput.value = '';
        });
    });
</script>
@endsection

