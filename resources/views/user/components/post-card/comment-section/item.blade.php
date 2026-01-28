<div class="comment" data-comment-id="{{ $comment['id'] ?? '' }}">
    @php
        $commentUser = $comment['user'] ?? [];
        $commentUserPhoto = $commentUser['avatar'] ?? '';
        $commentUserHasPhoto = ($commentUser['has_photo'] ?? false) && !empty($commentUserPhoto);
        $commentUserInitials = $commentUser['initials'] ??
            strtoupper(
                (isset($commentUser['first_name']) ? substr($commentUser['first_name'], 0, 1) : '') .
                (isset($commentUser['last_name']) ? substr($commentUser['last_name'], 0, 1) : 'U')
            );
    @endphp

    @if($commentUserHasPhoto && $commentUserPhoto)
        <img src="{{ $commentUserPhoto }}"
             class="user-img"
             alt="{{ $commentUser['name'] ?? 'User' }}"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        <div class="user-initials-avatar comment-avatar" style="display: none;">
            {{ $commentUserInitials }}
        </div>
    @else
        <div class="user-initials-avatar comment-avatar">
            {{ $commentUserInitials }}
        </div>
    @endif

    <div class="comment-body">
        <div class="comment-header">
            <a href="{{ route('user.profile', ['slug' => $commentUser['slug'] ?? '#']) }}" class="comment-username">
                <strong>{{ $commentUser['name'] ?? (($commentUser['first_name'] ?? '') . ' ' . ($commentUser['last_name'] ?? '')) }}</strong>
            </a>
            <span class="comment-time">
                @if(isset($comment['created_at']) && $comment['created_at'] instanceof \Carbon\Carbon)
                    {{ $comment['created_at']->diffForHumans() }}
                @else
                    30m ago
                @endif
            </span>
        </div>
        <div class="comment-content" id="commentContent-{{ $comment['id'] ?? '' }}">
            {{ $comment['content'] ?? '' }}
        </div>
        <div class="comment-actions">
            <button class="like-comment-btn {{ ($comment['user_has_reacted'] ?? false) ? 'active' : '' }}"
                    onclick="likeComment('{{ $comment['id'] ?? '' }}')">
                {{ ($comment['user_has_reacted'] ?? false) ? 'Liked' : 'Like' }}
            </button>
            <button class="reply-comment-btn" onclick="toggleReplyInput('{{ $comment['id'] ?? '' }}')">Reply</button>

            @if(auth()->check() && auth()->id() === ($comment['user_id'] ?? $commentUser['id'] ?? null))
                <button class="edit-comment-btn" onclick="editComment('{{ $comment['id'] ?? '' }}')">Edit</button>
                <button class="delete-comment-btn" onclick="deleteComment('{{ $comment['id'] ?? '' }}', '{{ $postId ?? ($post['id'] ?? '') }}')">Delete</button>
            @endif
        </div>

        {{-- Reply Input --}}
        @include('user.components.post-card.comment-section.reply', [
            'commentId' => $comment['id'] ?? '',
            'postId' => $postId ?? ($post['id'] ?? '')
        ])

        {{-- Replies List --}}
        @if(!empty($comment['replies']) && is_array($comment['replies']))
            <div class="replies-container">
                @foreach($comment['replies'] as $reply)
                    @include('user.components.post-card.comment-section.reply-item', [
                        'reply' => $reply,
                        'postId' => $postId ?? ($post['id'] ?? '')
                    ])
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

.comment-actions {
    display: flex;
    gap: 12px;
    margin-top: 8px;
}

.comment-actions button {
    background: none;
    border: none;
    color: #65676b;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    padding: 0;
    transition: color 0.2s;
}

.comment-actions button:hover {
    color: #0d6efd;
}

.like-comment-btn.active {
    color: #0d6efd;
}

.edit-comment-btn {
    color: #198754 !important;
}

.edit-comment-btn:hover {
    color: #146c43 !important;
}

.delete-comment-btn {
    color: #dc3545 !important;
}

.delete-comment-btn:hover {
    color: #bb2d3b !important;
}

/* Edit Mode Styles */
.comment-edit-mode {
    display: flex;
    flex-direction: column;
    gap: 8px;
    margin-top: 8px;
}

.comment-edit-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #0d6efd;
    border-radius: 20px;
    font-size: 14px;
    resize: none;
    min-height: 60px;
    font-family: inherit;
}

.comment-edit-input:focus {
    outline: none;
    border-color: #0a58ca;
    box-shadow: 0 0 0 3px rgba(13, 110, 253, 0.1);
}

.comment-edit-actions {
    display: flex;
    gap: 8px;
    justify-content: flex-end;
}

.comment-edit-actions button {
    padding: 6px 16px;
    border-radius: 20px;
    font-size: 13px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.comment-edit-save {
    background-color: #0d6efd;
    color: white;
}

.comment-edit-save:hover {
    background-color: #0a58ca;
}

.comment-edit-save:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.comment-edit-cancel {
    background-color: #e4e6eb;
    color: #050505;
}

.comment-edit-cancel:hover {
    background-color: #d0d0d0;
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
