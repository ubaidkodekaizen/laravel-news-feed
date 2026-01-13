<div class="post-actions">
    <div class="reaction-wrapper" onmouseleave="hideReactions(this)" data-post-id="{{ $post['id'] ?? '' }}">
        @php
            // Check if user has reacted to this post
            $userReaction = null;
            if (auth()->check() && isset($post['reactions'])) {
                foreach ($post['reactions'] as $reaction) {
                    if (($reaction['user_id'] ?? null) === auth()->id()) {
                        $userReaction = $reaction;
                        break;
                    }
                }
            }

            $reactionEmoji = '👍';
            $reactionLabel = 'Like';

            if ($userReaction) {
                $reactionEmoji = match($userReaction['type'] ?? 'like') {
                    'like' => '👍',
                    'love' => '💖',
                    'celebrate' => '👏',
                    'insightful' => '💡',
                    'funny' => '😂',
                    'haha' => '😂',
                    'wow' => '😲',
                    'sad' => '😢',
                    'angry' => '😠',
                    default => '👍'
                };

                $reactionLabel = match($userReaction['type'] ?? 'like') {
                    'like' => 'Like',
                    'love' => 'Love',
                    'celebrate' => 'Celebrate',
                    'insightful' => 'Insightful',
                    'funny' => 'Funny',
                    'haha' => 'Funny',
                    'wow' => 'Wow',
                    'sad' => 'Sad',
                    'angry' => 'Angry',
                    default => 'Like'
                };
            }
        @endphp
        <div class="action-btn"
             onmouseenter="showReactions(this)"
             onclick="handleReactionClick('{{ $post['id'] ?? '' }}', '{{ $userReaction['type'] ?? '' }}')"
             data-current-reaction="{{ $userReaction['type'] ?? '' }}">
            <span class="reaction-icon">{{ $reactionEmoji }}</span>
            <span class="reaction-label">{{ $reactionLabel }}</span>
        </div>
        <div class="reaction-panel d-none" onmouseenter="cancelHide()" onmouseleave="hideReactions(this)">
            <span class="reaction-emoji" onclick="applyReaction(this, '👍', 'Like', 'like')" title="Like">👍</span>
            <span class="reaction-emoji" onclick="applyReaction(this, '👏', 'Celebrate', 'celebrate')" title="Celebrate">👏</span>
            <span class="reaction-emoji" onclick="applyReaction(this, '💖', 'Love', 'love')" title="Love">💖</span>
            <span class="reaction-emoji" onclick="applyReaction(this, '💡', 'Insightful', 'insightful')" title="Insightful">💡</span>
            <span class="reaction-emoji" onclick="applyReaction(this, '😂', 'Funny', 'funny')" title="Funny">😂</span>
        </div>
    </div>

    <div class="action-btn comment-trigger" onclick="toggleComments('{{ $post['id'] ?? '' }}')">
        <i class="fa-regular fa-comment-dots"></i> Comment
    </div>
    <div class="action-btn" onclick="sharePost('{{ $post['id'] ?? '' }}')">
        <i class="fa-solid fa-retweet"></i> Repost
    </div>
    <div class="action-btn" onclick="sendPost('{{ $post['id'] ?? '' }}')">
        <i class="fa-regular fa-paper-plane"></i> Send
    </div>
</div>
