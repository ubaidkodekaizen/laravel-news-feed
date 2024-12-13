@extends('admin.layouts.main')
@section('content')
    <main class="main-content">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add Event</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.store.event') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="row">
                                    <div class="col-12 mb-3">
                                        <label for="event_title" class="form-label">Event Title</label>
                                        <input type="text" id="event_title" name="event_title" class="form-control"
                                            value="{{ old('event_title') }}" placeholder="Enter Event Title" required>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="event_city" class="form-label">Event City</label>
                                        <input type="text" id="event_city" name="event_city" class="form-control"
                                            value="{{ old('event_city') }}" placeholder="Enter Event City" required>
                                    </div>

                                    <div class="col-6 mb-3">
                                        <label for="event_time" class="form-label">Event Time</label>
                                        <input type="time" id="event_time" name="event_time" class="form-control"
                                            value="{{ old('event_time') }}" required>
                                    </div>

                                    <div class="col-6 mb-3">
                                        <label for="event_date" class="form-label">Event Date</label>
                                        <input type="date" id="event_date" name="event_date" class="form-control"
                                            value="{{ old('event_date') }}" required>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="event_venue" class="form-label">Event Venue</label>
                                        <input type="text" id="event_venue" name="event_venue" class="form-control"
                                            value="{{ old('event_venue') }}" placeholder="Enter Event Venue" required>
                                    </div>

                                    <div class="col-12 mb-3">
                                        <label for="event_url" class="form-label">Event URL</label>
                                        <input type="url" id="event_url" name="event_url" class="form-control"
                                            value="{{ old('event_url') }}" placeholder="Enter Event URL" required>
                                    </div>

                                    <!-- Event Image -->
                                    <div class="col-12 mb-3">
                                        <label for="event_image" class="form-label">Event Image</label>
                                        <input type="file" id="event_image" name="event_image" class="form-control"
                                            accept="image/*">
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Add Event</button>
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
