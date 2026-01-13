<!-- REACTIONS LIST MODAL -->
<div class="modal fade" id="reactionsModal" tabindex="-1" aria-labelledby="reactionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reactionsModalLabel">Reactions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Reaction Type Tabs -->
                <ul class="nav nav-tabs mb-3" id="reactionTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all-reactions" type="button" role="tab">
                            All <span class="badge bg-secondary ms-1" id="allCount">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="like-tab" data-bs-toggle="tab" data-bs-target="#like-reactions" type="button" role="tab">
                            👍 <span class="badge bg-secondary ms-1" id="likeCount">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="love-tab" data-bs-toggle="tab" data-bs-target="#love-reactions" type="button" role="tab">
                            💖 <span class="badge bg-secondary ms-1" id="loveCount">0</span>
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="celebrate-tab" data-bs-toggle="tab" data-bs-target="#celebrate-reactions" type="button" role="tab">
                            👏 <span class="badge bg-secondary ms-1" id="celebrateCount">0</span>
                        </button>
                    </li>
                </ul>

                <!-- Tab Content -->
                <div class="tab-content" id="reactionTabContent">
                    <div class="tab-pane fade show active" id="all-reactions" role="tabpanel">
                        <div id="allReactionsList" class="reactions-list">
                            <!-- Will be populated dynamically -->
                        </div>
                    </div>
                    <div class="tab-pane fade" id="like-reactions" role="tabpanel">
                        <div id="likeReactionsList" class="reactions-list">
                            <!-- Will be populated dynamically -->
                        </div>
                    </div>
                    <div class="tab-pane fade" id="love-reactions" role="tabpanel">
                        <div id="loveReactionsList" class="reactions-list">
                            <!-- Will be populated dynamically -->
                        </div>
                    </div>
                    <div class="tab-pane fade" id="celebrate-reactions" role="tabpanel">
                        <div id="celebrateReactionsList" class="reactions-list">
                            <!-- Will be populated dynamically -->
                        </div>
                    </div>
                </div>

                <!-- Loading State -->
                <div id="reactionsLoading" class="text-center py-4 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="reactionsEmpty" class="text-center py-4 text-muted d-none">
                    <i class="fa-regular fa-face-meh fa-3x mb-2"></i>
                    <p>No reactions yet</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.reactions-list {
    max-height: 400px;
    overflow-y: auto;
}

.reaction-item {
    display: flex;
    align-items: center;
    padding: 12px;
    border-bottom: 1px solid #f0f2f5;
    transition: background-color 0.2s;
}

.reaction-item:hover {
    background-color: #f8f9fa;
}

.reaction-item:last-child {
    border-bottom: none;
}

.reaction-item .user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 12px;
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
    margin-right: 12px;
}

.reaction-item .user-info {
    flex: 1;
}

.reaction-item .user-name {
    font-weight: 600;
    margin-bottom: 2px;
}

.reaction-item .user-position {
    font-size: 13px;
    color: #65676b;
}

.reaction-item .reaction-emoji {
    font-size: 20px;
    margin-left: auto;
}

#reactionTabs .nav-link {
    padding: 8px 16px;
    font-size: 14px;
}

#reactionTabs .badge {
    font-size: 11px;
}
</style>
