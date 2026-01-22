/**
 * Improved Reactions System
 * Handles all reaction interactions with proper state management
 * Reaction types: like, love, haha, wow, sad, angry (matching your modal)
 */

let hideTimeout = null;

export function showReactions(el) {
    const wrapper = el.closest(".reaction-wrapper");
    if (!wrapper) return;

    const panel = wrapper.querySelector(".reaction-panel");
    if (!panel) return;

    // Reset animations
    panel.querySelectorAll(".reaction-emoji").forEach((emoji) => {
        emoji.style.animation = "none";
        emoji.offsetHeight; // Trigger reflow
        emoji.style.animation = "";
    });

    panel.classList.remove("d-none");
    clearTimeout(hideTimeout);
}

export function hideReactions(el) {
    hideTimeout = setTimeout(() => {
        const panel = el.querySelector(".reaction-panel");
        if (panel) panel.classList.add("d-none");
    }, 250);
}

export function cancelHide() {
    clearTimeout(hideTimeout);
}

/**
 * Apply a reaction to a post (called from emoji-picker.js)
 */
export async function applyReaction(button, emoji, label, type) {
    if (!button || !type) {
        console.error("Invalid reaction parameters");
        return;
    }

    const wrapper = button.closest(".reaction-wrapper");
    if (!wrapper) {
        console.error("Reaction wrapper not found");
        return;
    }

    const postId = wrapper.getAttribute("data-post-id");
    const actionBtn = wrapper.querySelector(".action-btn");

    if (!postId) {
        console.error("Post ID not found");
        showNotification("Unable to add reaction", "error");
        return;
    }

    // Get current reaction
    const currentReaction =
        actionBtn?.getAttribute("data-current-reaction") || "";

    // If clicking the same reaction, remove it
    if (currentReaction === type) {
        await removeReaction(postId, actionBtn);
        return;
    }

    // Optimistically update UI
    const iconWrapper = actionBtn.querySelector(".reaction-icon");
    const labelEl = actionBtn.querySelector(".reaction-label");

    const previousEmoji = iconWrapper?.innerHTML;
    const previousLabel = labelEl?.textContent;
    const previousReaction = currentReaction;

    if (iconWrapper) iconWrapper.textContent = emoji;
    if (labelEl) labelEl.textContent = label;
    if (actionBtn) actionBtn.setAttribute("data-current-reaction", type);

    try {
        const response = await fetch("/feed/reactions", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify({
                reactionable_type: "Post",
                reactionable_id: postId,
                reaction_type: type,
            }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            // Update reaction count only if it was a new reaction
            if (!previousReaction || previousReaction === "") {
                updateReactionCount(postId, 1);
            }
            // Update reactions preview
            updateReactionDisplay(postId, result.reaction);
        } else {
            throw new Error(result.message || "Failed to save reaction");
        }
    } catch (error) {
        console.error("Error saving reaction:", error);

        // Revert UI on error
        if (previousReaction) {
            const revertEmoji = getReactionEmoji(previousReaction);
            const revertLabel = capitalizeFirst(previousReaction);
            if (iconWrapper) iconWrapper.textContent = revertEmoji;
            if (labelEl) labelEl.textContent = revertLabel;
            if (actionBtn)
                actionBtn.setAttribute(
                    "data-current-reaction",
                    previousReaction,
                );
        } else {
            if (iconWrapper)
                iconWrapper.innerHTML =
                    '<i class="fa-regular fa-thumbs-up"></i>';
            if (labelEl) labelEl.textContent = "Like";
            if (actionBtn) actionBtn.setAttribute("data-current-reaction", "");
        }

        showNotification("Failed to save reaction. Please try again.", "error");
    }
}

export function handleReactionClick(postId, currentReactionType) {
    if (!postId) return;

    // If user already reacted, remove the reaction
    if (currentReactionType && currentReactionType !== "") {
        const wrapper = document.querySelector(
            `.reaction-wrapper[data-post-id="${postId}"]`,
        );
        const actionBtn = wrapper?.querySelector(".action-btn");
        removeReaction(postId, actionBtn);
    }
    // Otherwise, the hover will show the reaction panel
}

async function removeReaction(postId, actionBtn) {
    if (!postId) return;

    const wrapper = document.querySelector(
        `.reaction-wrapper[data-post-id="${postId}"]`,
    );
    if (!wrapper) return;

    const iconWrapper = actionBtn?.querySelector(".reaction-icon");
    const labelEl = actionBtn?.querySelector(".reaction-label");

    // Store current state for potential revert
    const previousEmoji = iconWrapper?.innerHTML;
    const previousLabel = labelEl?.textContent;
    const previousReaction = actionBtn?.getAttribute("data-current-reaction");

    // Optimistically update UI to default
    if (iconWrapper)
        iconWrapper.innerHTML = '<i class="fa-regular fa-thumbs-up"></i>';
    if (labelEl) labelEl.textContent = "Like";
    if (actionBtn) actionBtn.setAttribute("data-current-reaction", "");

    try {
        const response = await fetch("/feed/reactions", {
            method: "DELETE",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
                "X-Requested-With": "XMLHttpRequest",
            },
            body: JSON.stringify({
                reactionable_type: "Post",
                reactionable_id: postId,
            }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            // Update count
            updateReactionCount(postId, -1);
            updateReactionDisplay(postId, null);
        } else {
            throw new Error(result.message || "Failed to remove reaction");
        }
    } catch (error) {
        console.error("Error removing reaction:", error);

        // Revert UI on error
        if (iconWrapper) iconWrapper.innerHTML = previousEmoji;
        if (labelEl) labelEl.textContent = previousLabel;
        if (actionBtn)
            actionBtn.setAttribute("data-current-reaction", previousReaction);

        showNotification(
            "Failed to remove reaction. Please try again.",
            "error",
        );
    }
}

function updateReactionCount(postId, change) {
    const postContainer = document.querySelector(
        `.post-container[data-post-id="${postId}"]`,
    );
    if (!postContainer) return;

    const likesCount = postContainer.querySelector(".likes-count");
    if (!likesCount) return;

    const countText = likesCount.querySelector(".count-text");

    if (countText) {
        const match = countText.textContent.match(/\d+/);
        if (match) {
            let count = parseInt(match[0]) + change;
            count = Math.max(0, count);

            if (count > 0) {
                countText.textContent = count.toString();
                likesCount.style.display = "";
            } else {
                likesCount.style.display = "none";
            }
        }
    } else if (change > 0) {
        // Create new count display
        likesCount.innerHTML = `
            <div class="reactions-preview">
                <span class="reaction-emoji-preview">👍</span>
            </div>
            <span class="count-text">1</span>
        `;
        likesCount.style.display = "";
    }
}

async function updateReactionDisplay(postId, reaction) {
    const postContainer = document.querySelector(
        `.post-container[data-post-id="${postId}"]`,
    );
    if (!postContainer) return;

    try {
        // Fetch updated reaction count from server
        const response = await fetch(`/feed/posts/${postId}/reactions-count`, {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        });

        if (!response.ok) return;

        const result = await response.json();

        if (result.success) {
            const likesCountEl = postContainer.querySelector(
                ".likes-count .count-text",
            );
            if (likesCountEl && result.count > 0) {
                likesCountEl.textContent = result.count;
            }

            // Update reactions preview
            if (result.reactions && result.reactions.length > 0) {
                updateReactionsPreview(postId, result.reactions);
            } else {
                // Clear reactions preview if no reactions
                const reactionsPreview =
                    postContainer.querySelector(".reactions-preview");
                if (reactionsPreview) {
                    reactionsPreview.innerHTML = "";
                }
            }
        }
    } catch (error) {
        console.error("Error fetching reaction count:", error);
    }
}

function updateReactionsPreview(postId, reactions) {
    const postContainer = document.querySelector(
        `.post-container[data-post-id="${postId}"]`,
    );
    if (!postContainer) return;

    const reactionsPreview = postContainer.querySelector(".reactions-preview");
    if (!reactionsPreview) return;

    // Clear existing reactions
    reactionsPreview.innerHTML = "";

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
    uniqueReactions.forEach((reaction) => {
        const emoji = getReactionEmoji(reaction.type);
        const span = document.createElement("span");
        span.className = "reaction-emoji-preview";
        span.textContent = emoji;
        reactionsPreview.appendChild(span);
    });
}

export async function showReactionsList(postId) {
    if (!postId) return;

    const modal = document.getElementById("reactionsModal");
    if (!modal) {
        console.error("Reactions modal not found");
        return;
    }

    // Show modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();

    // Show loading state
    const loadingEl = document.getElementById("reactionsLoading");
    const emptyEl = document.getElementById("reactionsEmpty");

    if (loadingEl) loadingEl.classList.remove("d-none");
    if (emptyEl) emptyEl.classList.add("d-none");

    try {
        const response = await fetch(`/feed/posts/${postId}/reactions-list`, {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        });

        if (!response.ok) {
            throw new Error("Failed to fetch reactions");
        }

        const result = await response.json();

        // Hide loading
        if (loadingEl) loadingEl.classList.add("d-none");

        if (result.success && result.reactions && result.reactions.length > 0) {
            displayReactionsList(result.reactions);
        } else {
            if (emptyEl) emptyEl.classList.remove("d-none");
        }
    } catch (error) {
        console.error("Error loading reactions:", error);
        if (loadingEl) loadingEl.classList.add("d-none");
        if (emptyEl) emptyEl.classList.remove("d-none");
        showNotification(
            "Failed to load reactions. Please try again.",
            "error",
        );
    }
}

function displayReactionsList(reactions) {
    // Group reactions by type - matching backend types
    const reactionsByType = {
        all: reactions,
        appreciate: reactions.filter((r) => r.type === "appreciate"),
        cheers: reactions.filter((r) => r.type === "cheers"),
        support: reactions.filter((r) => r.type === "support"),
        insight: reactions.filter((r) => r.type === "insight"),
        curious: reactions.filter((r) => r.type === "curious"),
        smile: reactions.filter((r) => r.type === "smile"),
    };

    // Update counts
    const updateCountElement = (elementId, count) => {
        const el = document.getElementById(elementId);
        if (el) el.textContent = count;
    };

    updateCountElement("allCount", reactionsByType.all.length);
    updateCountElement("appreciateCount", reactionsByType.appreciate.length);
    updateCountElement("cheersCount", reactionsByType.cheers.length);
    updateCountElement("supportCount", reactionsByType.support.length);
    updateCountElement("insightCount", reactionsByType.insight.length);
    updateCountElement("curiousCount", reactionsByType.curious.length);
    updateCountElement("smileCount", reactionsByType.smile.length);

    // Render lists
    renderReactionsList("allReactionsList", reactionsByType.all);
    renderReactionsList("appreciateReactionsList", reactionsByType.appreciate);
    renderReactionsList("cheersReactionsList", reactionsByType.cheers);
    renderReactionsList("supportReactionsList", reactionsByType.support);
    renderReactionsList("insightReactionsList", reactionsByType.insight);
    renderReactionsList("curiousReactionsList", reactionsByType.curious);
    renderReactionsList("smileReactionsList", reactionsByType.smile);
}

function renderReactionsList(containerId, reactions) {
    const container = document.getElementById(containerId);
    if (!container) return;

    container.innerHTML = "";

    if (reactions.length === 0) {
        container.innerHTML =
            '<p class="text-center text-muted py-4">No reactions of this type</p>';
        return;
    }

    reactions.forEach((reaction) => {
        const item = createReactionItem(reaction);
        container.appendChild(item);
    });
}

function createReactionItem(reaction) {
    const div = document.createElement("div");
    div.className = "reaction-item";
    const emoji = getReactionEmoji(reaction.type);
    const avatarHTML =
        reaction.user.has_photo && reaction.user.avatar
            ? `<div class="reaction-avatar-box">
                    <img src="${reaction.user.avatar}" class="user-avatar" alt="${reaction.user.name}"/>
                    <div class="reaction-emoji">${emoji}</div>
                </div>`
            : `<div class="reaction-avatar-box">
            <div class="user-initials">${reaction.user.initials}</div>
            <div class="reaction-emoji">${emoji}</div>
           </div>`;

    div.innerHTML = `
        ${avatarHTML}
        <div class="user-info">
            <div class="user-name">${escapeHtml(reaction.user.name)}</div>
            ${reaction.user.position ? `<div class="user-position">${escapeHtml(reaction.user.position)}</div>` : ""}
        </div>
    `;

    return div;
}

export async function likeComment(commentId) {
    if (!commentId) return;

    const button = event.target;
    const wasLiked = button.classList.contains("liked");

    // Optimistic UI update
    button.classList.toggle("liked");
    button.textContent = button.classList.contains("liked") ? "Liked" : "Like";

    try {
        const response = await fetch("/feed/reactions", {
            method: wasLiked ? "DELETE" : "POST",
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
                reaction_type: "like",
            }),
        });

        const result = await response.json();

        if (!result.success) {
            // Revert on failure
            button.classList.toggle("liked");
            button.textContent = button.classList.contains("liked")
                ? "Liked"
                : "Like";
            throw new Error(result.message || "Failed to update reaction");
        }
    } catch (error) {
        console.error("Error liking comment:", error);
        showNotification(
            "Failed to update reaction. Please try again.",
            "error",
        );
    }
}

