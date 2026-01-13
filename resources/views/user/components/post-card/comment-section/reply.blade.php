<div class="reply-input-wrapper" id="replyInput-{{ $commentId ?? '' }}" style="display: none;">
    <img src="{{ getImageUrl(auth()->user()->photo) ?? auth()->user()->avatar ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
         class="user-img"
         alt="{{ auth()->user()->name ?? 'You' }}">
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
