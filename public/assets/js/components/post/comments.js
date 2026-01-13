export function toggleComments(postId) {
    const commentSection = document.getElementById(`commentSection-${postId}`);
    if (!commentSection) return;

    if (commentSection.style.display === 'none' || commentSection.style.display === '') {
        commentSection.style.display = 'block';
        // Load comments if not already loaded
        const commentsList = commentSection.querySelector('.comments-list');
        if (commentsList && commentsList.children.length === 0) {
            loadComments(postId);
        }
    } else {
        commentSection.style.display = 'none';
    }
}

export function toggleCommentButton(input) {
    const postButton = input.closest('.comment-input-container').querySelector('.post-comment-btn');
    if (postButton) {
        const hasContent = input.value.trim() !== '';
        postButton.disabled = !hasContent;
        postButton.classList.toggle('enabled', hasContent);
    }
}

export function postComment(postId) {
    const commentSection = document.getElementById(`commentSection-${postId}`);
    if (!commentSection) return;

    const input = commentSection.querySelector('.comment-input');
    const commentText = input.value.trim();

    if (!commentText) {
        showNotification('Please enter a comment', 'error');
        return;
    }

    const postButton = input.closest('.comment-input-container').querySelector('.post-comment-btn');
    const originalText = postButton.innerHTML;
    postButton.disabled = true;
    postButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Posting...';

    $.ajax({
        url: `/feed/posts/${postId}/comments`,
        method: 'POST',
        data: {
            content: commentText,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Clear input
                input.value = '';
                input.dispatchEvent(new Event('input'));

                // Add comment to list
                addCommentToList(postId, response.data);

                // Update comment count
                updateCommentCount(postId);

                showNotification('Comment posted successfully!', 'success');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error posting comment:', error);
            let errorMessage = 'Failed to post comment. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showNotification(errorMessage, 'error');
        },
        complete: function() {
            postButton.disabled = false;
            postButton.innerHTML = originalText;
        }
    });
}

export function toggleReplyInput(commentId) {
    const replyWrapper = document.getElementById(`replyInput-${commentId}`);
    if (replyWrapper) {
        const isHidden = replyWrapper.style.display === 'none' || replyWrapper.style.display === '';
        replyWrapper.style.display = isHidden ? 'flex' : 'none';

        // Focus input if showing
        if (isHidden) {
            const input = replyWrapper.querySelector('.reply-input');
            if (input) input.focus();
        }
    }
}

export function toggleReplyButton(input) {
    const postButton = input.closest('.comment-input-container').querySelector('.post-reply-btn');
    if (postButton) {
        const hasContent = input.value.trim() !== '';
        postButton.disabled = !hasContent;
        postButton.classList.toggle('enabled', hasContent);
    }
}

export function postReply(commentId, postId) {
    const replyWrapper = document.getElementById(`replyInput-${commentId}`);
    if (!replyWrapper) return;

    const input = replyWrapper.querySelector('.reply-input');
    const replyText = input.value.trim();

    if (!replyText) {
        showNotification('Please enter a reply', 'error');
        return;
    }

    // Get post ID from comment element if not provided
    if (!postId) {
        const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
        const postContainer = commentElement?.closest('.post-container');
        postId = postContainer?.dataset.postId;
    }

    if (!postId) {
        console.error('Post ID not found');
        showNotification('Unable to post reply. Please refresh the page.', 'error');
        return;
    }

    const postButton = replyWrapper.querySelector('.post-reply-btn');
    const originalText = postButton.innerHTML;
    postButton.disabled = true;
    postButton.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Posting...';

    $.ajax({
        url: `/feed/posts/${postId}/comments`,
        method: 'POST',
        data: {
            content: replyText,
            parent_id: commentId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Clear input
                input.value = '';
                input.dispatchEvent(new Event('input'));

                // Hide reply input
                replyWrapper.style.display = 'none';

                // Add reply to comment
                addReplyToComment(commentId, response.data);

                // Update comment count
                updateCommentCount(postId);

                showNotification('Reply posted successfully!', 'success');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error posting reply:', error);
            let errorMessage = 'Failed to post reply. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showNotification(errorMessage, 'error');
        },
        complete: function() {
            postButton.disabled = false;
            postButton.innerHTML = originalText;
        }
    });
}

export function loadMoreComments(postId) {
    const loadMoreBtn = document.querySelector(`#commentSection-${postId} .load-more-btn`);
    if (!loadMoreBtn) return;

    const originalText = loadMoreBtn.textContent;
    loadMoreBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Loading...';
    loadMoreBtn.disabled = true;

    loadComments(postId, true);
}

