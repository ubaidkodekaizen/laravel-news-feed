<div class="post-container card" data-post-id="{{ $post['id'] ?? '' }}">
    @include('user.components.post-card.header', [
        'user' => $post['user'] ?? [],
        'time' => isset($post['created_at']) ? $post['created_at']->diffForHumans() : '1h ago',
        'isOwner' => $isOwner ?? false
    ])

    @include('user.components.post-card.content', [
        'content' => trim($post['content'] ?? ''),
        'postId' => $post['id'] ?? ''
    ])

    @include('user.components.post-card.stats', [
        'likesCount' => $post['likes_count'] ?? 0,
        'commentsCount' => $post['comments_count'] ?? 0,
        'reactions' => $post['reactions'] ?? []
    ])

    @include('user.components.post-card.actions')

    @include('user.components.post-card.comment-section.index', [
        'comments' => $post['comments'] ?? [],
        'totalComments' => $post['comments_count'] ?? 0,
        'postId' => $post['id'] ?? ''
    ])
</div>
