let hideTimeout = null;

export function showReactions(el) {
    const wrapper = el.closest('.reaction-wrapper');
    const panel = wrapper.querySelector('.reaction-panel');

    // Reset animations
    panel.querySelectorAll('.reaction-emoji').forEach(emoji => {
        emoji.style.animation = 'none';
        emoji.offsetHeight;
        emoji.style.animation = '';
    });

    panel.classList.remove('d-none');
    clearTimeout(hideTimeout);
}

export function hideReactions(el) {
    hideTimeout = setTimeout(() => {
        const panel = el.querySelector('.reaction-panel');
        if (panel) panel.classList.add('d-none');
    }, 250);
}

export function cancelHide() {
    clearTimeout(hideTimeout);
}

export function applyReaction(span, emoji, label, type) {
    const wrapper = span.closest('.reaction-wrapper');
    const iconWrapper = wrapper.querySelector('.reaction-icon');
    const labelEl = wrapper.querySelector('.reaction-label');
    const postId = wrapper.dataset.postId || wrapper.closest('.post-container').dataset.postId;

    if (!postId) {
        console.error('Post ID not found');
        return;
    }

    // Optimistically update UI
    if (iconWrapper) iconWrapper.innerHTML = emoji;
    if (labelEl) labelEl.textContent = label;
    wrapper.querySelector('.reaction-panel').classList.add('d-none');

    // Send to backend
    $.ajax({
        url: '/feed/reactions',
        method: 'POST',
        data: {
            reactionable_type: 'App\\Models\\Feed\\Post',
            reactionable_id: postId,
            reaction_type: type,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                console.log('Reaction saved:', response);

                // Update reaction count
                updateReactionCount(postId, response.reaction);

                // Show notification
                showNotification(`You reacted with ${emoji}`, 'success');
            }
        },
        error: function(xhr, status, error) {
            console.error('Error saving reaction:', error);

            // Revert UI on error
            if (iconWrapper) iconWrapper.innerHTML = '<i class="fa-regular fa-thumbs-up"></i>';
            if (labelEl) labelEl.textContent = 'Like';

            showNotification('Failed to save reaction. Please try again.', 'error');
        }
    });
}

function updateReactionCount(postId, reaction) {
    const postContainer = document.querySelector(`.post-container[data-post-id="${postId}"]`);
    if (!postContainer) return;

    const likesCountEl = postContainer.querySelector('.likes-count .count-text');

    if (reaction === null) {
        // Reaction was removed
        const currentCount = parseInt(likesCountEl.textContent) || 0;
        likesCountEl.textContent = Math.max(0, currentCount - 1);
    } else {
        // New reaction or changed reaction
        // For simplicity, we'll fetch the updated count
        fetchPostReactionCount(postId);
    }
}

function fetchPostReactionCount(postId) {
    $.ajax({
        url: `/feed/posts/${postId}/reactions-count`,
        method: 'GET',
        success: function(response) {
            if (response.success) {
                const postContainer = document.querySelector(`.post-container[data-post-id="${postId}"]`);
                if (postContainer) {
                    const likesCountEl = postContainer.querySelector('.likes-count .count-text');
                    if (likesCountEl) {
                        likesCountEl.textContent = response.count;
                    }

                    // Update reactions preview
                    updateReactionsPreview(postId, response.reactions);
                }
            }
        },
        error: function(xhr, status, error) {
            console.error('Error fetching reaction count:', error);
        }
    });
}

function updateReactionsPreview(postId, reactions) {
    const postContainer = document.querySelector(`.post-container[data-post-id="${postId}"]`);
    if (!postContainer) return;

    const reactionsPreview = postContainer.querySelector('.reactions-preview');
    if (!reactionsPreview) return;

    // Clear existing reactions
    reactionsPreview.innerHTML = '';

    // Get unique reaction types (up to 3)
    const uniqueReactions = [];
    const seenTypes = new Set();

    for (const reaction of reactions) {
        if (!seenTypes.has(reaction.type) && uniqueReactions.length < 3) {
            uniqueReactions.push(reaction);
            seenTypes.add(reaction.type);
        }
    }

    // Add reaction emojis
    uniqueReactions.forEach(reaction => {
        const emoji = getReactionEmoji(reaction.type);
        const span = document.createElement('span');
        span.className = 'reaction-emoji-preview';
        span.textContent = emoji;
        reactionsPreview.appendChild(span);
    });
}

function getReactionEmoji(type) {
    const emojiMap = {
        'like': '👍',
        'love': '💖',
        'celebrate': '👏',
        'insightful': '💡',
        'funny': '😂',
        'haha': '😂',
        'wow': '😲',
        'sad': '😢',
        'angry': '😠'
    };

    return emojiMap[type] || '👍';
}

function showNotification(message, type = 'info') {
    // Check if notification container exists, if not create it
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

    // Auto dismiss after 3 seconds
    setTimeout(() => {
        notification.alert('close');
    }, 3000);
}

// Handle reaction button click (remove if same type, otherwise show panel)
export function handleReactionClick(postId, currentReactionType) {
    if (currentReactionType) {
        // User has already reacted, remove it
        removeReaction(postId);
    }
    // Otherwise, the hover will show the panel
}

// Remove reaction function (called when clicking same reaction again)
export function removeReaction(postId) {
    $.ajax({
        url: '/feed/reactions',
        method: 'DELETE',
        data: {
            reactionable_type: 'App\\Models\\Feed\\Post',
            reactionable_id: postId,
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            if (response.success) {
                console.log('Reaction removed');

                // Update UI
                const postContainer = document.querySelector(`.post-container[data-post-id="${postId}"]`);
                if (postContainer) {
                    const wrapper = postContainer.querySelector('.reaction-wrapper');
                    const iconWrapper = wrapper.querySelector('.reaction-icon');
                    const labelEl = wrapper.querySelector('.reaction-label');

                    // Reset to default
                    if (iconWrapper) iconWrapper.innerHTML = '<i class="fa-regular fa-thumbs-up"></i>';
                    if (labelEl) labelEl.textContent = 'Like';
                }

                // Update count
                updateReactionCount(postId, null);
            }
        },
        error: function(xhr, status, error) {
            console.error('Error removing reaction:', error);
            showNotification('Failed to remove reaction. Please try again.', 'error');
        }
    });
}
