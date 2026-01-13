@extends('admin.layouts.main')
<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
<style>

     body{
        background: #fafbff !important;
    }

    .card {
        border: 2px solid #e9ebf0 !important;
        border-radius: 14.66px !important;
        overflow: hidden;
    }

    .card-body{
        background: #27357208;
        padding: 30px 30px 30px !important;
    }

    .card-header:first-child {
        border: 0;
        background: #2735721c;
        padding: 30px 30px 30px !important;
    }

    h4.card-title {
        font-family: "Inter";
        font-weight: 600;
        font-size: 24px;
        line-height: 100%;
        color: #273572;
        margin: 0;
    }

    label.form-label {
        margin: 0 0px 14px;
        position: relative;
        color: #333333;
        display: flex;
        align-items: center;
        justify-content: start;
        font-family: "Inter";
        font-weight: 600;
        font-size: 18px;
        line-height: 100%;
    }

    label.form-check-label {
        font-family: "Inter", sans-serif;
        font-weight: 400;
        font-size: 16px;
        line-height: 160%;
    }

    .form-control,
    .form-select {
        background: #FFFFFF;
        border-radius: 9.77px !important;
        border: 2px solid #E9EBF0 !important;
        font-family: Inter !important;
        font-weight: 400 !important;
        font-size: 16px !important;
        line-height: 260% !important;
        color: #000 !important;
        caret-color: auto !important;
    }

    /* Chrome, Edge, Safari, Brave */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    textarea:-webkit-autofill,
    select:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px #ffffff inset !important;
        box-shadow: 0 0 0px 1000px #ffffff inset !important;
        -webkit-text-fill-color: #333 !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    /* Firefox */
    input:-moz-autofill,
    textarea:-moz-autofill,
    select:-moz-autofill {
        box-shadow: 0 0 0px 1000px #ffffff inset !important;
        -moz-text-fill-color: #333 !important;
    }

    button.btn.btn-primary {
        border-radius: 9.77px;
        padding: 15px 56px;
        font-family: "Poppins", sans-serif;
        font-weight: 500;
        font-size: 22px;
        line-height: 100%;
        letter-spacing: 0px;
        text-align: center;
        margin: 0 0 0 0;
    }

    .card .card-header .card-title a img {
        width: 14px !important;
        margin-top: -6px;
        margin-right: 16px;
        border: none !important;
    }

    .form-check-input {
        width: 20px;
        height: 20px;
        margin-top: 0.25rem;
        margin-right: 0.5rem;
    }

    /* Preview Container */
    .preview-container {
        width: 362px;
        height: 567px;
        border: 2px solid #E9EBF0;
        border-radius: 9.77px;
        background-color: #FFFFFF;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        position: relative;
        padding: 0;
    }

    .preview-container img,
    .preview-container video {
        border-radius: 0 !important;
        border: none !important;
        width: auto !important;
        height: auto !important;
        max-width: 100% !important;
        max-height: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    #mediaPreview {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #mediaPreview img,
    #mediaPreview video {
        max-width: 100% !important;
        max-height: 100% !important;
        width: auto !important;
        height: auto !important;
        object-fit: contain !important;
        border-radius: 0 !important;
        border: none !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .preview-placeholder {
        color: #898F9B;
        font-family: "Inter", sans-serif;
        font-size: 16px;
        font-weight: 400;
        text-align: center;
        padding: 20px;
    }

    @media (max-width: 991px) {
        .preview-container {
            width: 100%;
            max-width: 362px;
            margin: 0 auto;
        }
    }

    /* Form container spacing */
    .card-body > .row:first-of-type {
        margin-top: 20px;
    }

    .col-lg-7.col-md-6 {
        padding-top: 20px;
    }
</style>
@section('content')
    <main class="main-content">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <a href="{{ route('admin.ads') }}"><img src="{{ asset('assets/images/dashboard/dashboardBackChevron.svg') }}" alt=""></a>
                                Add Ad
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.store.ad') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <!-- Display Validation Errors -->
                                @if ($errors->any())
                                    <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif

                                <div class="row align-items-center">
                                    <!-- Form Fields - Left Side -->
                                    <div class="col-lg-7 col-md-6">
                                        <div class="row">
                                            <!-- Media (Image/GIF/Video) -->
                                            <div class="col-12 mb-3">
                                                <label for="media" class="form-label">Media (Image, GIF, or Video) <span class="text-danger">*</span></label>
                                                <input 
                                                    type="file" 
                                                    id="media" 
                                                    name="media" 
                                                    class="form-control @error('media') is-invalid @enderror" 
                                                    accept="image/*,video/*"
                                                    required
                                                >
                                                <small class="form-text text-muted">
                                                    <strong>Recommended dimensions:</strong> 362px × 567px for best display quality.<br>
                                                    Accepted formats: JPG, PNG, GIF, MP4, MOV, AVI, WEBM (Max 10MB)
                                                </small>
                                                @error('media')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <!-- URL -->
                                            <div class="col-12 mb-3">
                                                <label for="url" class="form-label">URL</label>
                                                <input 
                                                    type="url" 
                                                    id="url" 
                                                    name="url" 
                                                    class="form-control @error('url') is-invalid @enderror" 
                                                    placeholder="https://example.com"
                                                    value="{{ old('url') }}"
                                                >
                                                @error('url')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>

                                            <!-- Submit Button -->
                                            <div class="col-12">
                                                <button type="submit" class="btn btn-primary">Submit Ad</button>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Preview Section - Right Side -->
                                    <div class="col-lg-5 col-md-6 mb-4">
                                        <label class="form-label mb-3">Preview (362px × 567px)</label>
                                        <div class="preview-container">
                                            <div id="mediaPreview">
                                                <div class="preview-placeholder">Preview will appear here</div>
                                            </div>
                                        </div>
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

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mediaInput = document.getElementById('media');
        const mediaPreview = document.getElementById('mediaPreview');

        mediaInput.addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const fileType = file.type;
                const reader = new FileReader();

                // Clear previous content
                mediaPreview.innerHTML = '';

                if (fileType.startsWith('image/')) {
                    // Handle images
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.style.maxWidth = '100%';
                        img.style.maxHeight = '100%';
                        img.style.objectFit = 'contain';
                        mediaPreview.appendChild(img);
                    };
                    reader.readAsDataURL(file);
                } else if (fileType.startsWith('video/')) {
                    // Handle videos
                    const video = document.createElement('video');
                    video.controls = true;
                    video.style.maxWidth = '100%';
                    video.style.maxHeight = '100%';
                    video.style.objectFit = 'contain';
                    video.src = URL.createObjectURL(file);
                    mediaPreview.appendChild(video);
                }
            } else {
                // Reset to placeholder if no file selected
                mediaPreview.innerHTML = '<div class="preview-placeholder">Preview will appear here</div>';
            }
        });
    });
</script>
@endsection
