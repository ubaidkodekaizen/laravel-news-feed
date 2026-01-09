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
</style>
@section('content')
    <main class="main-content">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <a href="{{ url('/admin/blogs') }}"><img src=" {{ asset('assets/images/dashboard/dashboardBackChevron.svg') }}" alt=""></a>
                                    Edit Blog
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.update.blog', $blog->id) }}" method="POST"
                                enctype="multipart/form-data">
                                @csrf
                                @method('PUT')

                                <div class="row">
                                    @if ($errors->any())
                                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                            <button type="button" class="btn-close" data-bs-dismiss="alert"
                                                aria-label="Close"></button>
                                        </div>
                                    @endif
                                    <!-- Blog Title -->
                                    <div class="col-12 mb-3">
                                        <label for="title" class="form-label">Blog Title</label>
                                        <input type="text" id="title" name="title" class="form-control"
                                            value="{{ old('title', $blog->title) }}" placeholder="Enter blog title"
                                            required>
                                    </div>

                                    <!-- Blog Content -->
                                    <div class="col-12 mb-3">
                                        <label for="content" class="form-label">Blog Content</label>
                                        <textarea id="content" name="content" class="form-control" rows="10"
                                            placeholder="Write your blog content here...">{{ old('content', $blog->content) }}</textarea>
                                    </div>

                                    <!-- Blog Image -->
                                    <div class="col-12 mb-3">
                                        <label for="image" class="form-label">Blog Image</label>
                                        <input type="file" id="image" name="image" class="form-control"
                                            accept="image/*">
                                    </div>

                                    <!-- Current Image Display -->
                                    @if ($blog->image)
                                        <div class="col-12 mb-3">
                                            <label for="current_image" class="form-label">Current Image</label>
                                            <img src="{{ getImageUrl($blog->image) }}" alt="Current Image"
                                                style="max-width: 100px; height: auto;">
                                        </div>
                                    @endif

                                    <!-- Submit Button -->
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Update Blog</button>
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
    <script src="https://cdn.ckeditor.com/ckeditor5/36.0.1/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#content'), {
                toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote',
                    'insertImage', '|', 'undo', 'redo'
                ],
                simpleUpload: {
                    uploadUrl: '/upload', // The server endpoint where the image will be uploaded
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}' // Add CSRF token for Laravel
                    }
                }
            })
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
