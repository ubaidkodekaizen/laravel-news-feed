/**
 * Improved Media Upload System
 * Handles photo and video uploads with 4MB validation
 */

// Make mediaList globally accessible
window.mediaList = [];

const MAX_FILE_SIZE = 4 * 1024 * 1024; // 4MB in bytes
const MAX_FILES = 10;

function renderThumbnails() {
    const wrapper = $('#mediaEditorThumbnails');
    wrapper.html('');

    window.mediaList.forEach((media, index) => {
        const isVideo = media.type === 'video';
        const sizeInMB = (media.size / (1024 * 1024)).toFixed(2);
        const sizeClass = media.size > MAX_FILE_SIZE ? 'size-error' :
                         media.size > MAX_FILE_SIZE * 0.8 ? 'size-warning' : '';

        const thumbnailContent = isVideo
            ? `<video class="media-thumbnail" src="${media.src}"></video>
               <div class="video-indicator"><i class="fa-solid fa-play"></i></div>`
            : `<img src="${media.src}" class="media-thumbnail" />`;

        wrapper.append(`
            <div class="col-lg-6">
                <div class="position-relative border p-1 media-box" data-index="${index}" data-type="${media.type}">
                    ${thumbnailContent}
                    <div class="file-size-indicator ${sizeClass}">${sizeInMB} MB</div>
                    <div class="d-flex justify-content-between mt-1">
                        <span class="text-muted small">${String(index + 1).padStart(2, '0')}</span>
                        <div>
                            <button class="btn btn-sm btn-light btn-left" title="Move Left"><i class="fa-solid fa-arrow-left"></i></button>
                            <button class="btn btn-sm btn-light btn-right" title="Move Right"><i class="fa-solid fa-arrow-right"></i></button>
                            ${!isVideo ? '<button class="btn btn-sm btn-light btn-edit" title="Edit"><i class="fa-solid fa-pen"></i></button>' : ''}
                            <button class="btn btn-sm btn-light btn-dup" title="Duplicate"><i class="fa-solid fa-copy"></i></button>
                            <button class="btn btn-sm btn-light btn-del" title="Delete"><i class="fa-solid fa-trash text-danger"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        `);
    });

    updateMainPreview();
}

function updateMainPreview() {
    if (window.mediaList.length === 0) {
        $('#mainMediaPreviewContainer #mainImagePreview').addClass('d-none');
        $('#mainMediaPreviewContainer #mainVideoPreview').addClass('d-none');
        $('#mainMediaPreviewContainer #noMediaPlaceholder').removeClass('d-none');
        return;
    }

    $('#mainMediaPreviewContainer #noMediaPlaceholder').addClass('d-none');

    const firstMedia = window.mediaList[0];

    if (firstMedia.type === 'video') {
        $('#mainImagePreview').addClass('d-none');
        const videoEl = $('#mainVideoPreview');
        videoEl.attr('src', firstMedia.src);
        videoEl.removeClass('d-none');
    } else {
        $('#mainVideoPreview').addClass('d-none');
        const imgEl = $('#mainImagePreview');
        imgEl.attr('src', firstMedia.src);
        imgEl.removeClass('d-none');
    }
}

// Open media modal when clicking photo or video buttons
$('#uploadPhotoBtn, #uploadVideoBtn, #editMediaBtn').click(function() {
    const isVideoBtn = $(this).attr('id') === 'uploadVideoBtn';

    if (isVideoBtn) {
        $('#multiMediaInput').attr('accept', 'video/*');
    } else {
        $('#multiMediaInput').attr('accept', 'image/*,video/*');
    }

    $('#multiMediaInput').click();
});

$('#addMediaBtn').click(() => {
    $('#multiMediaInput').attr('accept', 'image/*,video/*');
    $('#multiMediaInput').click();
});

$('#multiMediaInput').on('change', function(e) {
    const files = Array.from(e.target.files);

    if (files.length === 0) return;

    // Check total files limit
    if (window.mediaList.length + files.length > MAX_FILES) {
        showNotification(`You can only upload up to ${MAX_FILES} files.`, 'error');
        return;
    }

    let hasErrors = false;
    const validFiles = [];

    files.forEach(file => {
        // Validate file size
        if (file.size > MAX_FILE_SIZE) {
            showNotification(`File "${file.name}" exceeds 4MB limit and was skipped.`, 'error');
            hasErrors = true;
            return;
        }

        // Validate file type
        const isImage = file.type.startsWith('image/');
        const isVideo = file.type.startsWith('video/');

        if (!isImage && !isVideo) {
            showNotification(`File "${file.name}" is not a valid image or video.`, 'error');
            hasErrors = true;
            return;
        }

        validFiles.push(file);
    });

    // Process valid files
    validFiles.forEach(file => {
        const url = URL.createObjectURL(file);
        const type = file.type.startsWith('video/') ? 'video' : 'image';

        window.mediaList.push({
            src: url,
            file: file,
            type: type,
            size: file.size,
            name: file.name
        });
    });

    if (validFiles.length > 0) {
        renderThumbnails();
        $('#mediaUploadModal').modal('show');
        $('#postModal').modal('hide');
    }

    // Clear input
    $(this).val('');
});

$('#mediaUploadModal').on('hidden.bs.modal', function() {
    $('#postModal').modal('show');
});

$('#mediaEditorThumbnails').on('click', '.btn-del', function() {
    const i = $(this).closest('[data-index]').data('index');

    // Revoke object URL to free memory
    if (window.mediaList[i] && window.mediaList[i].src) {
        URL.revokeObjectURL(window.mediaList[i].src);
    }

    window.mediaList.splice(i, 1);
    renderThumbnails();
});

