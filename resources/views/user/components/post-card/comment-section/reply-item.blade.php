<div class="comment reply" data-comment-id="{{ $reply['id'] ?? '' }}">
    @php
        $replyUser = $reply['user'] ?? [];
        $replyUserPhoto = $replyUser['avatar'] ?? '';
        $replyUserHasPhoto = ($replyUser['has_photo'] ?? false) && !empty($replyUserPhoto);
        $replyUserInitials = $replyUser['initials'] ??
            strtoupper(
                (isset($replyUser['first_name']) ? substr($replyUser['first_name'], 0, 1) : '') .
                (isset($replyUser['last_name']) ? substr($replyUser['last_name'], 0, 1) : 'U')
            );
    @endphp

    @if($replyUserHasPhoto && $replyUserPhoto)
        <img src="{{ $replyUserPhoto }}"
             class="user-img reply-avatar-img"
             alt="{{ $replyUser['name'] ?? 'User' }}"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        <div class="user-initials-avatar reply-avatar" style="display: none;">
            {{ $replyUserInitials }}
        </div>
    @else
        <div class="user-initials-avatar reply-avatar">
            {{ $replyUserInitials }}
        </div>
    @endif

    <div class="comment-body">
        <div class="comment-header">
            <a href="{{ route('user.profile', ['slug' => $replyUser['slug'] ?? '#']) }}" class="comment-username">
                <strong>{{ $replyUser['name'] ?? (($replyUser['first_name'] ?? '') . ' ' . ($replyUser['last_name'] ?? '')) }}</strong>
            </a>
            <span class="comment-time">
                @if(isset($reply['created_at']) && $reply['created_at'] instanceof \Carbon\Carbon)
                    {{ $reply['created_at']->diffForHumans() }}
                @else
                    Just now
                @endif
            </span>
        </div>
        <div class="comment-content" id="commentContent-{{ $reply['id'] ?? '' }}">
            {{ $reply['content'] ?? '' }}
        </div>
        <div class="comment-actions">
            <button class="like-comment-btn" onclick="likeComment('{{ $reply['id'] ?? '' }}')">Like</button>

            @if(auth()->check() && auth()->id() === ($reply['user_id'] ?? $replyUser['id'] ?? null))
                <button class="edit-comment-btn" onclick="editComment('{{ $reply['id'] ?? '' }}')">Edit</button>
                <button class="delete-comment-btn" onclick="deleteComment('{{ $reply['id'] ?? '' }}', '{{ $postId ?? ($post['id'] ?? '') }}')">Delete</button>
            @endif
        </div>
    </div>
</div>

<style>
.comment.reply {
    margin-left: 52px; /* Indent replies */
    margin-top: 12px;
}

.reply .user-img,
.reply .user-initials-avatar {
    width: 32px;
    height: 32px;
}

.reply-avatar-img {
    border-radius: 50%;
    object-fit: cover;
}

.reply .user-initials-avatar {
    font-size: 12px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    text-transform: uppercase;
}

.replies-container {
    margin-top: 8px;
}

.replies-container .comment.reply:first-child {
    margin-top: 0;
}

.comment-username {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s;
}

.comment-username:hover {
    color: #0a66c2;
    text-decoration: underline;
}

.comment-username strong {
    font-weight: 600;
}
</style>
