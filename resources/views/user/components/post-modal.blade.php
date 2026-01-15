<!-- IMPROVED POST MODAL -->
<div class="modal modal-lg fade" id="postModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1"
    aria-labelledby="postModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="postForm" enctype="multipart/form-data">
                <div class="modal-header">
                    <h5 class="modal-title" id="postModalLabel">Create a Post</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                        <i class="fa-solid fa-xmark"></i>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Post Content -->
                    <textarea class="form-control" id="postText" rows="5" placeholder="What do you want to talk about?"
                        maxlength="10000"></textarea>

                    <div class="character-count text-muted small mt-1 text-end">
                        <span id="charCount">0</span>/10000
                    </div>

                    <!-- Media Actions & Settings Row -->
                    <div class="d-flex justify-content-between align-items-center mt-3 flex-wrap gap-2">
                        <!-- Left: Media Upload Buttons -->
                        <div class="d-flex create-post-upload-btns">
                            <input type="file" accept="image/*,video/*" id="mediaUpload" multiple hidden>
                            <button type="button" class="btn btn-light btn-sm" id="uploadPhotoBtn">
                                <img src="{{ asset('assets/images/postPhoto.svg') }}" class="img-fluid" alt="">
                                Photo
                            </button>
                            <button type="button" class="btn btn-light btn-sm" id="uploadVideoBtn">
                                <img src="{{ asset('assets/images/postVideo.svg') }}" class="img-fluid" alt="">
                                Video
                            </button>
                            <button type="button" class="btn btn-light btn-sm" id="emojiBtn">
                                <i class="fa-regular fa-face-smile"></i>
                            </button>
                        </div>

                        <!-- Right: Settings -->
                        <div class="d-flex gap-3 align-items-center">
                            <!-- Comments Toggle -->
                            <div class="form-check form-switch mb-0">
                                <input class="form-check-input" type="checkbox" id="commentsEnabledToggle" checked>
                                <label class="form-check-label" for="commentsEnabledToggle">
                                    <small>Comments</small>
                                </label>
                            </div>

                            <!-- Visibility Dropdown -->
                            <div class="dropdown">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button"
                                    id="visibilityDropdown" data-bs-toggle="dropdown" aria-expanded="false">

                                    <span id="visibilityText">Public</span>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="visibilityDropdown">
                                    <li>
                                        <a class="dropdown-item visibility-option" href="#"
                                            data-visibility="public">
                                            <i class="fa-solid fa-globe me-2"></i>
                                            <div>
                                                <strong>Public</strong>
                                                <div class="small text-muted">Anyone can see this post</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item visibility-option" href="#"
                                            data-visibility="connections">
                                            <i class="fa-solid fa-user-group me-2"></i>
                                            <div>
                                                <strong>Connections Only</strong>
                                                <div class="small text-muted">Only your connections can see</div>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item visibility-option" href="#"
                                            data-visibility="private">
                                            <i class="fa-solid fa-lock me-2"></i>
                                            <div>
                                                <strong>Private</strong>
                                                <div class="small text-muted">Only you can see this post</div>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Selected Media Preview -->
                    <div id="selectedMediaPreview" class="mt-3" style="display: none;">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h6 class="mb-0">Selected Media (<span id="mediaCount">0</span>)</h6>
                            <div>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="editMediaBtn">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="clearMediaBtn">
                                    <i class="fa-solid fa-trash"></i> Clear All
                                </button>
                            </div>
                        </div>
                        <div class="d-flex flex-wrap gap-2" id="previewMediaWrapper"></div>
                    </div>

                    <!-- Validation Errors -->
                    <div id="postValidationErrors" class="alert alert-danger mt-3 d-none"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submitPostBtn">
                        Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
    #postText {
        border: none;
        resize: none;
        font-size: 16px;
    }

    #postText:focus {
        outline: none;
        box-shadow: none;
    }

    .character-count {
        font-size: 12px;
    }

    .character-count.warning {
        color: #ff9800 !important;
    }

    .character-count.error {
        color: #f44336 !important;
    }

    .visibility-option {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
    }

    .visibility-option:hover {
        background-color: #f8f9fa;
    }

    .visibility-option i {
        font-size: 18px;
        width: 24px;
    }

    .visibility-option.active {
        background-color: #e7f3ff;
        color: #0d6efd;
    }

    #previewMediaWrapper .media-preview-item {
        position: relative;
        width: 100px;
        height: 100px;
        border-radius: 8px;
        overflow: hidden;
        border: 2px solid #dee2e6;
    }

    #previewMediaWrapper .media-preview-item img,
    #previewMediaWrapper .media-preview-item video {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    #previewMediaWrapper .media-preview-item .video-overlay {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background-color: rgba(0, 0, 0, 0.6);
        color: white;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
        pointer-events: none;
    }
</style>
