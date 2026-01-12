@extends('admin.layouts.main')
<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
<style>
    body{
        background: #fafbff !important;
    }
    .card-header:first-child {
        background: #fafbff !important;
        border: none;
    }
    .card-body {
        background: #fafbff !important;
    }
    .card-title {
        border-radius: 0;
        color: #333 !important;
        margin: 0;
        font-size: 28px;
        font-family: "Inter";
        font-weight: 500;
    }
    .info-label {
        font-family: "Inter";
        font-weight: 600;
        color: #333;
        margin-bottom: 5px;
    }
    .info-value {
        font-family: "Inter";
        color: #666;
        margin-bottom: 15px;
    }
</style>
@section('content')
<main class="main-content">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="card" style="border: none;">
                    <div class="card-header">
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="card-title">Service Details</h4>
                            <div>
                                <a href="{{ route('admin.edit.service', $service->id) }}" class="btn btn-warning">Edit</a>
                                <a href="{{ route('admin.products-services', ['filter' => 'services']) }}" class="btn btn-secondary">Back</a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-label">Service Title</div>
                                <div class="info-value">{{ $service->title }}</div>

                                <div class="info-label">Category</div>
                                <div class="info-value">{{ $service->category ?? 'N/A' }}</div>

                                <div class="info-label">Short Description</div>
                                <div class="info-value">{{ $service->short_description ?? 'N/A' }}</div>

                                <div class="info-label">Original Price</div>
                                <div class="info-value">${{ number_format($service->original_price, 2) }}</div>

                                <div class="info-label">Discounted Price</div>
                                <div class="info-value">{{ $service->discounted_price ? '$' . number_format($service->discounted_price, 2) : 'N/A' }}</div>

                                <div class="info-label">Duration</div>
                                <div class="info-value">{{ $service->duration ?? 'N/A' }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-label">Service Owner</div>
                                <div class="info-value">
                                    @if($service->user)
                                        {{ trim($service->user->first_name . ' ' . $service->user->last_name) ?: 'N/A' }}
                                    @else
                                        N/A
                                    @endif
                                </div>

                                <div class="info-label">Owner Email</div>
                                <div class="info-value">
                                    @if($service->user && $service->user->email)
                                        {{ $service->user->email }}
                                    @else
                                        N/A
                                    @endif
                                </div>

                                <div class="info-label">Service Image</div>
                                <div class="info-value">
                                    @if($service->service_image)
                                        <img src="{{ $service->service_image }}" alt="Service Image" style="max-width: 300px; max-height: 300px; border-radius: 10px;">
                                    @else
                                        No image
                                    @endif
                                </div>

                                <div class="info-label">Created At</div>
                                <div class="info-value">{{ $service->created_at->format('M d, Y h:i A') }}</div>

                                <div class="info-label">Updated At</div>
                                <div class="info-value">{{ $service->updated_at->format('M d, Y h:i A') }}</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection

