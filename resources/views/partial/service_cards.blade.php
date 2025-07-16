@forelse($services as $service)
    <div class="col-lg-4 mb-3">
        <div class="card service-trigger-wrapper" data-id="{{ $service->user->id }}" data-title="{{ $service->title }}"
            data-description="{{ $service->short_description }}"
            data-image="{{ $service->service_image ? asset('storage/' . $service->service_image) : 'https://placehold.co/420x250' }}"
            data-price="{{ $service->discounted_price && $service->discounted_price < $service->original_price ? '$' . $service->discounted_price . ' (was $' . $service->original_price . ')' : '$' . $service->original_price }}"
            data-quantity="{{ $service->duration }}" data-user-name="{{ $service->user->first_name }}"
            data-user-photo="{{ $service->user->photo ? asset('storage/' . $service->user->photo) : 'https://placehold.co/50x50' }}"
            data-date="{{ $service->created_at->format('d M Y') }}">
            <div class="card-header p-0 border-0 service_slider_img_box">
                <img src="{{ $service->service_image ? asset('storage/' . $service->service_image) : 'https://placehold.co/420x250' }}"
                    alt="{{ $service->title }}" class="img-fluid rounded trigger-element">
                <div class="service_price_duration my-0 event_price_label">
                    <p class="service_price">

                        <span>
                            @if ($service->discounted_price && $service->discounted_price < $service->original_price)
                                <s>${{ $service->original_price }}</s>
                                ${{ $service->discounted_price }}
                            @else
                                ${{ $service->original_price }}
                            @endif
                            / {{ $service->duration }}
                        </span>


                    </p>
                </div>

            </div>
            <div class="card-body">
                <h3 class="service_heading trigger-element">{{ $service->title }}</h3>
                <p>{{ Str::limit($service->short_description, 100) }}</p>
                <button type="button" class="btn btn-sm btn-primary mt-2 read-more-btn trigger-element">
                    Read More
                </button>
                <div class="service_price_duration">
                    <div class="service_price">
                        <div class="service_posted_by">
                            <div class="person_profile">
                                <img src="{{ $service->user->photo ? asset('storage/' . $service->user->photo) : 'https://placehold.co/50x50' }}"
                                    alt="{{ $service->user->first_name }}">
                            </div>
                            <div class="posted_name_date">
                                <h6>{{ $service->user->first_name }}
                                </h6>
                                <p>{{ $service->created_at->format('d M Y') }}</p>
                            </div>
                        </div>

                    </div>
                </div>
                <a href="javascript:void(0)" class="btn btn-primary direct-message-btn w-100"
                    data-receiver-id="{{ $service->user->id }}">Message Now</a>

            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="text-center">
            <p>No services available.</p>
        </div>
    </div>
@endforelse