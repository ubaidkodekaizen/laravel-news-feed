/**
 * Comments System - Complete Implementation
 * Handles comments, replies, edit, delete, and like functionality
 */

export function toggleComments(postId) {
    if (!postId) return;

    const commentSection = document.getElementById(`commentSection-${postId}`);
    if (!commentSection) return;

    const isVisible = commentSection.style.display !== "none";

    if (isVisible) {
        commentSection.style.display = "none";
    } else {
        commentSection.style.display = "block";

        // Load comments if not loaded yet
        const commentsList = document.getElementById(`commentsList-${postId}`);
        if (commentsList && commentsList.children.length === 0) {
            loadComments(postId);
        }
    }
}

export function toggleCommentButton(input) {
    if (!input) return;

    const container = input.closest(".comment-input-container");
    if (!container) return;

    const button = container.querySelector(
        ".post-comment-btn, .post-reply-btn",
    );
    if (!button) return;

    const hasContent = input.value.trim().length > 0;
    button.disabled = !hasContent;

    if (hasContent) {
        button.classList.add("enabled");
    } else {
        button.classList.remove("enabled");
    }
}

export async function postComment(postId) {
    if (!postId) return;

    const commentSection = document.getElementById(`commentSection-${postId}`);
    if (!commentSection) return;

    const input = commentSection.querySelector(".comment-input");
    if (!input) return;

    const content = input.value.trim();
    if (!content) {
        showNotification("Please enter a comment", "error");
        return;
    }

    if (content.length > 5000) {
        showNotification("Comment is too long (max 5000 characters)", "error");
        return;
    }

    // Disable input during submission
    const submitBtn = commentSection.querySelector(".post-comment-btn");
    const originalBtnText = submitBtn?.innerHTML;
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
    }
    input.disabled = true;

    try {
        const response = await fetch(`/news-feed/posts/${postId}/comments`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
            body: JSON.stringify({ content }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success && result.data) {
            // Clear input
            input.value = "";

            // Add comment to list
            const commentsList = document.getElementById(
                `commentsList-${postId}`,
            );
            if (commentsList) {
                const commentHTML = createCommentHTML(result.data, postId);
                commentsList.insertAdjacentHTML("beforeend", commentHTML);
            }

            // Update comment count
            updateCommentCount(postId, 1);

            showNotification("Comment posted successfully!", "success");
        } else {
            throw new Error(result.message || "Failed to post comment");
        }
    } catch (error) {
        console.error("Error posting comment:", error);
        showNotification("Failed to post comment. Please try again.", "error");
    } finally {
        // Re-enable input
        input.disabled = false;
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText || "Post";
        }
    }
}

export function toggleReplyInput(commentId) {
    if (!commentId) return;

    const replyWrapper = document.getElementById(`replyInput-${commentId}`);
    if (!replyWrapper) return;

    const isVisible = replyWrapper.style.display !== "none";
    replyWrapper.style.display = isVisible ? "none" : "flex";

    if (!isVisible) {
        const input = replyWrapper.querySelector(".reply-input");
        if (input) {
            setTimeout(() => input.focus(), 100);
        }
    }
}

export function toggleReplyButton(input) {
    toggleCommentButton(input); // Same logic as comment button
}

