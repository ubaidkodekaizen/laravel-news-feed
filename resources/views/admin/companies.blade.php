@extends('admin.layouts.main')
@section('content')
<main class="main-content">

    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Companies Management</h4>
                    </div>
                    <div class="card-body">
                        <table id="companiesTable" class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>John Doe</td>
                                    <td>johndoe@example.com</td>
                                    <td>Admin</td>
                                    <td>
                                        <span class="badge bg-success">Active</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm">View</button>
                                        <button class="btn btn-warning btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Jane Smith</td>
                                    <td>janesmith@example.com</td>
                                    <td>User</td>
                                    <td>
                                        <span class="badge bg-danger">Inactive</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm">View</button>
                                        <button class="btn btn-warning btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Sam Wilson</td>
                                    <td>samwilson@example.com</td>
                                    <td>Moderator</td>
                                    <td>
                                        <span class="badge bg-success">Active</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm">View</button>
                                        <button class="btn btn-warning btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>4</td>
                                    <td>Emily Johnson</td>
                                    <td>emilyj@example.com</td>
                                    <td>User</td>
                                    <td>
                                        <span class="badge bg-warning">Pending</span>
                                    </td>
                                    <td>
                                        <button class="btn btn-primary btn-sm">View</button>
                                        <button class="btn btn-warning btn-sm">Edit</button>
                                        <button class="btn btn-danger btn-sm">Delete</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</main>
@endsection
