@extends('layouts.main')
@section('content')
@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/components/news-feed.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/profile-card.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/post-create.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/sidebar.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/post/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/post/reactions.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/post/comments.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/post-images.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/post-actions.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/share-repost.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/stats-layout.css') }}">
    <style>

    </style>
@endsection

@php
    $staticPost = [
        'id' => 1,
        'content' => 'He said, Design is not just what it looks and feels like. Design is how it work

#stevejobs #design #apple',
        'created_at' => now()->subHours(7),
        'likes_count' => 172,
        'comments_count' => 10,
        'user' => [
            'name' => 'Ubaid Khan',
            'position' => 'CEO@kode kaizen',
            'avatar' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png',
        ],
        'media' => [
            [
                'media_type' => 'image',
                'media_url' => 'https://images.unsplash.com/photo-1531297484001-80022131f5a1?w=800&q=80',
                'mime_type' => 'image/jpeg',
            ],
        ],
        'comments' => [
            [
                'id' => 1,
                'content' => 'Great job! Keep building cool stuff. 🔥',
                'created_at' => now()->subMinutes(45),
                'user' => [
                    'name' => 'Ali Raza',
                    'avatar' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png',
                ],
            ],
            [
                'id' => 2,
                'content' => 'Excited to try this out soon!',
                'created_at' => now()->subMinutes(30),
                'user' => [
                    'name' => 'Sana Sheikh',
                    'avatar' => 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png',
                ],
            ],
        ],
        'reactions' => [
            ['emoji' => '👍', 'type' => 'Like'],
            ['emoji' => '❤️', 'type' => 'Love'],
            ['emoji' => '😲', 'type' => 'Wow'],
        ],
    ];
@endphp

<section class="newFeedSec">
    <div class="newFeedSecInner">

        <!-- Left Sidebar - Profile Card -->
        <div class="newFeedSecInnerCol">
            @include('user.components.profile-card')
        </div>

        <!-- Main Feed -->
        <div class="newFeedSecInnerCol">
            @include('user.components.post-create')
            {{-- Replace the static post block with this loop --}}
            @forelse($posts as $post)
                @include('user.components.post-card.index', [
                    'post' => $post,
                    'isOwner' => auth()->check() && auth()->id() === ($post['user']['id'] ?? null),
                ])
            @empty
                <div class="card mb-3">
                    <div class="card-body text-center text-muted">
                        No posts to show. Try creating a new post.
                    </div>
                </div>
            @endforelse

            {{-- Pagination --}}
            @if (isset($pagination) && method_exists($pagination, 'links'))
                <div class="mt-3">
                    {!! $pagination->links() !!}
                </div>
            @endif

        </div>

        <!-- Right Sidebar - Widgets -->
        <div class="newFeedSecInnerCol">
            @include('user.components.sidebar-widgets')
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
<script src="{{ asset('assets/js/components/emoji-picker.js') }}" type="module"></script>
<script src="{{ asset('assets/js/components/image-upload.js') }}"></script>
<script src="{{ asset('assets/js/components/cropper-editor.js') }}"></script>
<script src="{{ asset('assets/js/components/toggle.js') }}"></script>
<script type="module">
    import {
        togglePostText
    } from "{{ asset('assets/js/components/post/expand.js') }}";
    import {
        showReactions,
        hideReactions,
        cancelHide,
        applyReaction,
        removeReaction,
        handleReactionClick
    } from "{{ asset('assets/js/components/post/reactions.js') }}";
    import {
        toggleComments,
        toggleCommentButton,
        postComment,
        toggleReplyInput,
        toggleReplyButton,
        postReply,
        loadMoreComments,
        likeComment,
        deleteComment
    } from "{{ asset('assets/js/components/post/comments.js') }}";
    import {
        deletePost,
        editPost
    } from "{{ asset('assets/js/components/post/post-actions.js') }}";
    import {
        sharePost,
        sendPost,
        repostWithThoughts,
        instantRepost,
        copyPostLink
    } from "{{ asset('assets/js/components/post/share-repost.js') }}";

    import {
        connectUser
    } from "{{ asset('assets/js/components/connections.js') }}"; // ADD THIS

    // Make all functions globally available
    window.togglePostText = togglePostText;
    window.showReactions = showReactions;
    window.hideReactions = hideReactions;
    window.cancelHide = cancelHide;
    window.applyReaction = applyReaction;
    window.removeReaction = removeReaction;
    window.handleReactionClick = handleReactionClick;
    window.toggleComments = toggleComments;
    window.toggleCommentButton = toggleCommentButton;
    window.postComment = postComment;
    window.toggleReplyInput = toggleReplyInput;
    window.toggleReplyButton = toggleReplyButton;
    window.postReply = postReply;
    window.loadMoreComments = loadMoreComments;
    window.likeComment = likeComment;
    window.deleteComment = deleteComment;
    window.deletePost = deletePost;
    window.editPost = editPost;
    window.sharePost = sharePost;
    window.sendPost = sendPost;
    window.repostWithThoughts = repostWithThoughts;
    window.instantRepost = instantRepost;
    window.copyPostLink = copyPostLink;
     window.connectUser = connectUser;  // ADD THIS
</script>
@endsection
