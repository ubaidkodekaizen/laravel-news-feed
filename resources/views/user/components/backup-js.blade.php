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
let hideTimeout = null;

function showReactions(el) {
  const wrapper = el.closest('.reaction-wrapper');
  const panel = wrapper.querySelector('.reaction-panel');

  // Restart animations
  panel.querySelectorAll('.reaction-emoji').forEach(emoji => {
    emoji.style.animation = 'none';
    emoji.offsetHeight; // force reflow
    emoji.style.animation = '';
  });

  panel.classList.remove('d-none');
  clearTimeout(hideTimeout);
}


function hideReactions(el) {
  hideTimeout = setTimeout(() => {
    const panel = el.querySelector('.reaction-panel');
    if (panel) panel.classList.add('d-none');
  }, 250);
}

function cancelHide() {
  clearTimeout(hideTimeout);
}

function applyReaction(span, emoji, label) {
  const wrapper = span.closest('.reaction-wrapper');
  const iconWrapper = wrapper.querySelector('.reaction-icon');
  const labelEl = wrapper.querySelector('.reaction-label');

  if (iconWrapper) iconWrapper.textContent = emoji;
  if (labelEl) labelEl.textContent = label;

  wrapper.querySelector('.reaction-panel').classList.add('d-none');
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
