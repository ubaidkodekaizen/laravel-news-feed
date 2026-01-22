/**
 * Feed Manager - Handles infinite scroll, post loading, and feed interactions
 * UPDATED to align with FeedController
 */

let currentPage = 1;
let isLoading = false;
let hasMorePages = true;
let currentSort = "latest";

export function initializeFeed(sortOrder = "latest") {
    // Reset state when changing sort
    currentPage = 1;
    isLoading = false;
    hasMorePages = true;
    currentSort = sortOrder;

    // Clear existing posts
    const postsContainer = document.getElementById("postsContainer");
    if (!postsContainer) {
        console.error('Posts container not found');
        return;
    }

    postsContainer.innerHTML = "";

    // Show skeleton loading
    showSkeletonLoading();

    // Load first page
    loadPosts(true);

    // Setup infinite scroll (only once)
    if (!window.infiniteScrollInitialized) {
        setupInfiniteScroll();
        window.infiniteScrollInitialized = true;
    }
}

function setupInfiniteScroll() {
    let scrollTimeout;

    window.addEventListener("scroll", function () {
        clearTimeout(scrollTimeout);

        scrollTimeout = setTimeout(function () {
            const scrollPosition = window.innerHeight + window.scrollY;
            const threshold = document.documentElement.scrollHeight - 500;

            if (scrollPosition >= threshold && !isLoading && hasMorePages) {
                loadPosts();
            }
        }, 100);
    });
}

async function loadPosts(isFirstLoad = false) {
    if (isLoading) return;

    isLoading = true;

    const loadingIndicator = document.getElementById("loadingIndicator");
    if (!isFirstLoad && loadingIndicator) {
        loadingIndicator.classList.remove("d-none");
    }

    try {
        const response = await fetch(
            `/feed/posts?page=${currentPage}&per_page=10&sort=${currentSort}`,
            {
                method: "GET",
                headers: {
                    "Content-Type": "application/json",
                    "X-Requested-With": "XMLHttpRequest",
                    "X-CSRF-TOKEN": document.querySelector(
                        'meta[name="csrf-token"]'
                    ).content,
                },
            }
        );

        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }

        const result = await response.json();

        if (result.success) {
            // Hide skeleton loading on first load
            if (isFirstLoad) {
                hideSkeletonLoading();
            }

            // Check if we have posts
            if (result.data && result.data.length > 0) {
                renderPosts(result.data);
                currentPage++;
                hasMorePages = result.has_more;

                // Hide "no more posts" message
                const noMorePosts = document.getElementById("noMorePosts");
                if (noMorePosts) {
                    noMorePosts.classList.add("d-none");
                }
            } else if (isFirstLoad) {
                // Show empty state if no posts on first load
                showEmptyState();
            }

            // Show "no more posts" message if we've reached the end
            if (!result.has_more && currentPage > 1) {
                const noMorePosts = document.getElementById("noMorePosts");
                if (noMorePosts) {
                    noMorePosts.classList.remove("d-none");
                }
            }
        } else {
            throw new Error(result.message || 'Failed to load posts');
        }
    } catch (error) {
        console.error("Error loading posts:", error);

        if (isFirstLoad) {
            hideSkeletonLoading();
            showNotification(
                "Failed to load posts. Please refresh the page.",
                "error"
            );
        } else {
            showNotification("Failed to load more posts.", "error");
        }
    } finally {
        isLoading = false;
        if (loadingIndicator) {
            loadingIndicator.classList.add("d-none");
        }
    }
}

function renderPosts(posts) {
    const postsContainer = document.getElementById("postsContainer");
    if (!postsContainer) return;

    posts.forEach((post) => {
        const postHtml = createPostHTML(post);
        postsContainer.insertAdjacentHTML("beforeend", postHtml);
    });

    // Initialize video players after rendering
    initializeVideoPlayers();
}

