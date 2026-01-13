<div class="modal fade" id="reactionsModal" tabindex="-1" aria-labelledby="reactionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reactionsModalLabel">Reactions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tabs -->
                <ul class="nav nav-tabs reaction-tabs mb-3" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#allReactions" type="button" role="tab">
                            All <span id="allCount" class="badge bg-secondary ms-1">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#likeReactions" type="button" role="tab">
                            👍 <span id="likeCount" class="badge bg-secondary ms-1">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#loveReactions" type="button" role="tab">
                            ❤️ <span id="loveCount" class="badge bg-secondary ms-1">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#hahaReactions" type="button" role="tab">
                            😂 <span id="hahaCount" class="badge bg-secondary ms-1">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#wowReactions" type="button" role="tab">
                            😮 <span id="wowCount" class="badge bg-secondary ms-1">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sadReactions" type="button" role="tab">
                            😢 <span id="sadCount" class="badge bg-secondary ms-1">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" data-bs-toggle="tab" data-bs-target="#angryReactions" type="button" role="tab">
                            😠 <span id="angryCount" class="badge bg-secondary ms-1">0</span>
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content">
                    <!-- All Reactions -->
                    <div class="tab-pane fade show active" id="allReactions" role="tabpanel">
                        <div id="allReactionsList" class="reactions-list"></div>
                    </div>

                    <!-- Like Reactions -->
                    <div class="tab-pane fade" id="likeReactions" role="tabpanel">
                        <div id="likeReactionsList" class="reactions-list"></div>
                    </div>

                    <!-- Love Reactions -->
                    <div class="tab-pane fade" id="loveReactions" role="tabpanel">
                        <div id="loveReactionsList" class="reactions-list"></div>
                    </div>

                    <!-- Haha Reactions -->
                    <div class="tab-pane fade" id="hahaReactions" role="tabpanel">
                        <div id="hahaReactionsList" class="reactions-list"></div>
                    </div>

                    <!-- Wow Reactions -->
                    <div class="tab-pane fade" id="wowReactions" role="tabpanel">
                        <div id="wowReactionsList" class="reactions-list"></div>
                    </div>

                    <!-- Sad Reactions -->
                    <div class="tab-pane fade" id="sadReactions" role="tabpanel">
                        <div id="sadReactionsList" class="reactions-list"></div>
                    </div>

                    <!-- Angry Reactions -->
                    <div class="tab-pane fade" id="angryReactions" role="tabpanel">
                        <div id="angryReactionsList" class="reactions-list"></div>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="reactionsLoading" class="text-center py-4 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-2">Loading reactions...</p>
                </div>

                <!-- Empty State -->
                <div id="reactionsEmpty" class="text-center py-4 d-none">
                    <i class="fa-regular fa-face-meh fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No reactions yet</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.reaction-tabs {
    border-bottom: 2px solid #e4e6eb;
    flex-wrap: nowrap;
    overflow-x: auto;
    overflow-y: hidden;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: none;
}

.reaction-tabs::-webkit-scrollbar {
    display: none;
}

.reaction-tabs .nav-item {
    flex-shrink: 0;
}

.reaction-tabs .nav-link {
    border: none;
    color: #65676b;
    padding: 8px 12px;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 4px;
    border-bottom: 2px solid transparent;
    transition: all 0.2s;
}

.reaction-tabs .nav-link:hover {
    color: #050505;
    background-color: #f0f2f5;
}

.reaction-tabs .nav-link.active {
    color: #0d6efd;
    border-bottom-color: #0d6efd;
    background-color: transparent;
}

.reaction-tabs .badge {
    font-size: 11px;
    padding: 2px 6px;
}

.reactions-list {
    max-height: 400px;
    overflow-y: auto;
}

.reaction-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px;
    border-radius: 8px;
    transition: background-color 0.2s;
}

.reaction-item:hover {
    background-color: #f0f2f5;
}

.reaction-item .user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    flex-shrink: 0;
}

.reaction-item .user-initials {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-weight: 600;
    font-size: 14px;
    text-transform: uppercase;
    flex-shrink: 0;
}

.reaction-item .user-info {
    flex: 1;
    min-width: 0;
}

.reaction-item .user-name {
    font-weight: 600;
    font-size: 14px;
    color: #050505;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.reaction-item .user-position {
    font-size: 13px;
    color: #65676b;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.reaction-item .reaction-emoji {
    font-size: 20px;
    flex-shrink: 0;
}

/* Mobile responsive */
@media (max-width: 576px) {
    .modal-dialog {
        margin: 0;
        max-width: 100%;
        height: 100vh;
    }

    .modal-content {
        height: 100vh;
        border-radius: 0;
    }

    .reaction-tabs .nav-link {
        padding: 6px 8px;
        font-size: 13px;
    }
}
</style>
