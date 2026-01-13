/**
 * Delete Post Functionality
 * Add this to a new file or include in your main post functions
 */

export function deletePost(postId) {
    if (!postId) {
        console.error('Post ID is required');
        showNotification('Unable to delete post. Please try again.', 'error');
        return;
    }

    // Show confirmation dialog
    if (!confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
        return;
    }

    const postContainer = document.querySelector(`.post-container[data-post-id="${postId}"]`);
    if (!postContainer) {
        console.error('Post container not found');
        return;
    }

    // Show loading state on the post
    const deleteBtn = postContainer.querySelector('.cross_btn');
    if (deleteBtn) {
        deleteBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
        deleteBtn.disabled = true;
    }

    // Add loading overlay to post
    postContainer.style.position = 'relative';
    postContainer.style.opacity = '0.6';
    postContainer.style.pointerEvents = 'none';

    $.ajax({
        url: `/feed/posts/${postId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Animate post removal
                postContainer.style.transition = 'all 0.3s ease';
                postContainer.style.opacity = '0';
                postContainer.style.transform = 'translateX(20px)';

                setTimeout(() => {
                    postContainer.remove();
                    showNotification('Post deleted successfully!', 'success');

                    // Check if no posts left
                    const remainingPosts = document.querySelectorAll('.post-container');
                    if (remainingPosts.length === 0) {
                        showEmptyState();
                    }
                }, 300);
            } else {
                throw new Error(response.message || 'Failed to delete post');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error deleting post:', error);

            // Restore post state
            postContainer.style.opacity = '1';
            postContainer.style.pointerEvents = 'auto';

            if (deleteBtn) {
                deleteBtn.innerHTML = '<i class="fa-solid fa-xmark"></i>';
                deleteBtn.disabled = false;
            }

            let errorMessage = 'Failed to delete post. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 403) {
                errorMessage = 'You do not have permission to delete this post.';
            } else if (xhr.status === 404) {
                errorMessage = 'Post not found.';
            }

            showNotification(errorMessage, 'error');
        }
    });
}

export function editPost(postId) {
    const postContainer = document.querySelector(`.post-container[data-post-id="${postId}"]`);
    if (!postContainer) {
        console.error('Post container not found');
        return;
    }

    const postTextBlock = postContainer.querySelector('.post-text');
    if (!postTextBlock) {
        console.error('Post text block not found');
        return;
    }

    const currentContent = postTextBlock.textContent.trim();

    // Show edit modal or inline editor
    showEditPostModal(postId, currentContent);
}

function showEditPostModal(postId, currentContent) {
    // Check if edit modal exists, if not create it
    let editModal = document.getElementById('editPostModal');

    if (!editModal) {
        const modalHtml = `
            <div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <form id="editPostForm">
                            <div class="modal-header">
                                <h5 class="modal-title">Edit Post</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <textarea class="form-control" id="editPostText" rows="5" placeholder="What do you want to talk about?"></textarea>
                                <small class="text-muted mt-2 d-block">Note: Images cannot be edited. Delete and create a new post if you need to change images.</small>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Update Post</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML('beforeend', modalHtml);
        editModal = document.getElementById('editPostModal');

        // Add form submit handler
        document.getElementById('editPostForm').addEventListener('submit', function(e) {
            e.preventDefault();
            updatePost(postId);
        });
    }

    // Set current content
    document.getElementById('editPostText').value = currentContent;

    // Show modal
    const bsModal = new bootstrap.Modal(editModal);
    bsModal.show();
}

function updatePost(postId) {
    const content = document.getElementById('editPostText').value.trim();

    if (!content) {
        showNotification('Please enter some content for your post.', 'error');
        return;
    }

    const submitBtn = document.querySelector('#editPostForm button[type="submit"]');
    const originalText = submitBtn.innerHTML;
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Updating...';

    $.ajax({
        url: `/feed/posts/${postId}`,
        method: 'PUT',
        data: {
            content: content,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                // Update post content in DOM
                const postContainer = document.querySelector(`.post-container[data-post-id="${postId}"]`);
                if (postContainer) {
                    const postTextBlock = postContainer.querySelector('.post-text');
                    if (postTextBlock) {
                        postTextBlock.textContent = content;
                    }
                }

                // Close modal
                const editModal = bootstrap.Modal.getInstance(document.getElementById('editPostModal'));
                editModal.hide();

                showNotification('Post updated successfully!', 'success');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error updating post:', error);

            let errorMessage = 'Failed to update post. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }

            showNotification(errorMessage, 'error');
        },
        complete: function() {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    });
}

function showEmptyState() {
    const feedColumn = document.querySelector('.newFeedSecInnerCol:nth-child(2)');
    if (!feedColumn) return;

    const emptyStateHtml = `
        <div class="card mb-3" id="emptyStateCard">
            <div class="card-body text-center py-5">
                <i class="fa-regular fa-newspaper fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No posts yet</h5>
                <p class="text-muted">Start sharing your thoughts with your network!</p>
                <button class="btn btn-primary" id="createFirstPost">Create Post</button>
            </div>
        </div>
    `;

    feedColumn.insertAdjacentHTML('beforeend', emptyStateHtml);

    // Add click handler for create post button
    document.getElementById('createFirstPost').addEventListener('click', function() {
        document.getElementById('openPostModal').click();
    });
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
