<div class="post-container card">
    <!-- Post Header -->
    <div class="post-header">
        <div class="user-info">
            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" class="user-img" alt="User">
            <div class="user_post_name">
                <p class="username">Jahanzaib Ansari</p>
                <p class="user-position">Frontend Developer @ Koder360</p>
                <span class="user-position">1h ago</span>
            </div>
        </div>
        <button class="cross_btn post_btn"><i class="fa-solid fa-xmark"></i></button>
    </div>

    <!-- Post Text with View More -->
    <div class="post-text-wrapper">
        <div class="post-text" id="postTextBlock">
            Just launched a new feature on our LinkedIn-style post system in Laravel! 🚀 Feedback welcome!
            Here's some more detailed explanation about how we've implemented full emoji support, multi-image upload with crop/edit/duplicate/reorder and integrated commenting in Laravel Blade views.
            Also working on post analytics and reactions like LinkedIn. Stay tuned!
        </div>
        <span class="post-text-toggle" onclick="togglePostText(this)">See more</span>
    </div>

    <!-- Post Stats (Likes/Comments) -->
    <div class="post-stats">
        <div class="likes-count">
            <div class="reactions-preview">
                <span class="reaction-emoji-preview">👍</span>
                <span class="reaction-emoji-preview">❤️</span>
                <span class="reaction-emoji-preview">😲</span>
            </div>
            <span class="count-text">24</span>
        </div>
        <div class="comments-count">
            <span class="count-text">5 comments</span>
        </div>
    </div>

    <!-- Post Actions -->
    <div class="post-actions">
        <div class="reaction-wrapper" onmouseleave="hideReactions(this)">
            <div class="action-btn" onmouseenter="showReactions(this)">
                <span class="reaction-icon"><i class="fa-regular fa-thumbs-up"></i></span>
                <span class="reaction-label">Like</span>
            </div>
            <div class="reaction-panel d-none" onmouseenter="cancelHide()" onmouseleave="hideReactions(this)">
                <span class="reaction-emoji" onclick="applyReaction(this, '👍', 'Like')" title="Like">👍</span>
                <span class="reaction-emoji" onclick="applyReaction(this, '👏', 'Celebrate')" title="Celebrate">👏</span>
                <span class="reaction-emoji" onclick="applyReaction(this, '💖', 'Love')" title="Love">💖</span>
                <span class="reaction-emoji" onclick="applyReaction(this, '💡', 'Insightful')" title="Insightful">💡</span>
                <span class="reaction-emoji" onclick="applyReaction(this, '😂', 'Funny')" title="Funny">😂</span>
            </div>
        </div>

        <div class="action-btn comment-trigger"><i class="fa-regular fa-comment-dots"></i> Comment</div>
        <div class="action-btn"><i class="fa-solid fa-retweet"></i> Repost</div>
        <div class="action-btn"><i class="fa-regular fa-paper-plane"></i> Send</div>
    </div>

    <!-- Comment Section (Initially hidden) -->
    <div class="comment-section" style="display: none;">
        <!-- Comment Input -->
        <div class="comment-input-wrapper">
            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" class="user-img">
            <div class="comment-input-container">
                <input type="text" placeholder="Add a comment..." class="comment-input">
                <div class="comment-actions">
                    <button class="emoji-picker-btn"><i class="fa-regular fa-face-smile"></i></button>
                    <button class="post-comment-btn" disabled>Post</button>
                </div>
            </div>
        </div>

        <!-- Comments List (First 2 comments shown) -->
        <div class="comments-list">
            <div class="comment">
                <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" class="user-img">
                <div class="comment-body">
                    <div class="comment-header">
                        <strong>Ali Raza</strong>
                        <span class="comment-time">45m ago</span>
                    </div>
                    <div class="comment-content">Great job! Keep building cool stuff. 🔥</div>
                    <div class="comment-actions">
                        <button class="like-comment-btn">Like</button>
                        <button class="reply-comment-btn">Reply</button>
                    </div>

                    <!-- Reply Input (Hidden by default) -->
                    <div class="reply-input-wrapper" style="display: none;">
                        <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" class="user-img">
                        <div class="comment-input-container">
                            <input type="text" placeholder="Reply..." class="reply-input">
                            <div class="comment-actions">
                                <button class="emoji-picker-btn"><i class="fa-regular fa-face-smile"></i></button>
                                <button class="post-reply-btn" disabled>Post</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="comment">
                <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" class="user-img">
                <div class="comment-body">
                    <div class="comment-header">
                        <strong>Sana Sheikh</strong>
                        <span class="comment-time">30m ago</span>
                    </div>
                    <div class="comment-content">Excited to try this out soon!</div>
                    <div class="comment-actions">
                        <button class="like-comment-btn">Like</button>
                        <button class="reply-comment-btn">Reply</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Load More Comments -->
        <div class="load-more-comments">
            <button class="load-more-btn">Load more comments (3 more)</button>
        </div>
    </div>
</div>
