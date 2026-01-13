/**
 * Improved Modal System
 * Handles post creation with comprehensive validation
 */

$(document).ready(function() {
    // Open post modal
    $('#openPostModal, .addPhoto, .addVideo, #createFirstPost').click(function() {
        $('#postModal').modal('show');
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

        // Convert media files
        const filePromises = window.mediaList.map((media, index) => {
            return new Promise((resolve, reject) => {
                // If we have the original file, use it directly
                if (media.file) {
                    formData.append('media[]', media.file);
                    resolve();
                } else {
                    // Otherwise, fetch the blob from the object URL
                    fetch(media.src)
                        .then(res => res.blob())
                        .then(blob => {
                            const extension = media.type === 'video' ? 'mp4' : 'jpg';
                            const file = new File([blob], `${media.type}_${index}.${extension}`, {
                                type: blob.type || (media.type === 'video' ? 'video/mp4' : 'image/jpeg')
                            });
                            formData.append('media[]', file);
                            resolve();
                        })
                        .catch(reject);
                }
            });
        });

        // Wait for all media to be processed, then submit
        Promise.all(filePromises)
            .then(() => {
                return $.ajax({
                    url: '/feed/posts',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
            })
            .then(response => {
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
            .catch(error => {
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
            .finally(() => {
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
    if (window.mediaList) {
        window.mediaList.forEach(media => {
            if (media.src) {
                URL.revokeObjectURL(media.src);
            }
        });
        window.mediaList = [];
    }

    $('#selectedMediaPreview').hide();
    $('#previewMediaWrapper').html('');
    $('#mediaEditorThumbnails').html('');
    $('#mainImagePreview').attr('src', '').addClass('d-none');
    $('#mainVideoPreview').attr('src', '').addClass('d-none');
    $('#mainMediaPreviewContainer #noMediaPlaceholder').removeClass('d-none');

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
