export function connectUser(userId) {
    if (!userId) {
        showNotification('Unable to send connection request.', 'error');
        return;
    }

    // Find the button
    const button = event.target.closest('button');
    const originalContent = button.innerHTML;

    button.disabled = true;
    button.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Sending...';

    $.ajax({
        url: '/connections/send',
        method: 'POST',
        data: {
            user_id: userId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                button.innerHTML = '<i class="fa-solid fa-clock"></i> Pending';
                button.classList.remove('btn-outline-primary');
                button.classList.add('btn-outline-secondary');
                button.disabled = true;
                showNotification('Connection request sent!', 'success');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error sending connection request:', error);
            button.disabled = false;
            button.innerHTML = originalContent;

            let errorMessage = 'Failed to send connection request.';
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            showNotification(errorMessage, 'error');
        }
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
