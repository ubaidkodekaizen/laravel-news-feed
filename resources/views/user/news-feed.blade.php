@extends('layouts.main')
<link rel="stylesheet" href="{{asset('assets/css/news-feed.css')}}">
<!-- jQuery Emoji Picker CSS -->
<link href="https://cdn.jsdelivr.net/npm/emoji-picker-jquery@1.4.2/css/emoji.css" rel="stylesheet">

@section('content')
    <section class="newFeedSec">
        <div class="container">
            <div class="row justify-content-center">
               <div class="col-lg-3">
                    <div class="card shadow-sm rounded">
                        <!-- Cover background -->
                        <div class="profile_cover_bg">
                        </div>

                        <!-- Profile Picture -->
                        <div class="profile_card_pic">
                            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" class="rounded-circle border border-2 border-white" width="60" height="60" alt="Profile Image">
                        </div>

                        <!-- Info -->
                        <div class="profile_card_details">
                            <h6 class="fw-bold mb-0">Jahanzaib Ansari</h6>
                            <small class="d-block">Frontend Developer @ Koder360<br>Web Developer || React JS…</small>
                            <small class="text-muted">Karāchi, Sindh</small>
                        </div>
                    </div>
                </div>

                <div class="col-lg-9">
                    <div class="card mb-3">
                        <div class="card-body d-flex align-items-center">
                            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png"
                                alt="User" class="rounded-circle me-2" width="40" height="40">
                            <button class="form-control text-start" id="openPostModal">Start a post</button>
                        </div>
                    </div>
                    <div class="post-container card">
                        <!-- Post Header -->
                        <div class="post-header">
                            <div class="user-info">
                                <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" class="user-img" alt="User">
                                <div class="user_post_name">
                                    <p class="username">Jahanzaib Ansari</p>
                                    <p class="user-position">Frontend Developer @ Koder360</p>
                                </div>
                            </div>
                            <span style="font-size: 0.8rem; color: #888;">1h ago</span>
                        </div>

                        <!-- Post Text with View More -->
                       <div class="post-text-wrapper">
                            <div class="post-text" id="postTextBlock">
                                Just launched a new feature on our LinkedIn-style post system in Laravel! 🚀 Feedback welcome! Here's some more detailed explanation about how we’ve implemented full emoji support, multi-image upload with crop/edit/duplicate/reorder and integrated commenting in Laravel Blade views. Also working on post analytics and reactions like LinkedIn. Stay tuned!
                            </div>
                            <span class="post-text-toggle" onclick="togglePostText(this)">See more</span>
                        </div>


                        <!-- Post Actions -->
                        <div class="post-actions">
                            <div class="action-btn"><i class="fa-regular fa-thumbs-up"></i> Like</div>
                            <div class="action-btn"><i class="fa-regular fa-comment-dots"></i> Comment</div>
                            <div class="action-btn"><i class="fa-solid fa-retweet"></i> Repost</div>
                            <div class="action-btn"><i class="fa-regular fa-paper-plane"></i> Send</div>
                        </div>

                        <!-- Comment Section -->
                        <div class="comment-section">
                            <!-- Add Comment -->
                            <div class="comment-input">
                            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" class="user-img">
                            <input type="text" placeholder="Add a comment...">
                            </div>

                            <!-- Existing Comments -->
                            <div class="comment">
                            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" class="user-img">
                            <div class="comment-body">
                                <strong>Ali Raza</strong> Great job! Keep building cool stuff. 🔥
                                <div class="reply-input comment-input">
                                <input type="text" placeholder="Reply...">
                                </div>
                            </div>
                            </div>

                            <div class="comment">
                            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png" class="user-img">
                            <div class="comment-body">
                                <strong>Sana Sheikh</strong> Excited to try this out soon!
                            </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
    <!-- POST MODAL -->
    <div class="modal modal-lg fade" id="postModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="postModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-lg">
            <div class="modal-content">
                <form id="postForm" enctype="multipart/form-data">
                    <div class="modal-header">
                        <h5 class="modal-title" id="postModalLabel">Create a Post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <textarea class="form-control" id="postText" rows="5"
                            placeholder="What do you want to talk about?"></textarea>

                        <div class="d-flex justify-content-between mt-2">
                            <input type="file" accept="image/*,video/*" id="mediaUpload" hidden>
                            <button type="button" class="btn btn-light btn-sm" id="uploadMediaBtn">📷 Upload</button>
                            <button type="button" class="btn btn-light btn-sm" id="emojiBtn">😀 Emoji</button>
                        </div>

                        <div id="selectedImagesPreview" class="mt-3" style="display: none;">
                            <div class="d-flex flex-wrap gap-2" id="previewImagesWrapper"></div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="editImagesBtn">✏️ Edit</button>
                                <button type="button" class="btn btn-sm btn-outline-danger" id="clearImagesBtn">❌ Clear</button>
                            </div>
                        </div>

                        <!-- <div id="mediaPreview" class="mt-2"></div> -->

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- IMAGE UPLOAD MODAL -->
    <div class="modal fade" id="imageUploadModal" tabindex="-1" aria-labelledby="imageUploadModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl">
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
<!-- EDIT IMAGE MODAL -->
<div class="modal fade" id="imageEditModal" tabindex="-1" aria-labelledby="imageEditModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl modal-dialog-scrollable">
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


