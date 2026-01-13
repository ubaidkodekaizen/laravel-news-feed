$(document).ready(function () {
    // Open post modal
    $('#openPostModal').click(function () {
        $('#postModal').modal('show');
    });

    // Handle post form submission
    $('#postForm').submit(function (e) {
        e.preventDefault();

        const content = $('#postText').val().trim();
        const commentsEnabled = $('#commentsEnabledToggle').is(':checked') ? 1 : 0;

        // Validate content or images
        if (!content && imageList.length === 0) {
            alert('Please add some content or images to your post.');
            return;
        }

        // Prepare FormData
        const formData = new FormData();
        formData.append('content', content);
        formData.append('comments_enabled', commentsEnabled);

        // Convert blob URLs back to files
        const filePromises = imageList.map((img, index) => {
            return fetch(img.src)
                .then(res => res.blob())
                .then(blob => {
                    const file = new File([blob], `image_${index}.jpg`, { type: 'image/jpeg' });
                    formData.append('media[]', file);
                });
        });

        // Show loading state
        const submitBtn = $('#postForm button[type="submit"]');
        const originalText = submitBtn.text();
        submitBtn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Posting...');

        // Wait for all images to be converted, then submit
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
                    // Success! Close modal and refresh page
                    $('#postModal').modal('hide');

                    // Show success message
                    showNotification('Post created successfully!', 'success');

                    // Reload page to show new post
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
                } else if (error.message) {
                    errorMessage = error.message;
                }

                showNotification(errorMessage, 'error');
            })
            .finally(() => {
                // Reset button state
                submitBtn.prop('disabled', false).text(originalText);
            });
    });

    // Reset modal on close
    $('#postModal').on('hidden.bs.modal', function () {
        $('#postText').val('');
        $('#commentsEnabledToggle').prop('checked', true);
        imageList = [];
        $('#selectedImagesPreview').hide();
        $('#previewImagesWrapper').html('');
        $('#imageEditorThumbnails').html('');
        $('#mainImagePreview').attr('src', '');
    });
});

// Notification helper function
function showNotification(message, type = 'info') {
    // Check if notification container exists, if not create it
    if ($('#notification-container').length === 0) {
        $('body').append('<div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>');
    }

    const alertClass = type === 'success' ? 'alert-success' :
                      type === 'error' ? 'alert-danger' :
                      'alert-info';

    const notification = $(`
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);

    $('#notification-container').append(notification);

    // Auto dismiss after 5 seconds
    setTimeout(() => {
        notification.alert('close');
    }, 5000);
}
