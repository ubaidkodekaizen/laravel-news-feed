<!-- Skeleton Loading for Posts -->
<div class="skeleton-loading-container">
    @for($i = 0; $i < 3; $i++)
    <div class="post-container card skeleton-post">
        <!-- Post Header Skeleton -->
        <div class="post-header">
            <div class="user-info">
                <div class="skeleton-avatar"></div>
                <div class="skeleton-text-container">
                    <div class="skeleton-text skeleton-text-title"></div>
                    <div class="skeleton-text skeleton-text-subtitle"></div>
                    <div class="skeleton-text skeleton-text-small"></div>
                </div>
            </div>
        </div>

        <!-- Post Content Skeleton -->
        <div class="skeleton-content">
            <div class="skeleton-text skeleton-text-full"></div>
            <div class="skeleton-text skeleton-text-full"></div>
            <div class="skeleton-text skeleton-text-half"></div>
        </div>

        <!-- Post Image Skeleton -->
        <div class="skeleton-image"></div>

        <!-- Post Stats Skeleton -->
        <div class="post-stats">
            <div class="skeleton-text skeleton-text-stat"></div>
            <div class="skeleton-text skeleton-text-stat"></div>
        </div>

        <!-- Post Actions Skeleton -->
        <div class="post-actions">
            <div class="skeleton-text skeleton-text-action"></div>
            <div class="skeleton-text skeleton-text-action"></div>
            <div class="skeleton-text skeleton-text-action"></div>
            <div class="skeleton-text skeleton-text-action"></div>
        </div>
    </div>
    @endfor
</div>

<style>
.skeleton-loading-container {
    animation: fadeIn 0.3s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.skeleton-post {
    margin-bottom: 1rem;
    pointer-events: none;
}

.skeleton-avatar {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

.skeleton-text-container {
    flex: 1;
    margin-left: 12px;
}

.skeleton-text {
    height: 12px;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
    border-radius: 4px;
    margin-bottom: 8px;
}

.skeleton-text-title {
    width: 140px;
    height: 16px;
}

.skeleton-text-subtitle {
    width: 180px;
    height: 14px;
}

.skeleton-text-small {
    width: 80px;
    height: 12px;
}

.skeleton-content {
    padding: 16px 20px;
}

.skeleton-text-full {
    width: 100%;
    height: 14px;
}

.skeleton-text-half {
    width: 60%;
    height: 14px;
}

.skeleton-image {
    width: 100%;
    height: 300px;
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

.skeleton-text-stat {
    width: 80px;
    height: 12px;
}

.skeleton-text-action {
    width: 70px;
    height: 14px;
}

@keyframes shimmer {
    0% {
        background-position: -200% 0;
    }
    100% {
        background-position: 200% 0;
    }
}

/* Dark mode support */
@media (prefers-color-scheme: dark) {
    .skeleton-avatar,
    .skeleton-text,
    .skeleton-image {
        background: linear-gradient(90deg, #2a2a2a 25%, #1a1a1a 50%, #2a2a2a 75%);
        background-size: 200% 100%;
    }
}
</style>
