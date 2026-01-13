/**
 * Improved Modal System
 * Handles post creation with comprehensive validation
 */

$(document).ready(function() {
    // Initialize media list
    window.mediaList = window.mediaList || [];

    // Open post modal
    $('#openPostModal, .addPhoto, .addVideo, #createFirstPost').click(function() {
        $('#postModal').modal('show');
    });

    // Photo upload button click
    $('#uploadPhotoBtn').click(function() {
        $('#mediaUpload').attr('accept', 'image/*').click();
    });

    // Video upload button click
    $('#uploadVideoBtn').click(function() {
        $('#mediaUpload').attr('accept', 'video/*').click();
    });

    // Handle file selection
    $('#mediaUpload').on('change', function(e) {
        const files = Array.from(e.target.files);

        if (files.length === 0) return;

        // Validate total count
        if (window.mediaList.length + files.length > 10) {
            showNotification('Maximum 10 media files allowed per post.', 'error');
            this.value = ''; // Reset input
            return;
        }

        // Process each file
        files.forEach(file => {
            // Validate file size (4MB)
            if (file.size > 4 * 1024 * 1024) {
                showNotification(`${file.name} exceeds 4MB limit and was skipped.`, 'error');
                return;
            }

            // Validate file type
            const isImage = file.type.startsWith('image/');
            const isVideo = file.type.startsWith('video/');

            if (!isImage && !isVideo) {
                showNotification(`${file.name} is not a valid image or video file.`, 'error');
                return;
            }

            // Create object URL for preview
            const objectURL = URL.createObjectURL(file);

            // Add to media list
            window.mediaList.push({
                id: Date.now() + Math.random(),
                file: file,
                src: objectURL,
                type: isVideo ? 'video' : 'image',
                size: file.size,
                name: file.name
            });
        });

        // Update preview
        updateMediaPreview();

        // Reset input so same file can be selected again
        this.value = '';
    });

    // Edit media button
    $('#editMediaBtn').click(function() {
        if (window.mediaList.length > 0) {
            $('#mediaUploadModal').modal('show');
            renderMediaEditor();
        }
    });

    // Clear all media
    $('#clearMediaBtn').click(function() {
        if (confirm('Are you sure you want to remove all media?')) {
            clearAllMedia();
        }
    });

    // Character count
    $('#postText').on('input', function() {
        const length = $(this).val().length;
        const $charCount = $('#charCount');
        const $container = $charCount.parent();

        $charCount.text(length);

        // Remove all classes first
        $container.removeClass('warning error');

        // Add appropriate class based on length
        if (length > 9000) {
            $container.addClass('error');
        } else if (length > 8000) {
            $container.addClass('warning');
        }
    });

    // Visibility selection
    $('.visibility-option').click(function(e) {
        e.preventDefault();

        const visibility = $(this).data('visibility');
        const icons = {
            'public': 'fa-globe',
            'connections': 'fa-user-group',
            'private': 'fa-lock'
        };
        const labels = {
            'public': 'Public',
            'connections': 'Connections',
            'private': 'Private'
        };

        // Update button
        $('#visibilityIcon').attr('class', 'fa-solid ' + icons[visibility]);
        $('#visibilityText').text(labels[visibility]);

        // Store selection
        $('#postModal').data('visibility', visibility);

        // Update active state
        $('.visibility-option').removeClass('active');
        $(this).addClass('active');
    });

    // Handle post form submission
    $('#postForm').submit(function(e) {
        e.preventDefault();

        const content = $('#postText').val().trim();
        const commentsEnabled = $('#commentsEnabledToggle').is(':checked') ? 1 : 0;
        const visibility = $('#postModal').data('visibility') || 'public';

        // Clear previous errors
        $('#postValidationErrors').addClass('d-none').html('');

        // Validate content or media
        if (!content && window.mediaList.length === 0) {
            showValidationError('Please add some content or media to your post.');
            return;
        }

        // Validate content length
        if (content.length > 10000) {
            showValidationError('Post content is too long. Maximum 10,000 characters allowed.');
            return;
        }

        // Validate media count
        if (window.mediaList.length > 10) {
            showValidationError('Maximum 10 media files allowed per post.');
            return;
        }

        // Validate media sizes
        const oversizedFiles = window.mediaList.filter(m => m.size > 4 * 1024 * 1024);
        if (oversizedFiles.length > 0) {
            showValidationError('Some files exceed the 4MB size limit. Please remove them and try again.');
            return;
        }

        // Prepare FormData
        const formData = new FormData();
        formData.append('content', content);
        formData.append('comments_enabled', commentsEnabled);
        formData.append('visibility', visibility);

        // Show loading state
        const submitBtn = $('#submitPostBtn');
        const originalHtml = submitBtn.html();
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin me-1"></i> Posting...');

        // Add media files directly
        window.mediaList.forEach((media, index) => {
            if (media.file) {
                formData.append('media[]', media.file);
            }
        });

        // Submit form
        $.ajax({
            url: '/feed/posts',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })
        .done(function(response) {
            if (response.success) {
                // Close modal
                $('#postModal').modal('hide');

                // Show success message
                showNotification('Post created successfully!', 'success');

                // Clear form
                resetPostForm();

                // Reload feed to show new post
                setTimeout(() => {
                    window.location.reload();
                }, 500);
            } else {
                throw new Error(response.message || 'Failed to create post');
            }
        })
        .fail(function(error) {
            console.error('Error creating post:', error);

            let errorMessage = 'Failed to create post. Please try again.';

            if (error.responseJSON && error.responseJSON.message) {
                errorMessage = error.responseJSON.message;
            } else if (error.responseJSON && error.responseJSON.errors) {
                // Handle Laravel validation errors
                const errors = Object.values(error.responseJSON.errors).flat();
                errorMessage = errors.join('<br>');
            } else if (error.message) {
                errorMessage = error.message;
            }

            showValidationError(errorMessage);
        })
        .always(function() {
            // Reset button state
            submitBtn.prop('disabled', false).html(originalHtml);
        });
    });

    // Reset modal on close
    $('#postModal').on('hidden.bs.modal', function() {
        resetPostForm();
    });

    // Reset media upload modal on close
    $('#mediaUploadModal').on('hidden.bs.modal', function() {
        // Don't clear mediaList here, only when user clicks clear
    });
});

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

    window.mediaList.forEach(media => {
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

function clearAllMedia() {
    // Revoke all object URLs to free memory
    window.mediaList.forEach(media => {
        if (media.src) {
            URL.revokeObjectURL(media.src);
        }
    });

    window.mediaList = [];
    updateMediaPreview();

    // Also clear editor if open
    $('#mediaEditorThumbnails').html('');
    $('#mainImagePreview').attr('src', '').addClass('d-none');
    $('#mainVideoPreview').attr('src', '').addClass('d-none');
    $('#mainMediaPreviewContainer #noMediaPlaceholder').removeClass('d-none');
}

function renderMediaEditor() {
    const $thumbnails = $('#mediaEditorThumbnails');
    $thumbnails.empty();

    if (window.mediaList.length === 0) {
        $('#mainMediaPreviewContainer #noMediaPlaceholder').removeClass('d-none');
        return;
    }

    $('#mainMediaPreviewContainer #noMediaPlaceholder').addClass('d-none');

    window.mediaList.forEach((media, index) => {
        const $thumbnail = $(`
            <div class="thumbnail-item ${index === 0 ? 'active' : ''}" data-index="${index}">
                ${media.type === 'image'
                    ? `<img src="${media.src}" alt="Thumbnail">`
                    : `<video src="${media.src}"></video><div class="video-badge"><i class="fa-solid fa-play"></i></div>`
                }
                <button type="button" class="remove-thumbnail" data-index="${index}">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
        `);

        $thumbnail.find('.remove-thumbnail').click(function(e) {
            e.stopPropagation();
            removeMediaItem(index);
        });

        $thumbnail.click(function() {
            showMediaInEditor(index);
        });

        $thumbnails.append($thumbnail);
    });

    // Show first item in main preview
    if (window.mediaList.length > 0) {
        showMediaInEditor(0);
    }
}

function showMediaInEditor(index) {
    const media = window.mediaList[index];
    if (!media) return;

    // Update thumbnails
    $('.thumbnail-item').removeClass('active');
    $(`.thumbnail-item[data-index="${index}"]`).addClass('active');

    // Update main preview
    if (media.type === 'image') {
        $('#mainImagePreview').attr('src', media.src).removeClass('d-none');
        $('#mainVideoPreview').addClass('d-none');
    } else {
        $('#mainVideoPreview').attr('src', media.src).removeClass('d-none');
        $('#mainImagePreview').addClass('d-none');
    }
}

function removeMediaItem(index) {
    if (index < 0 || index >= window.mediaList.length) return;

    // Revoke object URL
    const media = window.mediaList[index];
    if (media.src) {
        URL.revokeObjectURL(media.src);
    }

    // Remove from array
    window.mediaList.splice(index, 1);

    // Update both previews
    updateMediaPreview();
    renderMediaEditor();

    // Close modal if no media left
    if (window.mediaList.length === 0) {
        $('#mediaUploadModal').modal('hide');
    }
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

    // Clear media
    clearAllMedia();

    // Clear validation errors
    $('#postValidationErrors').addClass('d-none').html('');
}

function showValidationError(message) {
    const errorContainer = $('#postValidationErrors');
    errorContainer.html(message).removeClass('d-none');

    // Scroll to error
    errorContainer[0].scrollIntoView({ behavior: 'smooth', block: 'nearest' });
}

// Notification helper function
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
    }, 5000);
}
