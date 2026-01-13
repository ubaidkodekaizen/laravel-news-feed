

<!-- EDIT IMAGE MODAL -->
<div class="modal fade" id="imageEditModal" tabindex="-1" aria-labelledby="imageEditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body d-flex">
        <!-- Editor Left Side -->
        <div class="flex-grow-1 text-center">
          <img id="cropImage" src="" class="img-fluid" style="max-height: 500px;" />
        </div>

        <!-- Controls -->
        <div class="ms-4" style="width: 280px;">
          <h6>Crop</h6>
          <div class="mb-3 d-flex gap-2 flex-wrap">
            <button class="btn btn-sm btn-outline-secondary aspect-btn" data-aspect="NaN">Original</button>
            <button class="btn btn-sm btn-outline-secondary aspect-btn" data-aspect="1">Square</button>
            <button class="btn btn-sm btn-outline-secondary aspect-btn" data-aspect="4">4:1</button>
            <button class="btn btn-sm btn-outline-secondary aspect-btn" data-aspect="0.75">3:4</button>
            <button class="btn btn-sm btn-outline-secondary aspect-btn" data-aspect="16/9">16:9</button>
          </div>

          <h6>Rotate & Flip</h6>
          <div class="mb-3 d-flex gap-2">
            <button class="btn btn-sm btn-outline-secondary" id="rotateLeft">⟲</button>
            <button class="btn btn-sm btn-outline-secondary" id="rotateRight">⟳</button>
            <button class="btn btn-sm btn-outline-secondary" id="flipH">⇋</button>
          </div>

          <h6>Zoom</h6>
          <input type="range" id="zoomSlider" min="0.1" max="3" step="0.1" value="1" class="form-range">

          <h6>Straighten</h6>
          <input type="range" id="straightenSlider" min="-45" max="45" step="1" value="0" class="form-range">

        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        <button class="btn btn-primary" id="applyImageEdit">Apply</button>
      </div>
    </div>
  </div>
</div>
