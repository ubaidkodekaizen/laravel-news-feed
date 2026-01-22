/**
 * Share and Repost System - Complete Implementation
 * Handles sharing posts via different methods: instant repost, repost with thoughts, and send
 */

export function sharePost(postId) {
    if (!postId) {
        console.error("Post ID is required");
        return;
    }

    // Get post data
    const postContainer = document.querySelector(
        `.post-container[data-post-id="${postId}"]`,
    );
    if (!postContainer) {
        console.error("Post container not found");
        return;
    }

    // Show share modal
    const modal = document.getElementById("shareModal");
    if (!modal) {
        // Create share modal if it doesn't exist
        createShareModal();
    }

    // Set current post ID
    window.currentSharePostId = postId;

    // Show modal
    const bsModal = new bootstrap.Modal(document.getElementById("shareModal"));
    bsModal.show();
}

function createShareModal() {
    const modalHTML = `
        <div class="modal fade" id="shareModal" tabindex="-1" aria-labelledby="shareModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="shareModalLabel">Share Post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="share-options">

                            <button class="share-option-btn with-thoughts" onclick="repostWithThoughts()">
                                <span>
                                        <img src="/assets/images/repost-with-thoughts-icon.png" class="img-fluid"/>
                                    </span>
                                    <div>
                                        <strong>Repost with your thoughts</strong>
                                    </div>
                            </button>

                            <button class="share-option-btn instant" onclick="instantRepost()">
                                  <span>
                                        <img src="/assets/images/repost-instant.png" class="img-fluid"/>
                                    </span>
                                    <div>
                                        <strong>Instant repost</strong>
                                    </div>
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML("beforeend", modalHTML);
}

export async function instantRepost() {
    const postId = window.currentSharePostId;
    if (!postId) {
        showNotification("Unable to repost. Please try again.", "error");
        return;
    }

    // Close share modal
    const shareModal = bootstrap.Modal.getInstance(
        document.getElementById("shareModal"),
    );
    if (shareModal) {
        shareModal.hide();
    }

    // Show loading notification
    showNotification("Reposting...", "info");

    try {
        const response = await fetch(`/feed/posts/${postId}/share`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
            body: JSON.stringify({
                share_type: "repost",
                shared_content: "", // Empty content for instant repost
            }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            // Update share count
            updateShareCount(postId, 1);

            // Show success message
            showNotification("Post reposted successfully!", "success");

            // Reload feed after short delay to show the repost
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(result.message || "Failed to repost");
        }
    } catch (error) {
        console.error("Error reposting:", error);
        showNotification("Failed to repost. Please try again.", "error");
    }
}

export function repostWithThoughts() {
    const postId = window.currentSharePostId;
    if (!postId) {
        showNotification("Unable to repost. Please try again.", "error");
        return;
    }

    // Close share modal
    const shareModal = bootstrap.Modal.getInstance(
        document.getElementById("shareModal"),
    );
    if (shareModal) {
        shareModal.hide();
    }

    // Get original post data
    const postContainer = document.querySelector(
        `.post-container[data-post-id="${postId}"]`,
    );
    if (!postContainer) {
        showNotification("Post not found.", "error");
        return;
    }

    // Create repost modal if it doesn't exist
    let repostModal = document.getElementById("repostModal");
    if (!repostModal) {
        createRepostModal();
        repostModal = document.getElementById("repostModal");
    }

    // Get post preview
    const postPreview = createPostPreview(postContainer);

    // Set content in modal
    document.getElementById("repostPreview").innerHTML = postPreview;
    document.getElementById("repostText").value = "";
    document.getElementById("repostCharCount").textContent = "0";

    // Store post ID
    window.currentRepostPostId = postId;

    // Show modal
    const bsModal = new bootstrap.Modal(repostModal);
    bsModal.show();
}

function createRepostModal() {
    const userAvatar = window.authUserAvatar || "";
    const userName = window.authUserName || "You";
    const userInitials = window.authUserInitials || "U";
    const hasPhoto = window.authUserHasPhoto || false;

    const avatarHTML =
        hasPhoto && userAvatar
            ? `<img src="${userAvatar}" class="user-img" alt="${userName}">`
            : `<div class="user-initials-avatar" style="width: 48px; height: 48px;">${userInitials}</div>`;

    const modalHTML = `
        <div class="modal fade" id="repostModal" tabindex="-1" aria-labelledby="repostModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="repostModalLabel">Repost with your thoughts</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="fa-solid fa-xmark"></i>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="repost-composer">
                            <div class="d-flex align-items-start gap-3">

                                <textarea class="form-control" id="repostText" rows="3"
                                          placeholder="What do you want to say about this?"
                                          maxlength="10000"
                                          oninput="updateRepostCharCount()"></textarea>
                            </div>
                            <div class="character-count text-muted small mt-1 text-end">
                                <span id="repostCharCount">0</span>/10000
                            </div>
                        </div>

                        <div id="repostPreview" class="mt-3"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" onclick="submitRepostWithThoughts()">Repost</button>
                    </div>
                </div>
            </div>
        </div>
    `;

    document.body.insertAdjacentHTML("beforeend", modalHTML);
}

function createPostPreview(postContainer) {
    const userInfo = postContainer.querySelector(".user-info");
    const postText = postContainer.querySelector(".post-text");
    const postMedia = postContainer.querySelector(".post-images, .post-videos");

    let userName = "User";
    let userPosition = "";
    let userAvatar = "";

    if (userInfo) {
        const nameElement = userInfo.querySelector(".username");
        const positionElement = userInfo.querySelector(".user-position");
        const avatarElement = userInfo.querySelector(
            ".user-img, .user-initials-avatar",
        );

        if (nameElement) userName = nameElement.textContent.trim();
        if (positionElement) userPosition = positionElement.textContent.trim();
        if (avatarElement) {
            if (avatarElement.tagName === "IMG") {
                userAvatar = `<img src="${avatarElement.src}" class="user-img" alt="${userName}">`;
            } else {
                userAvatar = `<div class="user-initials-avatar" style="width: 32px; height: 32px; font-size: 14px;">${avatarElement.textContent}</div>`;
            }
        }
    }

    const content = postText
        ? postText.textContent.trim().substring(0, 200)
        : "";
    const hasMore = postText && postText.textContent.trim().length > 200;

    let mediaPreview = "";
    if (postMedia) {
        const firstImage = postMedia.querySelector("img");
        const firstVideo = postMedia.querySelector("video");

        if (firstImage) {
            mediaPreview = `<img src="${firstImage.src}" alt="Post media" style="max-height: 150px; width: 100%; object-fit: cover; border-radius: 8px; margin-top: 8px;">`;
        } else if (firstVideo) {
            mediaPreview = `<video src="${firstVideo.src}" style="max-height: 150px; width: 100%; object-fit: cover; border-radius: 8px; margin-top: 8px;"></video>`;
        }
    }

    return `
        <div class="shared-post-preview">
            <div class="d-flex align-items-start gap-2 mb-2">
                ${userAvatar}
                <div>
                    <strong>${escapeHtml(userName)}</strong>
                    ${userPosition ? `<div class="text-muted small">${escapeHtml(userPosition)}</div>` : ""}
                </div>
            </div>
            ${content ? `<div class="post-preview-text">${escapeHtml(content)}${hasMore ? "..." : ""}</div>` : ""}
            ${mediaPreview}
        </div>
    `;
}

window.updateRepostCharCount = function () {
    const textarea = document.getElementById("repostText");
    const counter = document.getElementById("repostCharCount");

    if (textarea && counter) {
        const length = textarea.value.length;
        counter.textContent = length;

        const container = counter.parentElement;
        container.classList.remove("warning", "error");

        if (length > 9000) {
            container.classList.add("error");
        } else if (length > 8000) {
            container.classList.add("warning");
        }
    }
};

window.submitRepostWithThoughts = async function () {
    const postId = window.currentRepostPostId;
    if (!postId) {
        showNotification("Unable to repost. Please try again.", "error");
        return;
    }

    const textarea = document.getElementById("repostText");
    const content = textarea ? textarea.value.trim() : "";

    const submitBtn = document.querySelector("#repostModal .btn-primary");
    const originalText = submitBtn ? submitBtn.textContent : "Repost";

    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML =
            '<i class="fa fa-spinner fa-spin"></i> Reposting...';
    }

    try {
        const response = await fetch(`/feed/posts/${postId}/share`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]',
                ).content,
            },
            body: JSON.stringify({
                share_type: "repost",
                shared_content: content,
            }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            // Close modal
            const repostModal = bootstrap.Modal.getInstance(
                document.getElementById("repostModal"),
            );
            if (repostModal) {
                repostModal.hide();
            }

            // Update share count
            updateShareCount(postId, 1);

            // Show success message
            showNotification("Post reposted successfully!", "success");

            // Reload feed after short delay
            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(result.message || "Failed to repost");
        }
    } catch (error) {
        console.error("Error reposting:", error);
        showNotification("Failed to repost. Please try again.", "error");

        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    }
};

export function sendPost(postId) {
    if (!postId) {
        console.error("Post ID is required");
        showNotification("Unable to send post. Please try again.", "error");
        return;
    }

    // Get post URL
    const postContainer = document.querySelector(
        `.post-container[data-post-id="${postId}"]`,
    );
    const postSlug = postContainer?.dataset.postSlug;
    const postUrl = postSlug
        ? `${window.location.origin}/feed/posts/${postSlug}`
        : `${window.location.origin}/feed/posts/${postId}`;

    // Show social share options
    showSocialShareModal(postUrl, postId);
}

function showSocialShareModal(postUrl, postId) {
    let socialModal = document.getElementById("socialShareModal");

    if (!socialModal) {
        const modalHtml = `
            <div class="modal fade" id="socialShareModal" tabindex="-1" aria-labelledby="socialShareModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Share on Social Media</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"><i class="fa-solid fa-xmark"></i></button>
                        </div>
                        <div class="modal-body">
                            <div class="social-share-buttons">
                               <button class="social-share-facebook-btn" onclick="window.shareToFacebook('${postUrl}')">
                                    <i class="fa-brands fa-facebook me-2"></i> Share on Facebook
                                </button>
                                <button class="social-share-twitter-btn" onclick="window.shareToTwitter('${postUrl}')">
                                    <i class="fa-brands fa-twitter me-2"></i> Share on Twitter
                                </button>
                                <button class="social-share-linkedin-btn" onclick="window.shareToLinkedIn('${postUrl}')">
                                    <i class="fa-brands fa-linkedin me-2"></i> Share on LinkedIn
                                </button>
                                <button class="social-share-whatsapp-btn" onclick="window.shareToWhatsApp('${postUrl}')">
                                    <i class="fa-brands fa-whatsapp me-2"></i> Share on WhatsApp
                                </button>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button class="btn btn-outline-secondary w-100 copy-link-btn" onclick="window.copyPostLink('${postId}')">
                                <i class="fa-solid fa-link me-2"></i> Copy Link
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML("beforeend", modalHtml);
        socialModal = document.getElementById("socialShareModal");
    }

    // Update onclick handlers
    socialModal.querySelector(".social-share-buttons").innerHTML = `
        <button class="social-share-facebook-btn" onclick="window.shareToFacebook('${postUrl}')">
            <i class="fa-brands fa-facebook me-2"></i> Share on Facebook
        </button>
        <button class="social-share-twitter-btn" onclick="window.shareToTwitter('${postUrl}')">
            <i class="fa-brands fa-twitter me-2"></i> Share on Twitter
        </button>
        <button class="social-share-linkedin-btn" onclick="window.shareToLinkedIn('${postUrl}')">
            <i class="fa-brands fa-linkedin me-2"></i> Share on LinkedIn
        </button>
        <button class="social-share-whatsapp-btn" onclick="window.shareToWhatsApp('${postUrl}')">
            <i class="fa-brands fa-whatsapp me-2"></i> Share on WhatsApp
        </button>
    `;

    // Update copy link button in footer
    const copyBtn = socialModal.querySelector(".modal-footer .copy-link-btn");
    copyBtn.setAttribute("onclick", `window.copyPostLink('${postId}')`);

    const bsModal = new bootstrap.Modal(socialModal);
    bsModal.show();
}

