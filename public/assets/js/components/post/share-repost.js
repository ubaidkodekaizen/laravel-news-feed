/**
 * Improved Share/Repost System
 * Handles sharing to social media and reposting within platform
 */

export function sharePost(postId) {
    if (!postId) {
        console.error("Post ID is required");
        showNotification("Unable to share post. Please try again.", "error");
        return;
    }

    showShareModal(postId);
}

export function sendPost(postId) {
    if (!postId) {
        console.error("Post ID is required");
        showNotification("Unable to send post. Please try again.", "error");
        return;
    }

    // Get post URL
    const postContainer = document.querySelector(
        `.post-container[data-post-id="${postId}"]`
    );
    const postSlug = postContainer?.dataset.postSlug;
    const postUrl = postSlug
        ? `${window.location.origin}/feed/posts/${postSlug}`
        : `${window.location.origin}/feed/posts/${postId}`;

    // Show social share options
    showSocialShareModal(postUrl, postId);
}

function showShareModal(postId) {
    let shareModal = document.getElementById("sharePostModal");

    if (!shareModal) {
        const modalHtml = `
            <div class="modal fade" id="sharePostModal" tabindex="-1" aria-labelledby="sharePostModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Share Post</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                <i class="fa-solid fa-xmark"></i>
                            </button>
                        </div>
                        <div class="modal-body">
                            <div class="share-options">
                                <button class="share-option-btn with-thoughts" onclick="window.repostWithThoughts('${postId}')">
                                    <span>
                                        <img src="/assets/images/repost-with-thoughts-icon.png" class="img-fluid"/>
                                    </span>
                                    <div>
                                        <strong>Repost with your thoughts</strong>
                                    </div>
                                </button>
                                <button class="share-option-btn instant" onclick="window.instantRepost('${postId}')">
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

        document.body.insertAdjacentHTML("beforeend", modalHtml);
        shareModal = document.getElementById("sharePostModal");
    }

    // Update onclick handlers with current postId
    shareModal.querySelectorAll(".share-option-btn").forEach((btn, index) => {
        const actions = [
            () => repostWithThoughts(postId),
            () => instantRepost(postId),
            () => copyPostLink(postId),
        ];
        btn.onclick = actions[index];
    });

    const bsModal = new bootstrap.Modal(shareModal);
    bsModal.show();
}

export function repostWithThoughts(postId) {
    const shareModal = bootstrap.Modal.getInstance(
        document.getElementById("sharePostModal")
    );
    if (shareModal) shareModal.hide();

    showRepostModal(postId);
}

function showRepostModal(postId) {
    const postContainer = document.querySelector(
        `.post-container[data-post-id="${postId}"]`
    );
    if (!postContainer) {
        console.error("Post container not found");
        return;
    }

    const postContent =
        postContainer.querySelector(".post-text")?.textContent.trim() || "";
    const userName =
        postContainer.querySelector(".username")?.textContent.trim() ||
        "Unknown User";
    const userAvatar =
        postContainer.querySelector(".user-img")?.src ||
        postContainer.querySelector(".user-initials-avatar")?.textContent ||
        "";
    const postTime =
        postContainer.querySelector(".post-time")?.textContent.trim() || "";

    let repostModal = document.getElementById("repostModal");

    if (!repostModal) {
        const modalHtml = `
            <div class="modal fade" id="repostModal" tabindex="-1" aria-labelledby="repostModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <form id="repostForm">
                            <div class="modal-header">
                                <h5 class="modal-title">Repost with Your Thoughts</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <textarea class="form-control mb-3" id="repostText" rows="3"
                                          placeholder="What do you think about this?" maxlength="10000"></textarea>

                                <div class="original-post-preview">
                                    <div class="original-post-header">
                                        <div id="originalPostAvatar"></div>
                                        <div>
                                            <strong class="original-post-name"></strong>
                                            <span class="original-post-time"></span>
                                        </div>
                                    </div>
                                    <div class="original-post-content"></div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                <button type="submit" class="btn btn-primary">Repost</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        `;

        document.body.insertAdjacentHTML("beforeend", modalHtml);
        repostModal = document.getElementById("repostModal");

        document
            .getElementById("repostForm")
            .addEventListener("submit", function (e) {
                e.preventDefault();
                const repostContent = document
                    .getElementById("repostText")
                    .value.trim();
                const postId = this.dataset.postId;
                submitRepost(postId, repostContent, "repost");
            });
    }

    // Set data
    document.getElementById("repostForm").dataset.postId = postId;

    const avatarContainer = repostModal.querySelector("#originalPostAvatar");
    if (userAvatar.startsWith("http")) {
        avatarContainer.innerHTML = `<img src="${userAvatar}" class="original-post-avatar" alt="User">`;
    } else {
        avatarContainer.innerHTML = `<div class="user-initials-avatar" style="width: 40px; height: 40px; font-size: 14px;">${userAvatar}</div>`;
    }

    repostModal.querySelector(".original-post-name").textContent = userName;
    repostModal.querySelector(".original-post-time").textContent = postTime;
    repostModal.querySelector(".original-post-content").textContent =
        postContent;

    document.getElementById("repostText").value = "";

    const bsModal = new bootstrap.Modal(repostModal);
    bsModal.show();
}

export function instantRepost(postId) {
    if (!confirm("Share this post to your feed immediately?")) {
        return;
    }

    const shareModal = bootstrap.Modal.getInstance(
        document.getElementById("sharePostModal")
    );
    if (shareModal) shareModal.hide();

    submitRepost(postId, "", "share");
}

async function submitRepost(postId, content, shareType) {
    const submitBtn = document.querySelector(
        '#repostForm button[type="submit"]'
    );
    let originalText = "Repost";

    if (submitBtn) {
        originalText = submitBtn.innerHTML;
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
                    'meta[name="csrf-token"]'
                ).content,
            },
            body: JSON.stringify({
                shared_content: content,
                share_type: shareType,
            }),
        });

        const result = await response.json();

        if (result.success) {
            const repostModal = document.getElementById("repostModal");
            if (repostModal) {
                const bsModal = bootstrap.Modal.getInstance(repostModal);
                if (bsModal) bsModal.hide();
            }

            showNotification("Post shared successfully!", "success");

            setTimeout(() => {
                window.location.reload();
            }, 1000);
        } else {
            throw new Error(result.message || "Failed to share post");
        }
    } catch (error) {
        console.error("Error sharing post:", error);

        let errorMessage = "Failed to share post. Please try again.";
        if (error.responseJSON && error.responseJSON.message) {
            errorMessage = error.responseJSON.message;
        } else if (error.message) {
            errorMessage = error.message;
        }

        showNotification(errorMessage, "error");
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = originalText;
        }
    }
}

export function copyPostLink(postId) {
    const postContainer = document.querySelector(
        `.post-container[data-post-id="${postId}"]`
    );
    const postSlug = postContainer?.dataset.postSlug;

    const postUrl = postSlug
        ? `${window.location.origin}/feed/posts/${postSlug}`
        : `${window.location.origin}/feed/posts/${postId}`;

    if (navigator.clipboard && navigator.clipboard.writeText) {
        navigator.clipboard
            .writeText(postUrl)
            .then(() => {
                showNotification("Link copied to clipboard!", "success");

                const shareModal = bootstrap.Modal.getInstance(
                    document.getElementById("sharePostModal")
                );
                if (shareModal) shareModal.hide();
            })
            .catch((err) => {
                console.error("Failed to copy:", err);
                fallbackCopyToClipboard(postUrl);
            });
    } else {
        fallbackCopyToClipboard(postUrl);
    }
}

function fallbackCopyToClipboard(text) {
    const textArea = document.createElement("textarea");
    textArea.value = text;
    textArea.style.position = "fixed";
    textArea.style.left = "-999999px";
    document.body.appendChild(textArea);
    textArea.select();

    try {
        document.execCommand("copy");
        showNotification("Link copied to clipboard!", "success");

        const shareModal = bootstrap.Modal.getInstance(
            document.getElementById("sharePostModal")
        );
        if (shareModal) shareModal.hide();
    } catch (err) {
        console.error("Failed to copy:", err);
        showNotification("Failed to copy link. Please copy manually.", "error");
    }

    document.body.removeChild(textArea);
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
        url
    )}`;
    window.open(shareUrl, "_blank", "width=600,height=400");
}

export function shareToTwitter(url) {
    const text = "Check out this post on MuslimLynk!";
    const shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(
        url
    )}&text=${encodeURIComponent(text)}`;
    window.open(shareUrl, "_blank", "width=600,height=400");
}

export function shareToLinkedIn(url) {
    const shareUrl = `https://www.linkedin.com/sharing/share-offsite/?url=${encodeURIComponent(
        url
    )}`;
    window.open(shareUrl, "_blank", "width=600,height=400");
}

export function shareToWhatsApp(url) {
    const text = "Check out this post on MuslimLynk!";
    const shareUrl = `https://wa.me/?text=${encodeURIComponent(
        text + " " + url
    )}`;
    window.open(shareUrl, "_blank");
}

export async function showSharesList(postId) {
    console.log(postId);
    if (!postId) return;

    const modal = document.getElementById("sharesModal");
    if (!modal) {
        console.error("Shares modal not found");
        return;
    }

    const bsModal = new bootstrap.Modal(modal);
    bsModal.show();

    document.getElementById("sharesLoading")?.classList.remove("d-none");
    document.getElementById("sharesEmpty")?.classList.add("d-none");

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
        const item = createShareItem(share);
        container.appendChild(item);
    });
}

function createShareItem(share) {
    const div = document.createElement("div");
    div.className = "share-item";

    const avatarHTML =
        share.user.has_photo && share.user.avatar
            ? `<img src="${share.user.avatar}" class="user-avatar" alt="${share.user.name}">`
            : `<div class="user-initials">${share.user.initials}</div>`;

    const shareTextHTML = share.shared_content
        ? `<div class="share-text">${escapeHtml(share.shared_content)}</div>`
        : "";

    const typeBadge =
        share.share_type === "repost"
            ? '<span class="share-type-badge">Reposted</span>'
            : '<span class="share-type-badge">Shared</span>';

    div.innerHTML = `
        ${avatarHTML}
        <div class="share-content">
            <div class="share-meta">
                <span class="user-name">${escapeHtml(share.user.name)}</span>
                ${typeBadge}
            </div>
            ${
                share.user.position
                    ? `<div class="user-position">${escapeHtml(
                          share.user.position
                      )}</div>`
                    : ""
            }
            <div class="share-time">${formatTimeAgo(share.created_at)}</div>
            ${shareTextHTML}
        </div>
    `;

    return div;
}

function escapeHtml(text) {
    const div = document.createElement("div");
    div.textContent = text;
    return div.innerHTML;
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

function showNotification(message, type = "info") {
    if ($("#notification-container").length === 0) {
        $("body").append(
            '<div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>'
        );
    }

    const alertClass =
        type === "success"
            ? "alert-success"
            : type === "error"
            ? "alert-danger"
            : "alert-info";

    const notification = $(`
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="min-width: 250px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);

    $("#notification-container").append(notification);

    setTimeout(() => {
        notification.alert("close");
    }, 3000);
}

// Make functions globally available
window.shareToFacebook = shareToFacebook;
window.shareToTwitter = shareToTwitter;
window.shareToLinkedIn = shareToLinkedIn;
window.shareToWhatsApp = shareToWhatsApp;