$('#mediaEditorThumbnails').on('click', '.btn-dup', function() {
    const i = $(this).closest('[data-index]').data('index');
    const media = window.mediaList[i];

    // Check file limit
    if (window.mediaList.length >= MAX_FILES) {
        showNotification(`Maximum ${MAX_FILES} files allowed.`, 'error');
        return;
    }

    // Create a copy
    const newMedia = { ...media };
    window.mediaList.splice(i + 1, 0, newMedia);
    renderThumbnails();
});

$('#mediaEditorThumbnails').on('click', '.btn-left', function() {
    const i = $(this).closest('[data-index]').data('index');
    if (i > 0) {
        [window.mediaList[i - 1], window.mediaList[i]] = [window.mediaList[i], window.mediaList[i - 1]];
        renderThumbnails();
    }
});

$('#mediaEditorThumbnails').on('click', '.btn-right', function() {
    const i = $(this).closest('[data-index]').data('index');
    if (i < window.mediaList.length - 1) {
        [window.mediaList[i], window.mediaList[i + 1]] = [window.mediaList[i + 1], window.mediaList[i]];
        renderThumbnails();
    }
});

$('#mediaEditorThumbnails').on('click', '.media-box', function() {
    const index = $(this).data('index');
    const media = window.mediaList[index];

    if (!media) return;

    $('#mainMediaPreviewContainer #noMediaPlaceholder').addClass('d-none');

    if (media.type === 'video') {
        $('#mainImagePreview').addClass('d-none');
        const videoEl = $('#mainVideoPreview');
        videoEl.attr('src', media.src);
        videoEl.removeClass('d-none');
    } else {
        $('#mainVideoPreview').addClass('d-none');
        const imgEl = $('#mainImagePreview');
        imgEl.attr('src', media.src);
        imgEl.removeClass('d-none');
    }

    // Highlight selected
    $('.media-box').removeClass('active');
    $(this).addClass('active');
});

$('#mediaEditorDone').click(function() {
    if (window.mediaList.length === 0) {
        showNotification('Please add at least one photo or video.', 'error');
        return;
    }

    $('#mediaUploadModal').modal('hide');
    $('#selectedMediaPreview').show();
    $('#mediaCount').text(window.mediaList.length);

    const wrapper = $('#previewMediaWrapper');
    wrapper.html('');

    window.mediaList.forEach((media, i) => {
        const isVideo = media.type === 'video';
        const content = isVideo
            ? `<video src="${media.src}" class="img-fluid"></video>
               <div class="video-overlay"><i class="fa-solid fa-play"></i></div>`
            : `<img src="${media.src}" class="img-fluid">`;

        wrapper.append(`
            <div class="media-preview-item">
                ${content}
            </div>
        `);
    });
});

$('#clearMediaBtn').click(function() {
    // Revoke all object URLs
    window.mediaList.forEach(media => {
        if (media.src) {
            URL.revokeObjectURL(media.src);
        }
    });

    window.mediaList = [];
    $('#selectedMediaPreview').hide();
    $('#previewMediaWrapper').html('');
    $('#mediaEditorThumbnails').html('');
    $('#mainImagePreview').attr('src', '').addClass('d-none');
    $('#mainVideoPreview').attr('src', '').addClass('d-none');
    $('#mainMediaPreviewContainer #noMediaPlaceholder').removeClass('d-none');
});

// Character count for post text
$('#postText').on('input', function() {
    const len = $(this).val().length;
    const maxLen = 10000;

    $('#charCount').text(len);

    if (len > maxLen * 0.9) {
        $('#charCount').parent().addClass('warning');
    } else {
        $('#charCount').parent().removeClass('warning');
    }

    if (len >= maxLen) {
        $('#charCount').parent().addClass('error');
        $(this).val($(this).val().substring(0, maxLen));
    } else {
        $('#charCount').parent().removeClass('error');
    }
});

// Visibility dropdown
$('.visibility-option').click(function(e) {
    e.preventDefault();

    const visibility = $(this).data('visibility');
    const iconClass = visibility === 'public' ? 'fa-globe' :
                     visibility === 'private' ? 'fa-lock' : 'fa-user-group';
    const text = visibility.charAt(0).toUpperCase() + visibility.slice(1);

    $('#visibilityIcon').attr('class', `fa-solid ${iconClass}`);
    $('#visibilityText').text(text);
    $('#postModal').data('visibility', visibility);

    // Update active state
    $('.visibility-option').removeClass('active');
    $(this).addClass('active');
});

// Initialize Sortable for media reordering
$(document).ready(function() {
    const thumbnailsElement = document.getElementById('mediaEditorThumbnails');
    if (thumbnailsElement && typeof Sortable !== 'undefined') {
        new Sortable(thumbnailsElement, {
            animation: 150,
            handle: '.media-box',
            onEnd: function(evt) {
                const oldIndex = evt.oldIndex;
                const newIndex = evt.newIndex;
                if (oldIndex === newIndex) return;

                const movedItem = window.mediaList.splice(oldIndex, 1)[0];
                window.mediaList.splice(newIndex, 0, movedItem);
                renderThumbnails();
            }
        });
    }
});

function showNotification(message, type = 'info') {
    if ($('#notification-container').length === 0) {
        $('body').append('<div id="notification-container" style="position: fixed; top: 20px; right: 20px; z-index: 9999;"></div>');
    }

    const alertClass = type === 'success' ? 'alert-success' :
                      type === 'error' ? 'alert-danger' :
                      'alert-info';

    const notification = $(`
        <div class="alert ${alertClass} alert-dismissible fade show" role="alert" style="min-width: 250px;">
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `);

    $('#notification-container').append(notification);

    setTimeout(() => {
        notification.alert('close');
    }, 3000);
}

// Export for use in other modules
window.renderThumbnails = renderThumbnails;
