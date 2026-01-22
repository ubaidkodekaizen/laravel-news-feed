<div class="post-actions">
    <div class="reaction-wrapper" data-post-id="{{ $post['id'] ?? '' }}">
        <div class="action-btn"
             data-linkedin-reactions
             data-post-id="{{ $post['id'] ?? '' }}"
             onclick="handleReactionClick('{{ $post['id'] ?? '' }}', '{{ $post['user_reaction']['type'] ?? '' }}')"
             data-current-reaction="{{ $post['user_reaction']['type'] ?? '' }}">
            <span class="reaction-icon">
                @if(isset($post['user_reaction']['type']) && $post['user_reaction']['type'])
                    @php
                        $reactionEmojis = [
                            'appreciate' => '👍',
                            'cheers' => '🎉',
                            'support' => '💪',
                            'insight' => '💡',
                            'curious' => '🤔',
                            'smile' => '😊'
                        ];
                        $emoji = $reactionEmojis[$post['user_reaction']['type']] ?? '👍';
                    @endphp
                    {{ $emoji }}
                @else
                    <i class="fa-regular fa-thumbs-up"></i>
                @endif
            </span>
            <span class="reaction-label action-btn-text">
                {{ $post['user_reaction']['type'] ? ucfirst($post['user_reaction']['type']) : 'Like' }}
            </span>
        </div>
    </div>

    <div class="action-btn comment-trigger" onclick="toggleComments('{{ $post['id'] ?? '' }}')">
        <i class="fa-regular fa-comment-dots"></i>
        <span class="action-btn-text">Comment</span>
    </div>

    <div class="action-btn" onclick="sharePost('{{ $post['id'] ?? '' }}')">
        <i class="fa-solid fa-retweet"></i>
        <span class="action-btn-text">Repost</span>
    </div>

    <div class="action-btn" onclick="sendPost('{{ $post['id'] ?? '' }}')">
        <i class="fa-regular fa-paper-plane"></i>
        <span class="action-btn-text">Send</span>
    </div>
</div>
<style>
.post-actions {
    display: flex;
    justify-content: space-around;
    padding: 8px 20px;
    border-top: 1px solid #e4e6eb;
}

.action-btn {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    background: none;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    color: #65676b;
    transition: background-color 0.2s;
    position: relative;
}

.action-btn:hover {
    background-color: #f0f2f5;
}

.action-btn i {
    font-size: 18px;
}

.reaction-icon {
    font-size: 18px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    min-width: 18px;
}

/* Reaction Panel */
.reaction-wrapper {
    position: relative;
}

.reaction-panel {
    position: absolute;
    bottom: 100%;
    left: 50%;
    transform: translateX(-50%);
    background: white;
    border-radius: 50px;
    padding: 8px 12px;
    box-shadow: 0 2px 12px rgba(0, 0, 0, 0.15);
    display: flex;
    gap: 4px;
    margin-bottom: 8px;
    z-index: 10;
}

.reaction-panel::after {
    content: '';
    position: absolute;
    top: 100%;
    left: 50%;
    transform: translateX(-50%);
    width: 0;
    height: 0;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-top: 8px solid white;
}

.reaction-emoji {
    font-size: 28px;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 50%;
    transition: transform 0.2s;
    display: inline-flex;
    align-items: center;
    justify-content: center;
}

.reaction-emoji:hover {
    transform: scale(1.3);
    animation: bounce 0.5s ease;
}

@keyframes bounce {
    0%, 100% { transform: scale(1.3) translateY(0); }
    50% { transform: scale(1.3) translateY(-8px); }
}

/* Responsive */
@media (max-width: 768px) {
    .post-actions {
        padding: 6px 12px;
    }

    .action-btn {
        padding: 6px 12px;
        font-size: 13px;
        gap: 6px;
    }

    .action-btn i {
        font-size: 16px;
    }

    .action-btn span {
        display: none;
    }

    .reaction-panel {
        padding: 6px 8px;
        gap: 2px;
    }

    .reaction-emoji {
        font-size: 24px;
        padding: 2px 4px;
    }
}
</style>
