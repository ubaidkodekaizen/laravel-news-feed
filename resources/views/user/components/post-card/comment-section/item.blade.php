<div class="comment" data-comment-id="{{ $comment['id'] ?? '' }}">
    <img src="{{ $comment['user']['avatar'] ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
         class="user-img"
         alt="{{ $comment['user']['name'] ?? 'User' }}">
    <div class="comment-body">
        <div class="comment-header">
            <strong>{{ $comment['user']['name'] ?? 'Anonymous' }}</strong>
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
            <button class="like-comment-btn">Like</button>
            <button class="reply-comment-btn" onclick="toggleReplyInput('{{ $comment['id'] ?? '' }}')">Reply</button>
        </div>

        @include('user.components.post-card.comment-section.reply', [
            'commentId' => $comment['id'] ?? ''
        ])
    </div>
</div>
