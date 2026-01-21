<div class="comment-input-wrapper">
    @php
        $authUser = auth()->user();
        $authUserPhoto = $authUser->photo ?? null;
        $authUserHasPhoto = !empty($authUserPhoto);
        $authUserAvatar = $authUserHasPhoto ? (getImageUrl($authUserPhoto) ?? '') : '';
        $authUserInitials = strtoupper(
            (($authUser->first_name ?? '') ? substr($authUser->first_name, 0, 1) : '') .
            (($authUser->last_name ?? '') ? substr($authUser->last_name, 0, 1) : 'U')
        );
    @endphp

    @if($authUserHasPhoto && $authUserAvatar)
        <img src="{{ $authUserAvatar }}"
             class="user-img"
             alt="{{ $authUser->name ?? 'You' }}"
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
            <button class="post-comment-btn" disabled onclick="postComment('{{ $postId ?? ($post['id'] ?? '') }}')">Post</button>
        </div>
    </div>
</div>