export function likeComment(commentId) {
    console.log(`Liking comment ${commentId}`);
    // TODO: Implement comment reactions similar to post reactions
    showNotification('Comment reactions coming soon!', 'info');
}

export function deleteComment(commentId, postId) {
    if (!confirm('Are you sure you want to delete this comment?')) {
        return;
    }

    $.ajax({
        url: `/feed/comments/${commentId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Remove comment from DOM
                const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
                if (commentElement) {
                    commentElement.remove();
                }

                // Update comment count
                updateCommentCount(postId);

                showNotification('Comment deleted successfully!', 'success');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error deleting comment:', error);
            showNotification('Failed to delete comment. Please try again.', 'error');
        }
    });
}

// Helper Functions

function loadComments(postId, loadMore = false) {
    const commentSection = document.getElementById(`commentSection-${postId}`);
    if (!commentSection) return;

    const commentsList = commentSection.querySelector('.comments-list');
    const currentCount = commentsList ? commentsList.children.length : 0;

    $.ajax({
        url: `/feed/posts/${postId}/comments`,
        method: 'GET',
        data: {
            per_page: 20,
            skip: loadMore ? currentCount : 0
        },
        success: function(response) {
            if (response.success && response.data) {
                const comments = response.data.data || response.data;

                if (loadMore) {
                    // Append new comments
                    comments.forEach(comment => addCommentToList(postId, comment, true));
                } else {
                    // Replace all comments
                    commentsList.innerHTML = '';
                    comments.forEach(comment => addCommentToList(postId, comment, true));
                }

                // Update or hide "Load More" button
                const loadMoreBtn = commentSection.querySelector('.load-more-btn');
                if (loadMoreBtn) {
                    const totalComments = response.data.total || comments.length;
                    const displayedComments = commentsList.children.length;

                    if (displayedComments >= totalComments) {
                        loadMoreBtn.parentElement.style.display = 'none';
                    } else {
                        loadMoreBtn.innerHTML = `Load more comments (${totalComments - displayedComments} more)`;
                        loadMoreBtn.disabled = false;
                        loadMoreBtn.parentElement.style.display = 'block';
                    }
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error loading comments:', error);
            showNotification('Failed to load comments. Please try again.', 'error');
        }
    });
}

function addCommentToList(postId, comment, skipAnimation = false) {
    const commentSection = document.getElementById(`commentSection-${postId}`);
    if (!commentSection) return;

    const commentsList = commentSection.querySelector('.comments-list');
    if (!commentsList) return;

    const commentHtml = createCommentHtml(comment, postId);
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = commentHtml;
    const commentElement = tempDiv.firstElementChild;

    if (!skipAnimation) {
        commentElement.style.opacity = '0';
        commentElement.style.transform = 'translateY(-10px)';
    }

    commentsList.appendChild(commentElement);

    if (!skipAnimation) {
        setTimeout(() => {
            commentElement.style.transition = 'all 0.3s ease';
            commentElement.style.opacity = '1';
            commentElement.style.transform = 'translateY(0)';
        }, 10);
    }
}

function addReplyToComment(commentId, reply) {
    const commentElement = document.querySelector(`[data-comment-id="${commentId}"]`);
    if (!commentElement) return;

    const commentBody = commentElement.querySelector('.comment-body');
    if (!commentBody) return;

    // Check if replies container exists
    let repliesContainer = commentBody.querySelector('.replies-container');
    if (!repliesContainer) {
        repliesContainer = document.createElement('div');
        repliesContainer.className = 'replies-container';
        // Insert after reply input
        const replyInput = commentBody.querySelector('.reply-input-wrapper');
        if (replyInput) {
            replyInput.parentNode.insertBefore(repliesContainer, replyInput.nextSibling);
        } else {
            commentBody.appendChild(repliesContainer);
        }
    }

    const replyHtml = createReplyHtml(reply);
    const tempDiv = document.createElement('div');
    tempDiv.innerHTML = replyHtml;
    const replyElement = tempDiv.firstElementChild;

    replyElement.style.opacity = '0';
    replyElement.style.transform = 'translateY(-10px)';

    repliesContainer.appendChild(replyElement);

    setTimeout(() => {
        replyElement.style.transition = 'all 0.3s ease';
        replyElement.style.opacity = '1';
        replyElement.style.transform = 'translateY(0)';
    }, 10);
}

function createCommentHtml(comment, postId) {
    const userName = comment.user?.first_name && comment.user?.last_name
        ? `${comment.user.first_name} ${comment.user.last_name}`
        : comment.user?.name || 'Anonymous';

    const userAvatar = comment.user?.photo
        ? (typeof getImageUrl === 'function' ? getImageUrl(comment.user.photo) : comment.user.photo)
        : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png';

    const timeAgo = formatTimeAgo(comment.created_at);

    const isOwner = window.authUserId && comment.user_id === window.authUserId;

    return `
        <div class="comment" data-comment-id="${comment.id}">
            <img src="${userAvatar}" class="user-img" alt="${userName}">
            <div class="comment-body">
                <div class="comment-header">
                    <strong>${userName}</strong>
                    <span class="comment-time">${timeAgo}</span>
                </div>
                <div class="comment-content">${escapeHtml(comment.content)}</div>
                <div class="comment-actions">
                    <button class="like-comment-btn" onclick="likeComment('${comment.id}')">Like</button>
                    <button class="reply-comment-btn" onclick="toggleReplyInput('${comment.id}')">Reply</button>
                    ${isOwner ? `<button class="delete-comment-btn" onclick="deleteComment('${comment.id}', '${postId}')">Delete</button>` : ''}
                </div>
                <div class="reply-input-wrapper" id="replyInput-${comment.id}" style="display: none;">
                    <img src="${window.authUserAvatar || 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png'}" class="user-img" alt="You">
                    <div class="comment-input-container">
                        <input type="text" placeholder="Reply..." class="reply-input" oninput="toggleReplyButton(this)">
                        <div class="comment-actions">
                            <button class="emoji-picker-btn"><i class="fa-regular fa-face-smile"></i></button>
                            <button class="post-reply-btn" disabled onclick="postReply('${comment.id}', '${postId}')">Post</button>
                        </div>
                    </div>
                </div>
                ${comment.replies && comment.replies.length > 0 ? createRepliesHtml(comment.replies) : ''}
            </div>
        </div>
    `;
}

function createRepliesHtml(replies) {
    return `
        <div class="replies-container">
            ${replies.map(reply => createReplyHtml(reply)).join('')}
        </div>
    `;
}

function createReplyHtml(reply) {
    const userName = reply.user?.first_name && reply.user?.last_name
        ? `${reply.user.first_name} ${reply.user.last_name}`
        : reply.user?.name || 'Anonymous';

    const userAvatar = reply.user?.photo
        ? (typeof getImageUrl === 'function' ? getImageUrl(reply.user.photo) : reply.user.photo)
        : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png';

    const timeAgo = formatTimeAgo(reply.created_at);

    return `
        <div class="comment reply" data-comment-id="${reply.id}">
            <img src="${userAvatar}" class="user-img" alt="${userName}">
            <div class="comment-body">
                <div class="comment-header">
                    <strong>${userName}</strong>
                    <span class="comment-time">${timeAgo}</span>
                </div>
                <div class="comment-content">${escapeHtml(reply.content)}</div>
            </div>
        </div>
    `;
}

function updateCommentCount(postId) {
    const postContainer = document.querySelector(`.post-container[data-post-id="${postId}"]`);
    if (!postContainer) return;

    // Fetch updated count from server
    $.ajax({
        url: `/feed/posts/${postId}/comments-count`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const commentsCountEl = postContainer.querySelector('.comments-count .count-text');
                if (commentsCountEl) {
                    const count = response.count;
                    commentsCountEl.textContent = `${count} comment${count !== 1 ? 's' : ''}`;
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching comment count:', error);
        }
    });
}

function formatTimeAgo(timestamp) {
    if (!timestamp) return 'just now';

    const date = new Date(timestamp);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);

    if (seconds < 60) return 'just now';

    const minutes = Math.floor(seconds / 60);
    if (minutes < 60) return `${minutes}m ago`;

    const hours = Math.floor(minutes / 60);
    if (hours < 24) return `${hours}h ago`;

    const days = Math.floor(hours / 24);
    if (days < 7) return `${days}d ago`;

    const weeks = Math.floor(days / 7);
    if (weeks < 4) return `${weeks}w ago`;

    const months = Math.floor(days / 30);
    if (months < 12) return `${months}mo ago`;

    const years = Math.floor(days / 365);
    return `${years}y ago`;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function showNotification(message, type = 'info') {
    if ($('#notification-container').length === 0) {
        $('body').append('<div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>');
    }

    const alertClass = type === 'success' ? 'alert-success' :
                      type === 'error' ? 'alert-danger' :
                      'alert-info';

    const notification = $(`
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="min-width: 250px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);

    $('#notification-container').append(notification);

    setTimeout(() => {
        notification.alert('close');
    }, 3000);
}
