<div class="comments-list">
    @foreach(($post['comments'] ?? []) as $comment)
        @if($loop->index < 2) {{-- Simulate ->take(2) --}}
            @include('user.components.post-card.comment-section.item', [
                'comment' => $comment,
                'user' => $comment['user'] ?? null
            ])
        @endif
    @endforeach
</div>
