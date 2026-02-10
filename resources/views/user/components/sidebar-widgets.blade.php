<div id="sidebarWidgetWrapper">
    <!-- Products and Services removed - not part of newsfeed boilerplate -->




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
    @if (!isset($suggestedConnections) || count($suggestedConnections) === 0)
        <div class="sidebar-widget">
            <div class="widget-header">Stay Connected</div>
            <div class="divider"></div>
            <div class="sidebar-widget-inner">
                <p class="text-muted text-center py-4">
                    Connect with people and discover new posts in your feed.
                </p>
            </div>
        </div>
    @endif
</div>