function createPostHTML(post) {
    const isOwner = window.authUserId && window.authUserId === post.user.id;

    // Store images for lightbox
    if (post.media && post.media.length > 0) {
        const postId = `post-${post.id}`;
        window.postImages = window.postImages || {};
        window.postImages[postId] = post.media.filter(
            (m) => m.media_type === "image"
        );
    }

    // Check if this is a shared post
    const isSharedPost = post.original_post_id && post.original_post;

    return `
        <div class="post-container card" data-post-id="${post.id}" data-post-slug="${post.slug}">
            ${createPostHeader(post, isOwner)}
            ${isSharedPost ? createSharedPostBadge(post) : ''}
            ${isSharedPost ? createSharedContent(post) : ''}
            ${!isSharedPost ? createPostContent(post) : ''}
            ${!isSharedPost ? createPostMedia(post) : ''}
            ${isSharedPost ? createSharedPostDisplay(post) : ''}
            ${createPostStats(post)}
            ${createPostActions(post)}
            ${createCommentSection(post)}
        </div>
    `;
}

function createPostHeader(post, isOwner) {
    const userAvatar =
        post.user.has_photo && post.user.avatar
            ? `<img src="${post.user.avatar}" class="user-img" alt="${escapeHtml(post.user.name)}"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
           <div class="user-initials-avatar" style="display: none;">${post.user.initials}</div>`
            : `<div class="user-initials-avatar">${post.user.initials}</div>`;

    const visibilityBadge =
        post.visibility && post.visibility !== "public"
            ? `<span class="visibility-badge">
             <i class="fa-solid fa-${
                 post.visibility === "private" ? "lock" : "user-group"
             }"></i>
             ${
                 post.visibility.charAt(0).toUpperCase() +
                 post.visibility.slice(1)
             }
           </span>`
            : "";

    const menuHTML = isOwner
        ? `<div class="post-actions-menu">
             <div class="dropdown">
               <button class="post-menu-btn" type="button" data-bs-toggle="dropdown">
                 <i class="fa-solid fa-ellipsis"></i>
               </button>
               <ul class="dropdown-menu dropdown-menu-end">
                 <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); editPost('${post.id}')">
                   <i class="fa-solid fa-pen me-2"></i> Edit Post
                 </a></li>
                 <li><hr class="dropdown-divider"></li>
                 <li><a class="dropdown-item text-danger" href="#" onclick="event.preventDefault(); deletePost('${post.id}')">
                   <i class="fa-solid fa-trash me-2"></i> Delete Post
                 </a></li>
               </ul>
             </div>
           </div>`
        : "";

    return `
        <div class="post-header">
            <div class="user-info">
                ${userAvatar}
                <div class="user_post_name">
                    <a href="/user/profile/${post.user.slug}" class="username">${escapeHtml(post.user.name)}</a>
                    ${
                        post.user.position
                            ? `<p class="user-position">${escapeHtml(
                                  post.user.position
                              )}</p>`
                            : ""
                    }
                    <span class="post-time">${formatTimeAgo(
                        post.created_at
                    )}</span>
                </div>
            </div>
            ${visibilityBadge}
            ${menuHTML}
        </div>
    `;
}

function createSharedPostBadge(post) {
    return `
        <div class="post-shared-badge">
            <i class="fa-solid fa-retweet"></i>
            <span><strong>${escapeHtml(post.user.name)}</strong> reposted</span>
        </div>
    `;
}

// For shared posts, show the user's comment about the share
function createSharedContent(post) {
    if (!post.content || post.content.trim() === '') return '';

    return createPostContent(post);
}

function createPostContent(post) {
    if (!post.content || post.content.trim() === '') return "";

    const content = escapeHtml(post.content);
    const shouldTruncate = content.length > 300;
    const displayContent = shouldTruncate ? content.substring(0, 300) : content;

    return `
        <div class="post-text-wrapper">
            <div class="post-text" id="postTextBlock-${post.id}" data-full-content="${content}">
                ${displayContent.replace(/\n/g, "<br>")}
            </div>
            ${
                shouldTruncate
                    ? `<span class="post-text-toggle" onclick="togglePostText('${post.id}')">...see more</span>`
                    : ""
            }
        </div>
    `;
}

