@extends('admin.layouts.main')
@section('content')
    <main class="main-content">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between">
                            <h4 class="card-title">Events</h4>
                            <a href="{{ route('admin.add.event') }}" class="btn btn-primary btn-md">Add Event</a>
                        </div>
                        <div class="card-body">
                            <table id="blogsTable" class="table table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Title</th>
                                        <th>City</th>
                                        <th>Time</th>
                                        <th>Date</th>
                                        <th>Venue</th>
                                        <th>Event URL</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Event 1</td>
                                        <td>City Name</td>
                                        <td>10:00 AM</td>
                                        <td>15 Janurary 2024</td>
                                        <td>Venue here</td>
                                        <td>URL here</td>
                                        <td>
                                            <a href="javascript:void(0);" class="btn btn-warning btn-sm">View</a>
                                            <a href="javascript:void(0);" class="btn btn-primary btn-sm">Edit</a>
                                            <a href="javascript:void(0);" class="btn btn-danger btn-sm">Delete</a>
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
