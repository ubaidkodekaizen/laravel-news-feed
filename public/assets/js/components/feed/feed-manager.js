/**
 * Feed Manager - Handles infinite scroll, post loading, and feed interactions
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
    postsContainer.innerHTML = "";

    // Show skeleton loading
    showSkeletonLoading();

    // Load first page
    loadPosts(true);

    // Setup infinite scroll
    setupInfiniteScroll();
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
    if (!isFirstLoad) {
        loadingIndicator?.classList.remove("d-none");
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
            throw new Error("Failed to load posts");
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
                document.getElementById("noMorePosts")?.classList.add("d-none");
            } else if (isFirstLoad) {
                // Show empty state if no posts on first load
                showEmptyState();
            }

            // Show "no more posts" message if we've reached the end
            if (!result.has_more && currentPage > 1) {
                document
                    .getElementById("noMorePosts")
                    ?.classList.remove("d-none");
            }
        }
    } catch (error) {
        console.error("Error loading posts:", error);

        if (isFirstLoad) {
            hideSkeletonLoading();
            showNotification(
                "Failed to load posts. Please refresh the page.",
                "error"
            );
        }
    } finally {
        isLoading = false;
        loadingIndicator?.classList.add("d-none");
    }
}

function renderPosts(posts) {
    const postsContainer = document.getElementById("postsContainer");

    posts.forEach((post) => {
        const postHtml = createPostHTML(post);
        postsContainer.insertAdjacentHTML("beforeend", postHtml);
    });
    // Initialize video players
    initializeVideoPlayers();
}

// Modified createPostHTML to store images
function createPostHTML(post) {
    const isOwner = window.authUserId && window.authUserId === post.user.id;

    // Store images for lightbox
    if (post.media && post.media.length > 0) {
        const postId = `post-${post.id}`;
        window.postImages[postId] = post.media.filter(m => m.media_type === 'image');
    }

    return `
        <div class="post-container card" data-post-id="${post.id}" data-post-slug="${post.slug}">
            ${createPostHeader(post, isOwner)}
            ${createSharedPostBadge(post)}
            ${createPostContent(post)}
            ${createPostMedia(post)}
            ${post.original_post ? createSharedPostDisplay(post) : ""}
            ${createPostStats(post)}
            ${createPostActions(post)}
            ${createCommentSection(post)}
        </div>
    `;
}

function createPostHeader(post, isOwner) {
    const userAvatar =
        post.user.has_photo && post.user.avatar
            ? `<img src="${post.user.avatar}" class="user-img" alt="${post.user.name}"
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
                    <a href="/user/profile/${
                        post.user.slug
                    }" class="username">${escapeHtml(post.user.name)}</a>
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
    if (!post.original_post_id) return "";

    return `
        <div class="post-shared-badge">
            <i class="fa-solid fa-retweet"></i>
            <span><strong>${escapeHtml(post.user.name)}</strong> reposted</span>
        </div>
    `;
}

function createPostContent(post) {
    if (!post.content) return "";

    const content = escapeHtml(post.content);
    const shouldTruncate = content.length > 300;
    const displayContent = shouldTruncate ? content.substring(0, 300) : content;

    return `
        <div class="post-text-wrapper">
            <div class="post-text" id="postTextBlock-${
                post.id
            }" data-full-content="${content}">
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

    if (images.length === 0 && videos.length === 0) return "";

    // For now, show first image or video
    if (images.length > 0) {
        return createImageGrid(images);
    } // inside createPostMedia (replace fields to match your data model)
    else if (videos.length > 0) {
        const v = videos[0];
        const posterAttr = v.poster ? `poster="${v.poster}"` : "";
        const captionsTrack = v.captions_url
            ? `<track kind="captions" src="${v.captions_url}" srclang="en" label="English captions">`
            : "";
        return `
    <div class="post-media video-container">
      <video
        class="post-video"
        preload="metadata"
        playsinline
        controls
        controlslist="nodownload"
        ${posterAttr}
        aria-label="Video: ${escapeHtml(v.title || v.filename || "post video")}"
        data-src="${v.media_url}"
        data-mime="${v.mime_type || ""}"
        tabindex="0"
      >
        <!-- source will be set lazily by JS -->
        <source data-src="${v.media_url}" type="${v.mime_type || ""}">
        ${captionsTrack}
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

    return "";
}
// call after posts are rendered
function initializeVideoPlayers() {
    // pause all other videos when one plays
    function pauseAllExcept(current) {
        document.querySelectorAll("video.post-video").forEach((v) => {
            if (v !== current && !v.paused) {
                v.pause();
            }
        });
    }

    // lazy loader using IntersectionObserver
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
        const playBtn = overlay
            ? overlay.querySelector(".video-play-button")
            : null;

        if (!video || video.dataset.initialized) return;

        // lazy-load observer
        lazyObserver.observe(video);

        // overlay click / button toggles playback
        if (playBtn) {
            playBtn.addEventListener("click", (e) => {
                e.preventDefault();
                if (video.paused) {
                    // try to ensure source is loaded
                    const s = video.querySelector("source[data-src]");
                    if (s && !s.src) {
                        s.src = s.dataset.src;
                        video.load();
                    }
                    // attempt play
                    const p = video.play();
                    // handle promise rejection (autoplay blocked)
                    if (p && p.catch)
                        p.catch(() => {
                            /* swallow; user must interact */
                        });
                } else {
                    video.pause();
                }
            });
        }

        // also allow clicking overlay area to toggle
        if (overlay) {
            overlay.addEventListener("click", (e) => {
                // ignore clicks on controls (if any)
                if (e.target.closest("button")) return;
                playBtn && playBtn.click();
            });
        }

        // keyboard accessibility: space/enter toggles play when video focused
        video.addEventListener("keydown", (e) => {
            if (e.key === " " || e.key === "Spacebar" || e.key === "Enter") {
                e.preventDefault();
                if (video.paused) video.play();
                else video.pause();
            }
        });

        // update overlay visibility on play/pause/ended
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

        // show spinner when buffering (optional UX)
        video.addEventListener("waiting", () => {
            // you could append a spinner element here if desired
        });
        video.addEventListener("playing", () => {
            // remove spinner
        });

        // mark initialized
        video.dataset.initialized = "true";
    });
}
function createImageGrid(images) {
    const count = images.length;
    const postId = `post-${Date.now()}`; // Unique ID for this post's images

    if (count === 1) {
        return `
            <div class="post-images" data-image-count="1" data-post-id="${postId}">
                <div class="post-images-single">
                    <img src="${images[0].media_url}"
                         alt="Post image"
                         class="post-image"
                         onclick="openLightbox('${postId}', 0)"
                         style="cursor: pointer;">
                </div>
            </div>
        `;
    } else if (count === 2) {
        return `
            <div class="post-images post-images-grid post-images-two" data-image-count="2" data-post-id="${postId}">
                ${images
                    .map((img, index) =>
                        `<img src="${img.media_url}"
                              alt="Post image"
                              class="post-image"
                              onclick="openLightbox('${postId}', ${index})"
                              style="cursor: pointer;">`
                    )
                    .join("")}
            </div>
        `;
    } else if (count === 3) {
        return `
            <div class="post-images post-images-grid post-images-three" data-image-count="3" data-post-id="${postId}">
                <img src="${images[0].media_url}"
                     alt="Post image"
                     class="post-image post-image-large"
                     onclick="openLightbox('${postId}', 0)"
                     style="cursor: pointer;">
                <div class="post-images-small">
                    <img src="${images[1].media_url}"
                         alt="Post image"
                         class="post-image"
                         onclick="openLightbox('${postId}', 1)"
                         style="cursor: pointer;">
                    <img src="${images[2].media_url}"
                         alt="Post image"
                         class="post-image"
                         onclick="openLightbox('${postId}', 2)"
                         style="cursor: pointer;">
                </div>
            </div>
        `;
    } else {
        const remaining = count - 4;
        return `
            <div class="post-images post-images-grid post-images-four" data-image-count="${count}" data-post-id="${postId}">
                ${images
                    .slice(0, 3)
                    .map((img, index) =>
                        `<img src="${img.media_url}"
                              alt="Post image"
                              class="post-image"
                              onclick="openLightbox('${postId}', ${index})"
                              style="cursor: pointer;">`
                    )
                    .join("")}
                <div class="post-image-wrapper" onclick="openLightbox('${postId}', 3)" style="cursor: pointer;">
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
            ? `<img src="${op.user.avatar}" class="user-img" alt="${op.user.name}">`
            : `<div class="user-initials-avatar" style="width: 32px; height: 32px; font-size: 14px;">${op.user.initials}</div>`;

    return `
        <div class="shared-post-wrapper" onclick="window.location.href='/feed/posts/${
            op.slug
        }'">
            <div class="post-header">
                <div class="user-info">
                    ${opAvatar}
                    <div class="user_post_name">
                        <p class="username">${escapeHtml(op.user.name)}</p>
                        ${
                            op.user.position
                                ? `<p class="user-position">${escapeHtml(
                                      op.user.position
                                  )}</p>`
                                : ""
                        }
                        <span class="post-time">${formatTimeAgo(
                            op.created_at
                        )}</span>
                    </div>
                </div>
            </div>
            ${
                op.content
                    ? `<div class="post-text">${escapeHtml(
                          op.content
                      ).substring(0, 200)}${
                          op.content.length > 200 ? "..." : ""
                      }</div>`
                    : ""
            }
            ${
                op.media && op.media.length > 0
                    ? `
                <div class="post-images mt-2">
                    <img src="${
                        op.media[0].media_url
                    }" alt="Post image" class="post-image" style="max-height: 200px;">
                    ${
                        op.media.length > 1
                            ? `<div class="more-images-indicator">+${
                                  op.media.length - 1
                              } more</div>`
                            : ""
                    }
                </div>
            `
                    : ""
            }
        </div>
    `;
}

function createPostStats(post) {
    // Create reactions preview
    const reactionsHTML =
        post.likes_count > 0
            ? `<div class="reactions-preview">
             <span class="reaction-emoji-preview">👍</span>
           </div>
           <span class="count-text">${post.likes_count}</span>`
            : "";

    return `
        <div class="post-stats">
            <div class="likes-count" onclick="showReactionsList('${
                post.id
            }')" style="cursor: pointer;">
                ${reactionsHTML}
            </div>
            <div class="stats-right">
                ${
                    post.comments_count > 0
                        ? `
                    <div class="comments-count" onclick="toggleComments('${
                        post.id
                    }')" style="cursor: pointer;">
                        <span class="count-text">${
                            post.comments_count
                        } comment${post.comments_count !== 1 ? "s" : ""}</span>
                    </div>
                `
                        : ""
                }
                ${
                    post.shares_count > 0
                        ? `
                    <div class="shares-count" onclick="showSharesList('${
                        post.id
                    }')" style="cursor: pointer;">
                        <span class="count-text">${post.shares_count} share${
                              post.shares_count !== 1 ? "s" : ""
                          }</span>
                    </div>
                `
                        : ""
                }
            </div>
        </div>
    `;
}

function createPostActions(post) {
    const userReaction = post.user_reaction;
    const reactionEmoji = userReaction
        ? getReactionEmoji(userReaction.type)
        : "👍";
    const reactionLabel = userReaction
        ? capitalizeFirst(userReaction.type)
        : "Like";
    const reactionType = userReaction ? userReaction.type : "";

    return `
        <div class="post-actions">
            <div class="reaction-wrapper" onmouseleave="hideReactions(this)" data-post-id="${post.id}">
                <div class="action-btn"
                     onmouseenter="showReactions(this)"
                     onclick="handleReactionClick('${post.id}', '${reactionType}')"
                     data-current-reaction="${reactionType}">
                    <span class="reaction-icon">${reactionEmoji}</span>
                    <span class="reaction-label">${reactionLabel}</span>
                </div>
                <div class="reaction-panel d-none" onmouseenter="cancelHide()" onmouseleave="hideReactions(this)">
                    <span class="reaction-emoji" onclick="applyReaction(this, '👍', 'Like', 'like')" title="Like">👍</span>
                    <span class="reaction-emoji" onclick="applyReaction(this, '❤️', 'Love', 'love')" title="Love">❤️</span>
                    <span class="reaction-emoji" onclick="applyReaction(this, '😂', 'Haha', 'haha')" title="Haha">😂</span>
                    <span class="reaction-emoji" onclick="applyReaction(this, '😮', 'Wow', 'wow')" title="Wow">😮</span>
                    <span class="reaction-emoji" onclick="applyReaction(this, '😢', 'Sad', 'sad')" title="Sad">😢</span>
                    <span class="reaction-emoji" onclick="applyReaction(this, '😠', 'Angry', 'angry')" title="Angry">😠</span>
                </div>
            </div>
            <div class="action-btn comment-trigger" onclick="toggleComments('${post.id}')">
                <i class="fa-regular fa-comment-dots"></i> Comment
            </div>
            <div class="action-btn" onclick="sharePost('${post.id}')">
                <i class="fa-solid fa-retweet"></i> Repost
            </div>
            <div class="action-btn" onclick="sendPost('${post.id}')">
                <i class="fa-regular fa-paper-plane"></i> Send
            </div>
        </div>
    `;
}

function createCommentSection(post) {
    return `
        <div class="comment-section" id="commentSection-${
            post.id
        }" style="display: none;">
            ${createCommentInput(post.id)}
            <div class="comments-list" id="commentsList-${post.id}">
                <!-- Comments will be loaded dynamically -->
            </div>
            ${
                post.comments_count > 2
                    ? `
                <div class="load-more-comments">
                    <button class="load-more-btn" onclick="loadMoreComments('${
                        post.id
                    }')">
                        Load more comments (${post.comments_count - 2} more)
                    </button>
                </div>
            `
                    : ""
            }
        </div>
    `;
}

function createCommentInput(postId) {
    const userAvatar = window.authUserAvatar || "";
    const userInitials = window.authUserInitials || "U";

    // Build avatar HTML with onerror fallback
    const avatarHTML = userAvatar
        ? `
        <img src="${userAvatar}" class="user-img" alt="You"
             onerror="
                this.onerror=null;
                this.style.display='none';
                this.nextElementSibling.style.display='flex';
             ">
        <div class="comment-initials-avatar">
            ${userInitials}
        </div>
        `
        : `<div class="comment-initials-avatar">
                ${userInitials}
           </div>`;

    return `
        <div class="comment-input-wrapper">
            ${avatarHTML}
            <div class="comment-input-container">
                <input type="text" placeholder="Add a comment..." class="comment-input" oninput="toggleCommentButton(this)">
                <div class="comment-actions">
                    <button class="emoji-picker-btn"><i class="fa-regular fa-face-smile"></i></button>
                    <button class="post-comment-btn" disabled onclick="postComment('${postId}')">Post</button>
                </div>
            </div>
        </div>
    `;
}

function showSkeletonLoading() {
    const skeletonLoading = document.querySelector(
        ".skeleton-loading-container"
    );
    if (skeletonLoading) {
        skeletonLoading.style.display = "block";
    }
}

function hideSkeletonLoading() {
    const skeletonLoading = document.querySelector(
        ".skeleton-loading-container"
    );
    if (skeletonLoading) {
        skeletonLoading.style.display = "none";
    }
}

function showEmptyState() {
    document.getElementById("emptyState")?.classList.remove("d-none");
}

// Utility functions
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

function getReactionEmoji(type) {
    const emojiMap = {
        like: "👍",
        love: "❤️",
        haha: "😂",
        wow: "😮",
        sad: "😢",
        angry: "😠",
    };
    return emojiMap[type] || "👍";
}

function capitalizeFirst(str) {
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


// Store images for lightbox
window.postImages = window.postImages || {};
