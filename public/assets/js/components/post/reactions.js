/**
 * Improved Reactions System
 * Handles all reaction interactions with proper state management
 */

let hideTimeout = null;

export function showReactions(el) {
    const wrapper = el.closest('.reaction-wrapper');
    if (!wrapper) return;

    const panel = wrapper.querySelector('.reaction-panel');
    if (!panel) return;

    // Reset animations
    panel.querySelectorAll('.reaction-emoji').forEach(emoji => {
        emoji.style.animation = 'none';
        emoji.offsetHeight; // Trigger reflow
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
    if (!span || !type) {
        console.error('Invalid reaction parameters');
        return;
    }

    const wrapper = span.closest('.reaction-wrapper');
    if (!wrapper) return;

    const iconWrapper = wrapper.querySelector('.reaction-icon');
    const labelEl = wrapper.querySelector('.reaction-label');
    const postId = wrapper.dataset.postId || wrapper.closest('.post-container')?.dataset.postId;
    const actionBtn = wrapper.querySelector('.action-btn');

    if (!postId) {
        console.error('Post ID not found');
        showNotification('Unable to add reaction. Please refresh the page.', 'error');
        return;
    }

    // Get current reaction
    const currentReaction = actionBtn?.dataset.currentReaction || '';

    // Optimistically update UI
    if (iconWrapper) iconWrapper.innerHTML = emoji;
    if (labelEl) labelEl.textContent = label;
    if (actionBtn) actionBtn.dataset.currentReaction = type;

    wrapper.querySelector('.reaction-panel')?.classList.add('d-none');

    // Send to backend
    saveReaction(postId, type, currentReaction, {
        iconWrapper,
        labelEl,
        actionBtn,
        emoji,
        label
    });
}

async function saveReaction(postId, reactionType, currentReaction, elements) {
    try {
        const response = await fetch('/feed/reactions', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                reactionable_type: 'App\\Models\\Feed\\Post',
                reactionable_id: postId,
                reaction_type: reactionType
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            // Update reaction count and preview
            updateReactionDisplay(postId, result.reaction);
        } else {
            throw new Error(result.message || 'Failed to save reaction');
        }
    } catch (error) {
        console.error('Error saving reaction:', error);

        // Revert UI on error
        if (currentReaction) {
            // Restore previous reaction
            const emoji = getReactionEmoji(currentReaction);
            const label = capitalizeFirst(currentReaction);
            if (elements.iconWrapper) elements.iconWrapper.innerHTML = emoji;
            if (elements.labelEl) elements.labelEl.textContent = label;
            if (elements.actionBtn) elements.actionBtn.dataset.currentReaction = currentReaction;
        } else {
            // Restore to default (no reaction)
            if (elements.iconWrapper) elements.iconWrapper.innerHTML = '<i class="fa-regular fa-thumbs-up"></i>';
            if (elements.labelEl) elements.labelEl.textContent = 'Like';
            if (elements.actionBtn) elements.actionBtn.dataset.currentReaction = '';
        }

        showNotification('Failed to save reaction. Please try again.', 'error');
    }
}

export function handleReactionClick(postId, currentReactionType) {
    if (!postId) return;

    // If user already reacted with this type, remove the reaction
    if (currentReactionType) {
        removeReaction(postId);
    }
    // Otherwise, the hover will show the reaction panel
}

export async function removeReaction(postId) {
    if (!postId) return;

    const wrapper = document.querySelector(`.reaction-wrapper[data-post-id="${postId}"]`);
    if (!wrapper) return;

    const iconWrapper = wrapper.querySelector('.reaction-icon');
    const labelEl = wrapper.querySelector('.reaction-label');
    const actionBtn = wrapper.querySelector('.action-btn');

    // Store current state for potential revert
    const previousEmoji = iconWrapper?.innerHTML;
    const previousLabel = labelEl?.textContent;
    const previousReaction = actionBtn?.dataset.currentReaction;

    // Optimistically update UI to default
    if (iconWrapper) iconWrapper.innerHTML = '<i class="fa-regular fa-thumbs-up"></i>';
    if (labelEl) labelEl.textContent = 'Like';
    if (actionBtn) actionBtn.dataset.currentReaction = '';

    try {
        const response = await fetch('/feed/reactions', {
            method: 'DELETE',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                reactionable_type: 'App\\Models\\Feed\\Post',
                reactionable_id: postId
            })
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            // Update count
            updateReactionDisplay(postId, null);
        } else {
            throw new Error(result.message || 'Failed to remove reaction');
        }
    } catch (error) {
        console.error('Error removing reaction:', error);

        // Revert UI on error
        if (iconWrapper) iconWrapper.innerHTML = previousEmoji;
        if (labelEl) labelEl.textContent = previousLabel;
        if (actionBtn) actionBtn.dataset.currentReaction = previousReaction;

        showNotification('Failed to remove reaction. Please try again.', 'error');
    }
}

async function updateReactionDisplay(postId, reaction) {
    const postContainer = document.querySelector(`.post-container[data-post-id="${postId}"]`);
    if (!postContainer) return;

    try {
        // Fetch updated reaction count from server
        const response = await fetch(`/feed/posts/${postId}/reactions-count`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) return;

        const result = await response.json();

        if (result.success) {
            const likesCountEl = postContainer.querySelector('.likes-count .count-text');
            if (likesCountEl) {
                likesCountEl.textContent = result.count;
            }

            // Update reactions preview
            if (result.reactions && result.reactions.length > 0) {
                updateReactionsPreview(postId, result.reactions);
            } else {
                // Clear reactions preview if no reactions
                const reactionsPreview = postContainer.querySelector('.reactions-preview');
                if (reactionsPreview) {
                    reactionsPreview.innerHTML = '';
                }
            }
        }
    } catch (error) {
        console.error('Error fetching reaction count:', error);
    }
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

export async function showReactionsList(postId) {
    if (!postId) return;

    const modal = document.getElementById('reactionsModal');
    if (!modal) {
        console.error('Reactions modal not found');
        return;
    }

    // Show modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();

    // Show loading state
    document.getElementById('reactionsLoading')?.classList.remove('d-none');
    document.getElementById('reactionsEmpty')?.classList.add('d-none');

    try {
        const response = await fetch(`/feed/posts/${postId}/reactions-list`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        });

        if (!response.ok) {
            throw new Error('Failed to fetch reactions');
        }

        const result = await response.json();

        // Hide loading
        document.getElementById('reactionsLoading')?.classList.add('d-none');

        if (result.success && result.reactions && result.reactions.length > 0) {
            displayReactionsList(result.reactions);
        } else {
            document.getElementById('reactionsEmpty')?.classList.remove('d-none');
        }
    } catch (error) {
        console.error('Error loading reactions:', error);
        document.getElementById('reactionsLoading')?.classList.add('d-none');
        document.getElementById('reactionsEmpty')?.classList.remove('d-none');
        showNotification('Failed to load reactions. Please try again.', 'error');
    }
}

function displayReactionsList(reactions) {
    // Group reactions by type
    const reactionsByType = {
        all: reactions,
        like: reactions.filter(r => r.type === 'like'),
        love: reactions.filter(r => r.type === 'love'),
        celebrate: reactions.filter(r => r.type === 'celebrate'),
        insightful: reactions.filter(r => r.type === 'insightful'),
        funny: reactions.filter(r => r.type === 'funny' || r.type === 'haha'),
    };

    // Update counts
    document.getElementById('allCount').textContent = reactionsByType.all.length;
    document.getElementById('likeCount').textContent = reactionsByType.like.length;
    document.getElementById('loveCount').textContent = reactionsByType.love.length;
    document.getElementById('celebrateCount').textContent = reactionsByType.celebrate.length;

    // Render lists
    renderReactionsList('allReactionsList', reactionsByType.all);
    renderReactionsList('likeReactionsList', reactionsByType.like);
    renderReactionsList('loveReactionsList', reactionsByType.love);
    renderReactionsList('celebrateReactionsList', reactionsByType.celebrate);
}

function renderReactionsList(containerId, reactions) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = '';

    if (reactions.length === 0) {
        container.innerHTML = '<p class="text-center text-muted py-4">No reactions of this type</p>';
        return;
    }

    reactions.forEach(reaction => {
        const item = createReactionItem(reaction);
        container.appendChild(item);
    });
}

function createReactionItem(reaction) {
    const div = document.createElement('div');
    div.className = 'reaction-item';

    const avatarHTML = reaction.user.has_photo && reaction.user.avatar
        ? `<img src="${reaction.user.avatar}" class="user-avatar" alt="${reaction.user.name}">`
        : `<div class="user-initials">${reaction.user.initials}</div>`;

    const emoji = getReactionEmoji(reaction.type);

    div.innerHTML = `
        ${avatarHTML}
        <div class="user-info">
            <div class="user-name">${escapeHtml(reaction.user.name)}</div>
            ${reaction.user.position ? `<div class="user-position">${escapeHtml(reaction.user.position)}</div>` : ''}
        </div>
        <div class="reaction-emoji">${emoji}</div>
    `;

    return div;
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

function capitalizeFirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
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
