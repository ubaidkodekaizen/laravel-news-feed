<div class="post-header">
    <div class="user-info">
        <img src="{{ $user->avatar ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
             class="user-img"
             alt="{{ $user->name ?? 'User' }}">
        <div class="user_post_name">
            <p class="username">{{ $user->name ?? 'Jahanzaib Ansari' }}</p>
            <p class="user-position">{{ $user->position ?? 'Frontend Developer @ Koder360' }}</p>
            <span class="post-time">{{ $post->time ?? '1h ago' }}</span>
        </div>
    </div>
    @if($isOwner ?? false)
        <button class="cross_btn post_btn" onclick="deletePost({{ $post->id ?? '' }})">
            <i class="fa-solid fa-xmark"></i>
        </button>
    @endif
</div>
