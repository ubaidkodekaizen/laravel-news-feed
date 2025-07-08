@extends('layouts.main')
@section('content')
@section('styles')
  <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('assets/css/components/news-feed.css') }}">
  {{-- <link rel="stylesheet" href="{{ asset('assets/css/components/post-card.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/reactions.css') }}"> --}}
  <link rel="stylesheet" href="{{ asset('assets/css/components/profile-card.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/modal.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/post/main.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/post/reactions.css') }}">
  <link rel="stylesheet" href="{{ asset('assets/css/components/post/comments.css') }}">
@endsection

@php
    $staticPost = [
        'id' => 1,
        'content' => 'Just launched a new feature on our LinkedIn-style post system in Laravel! 🚀 Feedback welcome! Here\'s some more detailed explanation about how we\'ve implemented full emoji support, multi-image upload with crop/edit/duplicate/reorder and integrated commenting in Laravel Blade views.',
        'created_at' => now()->subHours(1),
        'likes_count' => 24,
        'comments_count' => 5,
        'user' => [
            'name' => 'Jahanzaib Ansari',
            'position' => 'Frontend Developer @ Koder360',
            'avatar' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png'
        ],
        'comments' => [
            [
                'id' => 1,
                'content' => 'Great job! Keep building cool stuff. 🔥',
                'created_at' => now()->subMinutes(45),
                'user' => [
                    'name' => 'Ali Raza',
                    'avatar' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png'
                ]
            ],
            [
                'id' => 2,
                'content' => 'Excited to try this out soon!',
                'created_at' => now()->subMinutes(30),
                'user' => [
                    'name' => 'Sana Sheikh',
                    'avatar' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png'
                ]
            ]
        ],
        'reactions' => [
            ['emoji' => '👍', 'type' => 'Like'],
            ['emoji' => '❤️', 'type' => 'Love'],
            ['emoji' => '😲', 'type' => 'Wow']
        ]
    ];
@endphp

<section class="newFeedSec">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-3">
                @include('user.components.profile-card')
            </div>

            <div class="col-lg-9">
                @include('user.components.post-create')
                @include('user.components.post-card.index', [
                    'post' => $staticPost,
                    'isOwner' => true
                ])
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

    <script src="{{ asset('assets/js/components/modal.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/components/post-expand.js') }}"></script> --}}
    {{-- <script src="{{ asset('assets/js/components/reactions.js') }}"></script> --}}
    <script src="{{ asset('assets/js/components/emoji-picker.js') }}" type="module"></script>
    <script src="{{ asset('assets/js/components/image-upload.js') }}"></script>
    <script src="{{ asset('assets/js/components/cropper-editor.js') }}"></script>
    <script src="{{ asset('assets/js/components/toggle.js') }}"></script>

    <script type="module">
        import { togglePostText } from "{{ asset('assets/js/components/post/expand.js') }}";
        import { showReactions, hideReactions, cancelHide, applyReaction } from "{{ asset('assets/js/components/post/reactions.js') }}";
        import {
            toggleComments,
            toggleCommentButton,
            postComment,
            toggleReplyInput,
            toggleReplyButton,
            postReply,
            loadMoreComments,
            likeComment
        } from "{{ asset('assets/js/components/post/comments.js') }}";

        // Make functions available globally if needed
        window.togglePostText = togglePostText;
        window.showReactions = showReactions;
        window.hideReactions = hideReactions;
        window.cancelHide = cancelHide;
        window.applyReaction = applyReaction;
        window.toggleComments = toggleComments;
        window.toggleCommentButton = toggleCommentButton;
        window.postComment = postComment;
        window.toggleReplyInput = toggleReplyInput;
        window.toggleReplyButton = toggleReplyButton;
        window.postReply = postReply;
        window.loadMoreComments = loadMoreComments;
        window.likeComment = likeComment;
    </script>

@endsection
