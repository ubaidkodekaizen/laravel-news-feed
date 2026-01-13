/**
 * Share/Repost Functionality
 */

export function sharePost(postId) {
    if (!postId) {
        console.error('Post ID is required');
        showNotification('Unable to share post. Please try again.', 'error');
        return;
    }

    // Show share modal
    showShareModal(postId);
}

export function sendPost(postId) {
    if (!postId) {
        console.error('Post ID is required');
        showNotification('Unable to send post. Please try again.', 'error');
        return;
    }

    // Show send modal with user selection
    showSendModal(postId);
}

function showShareModal(postId) {
    // Check if share modal exists, if not create it
    let shareModal = document.getElementById('sharePostModal');

    if (!shareModal) {
        const modalHtml = `
            <div class="modal fade" id="sharePostModal" tabindex="-1" aria-labelledby="sharePostModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Share Post</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="share-options">
                                <button class="share-option-btn" onclick="repostWithThoughts('${postId}')">
                                    <i class="fa-solid fa-retweet"></i>
                                    <div>
                                        <strong>Repost with your thoughts</strong>
                                        <p>Add your perspective to this post</p>
                                    </div>
                                </button>
                                <button class="share-option-btn" onclick="instantRepost('${postId}')">
                                    <i class="fa-solid fa-arrows-rotate"></i>
                                    <div>
                                        <strong>Instant repost</strong>
                                        <p>Share immediately to your feed</p>
                                    </div>
                                </button>
                                <button class="share-option-btn" onclick="copyPostLink('${postId}')">
                                    <i class="fa-solid fa-link"></i>
                                    <div>
                                        <strong>Copy link</strong>
                                        <p>Copy link to this post</p>
                                    </div>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
        shareModal = document.getElementById('sharePostModal');
    }

    // Show modal
    const bsModal = new bootstrap.Modal(shareModal);
    bsModal.show();
}

export function repostWithThoughts(postId) {
    // Close share modal
    const shareModal = bootstrap.Modal.getInstance(document.getElementById('sharePostModal'));
    if (shareModal) shareModal.hide();

    // Show repost with thoughts modal
    showRepostModal(postId);
}

function showRepostModal(postId) {
    // Get original post data
    const postContainer = document.querySelector(`.post-container[data-post-id="${postId}"]`);
    if (!postContainer) {
        console.error('Post container not found');
        return;
    }

    const postContent = postContainer.querySelector('.post-text')?.textContent.trim() || '';
    const userName = postContainer.querySelector('.username')?.textContent.trim() || 'Unknown User';
    const userAvatar = postContainer.querySelector('.user-img')?.src || '';
    const postTime = postContainer.querySelector('.post-time')?.textContent.trim() || '';

    // Check if repost modal exists, if not create it
    let repostModal = document.getElementById('repostModal');

    if (!repostModal) {
        const modalHtml = `
            <div class="modal fade" id="repostModal" tabindex="-1" aria-labelledby="repostModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <form id="repostForm">
                            <div class="modal-header">
                                <h5 class="modal-title">Repost with Your Thoughts</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <textarea class="form-control mb-3" id="repostText" rows="3" placeholder="What do you think about this?"></textarea>

                                <div class="original-post-preview">
                                    <div class="original-post-header">
                                        <img src="" class="original-post-avatar" alt="User">
                                        <div>
                                            <strong class="original-post-name"></strong>
                                            <span class="original-post-time"></span>
                                        </div>
                                    </div>
                                    <div class="original-post-content"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Repost</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
        repostModal = document.getElementById('repostModal');

        // Add form submit handler
        document.getElementById('repostForm').addEventListener('submit', function(e) {
            e.preventDefault();
            const repostContent = document.getElementById('repostText').value.trim();
            submitRepost(postId, repostContent, 'repost');
        });
    }

    // Set original post data
    repostModal.querySelector('.original-post-avatar').src = userAvatar;
    repostModal.querySelector('.original-post-name').textContent = userName;
    repostModal.querySelector('.original-post-time').textContent = postTime;
    repostModal.querySelector('.original-post-content').textContent = postContent;

    // Clear textarea
    document.getElementById('repostText').value = '';

    // Show modal
    const bsModal = new bootstrap.Modal(repostModal);
    bsModal.show();
}

export function instantRepost(postId) {
    if (!confirm('Share this post to your feed immediately?')) {
        return;
    }

    // Close share modal
    const shareModal = bootstrap.Modal.getInstance(document.getElementById('sharePostModal'));
    if (shareModal) shareModal.hide();

    submitRepost(postId, '', 'share');
}

function submitRepost(postId, content, shareType) {
    const submitBtn = document.querySelector('#repostForm button[type="submit"]');
    let originalText = 'Repost';

    if (submitBtn) {
        originalText = submitBtn.innerHTML;
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Reposting...';
    }

    $.ajax({
        url: `/feed/posts/${postId}/share`,
        method: 'POST',
        data: {
            shared_content: content,
            share_type: shareType,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Close modal if it exists
                const repostModal = document.getElementById('repostModal');
                if (repostModal) {
                    const bsModal = bootstrap.Modal.getInstance(repostModal);
                    if (bsModal) bsModal.hide();
                }

                showNotification('Post shared successfully!', 'success');

                // Reload page to show new post
                setTimeout(() => {
                    window.location.reload();
                }, 1000);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error sharing post:', error);

            let errorMessage = 'Failed to share post. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }

            showNotification(errorMessage, 'error');
        },
        complete: function() {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        }
    });
}

export function copyPostLink(postId) {
    // Get post slug from data attribute or construct URL
    const postContainer = document.querySelector(`.post-container[data-post-id="${postId}"]`);
    const postSlug = postContainer?.dataset.postSlug;

    let postUrl;
    if (postSlug) {
        postUrl = `${window.location.origin}/feed/posts/${postSlug}`;
    } else {
        postUrl = `${window.location.origin}/feed/posts/${postId}`;
    }

    // Copy to clipboard
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard.writeText(postUrl)
            .then(() => {
                showNotification('Link copied to clipboard!', 'success');

                // Close share modal
                const shareModal = bootstrap.Modal.getInstance(document.getElementById('sharePostModal'));
                if (shareModal) shareModal.hide();
            })
            .catch(err => {
                console.error('Failed to copy:', err);
                fallbackCopyToClipboard(postUrl);
            });
    } else {
        fallbackCopyToClipboard(postUrl);
    }
}

function fallbackCopyToClipboard(text) {
    const textArea = document.createElement('textarea');
    textArea.value = text;
    textArea.style.position = 'fixed';
    textArea.style.left = '-999999px';
    document.body.appendChild(textArea);
    textArea.select();

    try {
        document.execCommand('copy');
        showNotification('Link copied to clipboard!', 'success');

        // Close share modal
        const shareModal = bootstrap.Modal.getInstance(document.getElementById('sharePostModal'));
        if (shareModal) shareModal.hide();
    } catch (err) {
        console.error('Failed to copy:', err);
        showNotification('Failed to copy link. Please copy manually.', 'error');
    }

    document.body.removeChild(textArea);
}

function showSendModal(postId) {
    // Placeholder for send modal
    // This would show a modal with a list of connections to send the post to
    showNotification('Send post feature coming soon!', 'info');
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
