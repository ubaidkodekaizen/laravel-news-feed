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
                                <a href="{{ url('/admin/events') }}"><img src=" {{ asset('assets/images/dashboard/dashboardBackChevron.svg') }}" alt=""></a>
                                    Edit Event
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.update.event', $event->id) }}" method="POST"
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
                                    <!-- Event Title -->
                                    <div class="col-12 mb-3">
                                        <label for="event_title" class="form-label">Event Title</label>
                                        <input type="text" id="event_title" name="event_title" class="form-control"
                                            value="{{ old('event_title', $event->title) }}" placeholder="Enter Event Title"
                                            required>
                                    </div>

                                    <!-- Event City -->
                                    <div class="col-12 mb-3">
                                        <label for="event_city" class="form-label">Event City</label>
                                        <input type="text" id="event_city" name="event_city" class="form-control"
                                            value="{{ old('event_city', $event->city) }}" placeholder="Enter Event City"
                                            required>
                                    </div>

                                    <!-- Event Time -->
                                    <div class="col-6 mb-3">
                                        <label for="event_time" class="form-label">Event Time</label>
                                        <input type="time" id="event_time" name="event_time" class="form-control"
                                            value="{{ old('event_time', $event->time) }}" required>
                                    </div>

                                    <!-- Event Date -->
                                    <div class="col-6 mb-3">
                                        <label for="event_date" class="form-label">Event Date</label>
                                        <input type="date" id="event_date" name="event_date" class="form-control"
                                            value="{{ old('event_date', $event->date) }}" required>
                                    </div>

                                    <!-- Event Venue -->
                                    <div class="col-12 mb-3">
                                        <label for="event_venue" class="form-label">Event Venue</label>
                                        <input type="text" id="event_venue" name="event_venue" class="form-control"
                                            value="{{ old('event_venue', $event->venue) }}" placeholder="Enter Event Venue"
                                            required>
                                    </div>

                                    <!-- Event URL -->
                                    <div class="col-12 mb-3">
                                        <label for="event_url" class="form-label">Event URL</label>
                                        <input type="url" id="event_url" name="event_url" class="form-control"
                                            value="{{ old('event_url', $event->url) }}" placeholder="Enter Event URL"
                                            required>
                                    </div>

                                    <!-- Event Image -->
                                    <div class="col-12 mb-3">
                                        <label for="event_image" class="form-label">Event Image</label>
                                        @if ($event->image)
                                            <img src="{{ getImageUrl($event->image) }}" alt="Event Image"
                                                class="img-thumbnail mb-2" width="150">
                                        @endif
                                        <input type="file" id="event_image" name="event_image" class="form-control"
                                            accept="image/*">
                                    </div>

                                    <!-- Submit Button -->
                                    <div class="col-12">
                                        <button type="submit" class="btn btn-primary">Update Event</button>
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
