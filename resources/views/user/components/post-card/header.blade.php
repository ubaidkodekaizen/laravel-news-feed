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

                {{-- FIXED: Use array access consistently --}}
                <a href="{{ route('user.profile', ['slug' => $post['user']['slug'] ?? '#']) }}" class="username">
                    {{ $post['user']['name'] ?? 'Unknown User' }}
                </a>

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
.post-header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 16px 20px;
    border-bottom: 1px solid #e4e6eb;
}

.user-info {
    display: flex;
    gap: 12px;
    flex: 1;
}

.user-img {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

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

.user_post_name {
    flex: 1;
    min-width: 0;
}

.username {
    margin: 0;
    font-size: 15px;
    line-height: 1.4;
}

.username a {
    color: #050505;
    text-decoration: none;
    font-weight: 600;
}

.username a:hover {
    text-decoration: underline;
}

.user-position {
    margin: 2px 0 4px 0;
    font-size: 13px;
    color: #65676b;
    line-height: 1.3;
}

.post-time {
    font-size: 13px;
    color: #65676b;
}

.visibility-badge {
    padding: 4px 10px;
    border-radius: 12px;
    background-color: #f0f2f5;
    font-size: 12px;
    color: #65676b;
    display: flex;
    align-items: center;
    gap: 4px;
    white-space: nowrap;
    margin-right: 8px;
}

.visibility-badge i {
    font-size: 10px;
}

.post-actions-menu {
    margin-left: auto;
}

.post-menu-btn {
    background: none;
    border: none;
    padding: 8px;
    cursor: pointer;
    color: #65676b;
    border-radius: 50%;
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background-color 0.2s;
}

.post-menu-btn:hover {
    background-color: #f0f2f5;
}

.post-menu-btn i {
    font-size: 20px;
}

/* Dropdown menu styling */
.dropdown-menu {
    min-width: 200px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    border: 1px solid #e4e6eb;
}

.dropdown-item {
    padding: 10px 16px;
    font-size: 14px;
}

.dropdown-item:hover {
    background-color: #f0f2f5;
}

.dropdown-item i {
    width: 20px;
}

/* Responsive */
@media (max-width: 768px) {
    .post-header {
        padding: 12px 16px;
    }

    .user-img,
    .user-initials-avatar {
        width: 40px;
        height: 40px;
    }

    .user-initials-avatar {
        font-size: 16px;
    }

    .username {
        font-size: 14px;
    }

    .user-position {
        font-size: 12px;
    }
}
</style>