export async function postReply(commentId, postId) {
    if (!commentId || !postId) return;

    const replyWrapper = document.getElementById(`replyInput-${commentId}`);
    if (!replyWrapper) return;

    const input = replyWrapper.querySelector(".reply-input");
    if (!input) return;

    const content = input.value.trim();
    if (!content) {
        showNotification("Please enter a reply", "error");
        return;
    }

    if (content.length > 5000) {
        showNotification("Reply is too long (max 5000 characters)", "error");
        return;
    }

    // Disable input during submission
    const submitBtn = replyWrapper.querySelector(".post-reply-btn");
    const originalBtnText = submitBtn?.innerHTML;
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
    }
    input.disabled = true;

    try {
        const response = await fetch(`/news-feed/posts/${postId}/comments`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
            body: JSON.stringify({
                content,
                parent_id: commentId,
            }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success && result.data) {
            // Clear input
            input.value = "";

            // Hide reply input
            replyWrapper.style.display = "none";

            // Add reply to comment
            const commentElement = document.querySelector(
                `[data-comment-id="${commentId}"]`,
            );
            if (commentElement) {
                let repliesContainer =
                    commentElement.querySelector(".replies-container");

                if (!repliesContainer) {
                    repliesContainer = document.createElement("div");
                    repliesContainer.className = "replies-container";
                    commentElement
                        .querySelector(".comment-body")
                        .appendChild(repliesContainer);
                }

                const replyHTML = createReplyHTML(result.data, postId);
                repliesContainer.insertAdjacentHTML("beforeend", replyHTML);
            }

            // Update comment count
            updateCommentCount(postId, 1);

            showNotification("Reply posted successfully!", "success");
        } else {
            throw new Error(result.message || "Failed to post reply");
        }
    } catch (error) {
        console.error("Error posting reply:", error);
        showNotification("Failed to post reply. Please try again.", "error");
    } finally {
        // Re-enable input
        input.disabled = false;
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalBtnText || "Post";
        }
    }
}

export async function loadMoreComments(postId) {
    if (!postId) return;

    const button = event.target;
    const originalText = button.textContent;
    button.disabled = true;
    button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Loading...';

    try {
        const response = await fetch(
            `/news-feed/posts/${postId}/comments?per_page=20`,
            {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
            },
        );

        if (!response.ok) {
            throw new Error("Failed to load comments");
        }

        const result = await response.json();

        if (result.success && result.data && result.data.data) {
            const commentsList = document.getElementById(
                `commentsList-${postId}`,
            );
            if (commentsList) {
                // Clear existing comments
                commentsList.innerHTML = "";

                // Add all comments
                result.data.data.forEach((comment) => {
                    const commentHTML = createCommentHTML(comment, postId);
                    commentsList.insertAdjacentHTML("beforeend", commentHTML);
                });
            }

            // Hide load more button
            button.closest(".load-more-comments")?.remove();
        }
    } catch (error) {
        console.error("Error loading comments:", error);
        showNotification("Failed to load comments. Please try again.", "error");
        button.disabled = false;
        button.textContent = originalText;
    }
}

async function loadComments(postId) {
    const commentsList = document.getElementById(`commentsList-${postId}`);
    if (!commentsList) return;

    commentsList.innerHTML =
        '<div class="text-center py-3"><i class="fa fa-spinner fa-spin"></i> Loading comments...</div>';

    try {
        const response = await fetch(
            `/news-feed/posts/${postId}/comments?per_page=20`,
            {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                },
            },
        );

        if (!response.ok) {
            throw new Error("Failed to load comments");
        }

        const result = await response.json();

        commentsList.innerHTML = "";

        if (
            result.success &&
            result.data &&
            result.data.data &&
            result.data.data.length > 0
        ) {
            result.data.data.forEach((comment) => {
                const commentHTML = createCommentHTML(comment, postId);
                commentsList.insertAdjacentHTML("beforeend", commentHTML);
            });
        } else {
            commentsList.innerHTML =
                '<p class="text-center text-muted py-3">No comments yet</p>';
        }
    } catch (error) {
        console.error("Error loading comments:", error);
        commentsList.innerHTML =
            '<p class="text-center text-danger py-3">Failed to load comments</p>';
    }
}

export function editComment(commentId) {
    if (!commentId) return;

    const commentElement = document.querySelector(
        `[data-comment-id="${commentId}"]`,
    );
    if (!commentElement) return;

    const contentElement = commentElement.querySelector(
        `#commentContent-${commentId}`,
    );
    const actionsElement = commentElement.querySelector(".comment-actions");

    if (!contentElement || !actionsElement) return;

    const currentContent = contentElement.textContent.trim();

    // Create edit form
    const editForm = document.createElement("div");
    editForm.className = "comment-edit-mode";
    editForm.innerHTML = `
        <textarea class="comment-edit-input" id="editInput-${commentId}">${escapeHtml(currentContent)}</textarea>
        <div class="comment-edit-actions">
            <button class="comment-edit-cancel" onclick="cancelEditComment('${commentId}')">Cancel</button>
            <button class="comment-edit-save" onclick="saveEditComment('${commentId}')">Save</button>
        </div>
    `;

    // Hide content and actions
    contentElement.style.display = "none";
    actionsElement.style.display = "none";

    // Insert edit form
    contentElement.parentNode.insertBefore(editForm, actionsElement);

    // Focus textarea
    const textarea = editForm.querySelector("textarea");
    if (textarea) {
        textarea.focus();
        textarea.setSelectionRange(
            textarea.value.length,
            textarea.value.length,
        );
    }
}

