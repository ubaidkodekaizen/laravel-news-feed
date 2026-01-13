<!-- POST MODAL -->
    <div class="modal modal-lg fade" id="postModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="postForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="postModalLabel">Create a Post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <textarea class="form-control" id="postText" rows="5"
                            placeholder="What do you want to talk about?"></textarea>

                        <div class="d-flex justify-content-between align-items-center mt-3">
                            <div class="d-flex gap-2">
                                <input type="file" accept="image/*,video/*" id="mediaUpload" hidden>
                                <button type="button" class="btn btn-light btn-sm" id="uploadMediaBtn">
                                    <i class="fa-solid fa-image"></i> Photo
                                </button>
                                <button type="button" class="btn btn-light btn-sm" id="emojiBtn">
                                    <i class="fa-regular fa-face-smile"></i> Emoji
                                </button>
                            </div>

                            <!-- Comments Toggle -->
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="commentsEnabledToggle" checked>
                                <label class="form-check-label" for="commentsEnabledToggle">
                                    <small>Allow comments</small>
                                </label>
                            </div>
                        </div>

                        <div id="selectedImagesPreview" class="mt-3" style="display: none;">
                            <div class="d-flex flex-wrap gap-2" id="previewImagesWrapper"></div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="editImagesBtn">
                                    <i class="fa-solid fa-pen"></i> Edit
                                </button>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="clearImagesBtn">
                                    <i class="fa-solid fa-trash"></i> Clear
                                </button>
                            </div>
                        </div>

                    </div>
                    <div class="modal-footer d-flex justify-content-between align-items-center">
                        <div class="text-muted small">
                            <i class="fa-solid fa-globe"></i> Anyone can see this post
                        </div>
                        <button type="submit" class="btn btn-primary">Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
