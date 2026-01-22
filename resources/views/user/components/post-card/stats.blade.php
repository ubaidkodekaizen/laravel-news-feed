<div class="post-stats">
    <div class="likes-count" onclick="showReactionsList('{{ $post['id'] ?? '' }}')" style="cursor: pointer;">
        @if(!empty($post['reactions']) && count($post['reactions']) > 0)
        <div class="reactions-preview">
            @foreach(array_slice($post['reactions'], 0, 3) as $reaction)
                @php
                    $emoji = match($reaction['type'] ?? 'appreciate') {
                        'appreciate' => '👍',
                        'cheers' => '🎉',
                        'support' => '❤️',
                        'insight' => '💡',
                        'curious' => '🤔',
                        'smile' => '😊',
                        default => '👍'
                    };
                @endphp
                <span class="reaction-emoji-preview">{{ $emoji }}</span>
            @endforeach
        </div>
        @endif
        @if(($post['likes_count'] ?? 0) > 0)
            <span class="count-text">{{ $post['likes_count'] ?? 0 }}</span>
        @endif
    </div>

    <div class="stats-right">
        @if(($post['comments_count'] ?? 0) > 0)
        <div class="comments-count" onclick="toggleComments('{{ $post['id'] ?? '' }}')" style="cursor: pointer;">
            <span class="count-text">
                {{ $post['comments_count'] ?? 0 }}
                comment{{ ($post['comments_count'] ?? 0) !== 1 ? 's' : '' }}
            </span>
        </div>
        @endif

        @if(($post['shares_count'] ?? 0) > 0)
        <div class="shares-count" onclick="showSharesList('{{ $post['id'] ?? '' }}')" style="cursor: pointer;">
            <span class="count-text">
                {{ $post['shares_count'] ?? 0 }}
                share{{ ($post['shares_count'] ?? 0) !== 1 ? 's' : '' }}
            </span>
        </div>
        @endif
    </div>
</div>

<style>
.post-stats {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 20px;
    border-bottom: 1px solid #e4e6eb;
}

.likes-count {
    display: flex;
    align-items: center;
    gap: 6px;
    transition: color 0.2s;
}

.likes-count:hover {
    color: #0d6efd;
}

.reactions-preview {
    display: flex;
    align-items: center;
    margin-right: 4px;
}

.reaction-emoji-preview {
    font-size: 16px;
    width: 20px;
    height: 20px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white;
    border-radius: 50%;
    border: 1px solid #fff;
    margin-left: -4px;
}

.reaction-emoji-preview:first-child {
    margin-left: 0;
}

.stats-right {
    display: flex;
    align-items: center;
    gap: 16px;
}

.comments-count,
.shares-count {
    transition: color 0.2s;
}

.comments-count:hover,
.shares-count:hover {
    color: #0d6efd;
    text-decoration: underline;
}

.count-text {
    font-size: 14px;
    color: #65676b;
}
</style>
