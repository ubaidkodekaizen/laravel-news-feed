/**
 * Improved Comments System
 * Handles comment posting, replies, loading, and deletion with proper validation
 * FIXED: Comment likes now working properly
 */

export function toggleComments(postId) {
    if (!postId) return;

    const commentSection = document.getElementById(`commentSection-${postId}`);
    if (!commentSection) return;

    const isVisible = commentSection.style.display !== 'none';

    if (isVisible) {
        commentSection.style.display = 'none';
    } else {
        commentSection.style.display = 'block';

        // Load comments if not already loaded
        const commentsList = document.getElementById(`commentsList-${postId}`);
        if (commentsList && commentsList.children.length === 0) {
            loadCommentsForPost(postId);
        }
    }
}

export function toggleCommentButton(input) {
    const button = input.closest('.comment-input-container').querySelector('.post-comment-btn');
    const hasContent = input.value.trim().length > 0;

    button.disabled = !hasContent;
    button.classList.toggle('enabled', hasContent);
}

export function toggleReplyButton(input) {
    const button = input.closest('.comment-input-container').querySelector('.post-reply-btn');
    const hasContent = input.value.trim().length > 0;

    button.disabled = !hasContent;
    button.classList.toggle('enabled', hasContent);
}

export function toggleReplyInput(commentId) {
    if (!commentId) return;

    const replyInput = document.getElementById(`replyInput-${commentId}`);
    if (!replyInput) return;

    const isVisible = replyInput.style.display !== 'none';
    replyInput.style.display = isVisible ? 'none' : 'flex';

    if (!isVisible) {
        // Focus the input
        const input = replyInput.querySelector('.reply-input');
        if (input) {
            setTimeout(() => input.focus(), 100);
        }
    }
}

