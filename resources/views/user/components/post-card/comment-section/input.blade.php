<div class="comment-input-wrapper">
    <img src="{{ auth()->user()->avatar ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
         class="user-img"
         alt="{{ auth()->user()->name ?? 'You' }}">
    <div class="comment-input-container">
        <input type="text"
               placeholder="Add a comment..."
               class="comment-input"
               oninput="toggleCommentButton(this)">
        <div class="comment-actions">
            <button class="emoji-picker-btn"><i class="fa-regular fa-face-smile"></i></button>
            <button class="post-comment-btn" disabled onclick="postComment('{{ $post->id ?? '' }}')">Post</button>
        </div>
    </div>
</div>