window.cancelEditComment = function (commentId) {
    if (!commentId) return;

    const commentElement = document.querySelector(
        `[data-comment-id="${commentId}"]`,
    );
    if (!commentElement) return;

    const editForm = commentElement.querySelector(".comment-edit-mode");
    const contentElement = commentElement.querySelector(
        `#commentContent-${commentId}`,
    );
    const actionsElement = commentElement.querySelector(".comment-actions");

    if (editForm) editForm.remove();
    if (contentElement) contentElement.style.display = "block";
    if (actionsElement) actionsElement.style.display = "flex";
};

window.saveEditComment = async function (commentId) {
    if (!commentId) return;

    const textarea = document.getElementById(`editInput-${commentId}`);
    if (!textarea) return;

    const newContent = textarea.value.trim();

    if (!newContent) {
        showNotification("Comment cannot be empty", "error");
        return;
    }

    if (newContent.length > 5000) {
        showNotification("Comment is too long (max 5000 characters)", "error");
        return;
    }

    const saveBtn = textarea
        .closest(".comment-edit-mode")
        .querySelector(".comment-edit-save");
    const originalText = saveBtn?.textContent;
    if (saveBtn) {
        saveBtn.disabled = true;
        saveBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Saving...';
    }

    try {
        const response = await fetch(`/news-feed/comments/${commentId}`, {
            method: "PUT",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
            body: JSON.stringify({ content: newContent }),
        });

        if (!response.ok) {
            throw new Error("Failed to update comment");
        }

        const result = await response.json();

        if (result.success) {
            // Update content
            const contentElement = document.getElementById(
                `commentContent-${commentId}`,
            );
            if (contentElement) {
                contentElement.textContent = newContent;
            }

            // Remove edit form and show content
            window.cancelEditComment(commentId);

            showNotification("Comment updated successfully!", "success");
        } else {
            throw new Error(result.message || "Failed to update comment");
        }
    } catch (error) {
        console.error("Error updating comment:", error);
        showNotification(
            "Failed to update comment. Please try again.",
            "error",
        );

        if (saveBtn) {
            saveBtn.disabled = false;
            saveBtn.textContent = originalText || "Save";
        }
    }
};

export async function deleteComment(commentId, postId) {
    if (!commentId) return;

    if (!confirm("Are you sure you want to delete this comment?")) {
        return;
    }

    const commentElement = document.querySelector(
        `[data-comment-id="${commentId}"]`,
    );
    if (!commentElement) return;

    // Add loading state
    commentElement.style.opacity = "0.5";
    commentElement.style.pointerEvents = "none";

    try {
        const response = await fetch(`/news-feed/comments/${commentId}`, {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
        });

        if (!response.ok) {
            throw new Error("Failed to delete comment");
        }

        const result = await response.json();

        if (result.success) {
            // Animate and remove
            commentElement.style.transition = "all 0.3s ease";
            commentElement.style.opacity = "0";
            commentElement.style.transform = "translateX(20px)";

            setTimeout(() => {
                commentElement.remove();
            }, 300);

            // Update comment count
            if (postId) {
                updateCommentCount(postId, -1);
            }

            showNotification("Comment deleted successfully!", "success");
        } else {
            throw new Error(result.message || "Failed to delete comment");
        }
    } catch (error) {
        console.error("Error deleting comment:", error);

        // Restore state
        commentElement.style.opacity = "1";
        commentElement.style.pointerEvents = "auto";

        showNotification(
            "Failed to delete comment. Please try again.",
            "error",
        );
    }
}

