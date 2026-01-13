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
    <link rel="stylesheet" href="{{ asset('assets/css/components/skeleton-loading.css') }}">
@endsection

<section class="newFeedSec">
    <div class="newFeedSecInner">
        <!-- Left Sidebar - Profile Card -->
        <div class="newFeedSecInnerCol">
            @include('user.components.profile-card')
        </div>

        <!-- Main Feed -->
        <div class="newFeedSecInnerCol" id="mainFeedColumn">
            @include('user.components.post-create')

            <!-- Sort Options -->
            <div class="feed-sort-container mb-3">
                <div class="btn-group" role="group">
                    <input type="radio" class="btn-check" name="feedSort" id="sortLatest" value="latest" checked>
                    <label class="btn btn-outline-primary btn-sm" for="sortLatest">Latest</label>

                    <input type="radio" class="btn-check" name="feedSort" id="sortPopular" value="popular">
                    <label class="btn btn-outline-primary btn-sm" for="sortPopular">Popular</label>

                    <input type="radio" class="btn-check" name="feedSort" id="sortOldest" value="oldest">
                    <label class="btn btn-outline-primary btn-sm" for="sortOldest">Oldest</label>
                </div>
            </div>

            <!-- Posts Container -->
            <div id="postsContainer">
                <!-- Skeleton Loading (shown initially) -->
                @include('user.components.skeleton-loading')
            </div>

            <!-- Loading Indicator for Infinite Scroll -->
            <div id="loadingIndicator" class="text-center py-4 d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>

            <!-- No More Posts Message -->
            <div id="noMorePosts" class="text-center py-4 text-muted d-none">
                <i class="fa-regular fa-newspaper fa-2x mb-2"></i>
                <p>You've reached the end of your feed</p>
            </div>

            <!-- Empty State (shown if no posts) -->
            <div id="emptyState" class="card mb-3 d-none">
                <div class="card-body text-center py-5">
                    <i class="fa-regular fa-newspaper fa-3x text-muted mb-3"></i>
                    <h5 class="text-muted">No posts yet</h5>
                    <p class="text-muted">Start sharing your thoughts with your network!</p>
                    <button class="btn btn-primary" id="createFirstPost">Create Post</button>
                </div>
            </div>
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
@include('user.components.reactions-modal')
@include('user.components.shares-modal')
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<!-- Core functionality scripts -->
<script src="{{ asset('assets/js/components/modal.js') }}"></script>
<script src="{{ asset('assets/js/components/emoji-picker.js') }}" type="module"></script>
<script src="{{ asset('assets/js/components/image-upload.js') }}"></script>
<script src="{{ asset('assets/js/components/cropper-editor.js') }}"></script>

<!-- Main feed functionality -->
<script type="module">
    import { initializeFeed } from "{{ asset('assets/js/components/feed/feed-manager.js') }}";
    import { togglePostText } from "{{ asset('assets/js/components/post/expand.js') }}";
    import {
        showReactions,
        hideReactions,
        cancelHide,
        applyReaction,
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
    import { connectUser } from "{{ asset('assets/js/components/connections.js') }}";

    // Make all functions globally available
    window.togglePostText = togglePostText;
    window.showReactions = showReactions;
    window.hideReactions = hideReactions;
    window.cancelHide = cancelHide;
    window.applyReaction = applyReaction;
    window.handleReactionClick = handleReactionClick;
    window.toggleComments = toggleComments;
    window.toggleCommentButton = toggleCommentButton;
    window.postComment = postComment;
    window.toggleReplyInput = toggleReplyInput;
    window.toggleReplyButton = toggleReplyButton;
    window.postReply = postReply;
    window.loadMoreComments = loadMoreComments;
    window.deleteComment = deleteComment;
    window.deletePost = deletePost;
    window.editPost = editPost;
    window.sharePost = sharePost;
    window.sendPost = sendPost;
    window.repostWithThoughts = repostWithThoughts;
    window.instantRepost = instantRepost;
    window.copyPostLink = copyPostLink;
    window.connectUser = connectUser;

    // Initialize feed with infinite scroll
    document.addEventListener('DOMContentLoaded', function() {
        initializeFeed();

        // Handle sort change
        document.querySelectorAll('input[name="feedSort"]').forEach(radio => {
            radio.addEventListener('change', function() {
                initializeFeed(this.value);
            });
        });
    });
</script>
@endsection
