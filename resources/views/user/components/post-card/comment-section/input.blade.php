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