export function shareToFacebook(url) {
    const shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(
        url,
    )}`;
    window.open(shareUrl, "_blank", "width=600,height=400");
}

export function shareToTwitter(url) {
    const text = "Check out this post on MuslimLynk!";
    const shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(
        url,
    )}&text=${encodeURIComponent(text)}`;
    window.open(shareUrl, "_blank", "width=600,height=400");
}

export function shareToLinkedIn(url) {
    const shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(
        url,
    )}`;
    window.open(shareUrl, "_blank", "width=600,height=400");
}

export function shareToWhatsApp(url) {
    const text = "Check out this post on MuslimLynk!";
    const shareUrl = `https://wa.me/?text=${encodeURIComponent(
        text + " " + url,
    )}`;
    window.open(shareUrl, "_blank");
}
export function copyPostLink(postId) {
    // Use the global postId if not provided
    if (!postId) {
        postId = window.currentSharePostId;
    }

    if (!postId) {
        showNotification("Unable to copy link. Please try again.", "error");
        return;
    }

    // Get post slug
    const postContainer = document.querySelector(
        `.post-container[data-post-id="${postId}"]`,
    );
    const postSlug = postContainer ? postContainer.dataset.postSlug : null;

    if (!postSlug) {
        showNotification("Unable to get post link.", "error");
        return;
    }

    // Create post URL
    const postUrl = `${window.location.origin}/feed/posts/${postSlug}`;

    // Copy to clipboard
    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard
            .writeText(postUrl)
            .then(() => {
                showNotification("Link copied to clipboard!", "success");

                // Close share modal
                const shareModal = bootstrap.Modal.getInstance(
                    document.getElementById("shareModal"),
                );
                if (shareModal) {
                    shareModal.hide();
                }
            })
            .catch((err) => {
                console.error("Error copying to clipboard:", err);
                fallbackCopyToClipboard(postUrl);
            });
    } else {
        fallbackCopyToClipboard(postUrl);
    }
}

function fallbackCopyToClipboard(text) {
    const textarea = document.createElement("textarea");
    textarea.value = text;
    textarea.style.position = "fixed";
    textarea.style.opacity = "0";
    document.body.appendChild(textarea);
    textarea.select();

    try {
        document.execCommand("copy");
        showNotification("Link copied to clipboard!", "success");

        // Close share modal
        const shareModal = bootstrap.Modal.getInstance(
            document.getElementById("shareModal"),
        );
        if (shareModal) {
            shareModal.hide();
        }
    } catch (err) {
        console.error("Error copying to clipboard:", err);
        showNotification("Failed to copy link. Please try again.", "error");
    } finally {
        document.body.removeChild(textarea);
    }
}

export async function showSharesList(postId) {
    if (!postId) return;

    const modal = document.getElementById("sharesModal");
    if (!modal) {
        console.error("Shares modal not found");
        return;
    }

    // Show modal
    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();

    // Show loading
    document.getElementById("sharesLoading")?.classList.remove("d-none");
    document.getElementById("sharesEmpty")?.classList.add("d-none");
    document.getElementById("sharesList").innerHTML = "";

    try {
        const response = await fetch(`/feed/posts/${postId}/shares-list`, {
            method: "GET",
            headers: {
                "X-Requested-With": "XMLHttpRequest",
            },
        });

        if (!response.ok) {
            throw new Error("Failed to fetch shares");
        }

        const result = await response.json();

        // Hide loading
        document.getElementById("sharesLoading")?.classList.add("d-none");

        if (result.success && result.shares && result.shares.length > 0) {
            displaySharesList(result.shares);
            document.getElementById("totalSharesCount").textContent =
                result.count;
        } else {
            document.getElementById("sharesEmpty")?.classList.remove("d-none");
        }
    } catch (error) {
        console.error("Error loading shares:", error);
        document.getElementById("sharesLoading")?.classList.add("d-none");
        document.getElementById("sharesEmpty")?.classList.remove("d-none");
        showNotification("Failed to load shares. Please try again.", "error");
    }
}

function displaySharesList(shares) {
    const container = document.getElementById("sharesList");
    if (!container) return;

    container.innerHTML = "";

    shares.forEach((share) => {
        const shareItem = createShareItem(share);
        container.appendChild(shareItem);
    });
}

function createShareItem(share) {
    const div = document.createElement("div");
    div.className = "share-item";

    const avatarHTML =
        share.user.has_photo && share.user.avatar
            ? `<img src="${share.user.avatar}" class="user-avatar" alt="${escapeHtml(share.user.name)}">`
            : `<div class="user-initials">${share.user.initials}</div>`;

    const shareTypeLabel =
        share.share_type === "repost" ? "Reposted" : "Shared";

    div.innerHTML = `
        ${avatarHTML}
        <div class="share-content">
            <div class="share-meta">
                <div class="user-name">${escapeHtml(share.user.name)}</div>
                <span class="share-type-badge">${shareTypeLabel}</span>
            </div>
            ${share.user.position ? `<div class="user-position">${escapeHtml(share.user.position)}</div>` : ""}
            ${share.shared_content ? `<div class="share-text">${escapeHtml(share.shared_content)}</div>` : ""}
            <div class="share-time">${formatTimeAgo(share.created_at)}</div>
        </div>
    `;

    return div;
}

function updateShareCount(postId, change) {
    const postContainer = document.querySelector(
        `.post-container[data-post-id="${postId}"]`,
    );
    if (!postContainer) return;

    const sharesCount = postContainer.querySelector(
        ".shares-count .count-text",
    );
    if (sharesCount) {
        const match = sharesCount.textContent.match(/\d+/);
        if (match) {
            let count = parseInt(match[0]) + change;
            count = Math.max(0, count);
            sharesCount.textContent = `${count} share${count !== 1 ? "s" : ""}`;
        }
    } else if (change > 0) {
        // Create shares count if it doesn't exist
        const statsRight = postContainer.querySelector(".stats-right");
        if (statsRight) {
            const sharesDiv = document.createElement("div");
            sharesDiv.className = "shares-count";
            sharesDiv.style.cursor = "pointer";
            sharesDiv.onclick = () => showSharesList(postId);
            sharesDiv.innerHTML = `<span class="count-text">1 share</span>`;
            statsRight.appendChild(sharesDiv);
        }
    }
}

function formatTimeAgo(dateString) {
    const date = new Date(dateString);
    const now = new Date();
    const seconds = Math.floor((now - date) / 1000);

    const intervals = {
        year: 31536000,
        month: 2592000,
        week: 604800,
        day: 86400,
        hour: 3600,
        minute: 60,
    };

    for (const [key, value] of Object.entries(intervals)) {
        const interval = Math.floor(seconds / value);
        if (interval >= 1) {
            return `${interval} ${key}${interval !== 1 ? "s" : ""} ago`;
        }
    }

    return "Just now";
}

function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
}

function showNotification(message, type = "info") {
    let container = document.getElementById("notification-container");

    if (!container) {
        container = document.createElement("div");
        container.id = "notification-container";
        container.style.cssText =
            "position: fixed; top: 20px; right: 20px; z-index: 9999;";
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
    notification.style.minWidth = "250px";
    notification.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    container.appendChild(notification);

    setTimeout(() => {
        notification.classList.remove("show");
        setTimeout(() => notification.remove(), 150);
    }, 3000);
}

// Make functions globally available
window.sharePost = sharePost;
window.instantRepost = instantRepost;
window.repostWithThoughts = repostWithThoughts;
window.sendPost = sendPost;
window.copyPostLink = copyPostLink;
window.showSharesList = showSharesList;
window.shareToFacebook = shareToFacebook;
window.shareToTwitter = shareToTwitter;
window.shareToLinkedIn = shareToLinkedIn;
window.shareToWhatsApp = shareToWhatsApp;
