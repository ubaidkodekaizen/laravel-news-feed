<!-- resources/views/user-Services.blade.php -->

@extends('layouts.dashboard-layout')

@section('dashboard-content')
    <div class="row">
        <div class="col-12">
            <div class="section_heading_flex">
                <h2>Services</h2>
                <a href="{{ Route('user.add.service') }}" class="btn btn-primary">Add Service</a>
            </div>

            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-resposive data_table_user">
                <table id="services-table" class="display" style="width:100%">
                    <table class="table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Price</th>
                                <th>Discounted Price</th>
                                <th>Subscription</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($services as $service)
                                <tr>
                                    <td>{{ $service->id }}</td>
                                    <td>
                                        <img src="{{ $service->service_image ? asset('storage/' . $service->service_image) : asset('assets/images/logo_bg.png') }}"
                                            alt="Service Image" class="table_image">
                                    </td>
                                    <td>{{ $service->title }}</td>
                                    <td>
                                        ${{ number_format($service->original_price, 2) }}
                                    </td>
                                    <td>{{ $service->discounted_price ? '$' . number_format($service->discounted_price, 2) : 'N/A' }}</td>

                                    <td>{{ $service->duration }}</td>

                                    <td class="btn_flex">

                                        <a href="{{ route('user.edit.service', $service->id) }}" class="btn btn-primary">
                                            <i class="fa-solid fa-pen-to-square"></i>
                                        </a>
                                        <form action="{{ route('user.delete.service', $service->id) }}" method="POST" class="mb-0">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger"
                                                onclick="return confirm('Are you sure?')">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center">No services found.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </table>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#services-table').DataTable();
        });
    </script>
@endsection
