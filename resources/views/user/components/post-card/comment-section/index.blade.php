<div class="comment-section" id="commentSection-{{ $post->id ?? '' }}" style="display: none;">
    @include('user.components.post-card.comment-section.input')
    @include('user.components.post-card.comment-section.list')

    @if(($post->commentsCount ?? 0) > 2)
    <div class="load-more-comments">
        <button class="load-more-btn" onclick="loadMoreComments('{{ $post->id ?? '' }}')">
            Load more comments ({{ ($post->commentsCount ?? 0) - 2 }} more)
        </button>
    </div>
    @endif
</div>