function createPostMedia(post) {
    if (!post.media || post.media.length === 0) return "";

    const images = post.media.filter((m) => m.media_type === "image");
    const videos = post.media.filter((m) => m.media_type === "video");

    let html = '';

    // Show videos first
    if (videos.length > 0) {
        html += createVideoPlayer(videos[0]);
    }

    // Then show images
    if (images.length > 0) {
        html += createImageGrid(images, post.id);
    }

    return html;
}

function createVideoPlayer(video) {
    const posterAttr = video.thumbnail_url ? `poster="${video.thumbnail_url}"` : "";

    return `
        <div class="post-media video-container">
            <video
                class="post-video"
                preload="metadata"
                playsinline
                controls
                controlslist="nodownload"
                ${posterAttr}
                aria-label="Video: ${escapeHtml(video.file_name || "post video")}"
                data-src="${video.media_url}"
                data-mime="${video.mime_type || "video/mp4"}"
                tabindex="0"
            >
                <source data-src="${video.media_url}" type="${video.mime_type || "video/mp4"}">
                Your browser does not support the video tag.
            </video>
            <div class="video-play-overlay" aria-hidden="false">
                <button class="video-play-button" aria-label="Play video">
                    <i class="fa-solid fa-circle-play" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    `;
}

function initializeVideoPlayers() {
    function pauseAllExcept(current) {
        document.querySelectorAll("video.post-video").forEach((v) => {
            if (v !== current && !v.paused) {
                v.pause();
            }
        });
    }

    const lazyObserver = new IntersectionObserver(
        (entries, obs) => {
            entries.forEach((entry) => {
                if (!entry.isIntersecting) return;
                const video = entry.target;
                const source = video.querySelector("source[data-src]");
                if (source && !source.src) {
                    source.src = source.dataset.src;
                    video.load();
                }
                obs.unobserve(video);
            });
        },
        { rootMargin: "200px" }
    );

    document.querySelectorAll(".post-media").forEach((container) => {
        const video = container.querySelector("video.post-video");
        const overlay = container.querySelector(".video-play-overlay");
        const playBtn = overlay?.querySelector(".video-play-button");

        if (!video || video.dataset.initialized) return;

        lazyObserver.observe(video);

        if (playBtn) {
            playBtn.addEventListener("click", (e) => {
                e.preventDefault();
                if (video.paused) {
                    const s = video.querySelector("source[data-src]");
                    if (s && !s.src) {
                        s.src = s.dataset.src;
                        video.load();
                    }
                    const p = video.play();
                    if (p && p.catch) p.catch(() => {});
                } else {
                    video.pause();
                }
            });
        }

        if (overlay) {
            overlay.addEventListener("click", (e) => {
                if (e.target.closest("button")) return;
                playBtn && playBtn.click();
            });
        }

        video.addEventListener("keydown", (e) => {
            if (e.key === " " || e.key === "Spacebar" || e.key === "Enter") {
                e.preventDefault();
                if (video.paused) video.play();
                else video.pause();
            }
        });

        const hideOverlay = () => {
            if (overlay) overlay.classList.add("video-overlay-hidden");
        };
        const showOverlay = () => {
            if (overlay) overlay.classList.remove("video-overlay-hidden");
        };

        video.addEventListener("play", () => {
            pauseAllExcept(video);
            hideOverlay();
        });
        video.addEventListener("pause", showOverlay);
        video.addEventListener("ended", showOverlay);

        video.dataset.initialized = "true";
    });
}

