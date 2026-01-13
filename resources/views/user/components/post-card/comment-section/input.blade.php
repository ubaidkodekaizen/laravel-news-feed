<div class="comment-input-wrapper">
    @php
        $authUserHasPhoto = auth()->user()->photo ?? false;
        $authUserAvatar = getImageUrl(auth()->user()->photo) ?? auth()->user()->avatar ?? '';
        $authUserInitials = strtoupper(
            (auth()->user()->first_name ? substr(auth()->user()->first_name, 0, 1) : '') .
            (auth()->user()->last_name ? substr(auth()->user()->last_name, 0, 1) : 'U')
        );
    @endphp

    @if($authUserHasPhoto && $authUserAvatar)
        <img src="{{ $authUserAvatar }}"
             class="user-img"
             alt="{{ auth()->user()->name ?? 'You' }}"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
        <div class="user-initials-avatar comment-avatar" style="display: none;">
            {{ $authUserInitials }}
        </div>
    @else
        <div class="user-initials-avatar comment-avatar">
            {{ $authUserInitials }}
        </div>
    @endif

    <div class="comment-input-container">
        <input type="text"
               placeholder="Add a comment..."
               class="comment-input"
               oninput="toggleCommentButton(this)">
        <div class="comment-actions">
            <button class="emoji-picker-btn"><i class="fa-regular fa-face-smile"></i></button>
            <button class="post-comment-btn" disabled onclick="postComment('{{ $postId ?? $post['id'] ?? '' }}')">Post</button>
        </div>
    </div>
</div>

<style>
.comment-input-wrapper {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 20px;
}

.comment-avatar {
    width: 40px;
    height: 40px;
    font-size: 14px;
    flex-shrink: 0;
}

.comment-input-container {
    flex: 1;
    display: flex;
    flex-direction: column;
    gap: 8px;
}

.comment-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e4e6eb;
    border-radius: 20px;
    font-size: 14px;
}

.comment-input:focus {
    outline: none;
    border-color: #0d6efd;
}

.comment-actions {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
}

.emoji-picker-btn,
.post-comment-btn {
    padding: 4px 12px;
    border: none;
    background: none;
    cursor: pointer;
    font-size: 14px;
    color: #65676b;
}

.post-comment-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.post-comment-btn:not(:disabled) {
    color: #0d6efd;
    font-weight: 600;
}

.post-comment-btn:not(:disabled):hover {
    color: #0a58ca;
}
</style>
