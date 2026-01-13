<div class="post-header">
    <div class="user-info">
        @if($post['user']['has_photo'] && $post['user']['avatar'])
            <img src="{{ $post['user']['avatar'] }}"
                 class="user-img"
                 alt="{{ $post['user']['name'] ?? 'User' }}"
                 onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
            <div class="user-initials-avatar" style="display: none;">
                {{ $post['user']['initials'] ?? 'U' }}
            </div>
        @else
            <div class="user-initials-avatar">
                {{ $post['user']['initials'] ?? 'U' }}
            </div>
        @endif

        <div class="user_post_name">
            <p class="username">
                <a href="{{ route('user.profile', $post['user']['slug'] ?? '#') }}">
                    {{ $post['user']['name'] ?? 'Unknown User' }}
                </a>
            </p>
            @if(!empty($post['user']['position']))
                <p class="user-position">{{ $post['user']['position'] }}</p>
            @endif
            <span class="post-time">
                @if(isset($post['created_at']) && $post['created_at'] instanceof \Carbon\Carbon)
                    {{ $post['created_at']->diffForHumans() }}
                @else
                    {{ $time ?? '1h ago' }}
                @endif
            </span>
        </div>
    </div>

    <!-- Visibility Indicator -->
    @if(isset($post['visibility']) && $post['visibility'] !== 'public')
        <span class="visibility-badge">
            <i class="fa-solid fa-{{ $post['visibility'] === 'private' ? 'lock' : 'user-group' }}"></i>
            {{ ucfirst($post['visibility']) }}
        </span>
    @endif

    @if($isOwner ?? false)
        <div class="post-actions-menu">
            <div class="dropdown">
                <button class="post-menu-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fa-solid fa-ellipsis"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                        <a class="dropdown-item" href="#" onclick="event.preventDefault(); editPost('{{ $post['id'] ?? '' }}')">
                            <i class="fa-solid fa-pen me-2"></i> Edit Post
                        </a>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); deletePost('{{ $post['id'] ?? '' }}')">
                            <i class="fa-solid fa-trash me-2"></i> Delete Post
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    @endif
</div>

<style>
.user-initials-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 18px;
    text-transform: uppercase;
    flex-shrink: 0;
}

.visibility-badge {
    padding: 4px 8px;
    border-radius: 12px;
    background-color: #f0f2f5;
    font-size: 12px;
    color: #65676b;
    display: flex;
    align-items: center;
    gap: 4px;
}

.visibility-badge i {
    font-size: 10px;
}

.username a {
    color: inherit;
    text-decoration: none;
    font-weight: 600;
}

.username a:hover {
    text-decoration: underline;
}
</style>
