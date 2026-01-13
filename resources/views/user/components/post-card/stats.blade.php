<div class="post-stats">
    <div class="likes-count">
        @if(!empty($post['reactions']) && count($post['reactions']) > 0)
        <div class="reactions-preview">
            @foreach(array_slice($post['reactions'], 0, 3) as $reaction)
                @php
                    $emoji = match($reaction['type'] ?? 'like') {
                        'like' => '👍',
                        'love' => '❤️',
                        'celebrate' => '👏',
                        'insightful' => '💡',
                        'funny' => '😂',
                        'haha' => '😂',
                        'wow' => '😲',
                        'sad' => '😢',
                        'angry' => '😠',
                        default => '👍'
                    };
                @endphp
                <span class="reaction-emoji-preview">{{ $emoji }}</span>
            @endforeach
        </div>
        @endif
        <span class="count-text">{{ $post['likes_count'] ?? 0 }}</span>
    </div>
    <div class="stats-right">
        <div class="comments-count" onclick="toggleComments('{{ $post['id'] ?? '' }}')">
            <span class="count-text">{{ $post['comments_count'] ?? 0 }} comment{{ ($post['comments_count'] ?? 0) !== 1 ? 's' : '' }}</span>
        </div>
        @if(($post['shares_count'] ?? 0) > 0)
        <div class="shares-count">
            <span class="count-text">{{ $post['shares_count'] ?? 0 }} share{{ ($post['shares_count'] ?? 0) !== 1 ? 's' : '' }}</span>
        </div>
        @endif
    </div>
</div>
