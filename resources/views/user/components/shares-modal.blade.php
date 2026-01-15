<!-- SHARES LIST MODAL -->
<div class="modal fade" id="sharesModal" tabindex="-1" aria-labelledby="sharesModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="sharesModalLabel">
                    Shares <span class="badge bg-secondary ms-1" id="totalSharesCount">0</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
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
        max-height: 300px;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
        gap: 10px;
        padding: 0 10px 0 0;
    }

    #totalSharesCount {
        background: #273572 !important;
        border-radius: 50%;
        height: 20px;
        width: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 14px;
        font-family: "Inter", sans-serif;
        font-weight: 600;
        line-height: 1.3em;
    }

    .share-item {
        display: flex;
        align-items: flex-start;
        padding: 12px;
        transition: background-color 0.2s;
        background: #F5F5F526;
        border: 1px solid #F2F2F2;
        border-radius: 10px;
    }

    .share-item:hover {
        background-color: #f8f9fa;
    }



    .share-item .user-avatar {
        width: 48px;
        height: 48px;
        border-radius: 50%;
        object-fit: cover;
        margin-right: 12px;
        flex-shrink: 0;
    }

    .share-item .user-initials {
        width: 48px;
        height: 48px;
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

    .share-item .share-meta {
        display: flex;
        align-items: center;
        justify-content: start;
        margin: 0px 0px 4px 0;
    }

    .share-item .user-name {
        margin-bottom: 0;
        font-family: Inter;
        font-weight: 600;
        font-size: 14.5px;
        line-height: 100%;
        color: #17272F;
    }

    .share-item .user-position {
        color: #5D5D5D;
        margin-bottom: 6px;
        font-family: Roboto;
        font-weight: 400;
        font-size: 12.93px;
        line-height: 100%;
    }

    .share-item .share-text {
        color: #050505;
        margin: 0;
        padding: 14px 17px;
        border-radius: 8.36px;
        background: #BAE1FF1A;
        border: 1px solid #CAD9FF;
        font-family: Inter;
        font-weight: 600;
        font-size: 12.5px;
        line-height: 100%;
    }

    .share-item .share-time {
        color: #273572;
        margin: 4px 0 11px 0;
        font-family: Inter;
        font-weight: 600;
        font-size: 13.93px;
        line-height: 100%;
    }

    .share-item .share-type-badge {
        font-size: 11px;
        padding: 3px 8px;
        border-radius: 12px;
        background-color: #e7f3ff;
        color: #0d6efd;
        margin-left: 8px;
        line-height: 1.3em;
    }
</style>
