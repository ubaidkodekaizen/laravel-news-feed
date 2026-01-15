<div id="sidebarWidgetWrapper">
    <!-- Recent Products -->
    @if (isset($recentProducts) && count($recentProducts) > 0)
        <div class="sidebar-widget">
            <div class="widget-header">Recent Products
                <a href="{{ route('products') }}" class="see-all-btn">View All</a>
            </div>
            <div class="divider"></div>
            <div class="sidebar-widget-inner">
                @foreach ($recentProducts as $product)
                    <div class="feed-item">
                        <div class="feed-icon">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                            <small>${{ number_format($product->price ?? 0, 2) }}</small>
                        </div>
                        <div class="feed-info">
                            <div class="feed-name">{{ Str::limit($product->name ?? 'Product', 20) }}</div>
                            <div class="feed-para">{{ Str::limit($product->description ?? '', 60) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Recent Services -->
    @if (isset($recentServices) && count($recentServices) > 0)
        <div class="sidebar-widget services">
            <div class="widget-header">Recent Services
                <a href="{{ route('services') }}" class="see-all-btn">View All</a>
            </div>
            <div class="divider"></div>
            <div class="sidebar-widget-inner">
                @foreach ($recentServices as $key => $service)
                    <div class="feed-item feed-item-{{ $key + 1 }}">
                        <div class="feed-icon">
                            <img src="{{ $service->image_url }}" alt="{{ $service->name }}">
                            {{-- <small>${{ number_format($service->price ?? 0, 2) }}</small> --}}
                        </div>
                        <div class="feed-info">
                            <div class="feed-name">{{ Str::limit($service->name ?? 'Service', 20) }}</div>
                            <div class="feed-para">{{ Str::limit($service->description ?? '', 30) }}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif




    <!-- Suggested Connections -->
    @if (isset($suggestedConnections) && count($suggestedConnections) > 0)
        <div class="sidebar-widget">
            <div class="widget-header">People you may know
                <a href="#" class="see-all-btn">See All</a>
            </div>
            <div class="divider"></div>
            <div class="sidebar-widget-inner">
                @foreach ($suggestedConnections as $user)
                    <div class="feed-item">
                        <div class="feed-icon">
                            <img src="{{ getImageUrl($user->photo) ?? ($user->avatar ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png') }}"
                                alt="{{ $user->name }}">
                        </div>
                        <div class="feed-info">
                            <div class="feed-name">
                                {{ $user->name ?? ($user->first_name ?? '') . ' ' . ($user->last_name ?? '') }}</div>
                            <div class="feed-para">{{ $user->position ?? ($user->job_title ?? 'Professional') }}</div>
                            <button class="btn btn-sm btn-outline-primary mt-2"
                                onclick="connectUser({{ $user->id }})">
                                <i class="fa-solid fa-user-plus"></i> Connect
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Fallback if no data -->
    @if (
        (!isset($recentProducts) || count($recentProducts) === 0) &&
            (!isset($recentServices) || count($recentServices) === 0) &&
            (!isset($recentIndustries) || count($recentIndustries) === 0) &&
            (!isset($suggestedConnections) || count($suggestedConnections) === 0))
        <div class="sidebar-widget">
            <div class="widget-header">Stay Connected</div>
            <div class="divider"></div>
            <div class="sidebar-widget-inner">
                <p class="text-muted text-center py-4">
                    Explore products, services, and connect with professionals in your industry.
                </p>
            </div>
        </div>
    @endif
</div>