export async function likeComment(commentId) {
    if (!commentId) return;

    const commentElement = document.querySelector(
        `[data-comment-id="${commentId}"]`,
    );
    if (!commentElement) return;

    const likeBtn = commentElement.querySelector(".like-comment-btn");
    if (!likeBtn) return;

    const isLiked = likeBtn.classList.contains("active");
    const method = isLiked ? "DELETE" : "POST";

    // Optimistic UI update
    likeBtn.classList.toggle("active");
    likeBtn.textContent = isLiked ? "Like" : "Liked";

    try {
        const response = await fetch("/news-feed/reactions", {
            method: method,
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify({
                reactionable_type: "PostComment",
                reactionable_id: commentId,
                reaction_type: "appreciate",
            }),
        });

        if (!response.ok) {
            throw new Error("Failed to update reaction");
        }

        const result = await response.json();

        if (!result.success) {
            throw new Error(result.message || "Failed to update reaction");
        }
    } catch (error) {
        console.error("Error liking comment:", error);

        // Revert UI on error
        likeBtn.classList.toggle("active");
        likeBtn.textContent = isLiked ? "Liked" : "Like";

        showNotification(
            "Failed to update reaction. Please try again.",
            "error",
        );
    }
}

function createCommentHTML(comment, postId) {
    const isOwner = window.authUserId === comment.user_id;

    // Use consistent avatar pattern with proper fallback
    const avatarHTML =
        comment.user.has_photo && comment.user.avatar
            ? `<img src="${comment.user.avatar}" class="user-img" alt="${escapeHtml(comment.user.name)}"
                    onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
               <div class="user-initials-avatar comment-avatar" style="display: none;">
                   ${comment.user.initials}
               </div>`
            : `<div class="user-initials-avatar comment-avatar">
                   ${comment.user.initials}
               </div>`;

    const ownerActions = isOwner
        ? `
        <button class="edit-comment-btn" onclick="editComment('${comment.id}')">Edit</button>
        <button class="delete-comment-btn" onclick="deleteComment('${comment.id}', '${postId}')">Delete</button>
    `
        : "";

    let repliesHTML = "";
    if (comment.replies && comment.replies.length > 0) {
        repliesHTML = '<div class="replies-container">';
        comment.replies.forEach((reply) => {
            repliesHTML += createReplyHTML(reply, postId);
        });
        repliesHTML += "</div>";
    }

    return `
        <div class="comment" data-comment-id="${comment.id}">
            ${avatarHTML}
            <div class="comment-body">
                <div class="comment-header">
                    <a href="/user/profile/${comment.user.slug || '#'}" class="comment-username">
                        <strong>${escapeHtml(comment.user.name)}</strong>
                    </a>
                    <span class="comment-time">${formatTimeAgo(comment.created_at)}</span>
                </div>
                <div class="comment-content" id="commentContent-${comment.id}">${escapeHtml(comment.content)}</div>
                <div class="comment-actions">
                    <button class="like-comment-btn ${comment.user_has_reacted ? "active" : ""}"
                            onclick="likeComment('${comment.id}')">
                        ${comment.user_has_reacted ? "Liked" : "Like"}
                    </button>
                    <button class="reply-comment-btn" onclick="toggleReplyInput('${comment.id}')">Reply</button>
                    ${ownerActions}
                </div>
                ${createReplyInputHTML(comment.id, postId)}
                ${repliesHTML}
            </div>
        </div>
    `;
}