function createImageGrid(images, postId) {
    const count = images.length;
    const dataPostId = `post-${postId}`;

    if (count === 1) {
        return `
            <div class="post-images" data-image-count="1" data-post-id="${dataPostId}">
                <div class="post-images-single">
                    <img src="${images[0].media_url}"
                         alt="Post image"
                         class="post-image"
                         onclick="openLightbox('${dataPostId}', 0)"
                         style="cursor: pointer;">
                </div>
            </div>
        `;
    } else if (count === 2) {
        return `
            <div class="post-images post-images-grid post-images-two" data-image-count="2" data-post-id="${dataPostId}">
                ${images
                    .map(
                        (img, index) =>
                            `<img src="${img.media_url}"
                              alt="Post image"
                              class="post-image"
                              onclick="openLightbox('${dataPostId}', ${index})"
                              style="cursor: pointer;">`
                    )
                    .join("")}
            </div>
        `;
    } else if (count === 3) {
        return `
            <div class="post-images post-images-grid post-images-three" data-image-count="3" data-post-id="${dataPostId}">
                <img src="${images[0].media_url}"
                     alt="Post image"
                     class="post-image post-image-large"
                     onclick="openLightbox('${dataPostId}', 0)"
                     style="cursor: pointer;">
                <div class="post-images-small">
                    <img src="${images[1].media_url}"
                         alt="Post image"
                         class="post-image"
                         onclick="openLightbox('${dataPostId}', 1)"
                         style="cursor: pointer;">
                    <img src="${images[2].media_url}"
                         alt="Post image"
                         class="post-image"
                         onclick="openLightbox('${dataPostId}', 2)"
                         style="cursor: pointer;">
                </div>
            </div>
        `;
    } else {
        const remaining = count - 4;
        return `
            <div class="post-images post-images-grid post-images-four" data-image-count="${count}" data-post-id="${dataPostId}">
                ${images
                    .slice(0, 3)
                    .map(
                        (img, index) =>
                            `<img src="${img.media_url}"
                              alt="Post image"
                              class="post-image"
                              onclick="openLightbox('${dataPostId}', ${index})"
                              style="cursor: pointer;">`
                    )
                    .join("")}
                <div class="post-image-wrapper" onclick="openLightbox('${dataPostId}', 3)" style="cursor: pointer;">
                    <img src="${images[3].media_url}" alt="Post image" class="post-image">
                    ${remaining > 0 ? `<div class="post-image-overlay">+${remaining}</div>` : ""}
                </div>
            </div>
        `;
    }
}

function createSharedPostDisplay(post) {
    if (!post.original_post) return "";

    const op = post.original_post;
    const opAvatar =
        op.user.has_photo && op.user.avatar
            ? `<img src="${op.user.avatar}" class="user-img" alt="${escapeHtml(op.user.name)}"
                 onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
               <div class="user-initials-avatar" style="display: none; width: 32px; height: 32px; font-size: 14px;">${op.user.initials}</div>`
            : `<div class="user-initials-avatar" style="width: 32px; height: 32px; font-size: 14px;">${op.user.initials}</div>`;

    const images = op.media ? op.media.filter(m => m.media_type === 'image') : [];
    const videos = op.media ? op.media.filter(m => m.media_type === 'video') : [];

    let mediaHTML = '';

    if (videos.length > 0) {
        const video = videos[0];
        mediaHTML += `
            <div class="post-videos mt-2">
                <video controls class="shared-post-video" preload="metadata">
                    <source src="${video.media_url}" type="${video.mime_type || 'video/mp4'}">
                    Your browser does not support the video tag.
                </video>
                ${videos.length > 1 ? `<div class="more-media-indicator">+${videos.length - 1} more videos</div>` : ''}
            </div>
        `;
    }

    if (images.length > 0) {
        mediaHTML += `
            <div class="post-images mt-2">
                <img src="${images[0].media_url}" alt="Post image" class="post-image" style="max-height: 200px; width: 100%; object-fit: cover; border-radius: 8px;">
                ${images.length > 1 ? `<div class="more-media-indicator">+${images.length - 1} more images</div>` : ''}
            </div>
        `;
    }

    return `
        <div class="shared-post-wrapper" onclick="window.location.href='/feed/posts/${op.slug}'">
            <div class="post-header">
                <div class="user-info">
                    ${opAvatar}
                    <div class="user_post_name">
                        <p class="username">${escapeHtml(op.user.name)}</p>
                        ${op.user.position ? `<p class="user-position">${escapeHtml(op.user.position)}</p>` : ''}
                        <span class="post-time">${formatTimeAgo(op.created_at)}</span>
                    </div>
                </div>
            </div>
            ${op.content ? `<div class="post-text">${escapeHtml(op.content).substring(0, 200)}${op.content.length > 200 ? '...' : ''}</div>` : ''}
            ${mediaHTML}
        </div>
    `;
}