@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<!-- CropperJS CSS & JS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<script>
function togglePostText(btn) {
  const textBlock = btn.previousElementSibling;
  textBlock.classList.toggle('expanded');
  btn.textContent = textBlock.classList.contains('expanded') ? 'See less' : 'See more';
}

</script>

    <script>
        $(document).ready(function () {
            // Open modal
            $('#openPostModal').click(function () {
                $('#postModal').modal('show');
            });
            $('#postForm').submit(function (e) {
                e.preventDefault();
                alert('Post submitted!');
            });
        });
    </script>
    <script type="module">
        import { Picker } from 'https://esm.sh/emoji-picker-element@1.18.2';
        const picker = new Picker({ locale: 'en' });
        picker.style.position = 'absolute';
        picker.style.zIndex = '9999';
        picker.style.display = 'none';
        document.body.appendChild(picker);
        const emojiBtn = document.getElementById('emojiBtn');
        const textArea = document.getElementById('postText');
        emojiBtn.addEventListener('click', (event) => {
            const rect = emojiBtn.getBoundingClientRect();
            picker.style.top = `${rect.bottom + window.scrollY}px`;
            picker.style.left = `${rect.left + window.scrollX}px`;
            picker.style.display = picker.style.display === 'none' ? 'block' : 'none';
        });
        picker.addEventListener('emoji-click', event => {
            textArea.value += event.detail.unicode;
            picker.style.display = 'none';
        });
        const modal = document.getElementById('postModal');
        modal.addEventListener('hidden.bs.modal', () => {
            picker.style.display = 'none';
        });
    </script>

<script>
let imageList = [];
function renderThumbnails() {
    const wrapper = $('#imageEditorThumbnails');
    wrapper.html('');
    imageList.forEach((img, index) => {
        wrapper.append(`
            <div class="col-lg-6">
                <div class="position-relative border p-1 image-box" data-index="${index}">
                    <img src="${img.src}" class="img-thumbnail mb-1" />
                    <div class="d-flex justify-content-between">
                        <span class="text-muted small">0${index + 1}</span>
                        <div>
                            <button class="btn btn-sm btn-light btn-left" title="Move Left"><i class="fa-solid fa-arrow-left"></i></button>
                            <button class="btn btn-sm btn-light btn-right" title="Move Right"><i class="fa-solid fa-arrow-right"></i></button>
                            <button class="btn btn-sm btn-light btn-edit" title="Edit"><i class="fa-solid fa-pen"></i></button>
                            <button class="btn btn-sm btn-light btn-dup" title="Duplicate"><i class="fa-solid fa-copy"></i></button>
                            <button class="btn btn-sm btn-light btn-del" title="Delete"><i class="fa-solid fa-trash text-danger"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        `);
    });

    if (imageList.length) {
        $('#mainImagePreview').attr('src', imageList[0].src);
    } else {
        $('#mainImagePreview').attr('src', '');
    }
}

// Open editor modal
$('#uploadMediaBtn, #editImagesBtn').click(function () {
    $('#imageUploadModal').modal('show');
    $('#postModal').modal('hide');
});
$('#uploadMediaBtn').click(function () {
    $('#multiImageInput').click();
});

$('#imageUploadModal').on('hidden.bs.modal', function () {
    $('#postModal').modal('show');
});

// Add images
$('#addImageBtn').click(() => $('#multiImageInput').click());

