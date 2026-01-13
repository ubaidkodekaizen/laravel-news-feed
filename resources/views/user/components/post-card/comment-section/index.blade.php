<div class="comment-section" id="commentSection-{{ $postId ?? $post['id'] ?? '' }}" style="display: none;">
    @include('user.components.post-card.comment-section.input', ['postId' => $postId ?? $post['id'] ?? ''])

    <div class="comments-list">
        @if(!empty($comments))
            @foreach($comments as $comment)
                @if($loop->index < 2) {{-- Show first 2 comments initially --}}
                    @include('user.components.post-card.comment-section.item', [
                        'comment' => $comment,
                        'postId' => $postId ?? $post['id'] ?? ''
                    ])
                @endif
            @endforeach
        @endif
    </div>

    @if(($totalComments ?? $post['comments_count'] ?? 0) > 2)
    <div class="load-more-comments">
        <button class="load-more-btn" onclick="loadMoreComments('{{ $postId ?? $post['id'] ?? '' }}')">
            Load more comments ({{ ($totalComments ?? $post['comments_count'] ?? 0) - 2 }} more)
        </button>
    </div>
    @endif
</div>

<script>
    // Set auth user data for comments.js
    @auth
        window.authUserId = {{ auth()->id() }};
        window.authUserAvatar = "{{ getImageUrl(auth()->user()->photo) ?? auth()->user()->avatar ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}";
    @endauth
</script>
