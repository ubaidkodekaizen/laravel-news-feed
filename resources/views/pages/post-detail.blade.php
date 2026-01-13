@extends('layouts.main')

@section('content')
@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/components/news-feed.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/profile-card.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/post/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/post/reactions.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/post/comments.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/post-images.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/post-actions.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/share-repost.css') }}">
@endsection

<section class="newFeedSec">
    <div class="newFeedSecInner">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Back Button -->
                    <div class="mb-3">
                        <a href="{{ route('news-feed') }}" class="btn btn-outline-secondary btn-sm">
                            <i class="fa-solid fa-arrow-left me-2"></i> Back to Feed
                        </a>
                    </div>

                    <!-- Single Post -->
                    @if(isset($post))
                        @php
                            $isOwner = auth()->check() && auth()->id() === ($post['user']['id'] ?? null);
                        @endphp

                        @include('user.components.post-card.index', [
                            'post' => $post,
                            'isOwner' => $isOwner,
                            'showAllComments' => true
                        ])
                    @else
                        <div class="card">
                            <div class="card-body text-center py-5">
                                <i class="fa-regular fa-newspaper fa-3x text-muted mb-3"></i>
                                <h5 class="text-muted">Post not found</h5>
                                <p class="text-muted">This post may have been deleted or you don't have permission to view it.</p>
                                <a href="{{ route('news-feed') }}" class="btn btn-primary">Go to Feed</a>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

@include('user.components.reactions-modal')
@include('user.components.shares-modal')
@endsection

@section('scripts')
<script type="module">
    import { togglePostText } from "{{ asset('assets/js/components/post/expand.js') }}";
    import {
        showReactions,
        hideReactions,
        cancelHide,
        applyReaction,
        handleReactionClick,
        showReactionsList
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
        copyPostLink,
        showSharesList
    } from "{{ asset('assets/js/components/post/share-repost.js') }}";

    // Make functions globally available
    window.togglePostText = togglePostText;
    window.showReactions = showReactions;
    window.hideReactions = hideReactions;
    window.cancelHide = cancelHide;
    window.applyReaction = applyReaction;
    window.handleReactionClick = handleReactionClick;
    window.showReactionsList = showReactionsList;
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
    window.showSharesList = showSharesList;

    // Auto-open comments section on detail page
    document.addEventListener('DOMContentLoaded', function() {
        const postId = '{{ $post["id"] ?? "" }}';
        if (postId) {
            // Open comments by default on single post page
            const commentSection = document.getElementById(`commentSection-${postId}`);
            if (commentSection) {
                commentSection.style.display = 'block';
            }
        }
    });
</script>
@endsection
