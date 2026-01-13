// Make imageList globally accessible
window.imageList = [];

function renderThumbnails() {
    const wrapper = $('#imageEditorThumbnails');
    wrapper.html('');
    window.imageList.forEach((img, index) => {
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

    $('#mainImagePreview').attr('src', window.imageList.length ? window.imageList[0].src : '');
}

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

$('#addImageBtn').click(() => $('#multiImageInput').click());

$('#multiImageInput').on('change', function (e) {
    const files = Array.from(e.target.files);
    files.forEach(file => {
        const url = URL.createObjectURL(file);
        window.imageList.push({ src: url });
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

$('#imageEditorThumbnails').on('click', '.btn-del', function () {
    const i = $(this).closest('[data-index]').data('index');
    window.imageList.splice(i, 1);
    renderThumbnails();
});

$('#imageEditorThumbnails').on('click', '.btn-dup', function () {
    const i = $(this).closest('[data-index]').data('index');
    const newImg = { ...window.imageList[i] };
    window.imageList.splice(i + 1, 0, newImg);
    renderThumbnails();
});

$('#imageEditorThumbnails').on('click', '.btn-left', function () {
    const i = $(this).closest('[data-index]').data('index');
    if (i > 0) {
        [window.imageList[i - 1], window.imageList[i]] = [window.imageList[i], window.imageList[i - 1]];
        renderThumbnails();
    }
});

$('#imageEditorThumbnails').on('click', '.btn-right', function () {
    const i = $(this).closest('[data-index]').data('index');
    if (i < window.imageList.length - 1) {
        [window.imageList[i], window.imageList[i + 1]] = [window.imageList[i + 1], window.imageList[i]];
        renderThumbnails();
    }
});

$('#imageEditorThumbnails').on('click', 'img', function () {
    const src = $(this).attr('src');
    $('#mainImagePreview').attr('src', src);
});

$('#imageEditorDone').click(function () {
    $('#imageUploadModal').modal('hide');
    $('#selectedImagesPreview').show();
    const wrapper = $('#previewImagesWrapper');
    wrapper.html('');
    window.imageList.forEach((img, i) => {
        wrapper.append(`<img src="${img.src}" class="img-thumbnail" style="width:100px;height:100px;">`);
    });
});

$('#clearImagesBtn').click(function () {
    window.imageList = [];
    $('#selectedImagesPreview').hide();
    $('#previewImagesWrapper').html('');
});

// Initialize Sortable only if the element exists
$(document).ready(function() {
    const thumbnailsElement = document.getElementById('imageEditorThumbnails');
    if (thumbnailsElement) {
        new Sortable(thumbnailsElement, {
            animation: 150,
            onEnd: function (evt) {
                const oldIndex = evt.oldIndex;
                const newIndex = evt.newIndex;
                if (oldIndex === newIndex) return;

                const movedItem = window.imageList.splice(oldIndex, 1)[0];
                window.imageList.splice(newIndex, 0, movedItem);
                renderThumbnails();
            }
        });
    }
});
