<div class="post-actions">
    <div class="reaction-wrapper" onmouseleave="hideReactions(this)">
        <div class="action-btn" onmouseenter="showReactions(this)">
            <span class="reaction-icon"><i class="fa-regular fa-thumbs-up"></i></span>
            <span class="reaction-label">Like</span>
        </div>
        <div class="reaction-panel d-none" onmouseenter="cancelHide()" onmouseleave="hideReactions(this)">
            <span class="reaction-emoji" onclick="applyReaction(this, '👍', 'Like')" title="Like">👍</span>
            <span class="reaction-emoji" onclick="applyReaction(this, '👏', 'Celebrate')" title="Celebrate">👏</span>
            <span class="reaction-emoji" onclick="applyReaction(this, '💖', 'Love')" title="Love">💖</span>
            <span class="reaction-emoji" onclick="applyReaction(this, '💡', 'Insightful')" title="Insightful">💡</span>
            <span class="reaction-emoji" onclick="applyReaction(this, '😂', 'Funny')" title="Funny">😂</span>
        </div>
    </div>

    <div class="action-btn comment-trigger" onclick="toggleComments('{{ $post->id ?? '' }}')">
        <i class="fa-regular fa-comment-dots"></i> Comment
    </div>
    <div class="action-btn"><i class="fa-solid fa-retweet"></i> Repost</div>
    <div class="action-btn"><i class="fa-regular fa-paper-plane"></i> Send</div>
</div>
