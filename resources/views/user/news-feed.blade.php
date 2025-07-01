@extends('layouts.main')
<link rel="stylesheet" href="{{asset('assets/css/news-feed.css')}}">
<!-- jQuery Emoji Picker CSS -->
<link href="https://cdn.jsdelivr.net/npm/emoji-picker-jquery@1.4.2/css/emoji.css" rel="stylesheet">

@section('content')
    <section class="newFeedSec">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="card mb-3">
                        <div class="card-body d-flex align-items-center">
                            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png"
                                alt="User" class="rounded-circle me-2" width="40" height="40">
                            <button class="form-control text-start" id="openPostModal">Start a post</button>
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

                        <div id="mediaPreview" class="mt-2"></div>

                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Post</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@section('scripts')

    <script>
        $(document).ready(function () {
            // Open modal
            $('#openPostModal').click(function () {
                $('#postModal').modal('show');
            });

            // Trigger file input
            $('#uploadMediaBtn').click(function () {
                $('#mediaUpload').click();
            });

            // Show media preview
            $('#mediaUpload').on('change', function (e) {
                const file = e.target.files[0];
                const preview = $('#mediaPreview');
                preview.html('');
                if (!file) return;

                const url = URL.createObjectURL(file);
                if (file.type.startsWith('image')) {
                    preview.append(`<img src="${url}" class="img-fluid rounded" style="max-height: 300px;">`);
                } else if (file.type.startsWith('video')) {
                    preview.append(`<video controls class="w-100" style="max-height: 300px;"><source src="${url}"></video>`);
                }
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
            // Toggle picker position
            const rect = emojiBtn.getBoundingClientRect();
            picker.style.top = `${rect.bottom + window.scrollY}px`;
            picker.style.left = `${rect.left + window.scrollX}px`;
            picker.style.display = picker.style.display === 'none' ? 'block' : 'none';
        });

        picker.addEventListener('emoji-click', event => {
            textArea.value += event.detail.unicode;
            picker.style.display = 'none';
        });

        // Hide picker if modal is closed
        const modal = document.getElementById('postModal');
        modal.addEventListener('hidden.bs.modal', () => {
            picker.style.display = 'none';
        });
    </script>

@endsection