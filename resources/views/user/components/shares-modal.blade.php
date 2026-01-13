<!-- SHARES LIST MODAL -->
<div class="modal fade" id="sharesModal" tabindex="-1" aria-labelledby="sharesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sharesModalLabel">
                    Shares <span class="badge bg-secondary ms-1" id="totalSharesCount">0</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="sharesList" class="shares-list">
                    <!-- Will be populated dynamically -->
                </div>

                <!-- Loading State -->
                <div id="sharesLoading" class="text-center py-4 d-none">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                </div>

                <!-- Empty State -->
                <div id="sharesEmpty" class="text-center py-4 text-muted d-none">
                    <i class="fa-solid fa-share fa-3x mb-2"></i>
                    <p>No shares yet</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.shares-list {
    max-height: 500px;
    overflow-y: auto;
}

.share-item {
    display: flex;
    align-items: flex-start;
    padding: 12px;
    border-bottom: 1px solid #f0f2f5;
    transition: background-color 0.2s;
}

.share-item:hover {
    background-color: #f8f9fa;
}

.share-item:last-child {
    border-bottom: none;
}

.share-item .user-avatar {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    object-fit: cover;
    margin-right: 12px;
    flex-shrink: 0;
}

.share-item .user-initials {
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
    flex-shrink: 0;
}

.share-item .share-content {
    flex: 1;
}

.share-item .user-name {
    font-weight: 600;
    margin-bottom: 2px;
}

.share-item .user-position {
    font-size: 13px;
    color: #65676b;
    margin-bottom: 4px;
}

.share-item .share-text {
    font-size: 14px;
    color: #050505;
    margin-top: 8px;
    padding: 8px;
    background-color: #f8f9fa;
    border-radius: 4px;
}

.share-item .share-time {
    font-size: 12px;
    color: #65676b;
    margin-top: 4px;
}

.share-item .share-type-badge {
    font-size: 11px;
    padding: 2px 8px;
    border-radius: 12px;
    background-color: #e7f3ff;
    color: #0d6efd;
    margin-left: 8px;
}
</style>