function createReplyHTML(reply, postId) {
    const isOwner = window.authUserId === reply.user_id;

    // Use consistent avatar pattern with proper fallback
    const avatarHTML =
        reply.user.has_photo && reply.user.avatar
            ? `<img src="${reply.user.avatar}" class="user-img reply-avatar-img" alt="${escapeHtml(reply.user.name)}"
                    onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
               <div class="user-initials-avatar reply-avatar" style="display: none;">
                   ${reply.user.initials}
               </div>`
            : `<div class="user-initials-avatar reply-avatar">
                   ${reply.user.initials}
               </div>`;

    const ownerActions = isOwner
        ? `
        <button class="edit-comment-btn" onclick="editComment('${reply.id}')">Edit</button>
        <button class="delete-comment-btn" onclick="deleteComment('${reply.id}', '${postId}')">Delete</button>
    `
        : "";

    return `
        <div class="comment reply" data-comment-id="${reply.id}">
            ${avatarHTML}
            <div class="comment-body">
                <div class="comment-header">
                    <a href="/user/profile/${reply.user.slug || '#'}" class="comment-username">
                        <strong>${escapeHtml(reply.user.name)}</strong>
                    </a>
                    <span class="comment-time">${formatTimeAgo(reply.created_at)}</span>
                </div>
                <div class="comment-content" id="commentContent-${reply.id}">${escapeHtml(reply.content)}</div>
                <div class="comment-actions">
                    <button class="like-comment-btn ${reply.user_has_reacted ? "active" : ""}"
                            onclick="likeComment('${reply.id}')">
                        ${reply.user_has_reacted ? "Liked" : "Like"}
                    </button>
                    ${ownerActions}
                </div>
            </div>
        </div>
    `;
}

function createReplyInputHTML(commentId, postId) {
    const userAvatar = window.authUserAvatar || "";
    const userInitials = window.authUserInitials || "U";
    const hasPhoto = window.authUserHasPhoto || false;

    // Use consistent avatar pattern with proper fallback
    const avatarHTML =
        hasPhoto && userAvatar
            ? `<img src="${userAvatar}" class="user-img reply-avatar-img" alt="You"
                    onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
               <div class="user-initials-avatar reply-avatar" style="display: none;">
                   ${userInitials}
               </div>`
            : `<div class="user-initials-avatar reply-avatar">
                   ${userInitials}
               </div>`;

    return `
        <div class="reply-input-wrapper" id="replyInput-${commentId}" style="display: none;">
            ${avatarHTML}
            <div class="comment-input-container">
                <button class="emoji-picker-btn" type="button" data-emoji-trigger="#replyInputEmoji-${commentId}"><i class="fa-regular fa-face-smile"></i></button>
                <input type="text" placeholder="Reply..." id="replyInputEmoji-${commentId}" class="reply-input" oninput="toggleReplyButton(this)">
                <button class="post-reply-btn" disabled onclick="postReply('${commentId}', '${postId}')"><i class="fa-regular fa-paper-plane"></i></button>
            </div>
        </div>
    `;
}

function updateCommentCount(postId, change) {
    const postContainer = document.querySelector(
        `.post-container[data-post-id="${postId}"]`,
    );
    if (!postContainer) return;

    const commentsCount = postContainer.querySelector(
        ".comments-count .count-text",
    );
    if (commentsCount) {
        const match = commentsCount.textContent.match(/\d+/);
        if (match) {
            let count = parseInt(match[0]) + change;
            count = Math.max(0, count);
            commentsCount.textContent = `${count} comment${count !== 1 ? "s" : ""}`;
        }
    }
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
        minute: 60,
    };

    for (const [key, value] of Object.entries(intervals)) {
        const interval = Math.floor(seconds / value);
        if (interval >= 1) {
            return `${interval} ${key}${interval !== 1 ? "s" : ""} ago`;
        }
    }

    return "Just now";
}

function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

function showNotification(message, type = "info") {
    if ($("#notification-container").length === 0) {
        $("body").append(
            '<div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>',
        );
    }

    const alertClass =
        type === "success"
            ? "alert-success"
            : type === "error"
              ? "alert-danger"
              : "alert-info";

    const notification = $(`
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="min-width: 250px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);

    $("#notification-container").append(notification);

    setTimeout(() => {
        notification.alert("close");
    }, 3000);
}

// Make edit functions globally available
window.editComment = editComment;
window.deleteComment = deleteComment;
window.likeComment = likeComment;
window.postComment = postComment;
window.postReply = postReply;
window.toggleComments = toggleComments;
window.toggleCommentButton = toggleCommentButton;
window.toggleReplyInput = toggleReplyInput;
window.toggleReplyButton = toggleReplyButton;
window.loadMoreComments = loadMoreComments;
