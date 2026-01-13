<div class="comment" data-comment-id="{{ $comment['id'] ?? '' }}">
    @php
        $userHasPhoto = $comment['user']['has_photo'] ?? !empty($comment['user']['avatar']);
        $userAvatar = $comment['user']['avatar'] ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png';
        $userInitials = $comment['user']['initials'] ??
            strtoupper(
                (isset($comment['user']['first_name']) ? substr($comment['user']['first_name'], 0, 1) : '') .
                (isset($comment['user']['last_name']) ? substr($comment['user']['last_name'], 0, 1) : 'U')
            );
    @endphp

    @if($userHasPhoto && $userAvatar)
        <img src="{{ $userAvatar }}"
             class="user-img"
             alt="{{ $comment['user']['name'] ?? 'User' }}"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        <div class="user-initials-avatar comment-avatar" style="display: none;">
            {{ $userInitials }}
        </div>
    @else
        <div class="user-initials-avatar comment-avatar">
            {{ $userInitials }}
        </div>
    @endif

    <div class="comment-body">
        <div class="comment-header">
            <strong>{{ $comment['user']['name'] ?? ($comment['user']['first_name'] ?? '') . ' ' . ($comment['user']['last_name'] ?? '') }}</strong>
            <span class="comment-time">
                @if(isset($comment['created_at']) && $comment['created_at'] instanceof \Carbon\Carbon)
                    {{ $comment['created_at']->diffForHumans() }}
                @else
                    30m ago
                @endif
            </span>
        </div>
        <div class="comment-content">{{ $comment['content'] ?? '' }}</div>
        <div class="comment-actions">
            <button class="like-comment-btn" onclick="likeComment('{{ $comment['id'] ?? '' }}')">Like</button>
            <button class="reply-comment-btn" onclick="toggleReplyInput('{{ $comment['id'] ?? '' }}')">Reply</button>
            @if(auth()->check() && auth()->id() === ($comment['user_id'] ?? $comment['user']['id'] ?? null))
                <button class="delete-comment-btn" onclick="deleteComment('{{ $comment['id'] ?? '' }}', '{{ $postId ?? $post['id'] ?? '' }}')">Delete</button>
            @endif
        </div>

        @include('user.components.post-card.comment-section.reply', [
            'commentId' => $comment['id'] ?? '',
            'postId' => $postId ?? $post['id'] ?? ''
        ])

        {{-- Render replies if they exist --}}
        @if(!empty($comment['replies']) && is_array($comment['replies']))
            <div class="replies-container">
                @foreach($comment['replies'] as $reply)
                    @include('user.components.post-card.comment-section.reply-item', ['reply' => $reply])
                @endforeach
            </div>
        @endif
    </div>
</div>

<style>
.comment-avatar {
    width: 40px;
    height: 40px;
    font-size: 14px;
}

.reply .user-initials-avatar {
    width: 32px;
    height: 32px;
    font-size: 12px;
}
</style>
