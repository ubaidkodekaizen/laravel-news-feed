@extends('admin.layouts.main')
@section('content')
<main class="main-content">

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">User Management</h4>
                    </div>
                    <div class="card-body">
                        <table id="usersTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Fist Name</th>
                                    <th>Last Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td>{{$user->first_name}}</td>
                                    <td>{{$user->last_name}}</td>
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->phone}}</td>
                                    
                                    <td>
                                        <a href="{{ route('admin.user.profile', ['id' => $user->id]) }}" class="btn btn-primary btn-sm">View</a>
                                        <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                                        <a href="{{ route('admin.company.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit Company</a>
                                        {{-- <a href="{{ route('users.destroy', $user->id) }}" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Are you sure you want to delete this user?');">Delete</a> --}}

                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td>No Users</td>
                                    
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
@endsection