function createPostStats(post) {
    const reactionsHTML =
        post.likes_count > 0
            ? `<div class="reactions-preview">
                 <span class="reaction-emoji-preview">👍</span>
               </div>
               <span class="count-text">${post.likes_count}</span>`
            : "";

    return `
        <div class="post-stats">
            <div class="likes-count" onclick="showReactionsList('${post.id}')" style="cursor: pointer;">
                ${reactionsHTML}
            </div>
            <div class="stats-right">
                ${
                    post.comments_count > 0
                        ? `<div class="comments-count" onclick="toggleComments('${post.id}')" style="cursor: pointer;">
                             <span class="count-text">${post.comments_count} comment${post.comments_count !== 1 ? "s" : ""}</span>
                           </div>`
                        : ""
                }
                ${
                    post.shares_count > 0
                        ? `<div class="shares-count" onclick="showSharesList('${post.id}')" style="cursor: pointer;">
                             <span class="count-text">${post.shares_count} share${post.shares_count !== 1 ? "s" : ""}</span>
                           </div>`
                        : ""
                }
            </div>
        </div>
    `;
}
function createPostActions(post) {
    const userReaction = post.user_reaction;
    const reactionEmoji = userReaction ? getReactionEmoji(userReaction.type) : '<i class="fa-regular fa-thumbs-up"></i>';
    const reactionLabel = userReaction ? capitalizeFirst(userReaction.type) : "Like";
    const reactionType = userReaction ? userReaction.type : "";

    return `
        <div class="post-actions">
            <div class="reaction-wrapper" data-post-id="${post.id}">
                <div class="action-btn"
                     data-linkedin-reactions
                     data-post-id="${post.id}"
                     onclick="handleReactionClick('${post.id}', '${reactionType}')"
                     data-current-reaction="${reactionType}">
                    <span class="reaction-icon">${reactionEmoji}</span>
                    <span class="reaction-label action-btn-text">${reactionLabel}</span>
                </div>
            </div>
            <div class="action-btn comment-trigger" onclick="toggleComments('${post.id}')">
                <i class="fa-regular fa-comment-dots"></i> <span class="action-btn-text">Comment</span>
            </div>
            <div class="action-btn" onclick="sharePost('${post.id}')">
                <i class="fa-solid fa-retweet"></i> <span class="action-btn-text">Repost</span>
            </div>
            <div class="action-btn" onclick="sendPost('${post.id}')">
                <i class="fa-regular fa-paper-plane"></i> <span class="action-btn-text">Send</span>
            </div>
        </div>
    `;
}

function createCommentSection(post) {
    return `
        <div class="comment-section" id="commentSection-${post.id}" style="display: none;">
            ${createCommentInput(post.id)}
            <div class="comments-list" id="commentsList-${post.id}">
                <!-- Comments will be loaded dynamically -->
            </div>
            ${
                post.comments_count > 2
                    ? `<div class="load-more-comments">
                         <button class="load-more-btn" onclick="loadMoreComments('${post.id}')">
                           Load more comments (${post.comments_count - 2} more)
                         </button>
                       </div>`
                    : ""
            }
        </div>
    `;
}

function createCommentInput(postId) {
    const userAvatar = window.authUserAvatar || "";
    const userInitials = window.authUserInitials || "U";
    const hasPhoto = window.authUserHasPhoto || false;

    const avatarHTML = hasPhoto && userAvatar
        ? `<img src="${userAvatar}" class="user-img" alt="You"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
           <div class="user-initials-avatar comment-avatar" style="display: none;">
               ${userInitials}
           </div>`
        : `<div class="user-initials-avatar comment-avatar">
               ${userInitials}
           </div>`;

    return `
        <div class="comment-input-wrapper">
            ${avatarHTML}
            <div class="comment-input-container">
                <button class="emoji-picker-btn" type="button"  data-emoji-trigger="#commentInput-${postId}" ><i class="fa-regular fa-face-smile"></i></button>
                <input type="text" placeholder="Add a comment..."  id="commentInput-${postId}" class="comment-input" oninput="toggleCommentButton(this)">
                <button class="post-comment-btn" disabled onclick="postComment('${postId}')"><i class="fa-regular fa-paper-plane"></i></button>
            </div>
        </div>
    `;
}

