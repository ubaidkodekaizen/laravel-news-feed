{{-- This component displays a post that is a repost/share of another post --}}

@if(isset($post['original_post_id']) && $post['original_post_id'])
    <div class="post-shared-badge">
        <i class="fa-solid fa-retweet"></i>
        <span><strong>{{ $post['user']['name'] ?? 'User' }}</strong> reposted</span>
    </div>

    @if(!empty($post['content']))
        <div class="post-text-wrapper mb-3">
            <div class="post-text">
                {!! nl2br(e($post['content'])) !!}
            </div>
        </div>
    @endif

    @php
        // Get original post data
        $originalPost = $post['original_post'] ?? null;
    @endphp

    @if($originalPost)
        <div class="shared-post-wrapper" onclick="window.location.href='/feed/posts/{{ $originalPost['slug'] ?? '' }}'">
            <div class="post-header">
                <div class="user-info">
                    <img src="{{ $originalPost['user']['avatar'] ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
                         class="user-img"
                         alt="{{ $originalPost['user']['name'] ?? 'User' }}">
                    <div class="user_post_name">
                        <p class="username">{{ $originalPost['user']['name'] ?? 'Unknown User' }}</p>
                        <p class="user-position">{{ $originalPost['user']['position'] ?? '' }}</p>
                        <span class="post-time">
                            @if(isset($originalPost['created_at']) && $originalPost['created_at'] instanceof \Carbon\Carbon)
                                {{ $originalPost['created_at']->diffForHumans() }}
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            @if(!empty($originalPost['content']))
                <div class="post-text">
                    {{ Str::limit($originalPost['content'], 200) }}
                </div>
            @endif

            @if(!empty($originalPost['media']) && is_array($originalPost['media']))
                @php
                    $imageUrls = [];
                    foreach ($originalPost['media'] as $media) {
                        if (isset($media['media_type']) && $media['media_type'] === 'image' && isset($media['media_url'])) {
                            $imageUrls[] = getImageUrl($media['media_url']) ?? $media['media_url'];
                        }
                    }
                @endphp

                @if(!empty($imageUrls))
                    <div class="post-images mt-2">
                        <img src="{{ $imageUrls[0] }}" alt="Post image" class="post-image" style="max-height: 200px; width: 100%; object-fit: cover; border-radius: 8px;">
                        @if(count($imageUrls) > 1)
                            <div class="more-images-indicator">+{{ count($imageUrls) - 1 }} more</div>
                        @endif
                    </div>
                @endif
            @endif
        </div>
    @endif
@endif
