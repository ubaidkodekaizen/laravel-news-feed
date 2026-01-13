/**
 * Feed Manager - Handles infinite scroll, post loading, and feed interactions
 */

let currentPage = 1;
let isLoading = false;
let hasMorePages = true;
let currentSort = 'latest';

export function initializeFeed(sortOrder = 'latest') {
    // Reset state when changing sort
    currentPage = 1;
    isLoading = false;
    hasMorePages = true;
    currentSort = sortOrder;

    // Clear existing posts
    const postsContainer = document.getElementById('postsContainer');
    postsContainer.innerHTML = '';

    // Show skeleton loading
    showSkeletonLoading();

    // Load first page
    loadPosts(true);

    // Setup infinite scroll
    setupInfiniteScroll();
}

function setupInfiniteScroll() {
    let scrollTimeout;

    window.addEventListener('scroll', function() {
        clearTimeout(scrollTimeout);

        scrollTimeout = setTimeout(function() {
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

    const loadingIndicator = document.getElementById('loadingIndicator');
    if (!isFirstLoad) {
        loadingIndicator?.classList.remove('d-none');
    }

    try {
        const response = await fetch(`/feed/posts?page=${currentPage}&per_page=10&sort=${currentSort}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        });

        if (!response.ok) {
            throw new Error('Failed to load posts');
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
                document.getElementById('noMorePosts')?.classList.add('d-none');
            } else if (isFirstLoad) {
                // Show empty state if no posts on first load
                showEmptyState();
            }

            // Show "no more posts" message if we've reached the end
            if (!result.has_more && currentPage > 1) {
                document.getElementById('noMorePosts')?.classList.remove('d-none');
            }
        }
    } catch (error) {
        console.error('Error loading posts:', error);

        if (isFirstLoad) {
            hideSkeletonLoading();
            showNotification('Failed to load posts. Please refresh the page.', 'error');
        }
    } finally {
        isLoading = false;
        loadingIndicator?.classList.add('d-none');
    }
}

function renderPosts(posts) {
    const postsContainer = document.getElementById('postsContainer');

    posts.forEach(post => {
        const postHtml = createPostHTML(post);
        postsContainer.insertAdjacentHTML('beforeend', postHtml);
    });
}

function createPostHTML(post) {
    const isOwner = window.authUserId && window.authUserId === post.user.id;

    return `
        <div class="post-container card" data-post-id="${post.id}" data-post-slug="${post.slug}">
            ${createPostHeader(post, isOwner)}
            ${createSharedPostBadge(post)}
            ${createPostContent(post)}
            ${createPostMedia(post)}
            ${post.original_post ? createSharedPostDisplay(post) : ''}
            ${createPostStats(post)}
            ${createPostActions(post)}
            ${createCommentSection(post)}
        </div>
    `;
}

function createPostHeader(post, isOwner) {
    const userAvatar = post.user.has_photo && post.user.avatar
        ? `<img src="${post.user.avatar}" class="user-img" alt="${post.user.name}"
             onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
           <div class="user-initials-avatar" style="display: none;">${post.user.initials}</div>`
        : `<div class="user-initials-avatar">${post.user.initials}</div>`;

    const visibilityBadge = post.visibility && post.visibility !== 'public'
        ? `<span class="visibility-badge">
             <i class="fa-solid fa-${post.visibility === 'private' ? 'lock' : 'user-group'}"></i>
             ${post.visibility.charAt(0).toUpperCase() + post.visibility.slice(1)}
           </span>`
        : '';

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
        : '';

    return `
        <div class="post-header">
            <div class="user-info">
                ${userAvatar}
                <div class="user_post_name">
                    <a href="/user/profile/${post.user.slug}" class="username">${escapeHtml(post.user.name)}</a>
                    ${post.user.position ? `<p class="user-position">${escapeHtml(post.user.position)}</p>` : ''}
                    <span class="post-time">${formatTimeAgo(post.created_at)}</span>
                </div>
            </div>
            ${visibilityBadge}
            ${menuHTML}
        </div>
    `;
}

function createSharedPostBadge(post) {
    if (!post.original_post_id) return '';

    return `
        <div class="post-shared-badge">
            <i class="fa-solid fa-retweet"></i>
            <span><strong>${escapeHtml(post.user.name)}</strong> reposted</span>
        </div>
    `;
}

function createPostContent(post) {
    if (!post.content) return '';

    const content = escapeHtml(post.content);
    const shouldTruncate = content.length > 300;
    const displayContent = shouldTruncate ? content.substring(0, 300) : content;

    return `
        <div class="post-text-wrapper">
            <div class="post-text" id="postTextBlock-${post.id}" data-full-content="${content}">
                ${displayContent.replace(/\n/g, '<br>')}
            </div>
            ${shouldTruncate ? `<span class="post-text-toggle" onclick="togglePostText('${post.id}')">...see more</span>` : ''}
        </div>
    `;
}

function createPostMedia(post) {
    if (!post.media || post.media.length === 0) return '';

    const images = post.media.filter(m => m.media_type === 'image');
    const videos = post.media.filter(m => m.media_type === 'video');

    if (images.length === 0 && videos.length === 0) return '';

    // For now, show first image or video
    if (images.length > 0) {
        return createImageGrid(images);
    } else if (videos.length > 0) {
        return `
            <div class="post-media">
                <video controls class="post-video" style="width: 100%; max-height: 500px;">
                    <source src="${videos[0].media_url}" type="${videos[0].mime_type}">
                    Your browser does not support the video tag.
                </video>
            </div>
        `;
    }

    return '';
}

function createImageGrid(images) {
    const count = images.length;

    if (count === 1) {
        return `
            <div class="post-images" data-image-count="1">
                <div class="post-images-single">
                    <img src="${images[0].media_url}" alt="Post image" class="post-image">
                </div>
            </div>
        `;
    } else if (count === 2) {
        return `
            <div class="post-images post-images-grid post-images-two" data-image-count="2">
                ${images.map(img => `<img src="${img.media_url}" alt="Post image" class="post-image">`).join('')}
            </div>
        `;
    } else if (count === 3) {
        return `
            <div class="post-images post-images-grid post-images-three" data-image-count="3">
                <img src="${images[0].media_url}" alt="Post image" class="post-image post-image-large">
                <div class="post-images-small">
                    <img src="${images[1].media_url}" alt="Post image" class="post-image">
                    <img src="${images[2].media_url}" alt="Post image" class="post-image">
                </div>
            </div>
        `;
    } else {
        const remaining = count - 4;
        return `
            <div class="post-images post-images-grid post-images-four" data-image-count="${count}">
                ${images.slice(0, 3).map(img => `<img src="${img.media_url}" alt="Post image" class="post-image">`).join('')}
                <div class="post-image-wrapper">
                    <img src="${images[3].media_url}" alt="Post image" class="post-image">
                    ${remaining > 0 ? `<div class="post-image-overlay">+${remaining}</div>` : ''}
                </div>
            </div>
        `;
    }
}

function createSharedPostDisplay(post) {
    if (!post.original_post) return '';

    const op = post.original_post;
    const opAvatar = op.user.has_photo && op.user.avatar
        ? `<img src="${op.user.avatar}" class="user-img" alt="${op.user.name}">`
        : `<div class="user-initials-avatar" style="width: 32px; height: 32px; font-size: 14px;">${op.user.initials}</div>`;

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
            ${op.media && op.media.length > 0 ? `
                <div class="post-images mt-2">
                    <img src="${op.media[0].media_url}" alt="Post image" class="post-image" style="max-height: 200px;">
                    ${op.media.length > 1 ? `<div class="more-images-indicator">+${op.media.length - 1} more</div>` : ''}
                </div>
            ` : ''}
        </div>
    `;
}

function createPostStats(post) {
    // Create reactions preview
    const reactionsHTML = post.likes_count > 0
        ? `<div class="reactions-preview">
             <span class="reaction-emoji-preview">👍</span>
           </div>
           <span class="count-text">${post.likes_count}</span>`
        : '';

    return `
        <div class="post-stats">
            <div class="likes-count" onclick="showReactionsList('${post.id}')" style="cursor: pointer;">
                ${reactionsHTML}
            </div>
            <div class="stats-right">
                ${post.comments_count > 0 ? `
                    <div class="comments-count" onclick="toggleComments('${post.id}')" style="cursor: pointer;">
                        <span class="count-text">${post.comments_count} comment${post.comments_count !== 1 ? 's' : ''}</span>
                    </div>
                ` : ''}
                ${post.shares_count > 0 ? `
                    <div class="shares-count" onclick="showSharesList('${post.id}')" style="cursor: pointer;">
                        <span class="count-text">${post.shares_count} share${post.shares_count !== 1 ? 's' : ''}</span>
                    </div>
                ` : ''}
            </div>
        </div>
    `;
}

function createPostActions(post) {
    const userReaction = post.user_reaction;
    const reactionEmoji = userReaction ? getReactionEmoji(userReaction.type) : '👍';
    const reactionLabel = userReaction ? capitalizeFirst(userReaction.type) : 'Like';
    const reactionType = userReaction ? userReaction.type : '';

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
                    <span class="reaction-emoji" onclick="applyReaction(this, '👏', 'Celebrate', 'celebrate')" title="Celebrate">👏</span>
                    <span class="reaction-emoji" onclick="applyReaction(this, '💖', 'Love', 'love')" title="Love">💖</span>
                    <span class="reaction-emoji" onclick="applyReaction(this, '💡', 'Insightful', 'insightful')" title="Insightful">💡</span>
                    <span class="reaction-emoji" onclick="applyReaction(this, '😂', 'Funny', 'funny')" title="Funny">😂</span>
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
        <div class="comment-section" id="commentSection-${post.id}" style="display: none;">
            ${createCommentInput(post.id)}
            <div class="comments-list" id="commentsList-${post.id}">
                <!-- Comments will be loaded dynamically -->
            </div>
            ${post.comments_count > 2 ? `
                <div class="load-more-comments">
                    <button class="load-more-btn" onclick="loadMoreComments('${post.id}')">
                        Load more comments (${post.comments_count - 2} more)
                    </button>
                </div>
            ` : ''}
        </div>
    `;
}

function createCommentInput(postId) {
    const userAvatar = window.authUserAvatar || '';
    const userInitials = window.authUserInitials || 'U';
    // console.log("userAvatar", userAvatar);
    // console.log("userInitials", typeof userInitials);

    const avatarHTML = userAvatar
        ? `<img src="${userAvatar}" class="user-img" alt="You">`
        : `<div class="user-initials-avatar" style="width: 40px; height: 40px; font-size: 14px;">${userInitials}</div>`;

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
    const skeletonLoading = document.querySelector('.skeleton-loading-container');
    if (skeletonLoading) {
        skeletonLoading.style.display = 'block';
    }
}

function hideSkeletonLoading() {
    const skeletonLoading = document.querySelector('.skeleton-loading-container');
    if (skeletonLoading) {
        skeletonLoading.style.display = 'none';
    }
}

function showEmptyState() {
    document.getElementById('emptyState')?.classList.remove('d-none');
}

// Utility functions
function escapeHtml(text) {
    const div = document.createElement('div');
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
        minute: 60
    };

    for (const [key, value] of Object.entries(intervals)) {
        const interval = Math.floor(seconds / value);
        if (interval >= 1) {
            return `${interval} ${key}${interval !== 1 ? 's' : ''} ago`;
        }
    }

    return 'Just now';
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
    return str.charAt(0).toUpperCase() + str.slice(1);
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

// Make functions globally available
window.showReactionsList = showReactionsList;
window.showSharesList = showSharesList;

function showReactionsList(postId) {
    // This will be implemented in reactions.js
    console.log('Show reactions list for post:', postId);
}

function showSharesList(postId) {
    // This will be implemented in share-repost.js
    console.log('Show shares list for post:', postId);
}
