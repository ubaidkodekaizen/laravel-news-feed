<div class="comment-section" id="commentSection-{{ $postId ?? $post['id'] ?? '' }}" style="display: none;">
    @include('user.components.post-card.comment-section.input', ['postId' => $postId ?? $post['id'] ?? ''])

    <div class="comments-list" id="commentsList-{{ $postId ?? $post['id'] ?? '' }}">
        @if(!empty($comments) && is_array($comments))
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

{{-- Set auth user data globally for JavaScript (only once) --}}
@once
<script>
    @auth
        window.authUserId = {{ auth()->id() }};
        window.authUserAvatar = "{{ getImageUrl(auth()->user()->photo) ?? auth()->user()->avatar ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}";
        window.authUserName = "{{ auth()->user()->first_name ?? 'User' }} {{ auth()->user()->last_name ?? '' }}";
        window.authUserInitials = "{{ strtoupper((auth()->user()->first_name ? substr(auth()->user()->first_name, 0, 1) : '') . (auth()->user()->last_name ? substr(auth()->user()->last_name, 0, 1) : 'U')) }}";
        window.authUserHasPhoto = {{ !empty(auth()->user()->photo) ? 'true' : 'false' }};
    @endauth
</script>
@endonce

<style>
.comment-section {
    border-top: 1px solid #e4e6eb;
    background-color: #f8f9fa;
}

.comments-list {
    max-height: 500px;
    overflow-y: auto;
}

.load-more-comments {
    padding: 12px 20px;
    text-align: center;
    border-top: 1px solid #e4e6eb;
}

.load-more-btn {
    background: none;
    border: none;
    color: #0d6efd;
    font-size: 14px;
    font-weight: 600;
    cursor: pointer;
    padding: 8px 16px;
    border-radius: 4px;
    transition: background-color 0.2s;
}

.load-more-btn:hover {
    background-color: #e7f3ff;
}
</style>
