@forelse ($products as $product)
    <div class="col-lg-4 mb-3">
        <div class="card product-trigger-wrapper" data-id="{{ $product->user->id }}"
            data-title="{{ $product->title }}" data-description="{{ $product->short_description }}"
            data-image="{{ $product->product_image ? asset('storage/' . $product->product_image) : 'assets/images/servicePlaceholderImg.png' }}"
            data-price="{{ $product->discounted_price && $product->discounted_price < $product->original_price ? '$' . $product->discounted_price . ' (was $' . $product->original_price . ')' : '$' . $product->original_price }}"
            data-quantity="{{ $product->quantity }}-{{ $product->unit_of_quantity }}"
            data-user-name="{{ $product->user->first_name }}"
            data-user-photo="{{ $product->user->photo ? asset('storage/' . $product->user->photo) : 'https://placehold.co/50x50' }}"
            data-date="{{ $product->created_at->format('d M Y') }}">

            <div class="event_slider_img_box">
                <img src="{{ $product->product_image ? asset('storage/' . $product->product_image) : 'assets/images/servicePlaceholderImg.png' }}"
                    alt="{{ $product->title }}" class="trigger-element">
                <div class="service_price_duration my-0 event_price_label">
                    <p class="service_price">
                        <span>
                            @if ($product->discounted_price && $product->discounted_price < $product->original_price)
                                <s>${{ $product->original_price }}</s>
                                ${{ $product->discounted_price }}
                            @else
                                ${{ $product->original_price }}
                            @endif
                            / {{ $product->quantity }}-{{ $product->unit_of_quantity }}
                        </span>
                    </p>
                </div>
            </div>

            <div class="card-content">
                <div class="details">
                    <h3 class="trigger-element">{{ $product->title }}</h3>
                    <p>{{ Str::limit($product->short_description, 100) }}</p>
                    <button type="button" class="btn btn-sm btn-primary mt-2 read-more-btn trigger-element">
                        Read More
                    </button>

                    <div class="service_posted_by mt-2">
                        <div class="person_profile">
                            <img src="{{ $product->user->photo ? asset('storage/' . $product->user->photo) : 'https://placehold.co/50x50' }}"
                                alt="{{ $product->user->first_name }}">
                        </div>
                        <div class="posted_name_date">
                            <h6>{{ $product->user->first_name }}</h6>
                            <p>{{ $product->created_at->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                <a href="javascript:void(0)" class="view-more direct-message-btn w-100"
                    data-receiver-id="{{ $product->user->id }}">Message Now</a>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="text-center">
            <p>No products found.</p>
        </div>
    </div>
@endforelse
