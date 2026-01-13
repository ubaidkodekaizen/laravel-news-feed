<div class="comment reply" data-comment-id="{{ $reply['id'] ?? '' }}">
    @php
        $replyUserHasPhoto = $reply['user']['has_photo'] ?? !empty($reply['user']['avatar']);
        $replyUserAvatar = $reply['user']['avatar'] ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png';
        $replyUserInitials = $reply['user']['initials'] ??
            strtoupper(
                (isset($reply['user']['first_name']) ? substr($reply['user']['first_name'], 0, 1) : '') .
                (isset($reply['user']['last_name']) ? substr($reply['user']['last_name'], 0, 1) : 'U')
            );
    @endphp

    @if($replyUserHasPhoto && $replyUserAvatar)
        <img src="{{ $replyUserAvatar }}"
             class="user-img"
             alt="{{ $reply['user']['name'] ?? 'User' }}"
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
            <strong>{{ $reply['user']['name'] ?? ($reply['user']['first_name'] ?? '') . ' ' . ($reply['user']['last_name'] ?? '') }}</strong>
            <span class="comment-time">
                @if(isset($reply['created_at']) && $reply['created_at'] instanceof \Carbon\Carbon)
                    {{ $reply['created_at']->diffForHumans() }}
                @else
                    Just now
                @endif
            </span>
        </div>
        <div class="comment-content">{{ $reply['content'] ?? '' }}</div>
        <div class="comment-actions">
            <button class="like-comment-btn" onclick="likeComment('{{ $reply['id'] ?? '' }}')">Like</button>
            @if(auth()->check() && auth()->id() === ($reply['user_id'] ?? $reply['user']['id'] ?? null))
                <button class="delete-comment-btn" onclick="deleteComment('{{ $reply['id'] ?? '' }}', '{{ $postId ?? $post['id'] ?? '' }}')">Delete</button>
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

.reply .user-initials-avatar {
    font-size: 12px;
}

.replies-container {
    margin-top: 8px;
}

.replies-container .comment.reply:first-child {
    margin-top: 0;
}
</style>