export async function postComment(postId) {
    if (!postId) {
        showNotification('Unable to post comment. Please try again.', 'error');
        return;
    }

    const postContainer = document.querySelector(`.post-container[data-post-id="${postId}"]`);
    if (!postContainer) return;

    const commentSection = postContainer.querySelector(`#commentSection-${postId}`);
    const input = commentSection.querySelector('.comment-input');
    const button = commentSection.querySelector('.post-comment-btn');

    if (!input || !button) return;

    const content = input.value.trim();
    if (!content) {
        showNotification('Please enter a comment.', 'error');
        return;
    }

    if (content.length > 5000) {
        showNotification('Comment is too long. Maximum 5000 characters.', 'error');
        return;
    }

    // Disable input and button
    input.disabled = true;
    button.disabled = true;
    button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

    try {
        const response = await fetch(`/feed/posts/${postId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ content, parent_id: null })
        });

        const result = await response.json();

        if (result.success && result.data) {
            // Clear input
            input.value = '';

            // Add comment to list
            const commentsList = document.getElementById(`commentsList-${postId}`);
            if (commentsList) {
                const commentHtml = createCommentHTML(result.data, postId);
                commentsList.insertAdjacentHTML('beforeend', commentHtml);
            }

            // Update comment count
            updateCommentCount(postId, 1);

            showNotification('Comment posted successfully!', 'success');
        } else {
            throw new Error(result.message || 'Failed to post comment');
        }
    } catch (error) {
        console.error('Error posting comment:', error);
        showNotification(error.message || 'Failed to post comment. Please try again.', 'error');
    } finally {
        input.disabled = false;
        button.disabled = false;
        button.textContent = 'Post';
    }
}

export async function postReply(commentId, postId) {
    if (!commentId || !postId) {
        showNotification('Unable to post reply. Please try again.', 'error');
        return;
    }

    const replyInput = document.getElementById(`replyInput-${commentId}`);
    if (!replyInput) return;

    const input = replyInput.querySelector('.reply-input');
    const button = replyInput.querySelector('.post-reply-btn');

    if (!input || !button) return;

    const content = input.value.trim();
    if (!content) {
        showNotification('Please enter a reply.', 'error');
        return;
    }

    if (content.length > 5000) {
        showNotification('Reply is too long. Maximum 5000 characters.', 'error');
        return;
    }

    // Disable input and button
    input.disabled = true;
    button.disabled = true;
    button.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';

    try {
        const response = await fetch(`/feed/posts/${postId}/comments`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                content,
                parent_id: commentId
            })
        });

        const result = await response.json();

        if (result.success && result.data) {
            // Clear input
            input.value = '';

            // Hide reply input
            replyInput.style.display = 'none';

            // Find or create replies container
            const commentElement = document.querySelector(`.comment[data-comment-id="${commentId}"]`);
            if (commentElement) {
                let repliesContainer = commentElement.querySelector('.replies-container');

                if (!repliesContainer) {
                    repliesContainer = document.createElement('div');
                    repliesContainer.className = 'replies-container';

                    // Insert after reply input
                    const commentBody = commentElement.querySelector('.comment-body');
                    commentBody.appendChild(repliesContainer);
                }

                // Add reply to container
                const replyHtml = createReplyHTML(result.data);
                repliesContainer.insertAdjacentHTML('beforeend', replyHtml);
            }

            // Update comment count
            updateCommentCount(postId, 1);

            showNotification('Reply posted successfully!', 'success');
        } else {
            throw new Error(result.message || 'Failed to post reply');
        }
    } catch (error) {
        console.error('Error posting reply:', error);
        showNotification(error.message || 'Failed to post reply. Please try again.', 'error');
    } finally {
        input.disabled = false;
        button.disabled = false;
        button.textContent = 'Post';
    }
}

export async function loadMoreComments(postId) {
    if (!postId) return;

    const button = event.target;
    button.disabled = true;
    button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Loading...';

    try {
        const response = await fetch(`/feed/posts/${postId}/comments?per_page=20`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (result.success && result.data && result.data.data) {
            const commentsList = document.getElementById(`commentsList-${postId}`);
            if (commentsList) {
                // Clear existing comments
                commentsList.innerHTML = '';

                // Add all comments
                result.data.data.forEach(comment => {
                    const commentHtml = createCommentHTML(comment, postId);
                    commentsList.insertAdjacentHTML('beforeend', commentHtml);
                });
            }

            // Hide load more button
            button.closest('.load-more-comments').style.display = 'none';
        }
    } catch (error) {
        console.error('Error loading comments:', error);
        showNotification('Failed to load comments. Please try again.', 'error');
        button.disabled = false;
        button.textContent = 'Load more comments';
    }
}

export async function deleteComment(commentId, postId) {
    if (!commentId || !postId) return;

    if (!confirm('Are you sure you want to delete this comment?')) {
        return;
    }

    const commentElement = document.querySelector(`.comment[data-comment-id="${commentId}"]`);
    if (!commentElement) return;

    try {
        const response = await fetch(`/feed/comments/${commentId}`, {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        const result = await response.json();

        if (result.success) {
            // Animate removal
            commentElement.style.transition = 'opacity 0.3s ease';
            commentElement.style.opacity = '0';

            setTimeout(() => {
                commentElement.remove();

                // Update comment count
                updateCommentCount(postId, -1);

                showNotification('Comment deleted successfully!', 'success');
            }, 300);
        } else {
            throw new Error(result.message || 'Failed to delete comment');
        }
    } catch (error) {
        console.error('Error deleting comment:', error);
        showNotification(error.message || 'Failed to delete comment. Please try again.', 'error');
    }
}

export async function likeComment(commentId) {
    if (!commentId) return;

    const button = event.target.closest('.like-comment-btn');
    if (!button) return;

    const wasLiked = button.classList.contains('liked');

    // Optimistic UI update
    button.classList.toggle('liked');
    button.innerHTML = button.classList.contains('liked')
        ? '<i class="fa-solid fa-thumbs-up"></i> Liked'
        : '<i class="fa-regular fa-thumbs-up"></i> Like';

    try {
        const response = await fetch('/feed/reactions', {
            method: wasLiked ? 'DELETE' : 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                reactionable_type: 'App\\Models\\Feed\\PostComment',
                reactionable_id: commentId,
                reaction_type: 'like'
            })
        });

        const result = await response.json();

        if (!result.success) {
            // Revert on failure
            button.classList.toggle('liked');
            button.innerHTML = button.classList.contains('liked')
                ? '<i class="fa-solid fa-thumbs-up"></i> Liked'
                : '<i class="fa-regular fa-thumbs-up"></i> Like';
            throw new Error(result.message || 'Failed to update reaction');
        }
    } catch (error) {
        console.error('Error liking comment:', error);
        showNotification('Failed to update reaction. Please try again.', 'error');
    }
}

async function loadCommentsForPost(postId) {
    try {
        const response = await fetch(`/feed/posts/${postId}/comments?per_page=2`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        const result = await response.json();

        if (result.success && result.data && result.data.data) {
            const commentsList = document.getElementById(`commentsList-${postId}`);
            if (commentsList) {
                result.data.data.forEach(comment => {
                    const commentHtml = createCommentHTML(comment, postId);
                    commentsList.insertAdjacentHTML('beforeend', commentHtml);
                });
            }
        }
    } catch (error) {
        console.error('Error loading comments:', error);
    }
}

function createCommentHTML(comment, postId) {
    console.log("comment", comment);
    const userAvatar = comment.user.has_photo && comment.user.avatar
        ? `<img src="${comment.user.avatar}" class="user-img" alt="${comment.user.name}">`
        : `<div class="user-initials-avatar" style="width: 40px; height: 40px; font-size: 14px;">${comment.user.initials}</div>`;

    const isOwner = window.authUserId && window.authUserId === comment.user.id;
    const deleteButton = isOwner
        ? `<button class="delete-comment-btn" onclick="deleteComment('${comment.id}', '${postId}')">Delete</button>`
        : '';

    const isLiked = comment.user_has_reacted || false;
    const likeButtonClass = isLiked ? 'like-comment-btn liked' : 'like-comment-btn';
    const likeButtonIcon = isLiked ? 'fa-solid' : 'fa-regular';
    const likeButtonText = isLiked ? 'Liked' : 'Like';

    const repliesHTML = comment.replies && comment.replies.length > 0
        ? `<div class="replies-container">
             ${comment.replies.map(reply => createReplyHTML(reply)).join('')}
           </div>`
        : '';

    return `
        <div class="comment" data-comment-id="${comment.id}">
            ${userAvatar}
            <div class="comment-body">
                <div class="comment-header">
                    <strong>${escapeHtml(comment.user.name)}</strong>
                    <span class="comment-time">${formatTimeAgo(comment.created_at)}</span>
                </div>
                <div class="comment-content">${escapeHtml(comment.content)}</div>
                <div class="comment-actions">
                    <button class="${likeButtonClass}" onclick="likeComment('${comment.id}')">
                        <i class="${likeButtonIcon} fa-thumbs-up"></i> ${likeButtonText}
                    </button>
                    <button class="reply-comment-btn" onclick="toggleReplyInput('${comment.id}')">Reply</button>
                    ${deleteButton}
                </div>
                ${createReplyInputHTML(comment.id, postId)}
                ${repliesHTML}
            </div>
        </div>
    `;
}

function createReplyHTML(reply) {
    const userAvatar = reply.user.has_photo && reply.user.avatar
        ? `<img src="${reply.user.avatar}" class="user-img" alt="${reply.user.name}">`
        : `<div class="user-initials-avatar" style="width: 32px; height: 32px; font-size: 12px;">${reply.user.initials}</div>`;

    return `
        <div class="comment reply" data-comment-id="${reply.id}">
            ${userAvatar}
            <div class="comment-body">
                <div class="comment-header">
                    <strong>${escapeHtml(reply.user.name)}</strong>
                    <span class="comment-time">${formatTimeAgo(reply.created_at)}</span>
                </div>
                <div class="comment-content">${escapeHtml(reply.content)}</div>
            </div>
        </div>
    `;
}

function createReplyInputHTML(commentId, postId) {
    const userAvatar = window.authUserAvatar || '';
    const userInitials = window.authUserInitials || 'U';

    const avatarHTML = userAvatar
        ? `<img src="${userAvatar}" class="user-img" alt="You">`
        : `<div class="user-initials-avatar" style="width: 40px; height: 40px; font-size: 14px;">${userInitials}</div>`;

    return `
        <div class="reply-input-wrapper" id="replyInput-${commentId}" style="display: none;">
            ${avatarHTML}
            <div class="comment-input-container">
                <input type="text" placeholder="Reply..." class="reply-input" oninput="toggleReplyButton(this)">
                <div class="comment-actions">
                    <button class="emoji-picker-btn"><i class="fa-regular fa-face-smile"></i></button>
                    <button class="post-reply-btn" disabled onclick="postReply('${commentId}', '${postId}')">Post</button>
                </div>
            </div>
        </div>
    `;
}

function updateCommentCount(postId, delta) {
    const postContainer = document.querySelector(`.post-container[data-post-id="${postId}"]`);
    if (!postContainer) return;

    const commentCountEl = postContainer.querySelector('.comments-count .count-text');
    if (commentCountEl) {
        const currentCount = parseInt(commentCountEl.textContent) || 0;
        const newCount = Math.max(0, currentCount + delta);
        commentCountEl.textContent = `${newCount} comment${newCount !== 1 ? 's' : ''}`;
    }
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);

    const intervals = {
        year: 31536000,
        month: 2592000,
        week: 604800,
        day: 86400,
        hour: 3600,
        minute: 60
    };

    for (const [key, value] of Object.entries(intervals)) {
        const interval = Math.floor(seconds / value);
        if (interval >= 1) {
            return `${interval} ${key}${interval !== 1 ? 's' : ''} ago`;
        }
    }

    return 'Just now';
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
