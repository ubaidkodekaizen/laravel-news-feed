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

    .add_form .form-control,
    .add_form .form-select {
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
                <h2><a href="{{ route('user.services')}}"><img src="{{ asset('assets/images/dashboard/dashboardBackChevron.svg')}}" alt=""></a> Add Service</h2>
            </div>
            <div class="add_form">
                <form action="{{ route('user.store.service', $service->id ?? '') }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <div class="col-12 mb-3">
                            <label for="service_image" class="form-label">Service Image:</label>
                            <div class="image-uploader">
                                <input type="file" name="service_image" id="service_image" class="form-control"
                                    accept="image/*" style="display: none;">
                                <div class="upload-area" id="upload-area">
                                    <img src="{{ asset('assets/images/dashboard/ProductUploadImageIcon.svg')}}" alt="">
                                    <h4>Click to upload or drag and drop</h4>
                                    <span>Support PNG,JPEG, WEBP</span>
                                </div>
                                <div class="image-preview" id="image-preview">
                                    @if (!empty($service->service_image))
                                        <img id="preview-image" src="{{ getImageUrl($service->service_image) }}"
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
                                value="{{ old('title', $service->title ?? '') }}" required>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <label for="short_description">Short Description</label>
                            <textarea name="short_description" id="short_description" rows="4" class="form-control">{{ old('short_description', $service->short_description ?? '') }}</textarea>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="original_price" class="form-label">Original Price:</label>
                            <div class="input-group">
                                <!-- <span class="input-group-text" id="original_price">$</span> -->
                                <input type="number" name="original_price" class="form-control"
                                    value="{{ old('original_price', $service->original_price ?? '') }}"
                                    aria-label="Original Price" aria-describedby="original_price" required>
                            </div>
                        </div>

                        <div class="col-lg-6 mb-3">
                            <label for="discounted_price" class="form-label">Discounted Price:</label>
                            <div class="input-group">
                                <!-- <span class="input-group-text" id="discounted_price">$</span> -->
                                <input type="number" name="discounted_price" class="form-control"
                                    value="{{ old('discounted_price', $service->discounted_price ?? '') }}"
                                    aria-label="Discounted Price" aria-describedby="discounted_price">
                            </div>
                        </div>

                        <div class="col-lg-12 mb-3">
                            <label for="duration" class="form-label">Duration</label>
                            <select name="duration" id="duration" class="form-select" required>
                                <option value="">Select Duration</option>
                              
                              	 <option value="Starting"
                                    {{ old('duration', $service->duration ?? '') == 'Starting' ? 'selected' : '' }}>Starting
                                </option>
                      
                                <option value="One time"
                                    {{ old('duration', $service->duration ?? '') == 'One time' ? 'selected' : '' }}>One Time
                                </option>
                                <option value="Monthly"
                                    {{ old('duration', $service->duration ?? '') == 'Monthly' ? 'selected' : '' }}>Monthly
                                </option>
                                <option value="Yearly"
                                    {{ old('duration', $service->duration ?? '') == 'Yearly' ? 'selected' : '' }}>Yearly
                                </option>
                                <option value="Quarterly"
                                    {{ old('duration', $service->duration ?? '') == 'Quarterly' ? 'selected' : '' }}>
                                    Quarterly</option>
                            </select>
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
         let isInitialized = false;

        document.addEventListener('DOMContentLoaded', function() {
            if (isInitialized) return; // Prevent duplicate initialization
            isInitialized = true;
            const uploadArea = document.getElementById('upload-area');
            const fileInput = document.getElementById('service_image');
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