function showSkeletonLoading() {
    const skeletonLoading = document.querySelector(".skeleton-loading-container");
    if (skeletonLoading) {
        skeletonLoading.style.display = "block";
    }
}

function hideSkeletonLoading() {
    const skeletonLoading = document.querySelector(".skeleton-loading-container");
    if (skeletonLoading) {
        skeletonLoading.style.display = "none";
    }
}

function showEmptyState() {
    const emptyState = document.getElementById("emptyState");
    if (emptyState) {
        emptyState.classList.remove("d-none");
    }
}

// Utility functions
function escapeHtml(text) {
    if (!text) return '';
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

function getReactionEmoji(type) {
    const emojiMap = {
        like: "👍",
        love: "❤️",
        celebrate: "👏",
        support: "💪",
        insightful: "💡"
    };
    return emojiMap[type] || "👍";
}

function capitalizeFirst(str) {
    if (!str) return '';
    return str.charAt(0).toUpperCase() + str.slice(1);
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

// Lightbox Functions
window.postImages = window.postImages || {};

window.openLightbox = function (postId, index) {
    const images = window.postImages[postId];
    if (!images || images.length === 0) return;

    let lightbox = document.getElementById("imageLightbox");
    if (!lightbox) {
        lightbox = document.createElement("div");
        lightbox.id = "imageLightbox";
        lightbox.className = "lightbox";
        lightbox.innerHTML = `
            <div class="lightbox-overlay" onclick="closeLightbox()"></div>
            <div class="lightbox-content">
                <button class="lightbox-close" onclick="closeLightbox()">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <button class="lightbox-prev" onclick="navigateLightbox(-1)">
                    <i class="fa-solid fa-chevron-left"></i>
                </button>
                <button class="lightbox-next" onclick="navigateLightbox(1)">
                    <i class="fa-solid fa-chevron-right"></i>
                </button>
                <img class="lightbox-image" src="" alt="Image">
                <div class="lightbox-counter"></div>
            </div>
        `;
        document.body.appendChild(lightbox);
    }

    window.currentLightboxImages = images;
    window.currentLightboxIndex = index;

    updateLightboxImage();

    lightbox.classList.add("active");
    document.body.style.overflow = "hidden";
};

window.closeLightbox = function () {
    const lightbox = document.getElementById("imageLightbox");
    if (lightbox) {
        lightbox.classList.remove("active");
        document.body.style.overflow = "";
    }
};

window.navigateLightbox = function (direction) {
    if (!window.currentLightboxImages) return;

    window.currentLightboxIndex += direction;

    if (window.currentLightboxIndex < 0) {
        window.currentLightboxIndex = window.currentLightboxImages.length - 1;
    } else if (window.currentLightboxIndex >= window.currentLightboxImages.length) {
        window.currentLightboxIndex = 0;
    }

    updateLightboxImage();
};

function updateLightboxImage() {
    const image = window.currentLightboxImages[window.currentLightboxIndex];
    const lightboxImage = document.querySelector(".lightbox-image");
    const counter = document.querySelector(".lightbox-counter");
    const prevBtn = document.querySelector(".lightbox-prev");
    const nextBtn = document.querySelector(".lightbox-next");

    if (lightboxImage && image) {
        lightboxImage.src = image.media_url;
    }

    if (counter) {
        counter.textContent = `${window.currentLightboxIndex + 1} / ${window.currentLightboxImages.length}`;
    }

    if (window.currentLightboxImages.length <= 1) {
        if (prevBtn) prevBtn.style.display = "none";
        if (nextBtn) nextBtn.style.display = "none";
    } else {
        if (prevBtn) prevBtn.style.display = "flex";
        if (nextBtn) nextBtn.style.display = "flex";
    }
}

// Keyboard navigation
document.addEventListener("keydown", function (e) {
    const lightbox = document.getElementById("imageLightbox");
    if (lightbox && lightbox.classList.contains("active")) {
        if (e.key === "Escape") {
            closeLightbox();
        } else if (e.key === "ArrowLeft") {
            navigateLightbox(-1);
        } else if (e.key === "ArrowRight") {
            navigateLightbox(1);
        }
    }
});
