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
                                    @forelse($events as $key => $event)
                                        <tr>
                                            <td>{{ $key + 1 }}</td> <!-- Row Number -->
                                            <td>{{ $event->title }}</td> <!-- Event Title -->
                                            <td>{{ $event->city }}</td> <!-- Event City -->
                                            <td>{{ \Carbon\Carbon::parse($event->time)->format('h:i A') }}</td>
                                            <!-- Event Time -->
                                            <td>{{ \Carbon\Carbon::parse($event->date)->format('d F Y') }}</td>
                                            <!-- Event Date -->
                                            <td>{{ $event->venue }}</td> <!-- Event Venue -->
                                            <td><a href="{{ $event->url }}" target="_blank">{{ $event->url }}</a></td>
                                            <!-- Event URL -->
                                            <td>
                                                <!-- View, Edit, and Delete Buttons -->
                                                <a href="#" class="btn btn-warning btn-sm">View</a>
                                                <a href="{{ route('admin.edit.event', $event->id) }}"
                                                    class="btn btn-primary btn-sm">Edit</a>
                                                <form action="{{ route('admin.delete.event', $event->id) }}" method="POST"
                                                    style="display:inline-block;" onsubmit="return confirmDelete();">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">No events available to display.</td>
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

@section('scripts')
    <script>
        function confirmDelete() {
            return confirm('Are you sure you want to delete this event?');
        }
    </script>
@endsection
