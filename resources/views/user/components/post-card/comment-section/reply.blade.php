<div class="reply-input-wrapper" id="replyInput-{{ $commentId ?? '' }}" style="display: none;">
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
        <div class="user-initials-avatar reply-avatar" style="display: none;">
            {{ $authUserInitials }}
        </div>
    @else
        <div class="user-initials-avatar reply-avatar">
            {{ $authUserInitials }}
        </div>
    @endif

    <div class="comment-input-container">
        <input type="text"
               placeholder="Reply..."
               class="reply-input"
               oninput="toggleReplyButton(this)">
        <div class="comment-actions">
            <button class="emoji-picker-btn"><i class="fa-regular fa-face-smile"></i></button>
            <button class="post-reply-btn"
                    disabled
                    onclick="postReply('{{ $commentId ?? '' }}', '{{ $postId ?? '' }}')">Post</button>
        </div>
    </div>
</div>

<style>
.reply-input-wrapper {
    display: flex;
    align-items: flex-start;
    gap: 12px;
    padding: 12px 0 12px 52px; /* Indent replies */
    margin-top: 8px;
}

.reply-avatar {
    width: 32px;
    height: 32px;
    font-size: 12px;
    flex-shrink: 0;
}

.reply-input {
    width: 100%;
    padding: 8px 12px;
    border: 1px solid #e4e6eb;
    border-radius: 20px;
    font-size: 14px;
}

.reply-input:focus {
    outline: none;
    border-color: #0d6efd;
}

.post-reply-btn:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.post-reply-btn:not(:disabled) {
    color: #0d6efd;
    font-weight: 600;
}

.post-reply-btn:not(:disabled):hover {
    color: #0a58ca;
}
</style>
