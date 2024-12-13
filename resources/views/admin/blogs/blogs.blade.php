@extends('admin.layouts.main')
@section('content')
    <main class="main-content">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title">Blogs</h4>
                            <a href="{{ route('admin.add.blog') }}" class="btn btn-primary btn-md">Add Blog</a>
                        </div>
                        <div class="card-body">
                            <table id="blogsTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>Content</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($blogs as $blog)
                                        <tr>
                                            <td>{{ $blog->id }}</td>
                                            <td>{{ $blog->title }}</td>
                                            <td>{{ Str::limit(strip_tags($blog->content), 50) }}</td>
                                            <td>
                                                <a href="#" class="btn btn-warning btn-sm">View</a>
                                                <a href="{{ route('admin.edit.blog', $blog->id) }}"
                                                    class="btn btn-primary btn-sm">Edit</a>
                                                <form action="{{ route('admin.delete.blog', $blog->id) }}" method="POST"
                                                    style="display:inline-block;" onsubmit="return confirmDelete();">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center">No blogs found</td>
                                        </tr>
                                    @endforelse

                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>

    <script>
        function confirmDelete() {
            // Show confirmation alert
            return confirm('Are you sure you want to delete this blog?');
        }
    </script>
@endsection
