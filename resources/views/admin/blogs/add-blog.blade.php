@extends('admin.layouts.main')
@section('content')
    <main class="main-content">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add Blog</h4>
                        </div>
                        <div class="card-body">
                           <form action="{{ route('admin.store.blog') }}" method="POST" enctype="multipart/form-data">
                                  @csrf
                               <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="title" class="form-label">Blog Title</label>
                                        <input type="text" id="title" name="title" class="form-control" placeholder="Enter blog title" required>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="content" class="form-label">Blog Content</label>
                                        <textarea id="content" name="content" class="form-control" rows="10" placeholder="Write your blog content here..."></textarea>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="image" class="form-label">Blog Image</label>
                                        <input type="file" id="image" name="image" class="form-control" accept="image/*">
                                    </div>

                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Submit Blog</button>
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
            toolbar: ['heading', '|', 'bold', 'italic', 'link', 'bulletedList', 'numberedList', 'blockQuote', 'insertImage', '|', 'undo', 'redo'],
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
