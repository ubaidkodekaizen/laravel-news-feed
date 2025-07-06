@extends('layouts.main')
@section('content')
@section('styles')
  <link rel="stylesheet" href="{{ asset('assets/css/components/news-feed.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/post-card.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/reactions.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/profile-card.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/modal.css') }}">
@endsection

<section class="newFeedSec">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-3">
                @include('user.components.profile-card')
            </div>

            <div class="col-lg-9">
                @include('user.components.post-create')
                @include('user.components.post-card')
                @include('user.components.post-card')
            </div>
        </div>
    </div>
</section>
@include('user.components.post-modal')
@include('user.components.image-upload-modal')
@include('user.components.image-edit-modal')
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
    <!-- CropperJS CSS & JS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

  <script src="{{ asset('assets/js/components/modal.js') }}"></script>
  <script src="{{ asset('assets/js/components/post-expand.js') }}"></script>
  <script src="{{ asset('assets/js/components/reactions.js') }}"></script>
  <script src="{{ asset('assets/js/components/emoji-picker.js') }}" type="module"></script>
  <script src="{{ asset('assets/js/components/image-upload.js') }}"></script>
  <script src="{{ asset('assets/js/components/cropper-editor.js') }}"></script>
  <script src="{{ asset('assets/js/components/toggle.js') }}"></script>

@endsection
