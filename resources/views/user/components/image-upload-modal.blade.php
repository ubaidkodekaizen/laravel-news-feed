    <!-- IMAGE UPLOAD MODAL -->
    <div class="modal fade" id="imageUploadModal" tabindex="-1" aria-labelledby="imageUploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Editor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex">
                <!-- Large Preview Area -->
                <div class="flex-grow-1 text-center">
                <img id="mainImagePreview" src="" class="img-fluid" style="max-height: 400px;" />
                <!-- <div class="mt-2">
                    <input type="text" class="form-control" placeholder="ALT text (optional)">
                </div> -->
                </div>

                <!-- Thumbnail Column -->
                <div class="thumbnail_col">
                    <div class="row g-2" id="imageEditorThumbnails"></div>
                <div class="mt-3 text-center">
                    <input type="file" id="multiImageInput" accept="image/*" multiple hidden>
                    <button type="button" class="btn btn-outline-primary btn-sm" id="addImageBtn">➕ Add</button>
                </div>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" data-bs-dismiss="modal">Back</button>
                <button class="btn btn-primary" id="imageEditorDone">Next</button>
            </div>
            </div>
        </div>
    </div>
