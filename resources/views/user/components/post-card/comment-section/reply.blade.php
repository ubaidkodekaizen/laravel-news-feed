<div class="reply-input-wrapper" id="replyInput-{{ $commentId ?? '' }}" style="display: none;">
    @php
        $authUser = auth()->user();
        $authUserPhoto = $authUser->photo ?? null;
        $authUserHasPhoto = !empty($authUserPhoto);
        $authUserAvatar = $authUserHasPhoto ? getImageUrl($authUserPhoto) ?? '' : '';
        $authUserInitials = strtoupper(
            ($authUser->first_name ?? '' ? substr($authUser->first_name, 0, 1) : '') .
                ($authUser->last_name ?? '' ? substr($authUser->last_name, 0, 1) : 'U'),
        );
    @endphp

    @if ($authUserHasPhoto && $authUserAvatar)
        <img src="{{ $authUserAvatar }}" class="user-img reply-avatar-img" alt="{{ $authUser->name ?? 'You' }}"
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
        <button class="emoji-picker-btn"><i class="fa-regular fa-face-smile"></i></button>

        <input type="text" placeholder="Reply..." class="reply-input" oninput="toggleReplyButton(this)">

        <button class="post-reply-btn" disabled onclick="postReply('{{ $commentId ?? '' }}', '{{ $postId ?? '' }}')"><i
                class="fa-regular fa-paper-plane"></i></button>

    </div>
</div>

<style>
    .reply-input-wrapper {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        padding: 12px 0 12px 52px;
        /* Indent replies */
        margin-top: 8px;
    }

    .reply-avatar-img,
    .reply-avatar {
        width: 32px;
        height: 32px;
        flex-shrink: 0;
    }

    .reply-avatar-img {
        border-radius: 50%;
        object-fit: cover;
    }

    .reply-avatar {
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
