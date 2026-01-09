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

    .view-field {
        background: #FFFFFF;
        border-radius: 9.77px !important;
        border: 2px solid #E9EBF0 !important;
        font-family: Inter !important;
        font-weight: 400 !important;
        font-size: 16px !important;
        padding: 12px 15px;
        color: #333;
        margin-bottom: 20px;
    }

    .card .card-header .card-title a img {
        width: 14px !important;
        margin-top: -6px;
        margin-right: 16px;
        border: none !important;
    }

    .event-image {
        max-width: 100%;
        height: auto;
        border-radius: 9.77px;
        margin-top: 10px;
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
                                <a href="{{ route('admin.events') }}"><img src="{{ asset('assets/images/dashboard/dashboardBackChevron.svg') }}" alt=""></a>
                                View Event
                            </h4>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <!-- Event Title -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Event Title</label>
                                    <div class="view-field">{{ $event->title }}</div>
                                </div>

                                <!-- Event City -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Event City</label>
                                    <div class="view-field">{{ $event->city }}</div>
                                </div>

                                <!-- Event Time and Date -->
                                <div class="col-6 mb-3">
                                    <label class="form-label">Event Time</label>
                                    <div class="view-field">{{ \Carbon\Carbon::parse($event->time)->format('h:i A') }}</div>
                                </div>

                                <div class="col-6 mb-3">
                                    <label class="form-label">Event Date</label>
                                    <div class="view-field">{{ \Carbon\Carbon::parse($event->date)->format('d F Y') }}</div>
                                </div>

                                <!-- Event Venue -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Event Venue</label>
                                    <div class="view-field">{{ $event->venue }}</div>
                                </div>

                                <!-- Event URL -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Event URL</label>
                                    <div class="view-field">
                                        <a href="{{ $event->url }}" target="_blank" class="text-primary">{{ $event->url }}</a>
                                    </div>
                                </div>

                                <!-- Event Image -->
                                @if($event->image)
                                <div class="col-12 mb-3">
                                    <label class="form-label">Event Image</label>
                                    <div>
                                        <img src="{{ getImageUrl($event->image) }}" alt="Event Image" class="event-image" style="max-width: 400px;">
                                    </div>
                                </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="col-12 mt-4">
                                    @php
                                        $user = Auth::user();
                                        $isAdmin = $user && $user->role_id == 1;
                                        $canEdit = $isAdmin || ($user && $user->hasPermission('events.edit'));
                                    @endphp
                                    @if($canEdit)
                                    <a href="{{ route('admin.edit.event', $event->id) }}" class="btn btn-primary">Edit Event</a>
                                    @endif
                                    <a href="{{ route('admin.events') }}" class="btn btn-secondary">Back to Events</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

