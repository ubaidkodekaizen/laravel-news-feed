<div class="modal fade" id="reactionsModal" tabindex="-1" aria-labelledby="reactionsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="reactionsModalLabel">Reactions</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <ul class="nav nav-tabs reaction-tabs mb-3" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#allReactions" type="button"
                        role="tab">
                        All <span id="allCount" class="badge bg-secondary ms-1">0</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#likeReactions" type="button"
                        role="tab">
                        👍 <span id="likeCount" class="badge bg-secondary ms-1">0</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#loveReactions" type="button"
                        role="tab">
                        ❤️ <span id="loveCount" class="badge bg-secondary ms-1">0</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#hahaReactions" type="button"
                        role="tab">
                        😂 <span id="hahaCount" class="badge bg-secondary ms-1">0</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#wowReactions" type="button"
                        role="tab">
                        😮 <span id="wowCount" class="badge bg-secondary ms-1">0</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#sadReactions" type="button"
                        role="tab">
                        😢 <span id="sadCount" class="badge bg-secondary ms-1">0</span>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#angryReactions" type="button"
                        role="tab">
                        😠 <span id="angryCount" class="badge bg-secondary ms-1">0</span>
                    </button>
                </li>
            </ul>
            <div class="modal-body">



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
        flex-wrap: nowrap;
        overflow-x: auto;
        overflow-y: hidden;
        -webkit-overflow-scrolling: touch;
        scrollbar-width: none;
        padding: 9px 24px;
        background: #D9D9D933;
        display: flex;
        align-items: center;
        justify-content: start;
        gap: 5px;
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
        padding: 4px 12px 4px 9px;
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
        border: 1px solid var(--2, #2D3B68);
        border-radius: 53px;
        line-height: 1.3em;
    }

    .reaction-tabs .nav-link:hover {
        color: #050505;
        background-color: #f0f2f5;
    }

    .reaction-tabs .nav-link.active {
        color: #fff;
        border-color: #2D3B68;
        background: #2D3B68;
    }

    .reaction-tabs .nav-link.active .badge {
        color: #fff;
    }

    .reaction-tabs .badge {
        padding: 0;
        font-family: Inter;
        font-weight: 600;
        font-size: 12px;
        line-height: 100%;
        color: #333333;
        background: none !important;
        margin: 0px 0px 0px 4px !important;
    }

    .reactions-list {
        max-height: 298px;
        overflow-y: auto;
        padding: 0px 10px 0 0px;
    }

    .reaction-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 0;
        border-bottom: 1.34px solid #CAD9FF99;
        transition: background-color 0.2s;
    }

    .reaction-item:last-child {
        border-bottom: none;
    }

    .reaction-item:hover {
        background-color: #f0f2f5;
    }

    .reaction-item .user-avatar {
        width: 50.13px;
        height: 50.13px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .reaction-item .user-initials {
        width: 50.13px;
        height: 50.13px;
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
        color: #17272F;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-family: Inter;
        font-weight: 600;
        font-size: 14.5px;
        line-height: 100%;
        margin: 0 0 4px 0;
    }

    .reaction-item .user-position {
        color: #5D5D5D;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-family: Roboto;
        font-weight: 400;
        font-size: 12.93px;
        line-height: 100%;
    }

    .reaction-avatar-box {
        position: relative;
    }

    .reaction-item .reaction-emoji {
        cursor: pointer;
        padding: 0 !important;
        border-radius: 50%;
        transition: transform 0.2s;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 16px;
        height: 16px;
        background: none;
        font-size: 16px;
        position: absolute;
        right: 0;
        bottom: 0;
    }

    /* Mobile responsive */
    @media (max-width: 576px) {
        .modal-dialog {
            margin: auto;
            max-width: 95%;
            height: 100vh;
        }

        .modal-content {
            height: unset;
        }

        .reaction-tabs .nav-link {
            padding: 6px 8px;
            font-size: 13px;
        }
    }
</style>
