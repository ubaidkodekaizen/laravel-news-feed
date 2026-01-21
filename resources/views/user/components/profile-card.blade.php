<div class="card" id="feedProfileCard">
    <div class="profile_cover_bg"
        style="
        {{ !empty($authUserData['company']['logo'])
            ? "background-image: url('{$authUserData['company']['logo']}');"
            : 'background: #b8c034;' }}
    ">
    </div>



    <div class="profile_card_pic">
        @if ($authUserData['user_has_photo'] && $authUserData['photo'])
            <img src="{{ $authUserData['photo'] }}" class=""
                alt="{{ trim($authUserData['first_name'] . ' ' . $authUserData['last_name']) }}"
                onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="profile-initials-avatar" style="display: none;">
                {{ $authUserData['user_initials'] }}
            </div>
        @else
            <div class="profile-initials-avatar">
                {{ $authUserData['user_initials'] }}
            </div>
        @endif
    </div>


    <div class="profile_card_details">
        <h6>{{ auth()->user()->name ?? (auth()->user()->first_name ?? '') . ' ' . (auth()->user()->last_name ?? '') }}
        </h6>
        <p>{{ auth()->user()->bio ?? (auth()->user()->headline ?? 'Welcome to MuslimLynk - your gateway to empowerment, collaboration, and success within the Muslim community.') }}
        </p>

        <div class="profile_card_details_inner">
            {{-- <div class="profile_card_details_inner_box">
                <h4>Profile views</h4>
                <p>{{ $profileViews ?? 0 }}</p>
            </div> --}}
            <div class="profile_card_details_inner_box">
                <h4>Post impressions</h4>
                <p>{{ $postImpressions ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>


@if (isset($ads) && $ads->count() > 0)
    <div class="card" id="feedAdCard">
        <div class="swiper">
            <div class="swiper-wrapper">
                @foreach ($ads as $ad)
                    <div class="swiper-slide">
                        <a href="{{ $ad->url }}" target="_blank" rel="noopener noreferrer"
                            onclick="trackAdClick({{ $ad->id }})">
                            <div class="ad-slide-content">
                                <img src="{{ $ad->media }}" alt="Advertisement" class="ad-image"
                                    onerror="this.src='{{ asset('assets/images/ad-placeholder.png') }}'">
                                @if ($ad->featured)
                                    <span class="featured-badge">
                                        <i class="fa-solid fa-star"></i> Featured
                                    </span>
                                @endif
                            </div>
                        </a>
                    </div>
                @endforeach
            </div>

            <!-- Pagination dots -->
            @if ($ads->count() > 1)
                <div class="swiper-pagination"></div>
            @endif

            <!-- Navigation arrows (only show if more than 1 ad) -->
            @if ($ads->count() > 1)
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            @endif
        </div>
    </div>



    <script>
        function trackAdClick(adId) {
            // Track ad clicks
            fetch('/ads/track-click', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify({
                    ad_id: adId
                })
            }).catch(err => console.log('Ad tracking failed:', err));
        }
    </script>
@endif
