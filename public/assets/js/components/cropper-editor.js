let cropper;
let currentEditIndex = null;

$('#imageEditorThumbnails').on('click', '.btn-edit', function () {
  const i = $(this).closest('[data-index]').data('index');
  currentEditIndex = i;
  const img = window.imageList[i];

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

$('.aspect-btn').on('click', function () {
  const aspect = parseFloat($(this).data('aspect'));
  cropper.setAspectRatio(isNaN(aspect) ? NaN : aspect);
});

$('#rotateLeft').click(() => cropper.rotate(-90));
$('#rotateRight').click(() => cropper.rotate(90));

let flipped = false;
$('#flipH').click(() => {
  cropper.scaleX(flipped ? 1 : -1);
  flipped = !flipped;
});

$('#zoomSlider').on('input', function () {
  const zoom = parseFloat(this.value);
  cropper.zoomTo(zoom);
});

$('#straightenSlider').on('input', function () {
  cropper.rotateTo(parseInt(this.value));
});

$('#applyImageEdit').click(function () {
  const canvas = cropper.getCroppedCanvas();
  const newSrc = canvas.toDataURL('image/jpeg');

  window.imageList[currentEditIndex].src = newSrc;
  $('#imageEditModal').modal('hide');
  renderThumbnails();
});
