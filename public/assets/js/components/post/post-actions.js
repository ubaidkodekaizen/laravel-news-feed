/**
 * Post Actions - Delete & Edit
 */

export function deletePost(postId) {
    if (!postId) {
        showNotification('Unable to delete post. Please try again.', 'error');
        return;
    }

    if (!confirm('Are you sure you want to delete this post? This action cannot be undone.')) {
        return;
    }

    const postContainer = document.querySelector(`.post-container[data-post-id="${postId}"]`);
    if (!postContainer) {
        console.error('Post container not found');
        return;
    }

    const deleteBtn = postContainer.querySelector('.post-menu-btn');
    if (deleteBtn) {
        deleteBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
        deleteBtn.disabled = true;
    }

    postContainer.style.position = 'relative';
    postContainer.style.opacity = '0.6';
    postContainer.style.pointerEvents = 'none';

    $.ajax({
        url: `/news-feed/posts/${postId}`,
        method: 'DELETE',
        data: {
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                postContainer.style.transition = 'all 0.3s ease';
                postContainer.style.opacity = '0';
                postContainer.style.transform = 'translateX(20px)';

                setTimeout(() => {
                    postContainer.remove();
                    showNotification('Post deleted successfully!', 'success');

                    const remainingPosts = document.querySelectorAll('.post-container');
                    if (remainingPosts.length === 0) {
                        showEmptyState();
                    }
                }, 300);
            }
        },
        error: function(xhr) {
            postContainer.style.opacity = '1';
            postContainer.style.pointerEvents = 'auto';

            if (deleteBtn) {
                deleteBtn.innerHTML = '<i class="fa-solid fa-ellipsis"></i>';
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
    if (!postId) {
        showNotification('Unable to edit post.', 'error');
        return;
    }

    // Show loading notification
    showNotification('Loading post...', 'info');

    // Fetch post data - FIXED URL
    $.ajax({
        url: `/news-feed/posts/${postId}/data`, // Changed from /news-feed/posts/${postId}
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            'X-Requested-With': 'XMLHttpRequest'
        },
        success: function(response) {
            if (response.success && response.data) {
                openEditModal(response.data);
            } else {
                throw new Error(response.message || 'Failed to load post');
            }
        },
        error: function(xhr) {
            console.error('Error loading post:', xhr);
            let errorMessage = 'Failed to load post data. Please try again.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showNotification(errorMessage, 'error');
        }
    });
}

 function openEditModal(postData) {
    // Store post ID and original media IDs for tracking
    window.editingPostId = postData.id;
    window.originalPostMedia = postData.media ? postData.media.map(m => m.id) : [];

    // Populate modal with existing content
    $('#postText').val(postData.content || '');
    const contentLength = (postData.content || '').length;
    $('#charCount').text(contentLength);

    // Update character count styling
    const $charCountParent = $('#charCount').parent();
    $charCountParent.removeClass('warning error');
    if (contentLength > 9000) {
        $charCountParent.addClass('error');
    } else if (contentLength > 8000) {
        $charCountParent.addClass('warning');
    }

    // Set visibility
    const visibility = postData.visibility || 'public';
    $('#postModal').data('visibility', visibility);
    $('.visibility-option').removeClass('active');
    $(`.visibility-option[data-visibility="${visibility}"]`).addClass('active');

    const visibilityIcons = {
        public: 'fa-globe',
        connections: 'fa-user-group',
        private: 'fa-lock'
    };
    const visibilityLabels = {
        public: 'Public',
        connections: 'Connections',
        private: 'Private'
    };
    $('#visibilityIcon').attr('class', 'fa-solid ' + visibilityIcons[visibility]);
    $('#visibilityText').text(visibilityLabels[visibility]);

    // Set comments toggle
    $('#commentsEnabledToggle').prop('checked', postData.comments_enabled !== false);

    // Handle existing media
    if (postData.media && postData.media.length > 0) {
        window.mediaList = postData.media.map((media, index) => ({
            id: media.id,
            existingMediaId: media.id,
            src: media.media_url,
            type: media.media_type,
            size: 0,
            name: media.file_name || `Media ${index + 1}`,
            file: null,
            mime_type: media.mime_type
        }));

        updateMediaPreview();
        updateMediaEditor();
    } else {
        window.mediaList = [];
        $('#selectedMediaPreview').hide();
    }

    // Change modal title and button text
    $('#postModalLabel').text('Edit Post');
    $('#submitPostBtn').html('<i class="fa-solid fa-pen me-1"></i> Update Post');

    // Show modal
    $('#postModal').modal('show');
}

// Modify the form submission to handle edits
$(document).ready(function() {
    const originalSubmitHandler = $('#postForm').off('submit').on('submit', function(e) {
        e.preventDefault();

        const isEditing = window.editingPostId !== undefined;
        const postId = window.editingPostId;

        const content = $('#postText').val().trim();
        const commentsEnabled = $('#commentsEnabledToggle').is(':checked') ? 1 : 0;
        const visibility = $('#postModal').data('visibility') || 'public';

        $('#postValidationErrors').addClass('d-none').html('');

        if (!content && window.mediaList.length === 0) {
            showValidationError('Please add some content or media to your post.');
            return;
        }

        if (content.length > 10000) {
            showValidationError('Post content is too long. Maximum 10,000 characters allowed.');
            return;
        }

        if (window.mediaList.length > 10) {
            showValidationError('Maximum 10 media files allowed per post.');
            return;
        }

        // Calculate total size for new media only
        const newMediaSize = window.mediaList
            .filter(m => !m.existingMediaId && m.file)
            .reduce((sum, m) => sum + (m.file?.size || 0), 0);

        if (newMediaSize > 10 * 1024 * 1024) {
            showValidationError('Total new media size exceeds 10MB limit.');
            return;
        }

        const formData = new FormData();
        formData.append('content', content);
        formData.append('comments_enabled', commentsEnabled);
        formData.append('visibility', visibility);

        // Track which existing media to keep vs remove
        const existingMediaIds = window.mediaList
            .filter(m => m.existingMediaId)
            .map(m => m.existingMediaId);

        // Add new media files
        window.mediaList.forEach((media) => {
            if (media.file && !media.existingMediaId) {
                formData.append('media[]', media.file);
            }
        });

        // If editing, track removed media
        if (isEditing && postId) {
            // Get all original media IDs from the post data
            const originalMediaIds = window.originalPostMedia || [];
            const removedIds = originalMediaIds.filter(id => !existingMediaIds.includes(id));

            removedIds.forEach(id => {
                formData.append('remove_media_ids[]', id);
            });
        }

        const submitBtn = $('#submitPostBtn');
        const originalHtml = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> ' + (isEditing ? 'Updating...' : 'Posting...'));

        const url = isEditing ? `/news-feed/posts/${postId}` : '/news-feed/posts';
        const method = isEditing ? 'POST' : 'POST'; // We'll use _method for PUT

        if (isEditing) {
            formData.append('_method', 'PUT');
        }

        $.ajax({
            url: url,
            type: method,
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        .done(function(response) {
            if (response.success) {
                $('#postModal').modal('hide');
                showNotification(isEditing ? 'Post updated successfully!' : 'Post created successfully!', 'success');

                resetPostForm();

                // Update existing post or reload feed
                if (isEditing && postId) {
                    updatePostInDOM(postId, response.data);
                } else {
                    // For new posts, reload after short delay
                    setTimeout(() => {
                        window.location.reload();
                    }, 500);
                }
            } else {
                throw new Error(response.message || (isEditing ? 'Failed to update post' : 'Failed to create post'));
            }
        })
        .fail(function(error) {
            let errorMessage = isEditing ? 'Failed to update post. Please try again.' : 'Failed to create post. Please try again.';

            if (error.responseJSON && error.responseJSON.message) {
                errorMessage = error.responseJSON.message;
            } else if (error.responseJSON && error.responseJSON.errors) {
                const errors = Object.values(error.responseJSON.errors).flat();
                errorMessage = errors.join('<br>');
            }

            showValidationError(errorMessage);
        })
        .always(function() {
            submitBtn.prop('disabled', false).html(originalHtml);
        });
    });
});

function updatePostInDOM(postId, postData) {
    const postContainer = document.querySelector(`.post-container[data-post-id="${postId}"]`);
    if (!postContainer) {
        // Post not in current view, reload page
        window.location.reload();
        return;
    }

    // Update content
    const postTextBlock = postContainer.querySelector('.post-text');
    if (postTextBlock && postData.content) {
        postTextBlock.innerHTML = postData.content.replace(/\n/g, '<br>');
    }

    // Update media (simplified - full implementation would rebuild media grid)
    // For now, just reload the page to show updated media
    setTimeout(() => {
        window.location.reload();
    }, 500);
}

function resetPostForm() {
    $('#postText').val('');
    $('#charCount').text('0');
    $('#charCount').parent().removeClass('warning error');
    $('#commentsEnabledToggle').prop('checked', true);
    $('#postModal').data('visibility', 'public');
    $('#visibilityIcon').attr('class', 'fa-solid fa-globe');
    $('#visibilityText').text('Public');
    $('.visibility-option').removeClass('active');
    $('.visibility-option[data-visibility="public"]').addClass('active');

    // Clear editing state
    delete window.editingPostId;
    delete window.originalPostMedia;

    // Reset modal title/button
    $('#postModalLabel').text('Create a Post');
    $('#submitPostBtn').text('Post');

    clearAllMedia();
    $('#postValidationErrors').addClass('d-none').html('');
}

// Update modal close handler
$('#postModal').on('hidden.bs.modal', function() {
    resetPostForm();
});

function clearAllMedia() {
    window.mediaList.forEach(media => {
        if (media.src && !media.existingMediaId) {
            URL.revokeObjectURL(media.src);
        }
    });

    window.mediaList = [];
    $('#selectedMediaPreview').hide();
    $('#previewMediaWrapper').html('');
    $('#mediaEditorThumbnails').html('');
    $('#mainImagePreview').attr('src', '').addClass('d-none');
    $('#mainVideoPreview').attr('src', '').addClass('d-none');
    $('#mainMediaPreviewContainer #noMediaPlaceholder').removeClass('d-none');
}

function updateMediaPreview() {
    const $preview = $('#selectedMediaPreview');
    const $wrapper = $('#previewMediaWrapper');
    const $count = $('#mediaCount');

    if (window.mediaList.length === 0) {
        $preview.hide();
        return;
    }

    $preview.show();
    $count.text(window.mediaList.length);
    $wrapper.empty();

    window.mediaList.forEach((media) => {
        const $item = $('<div class="media-preview-item"></div>');

        if (media.type === 'image') {
            $item.append(`<img src="${media.src}" alt="Preview">`);
        } else {
            $item.append(`
                <video src="${media.src}"></video>
                <div class="video-overlay">
                    <i class="fa-solid fa-play"></i>
                </div>
            `);
        }

        $wrapper.append($item);
    });
}
function updateMediaEditor() {
    if (!window.mediaList || window.mediaList.length === 0) {
        $('#mediaEditorThumbnails').html('');
        $('#mainImagePreview').attr('src', '').addClass('d-none');
        $('#mainVideoPreview').attr('src', '').addClass('d-none');
        $('#mainMediaPreviewContainer #noMediaPlaceholder').removeClass('d-none');
        return;
    }

    // Update thumbnails
    const $thumbnails = $('#mediaEditorThumbnails');
    $thumbnails.empty();

    window.mediaList.forEach((media, index) => {
        const $thumb = $('<div>', {
            class: 'media-thumbnail' + (index === 0 ? ' active' : ''),
            'data-index': index
        });

        if (media.type === 'image') {
            $thumb.append(`<img src="${media.src}" alt="Thumbnail">`);
        } else {
            $thumb.append(`
                <video src="${media.src}"></video>
                <div class="video-indicator">
                    <i class="fa-solid fa-play"></i>
                </div>
            `);
        }

        const $deleteBtn = $('<button>', {
            class: 'delete-media-btn',
            type: 'button',
            html: '<i class="fa-solid fa-times"></i>',
            click: function(e) {
                e.stopPropagation();
                removeMediaFromList(index);
            }
        });

        $thumb.append($deleteBtn);
        $thumb.on('click', function() {
            showMediaInPreview(index);
        });

        $thumbnails.append($thumb);
    });

    // Show first media in preview
    showMediaInPreview(0);
}

function showMediaInPreview(index) {
    if (!window.mediaList || !window.mediaList[index]) return;

    const media = window.mediaList[index];

    // Update active thumbnail
    $('#mediaEditorThumbnails .media-thumbnail').removeClass('active');
    $(`#mediaEditorThumbnails .media-thumbnail[data-index="${index}"]`).addClass('active');

    // Show appropriate preview
    if (media.type === 'image') {
        $('#mainImagePreview').attr('src', media.src).removeClass('d-none');
        $('#mainVideoPreview').addClass('d-none');
        $('#mainMediaPreviewContainer #noMediaPlaceholder').addClass('d-none');
    } else {
        $('#mainVideoPreview').attr('src', media.src).removeClass('d-none');
        $('#mainImagePreview').addClass('d-none');
        $('#mainMediaPreviewContainer #noMediaPlaceholder').addClass('d-none');
    }
}

function removeMediaFromList(index) {
    if (!window.mediaList || !window.mediaList[index]) return;

    const media = window.mediaList[index];

    // Revoke object URL if it's a new upload
    if (media.src && !media.existingMediaId && media.src.startsWith('blob:')) {
        URL.revokeObjectURL(media.src);
    }

    // Remove from list
    window.mediaList.splice(index, 1);

    // Update both previews
    updateMediaPreview();
    updateMediaEditor();

    // If no media left, hide preview
    if (window.mediaList.length === 0) {
        $('#selectedMediaPreview').hide();
    }
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

function showValidationError(message) {
    const errorContainer = $('#postValidationErrors');
    errorContainer.html(message).removeClass('d-none');
    errorContainer[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Export for global access
window.updateMediaPreview = updateMediaPreview;
window.clearAllMedia = clearAllMedia;
window.updateMediaEditor = updateMediaEditor;
window.clearAllMedia = clearAllMedia;
