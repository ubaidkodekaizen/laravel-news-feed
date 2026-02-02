{{-- Displays a reposted/shared post --}}

@if(isset($post['original_post_id']) && $post['original_post_id'] && isset($post['original_post']))
    <div class="post-shared-badge">
        <i class="fa-solid fa-retweet"></i>
        <span>
            <a href="{{ route('user.profile', ['slug' => $post['user']['slug'] ?? '#']) }}" class="reposted-username">
                <strong>{{ $post['user']['name'] ?? 'User' }}</strong>
            </a> reposted
        </span>
    </div>

    @if(!empty($post['content']))
        <div class="post-text-wrapper mb-3">
            <div class="post-text">
                {!! nl2br(e($post['content'])) !!}
            </div>
        </div>
    @endif

    @php
        $originalPost = $post['original_post'];
        $opUserHasPhoto = $originalPost['user']['has_photo'] ?? !empty($originalPost['user']['avatar']);
        $opUserAvatar = $originalPost['user']['avatar'] ?? '';
        $opUserInitials = $originalPost['user']['initials'] ?? 'U';
    @endphp

    <div class="shared-post-wrapper" onclick="if('{{ $originalPost['slug'] ?? '' }}') window.location.href='/news-feed/posts/{{ $originalPost['slug'] }}'">
        <div class="post-header">
            <div class="user-info">
                @if($opUserHasPhoto && $opUserAvatar)
                    <img src="{{ $opUserAvatar }}"
                         class="user-img"
                         alt="{{ $originalPost['user']['name'] ?? 'User' }}"
                         onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="user-initials-avatar" style="display: none; width: 40px; height: 40px; font-size: 14px;">
                        {{ $opUserInitials }}
                    </div>
                @else
                    <div class="user-initials-avatar" style="width: 40px; height: 40px; font-size: 14px;">
                        {{ $opUserInitials }}
                    </div>
                @endif

                <div class="user_post_name">
                    <a href="{{ route('user.profile', ['slug' => $originalPost['user']['slug'] ?? '#']) }}" class="username">
                        {{ $originalPost['user']['name'] ?? 'Unknown User' }}
                    </a>
                    @if(!empty($originalPost['user']['position']))
                        <p class="user-position">{{ $originalPost['user']['position'] }}</p>
                    @endif
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
                $videoUrls = [];
                foreach ($originalPost['media'] as $media) {
                    if (isset($media['media_type']) && isset($media['media_url'])) {
                        $mediaUrl = getImageUrl($media['media_url']) ?? $media['media_url'];
                        if ($media['media_type'] === 'image') {
                            $imageUrls[] = $mediaUrl;
                        } elseif ($media['media_type'] === 'video') {
                            $videoUrls[] = [
                                'url' => $mediaUrl,
                                'mime_type' => $media['mime_type'] ?? 'video/mp4'
                            ];
                        }
                    }
                }
            @endphp

            {{-- Display videos --}}
            @if(!empty($videoUrls))
                <div class="post-videos mt-2">
                    <video controls class="shared-post-video">
                        <source src="{{ $videoUrls[0]['url'] }}" type="{{ $videoUrls[0]['mime_type'] }}">
                        Your browser does not support the video tag.
                    </video>
                    @if(count($videoUrls) > 1)
                        <div class="more-media-indicator">+{{ count($videoUrls) - 1 }} more videos</div>
                    @endif
                </div>
            @endif

            {{-- Display images --}}
            @if(!empty($imageUrls))
                <div class="post-images mt-2">
                    <img src="{{ $imageUrls[0] }}"
                         alt="Post image"
                         class="post-image"
                         style="max-height: 200px; width: 100%; object-fit: cover; border-radius: 8px;">
                    @if(count($imageUrls) > 1)
                        <div class="more-media-indicator">+{{ count($imageUrls) - 1 }} more images</div>
                    @endif
                </div>
            @endif
        @endif
    </div>
@endif

<style>
.post-shared-badge {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 20px;
    font-size: 13px;
    color: #65676b;
    background-color: #f8f9fa;
    border-bottom: 1px solid #e4e6eb;
}

.post-shared-badge i {
    color: #0d6efd;
}

.shared-post-wrapper {
    margin: 16px 20px;
    padding: 16px;
    border: 1px solid #e4e6eb;
    border-radius: 8px;
    background-color: #f8f9fa;
    cursor: pointer;
    transition: all 0.2s;
}

.shared-post-wrapper:hover {
    background-color: #f0f2f5;
    border-color: #d0d0d0;
    transform: translateY(-1px);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
}

.shared-post-wrapper .post-header {
    padding: 0;
    border: none;
    margin-bottom: 12px;
}

.shared-post-wrapper .user_post_name {
    margin-left: 0;
}

.shared-post-wrapper .post-text {
    margin-top: 12px;
    font-size: 14px;
    color: #050505;
    line-height: 1.5;
}

.shared-post-wrapper .post-images,
.shared-post-wrapper .post-videos {
    margin-top: 12px;
    position: relative;
}

.shared-post-video {
    width: 100%;
    max-height: 200px;
    border-radius: 8px;
    background-color: #000;
}

.more-media-indicator {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    padding: 4px 12px;
    border-radius: 16px;
    font-size: 12px;
    font-weight: 600;
}

.reposted-username {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s;
}

.reposted-username:hover {
    color: #0a66c2;
    text-decoration: underline;
}

.reposted-username strong {
    font-weight: 600;
}
</style>
