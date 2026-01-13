<div class="post-header">
    <div class="user-info">
        <img src="{{ $post['user']['avatar'] ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
             class="user-img"
             alt="{{ $post['user']['name'] ?? 'User' }}">
        <div class="user_post_name">
            <p class="username">{{ $post['user']['name'] ?? 'Unknown User' }}</p>
            <p class="user-position">{{ $post['user']['position'] ?? '' }}</p>
            <span class="post-time">
                @if(isset($post['created_at']) && $post['created_at'] instanceof \Carbon\Carbon)
                    {{ $post['created_at']->diffForHumans() }}
                @else
                    {{ $time ?? '1h ago' }}
                @endif
            </span>
        </div>
    </div>
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
