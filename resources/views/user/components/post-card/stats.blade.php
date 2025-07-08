<div class="post-stats">
    <div class="likes-count">
        <div class="reactions-preview">
            <span class="reaction-emoji-preview">👍</span>
            <span class="reaction-emoji-preview">❤️</span>
            <span class="reaction-emoji-preview">😲</span>
        </div>
        <span class="count-text">{{ $post->likesCount ?? 24 }}</span>
    </div>
    <div class="comments-count" onclick="toggleComments('{{ $post->id ?? '' }}')">
        <span class="count-text">{{ $post->commentsCount ?? 5 }} comments</span>
    </div>
</div>