$('#multiImageInput').on('change', function (e) {
    const files = Array.from(e.target.files);
    files.forEach(file => {
        const url = URL.createObjectURL(file);
        imageList.push({ src: url });
    });
    renderThumbnails();
    $('#imageUploadModal').modal('show');
});
$('#postText').on('input', function () {
    const len = $(this).val().length;
    if (len > 3000) {
        $(this).val($(this).val().substring(0, 3000));
    }
});


// Button events
$('#imageEditorThumbnails').on('click', '.btn-del', function () {
    const i = $(this).closest('[data-index]').data('index');
    imageList.splice(i, 1);
    renderThumbnails();
});

$('#imageEditorThumbnails').on('click', '.btn-dup', function () {
    const i = $(this).closest('[data-index]').data('index');
    const newImg = { ...imageList[i] };
    imageList.splice(i + 1, 0, newImg);
    renderThumbnails();
});

$('#imageEditorThumbnails').on('click', '.btn-left', function () {
    const i = $(this).closest('[data-index]').data('index');
    if (i > 0) {
        [imageList[i - 1], imageList[i]] = [imageList[i], imageList[i - 1]];
        renderThumbnails();
    }
});

$('#imageEditorThumbnails').on('click', '.btn-right', function () {
    const i = $(this).closest('[data-index]').data('index');
    if (i < imageList.length - 1) {
        [imageList[i], imageList[i + 1]] = [imageList[i + 1], imageList[i]];
        renderThumbnails();
    }
});
// Set preview image when clicked
$('#imageEditorThumbnails').on('click', 'img', function () {
    const src = $(this).attr('src');
    $('#mainImagePreview').attr('src', src);
});

// Done editing
$('#imageEditorDone').click(function () {
    $('#imageUploadModal').modal('hide');
    $('#selectedImagesPreview').show();
    const wrapper = $('#previewImagesWrapper');
    wrapper.html('');
    imageList.forEach((img, i) => {
        wrapper.append(`<img src="${img.src}" class="img-thumbnail" style="width:100px;height:100px;">`);
    });
});
// Clear selected images
$('#clearImagesBtn').click(function () {
    imageList = [];
    $('#selectedImagesPreview').hide();
    $('#previewImagesWrapper').html('');
});

// Drag-and-drop sorting with SortableJS
new Sortable(document.getElementById('imageEditorThumbnails'), {
    animation: 150,
    onEnd: function (evt) {
        const oldIndex = evt.oldIndex;
        const newIndex = evt.newIndex;
        if (oldIndex === newIndex) return;

        const movedItem = imageList.splice(oldIndex, 1)[0];
        imageList.splice(newIndex, 0, movedItem);
        renderThumbnails();
    }
});
</script>

<script>
let cropper;
let currentEditIndex = null;

$('#imageEditorThumbnails').on('click', '.btn-edit', function () {
  const i = $(this).closest('[data-index]').data('index');
  currentEditIndex = i;
  const img = imageList[i];

  $('#cropImage').attr('src', img.src);
  $('#zoomSlider').val(1);
  $('#straightenSlider').val(0);

  $('#imageEditModal').modal('show');

  setTimeout(() => {
    const image = document.getElementById('cropImage');
    cropper = new Cropper(image, {
      viewMode: 1,
      autoCropArea: 1
    });
  }, 200);
});

$('#imageEditModal').on('hidden.bs.modal', function () {
  if (cropper) {
    cropper.destroy();
    cropper = null;
  }
});

// Aspect ratio buttons
$('.aspect-btn').on('click', function () {
  const aspect = parseFloat($(this).data('aspect'));
  cropper.setAspectRatio(isNaN(aspect) ? NaN : aspect);
});

// Rotate buttons
$('#rotateLeft').click(() => cropper.rotate(-90));
$('#rotateRight').click(() => cropper.rotate(90));

// Flip horizontal
let flipped = false;
$('#flipH').click(() => {
  cropper.scaleX(flipped ? 1 : -1);
  flipped = !flipped;
});

// Zoom control
$('#zoomSlider').on('input', function () {
  const zoom = parseFloat(this.value);
  cropper.zoomTo(zoom);
});

// Straighten control (rotate)
$('#straightenSlider').on('input', function () {
  cropper.rotateTo(parseInt(this.value));
});

// Apply cropped image
$('#applyImageEdit').click(function () {
  const canvas = cropper.getCroppedCanvas();
  const newSrc = canvas.toDataURL('image/jpeg');

  imageList[currentEditIndex].src = newSrc;
  $('#imageEditModal').modal('hide');
  renderThumbnails();
});
</script>


@endsection