function getReactionEmoji(type) {
    const emojiMap = {
        appreciate: "👍",
        cheers: "🎉",
        support: "🤝",
        insight: "💡",
        curious: "🤔",
        smile: "😊"
    };
    return emojiMap[type] || "👍";
}

function capitalizeFirst(str) {
    if (!str) return "";
    return str.charAt(0).toUpperCase() + str.slice(1);
}

function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

function showNotification(message, type = "info") {
    if (document.getElementById("notification-container") === null) {
        const container = document.createElement("div");
        container.id = "notification-container";
        container.style.cssText = "position: fixed; top: 20px; right: 20px; z-index: 9999;";
        document.body.appendChild(container);
    }

    const alertClass =
        type === "success"
            ? "alert-success"
            : type === "error"
              ? "alert-danger"
              : "alert-info";

    const notification = document.createElement("div");
    notification.className = `alert ${alertClass} alert-dismissible fade show`;
    notification.setAttribute("role", "alert");
    notification.style.minWidth = "250px";
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.getElementById("notification-container").appendChild(notification);

    setTimeout(() => {
        notification.remove();
    }, 3000);
}

// Make functions globally available
window.showReactions = showReactions;
window.hideReactions = hideReactions;
window.cancelHide = cancelHide;
window.applyReaction = applyReaction;
window.handleReactionClick = handleReactionClick;
window.showReactionsList = showReactionsList;
window.likeComment = likeComment;
