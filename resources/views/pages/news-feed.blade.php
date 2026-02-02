@extends('layouts.main')
@section('content')
@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css">
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
    <link rel="stylesheet" href="{{ asset('assets/css/components/lightbox.css') }}">
@endsection

<section class="newFeedSec">
    <div class="newFeedSecInner">
        <!-- Left Sidebar - Profile Card -->
        <div class="newFeedSecInnerCol newFeedSecInnerColFirst">
            @include('user.components.profile-card')
        </div>

        <!-- Main Feed -->
        <div class="newFeedSecInnerCol" id="mainFeedColumn">
            @include('user.components.post-create')

            <!-- Sort Options -->
            <div class="feed-sort-container mb-3">
                <label for="feedSort">Filter by:</label>
                <select name="feedSort" id="feedSort" class="form-select form-select-sm">
                    <option value="latest" selected>Latest</option>
                    <option value="popular">Popular</option>
                    <option value="oldest">Oldest</option>
                </select>
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
        <div class="newFeedSecInnerCol newFeedSecInnerColLast">
            @include('user.components.sidebar-widgets')
        </div>
    </div>
</section>

@include('user.components.post-modal')
@include('user.components.image-upload-modal')
@include('user.components.image-edit-modal')
@include('user.components.reactions-modal')
@include('user.components.shares-modal')
@include('user.components.profile-views-modal')
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.js"></script>

<!-- Core functionality scripts -->
<script src="{{ asset('assets/js/components/modal.js') }}"></script>
<script src="{{ asset('assets/js/components/emoji-picker.js') }}" type="module"></script>
<script src="{{ asset('assets/js/components/image-upload.js') }}"></script>
<script src="{{ asset('assets/js/components/cropper-editor.js') }}"></script>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>

<!-- Main feed functionality -->
<script type="module">
    import {
        initializeFeed
    } from "{{ asset('assets/js/components/feed/feed-manager.js') }}";
    import {
        togglePostText
    } from "{{ asset('assets/js/components/post/expand.js') }}";
    import {
        showReactions,
        hideReactions,
        cancelHide,
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
        deleteComment,
        likeComment,
        editComment
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
    import {
        connectUser
    } from "{{ asset('assets/js/components/connections.js') }}";
    import { DEFAULT_EMOJIS } from "{{ asset('assets/js/config/emoji-config.js') }}";


    // Make globally available
    window.showSharesList = showSharesList;
    window.likeComment = likeComment;
    window.editComment = editComment;
    // Make all functions globally available
    window.togglePostText = togglePostText;
    window.showReactions = showReactions;
    window.hideReactions = hideReactions;
    window.showReactionsList = showReactionsList;
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
        // initialize with default sort
        initializeFeed(document.getElementById('feedSort').value);

        // Handle sort change
        const feedSortSelect = document.getElementById('feedSort');
        feedSortSelect.addEventListener('change', function() {
            initializeFeed(this.value);
        });

        // Initialize emoji pickers for comment inputs
        initializeCommentEmojiPickers();
        const swiperEl = document.querySelector('#feedAdCard .swiper');
        const wrapper = swiperEl.querySelector('.swiper-wrapper');
        const slides = wrapper.querySelectorAll('.swiper-slide');

        if (slides.length === 1) {
            for (let i = 0; i < 3; i++) {
                const clone = slides[0].cloneNode(true);
                wrapper.appendChild(clone);
            }
        }

        const adSwiper = new Swiper('#feedAdCard .swiper', {
            slidesPerView: 1,
            // loopedSlides: 2,
            spaceBetween: 0,
            loop: true,
            autoplay: {
                delay: 5000,
                disableOnInteraction: false,
                pauseOnMouseEnter: true,
            },
            speed: 800,
            // effect: 'fade',
            pagination: {
                el: '#feedAdCard .swiper-pagination',
                clickable: true,
            },
        });
    });



    // Function to initialize emoji pickers for dynamically loaded comments
    function initializeCommentEmojiPickers() {
        document.querySelectorAll('.emoji-picker-btn').forEach(btn => {
            if (!btn.dataset.initialized) {
                btn.addEventListener('click', function(e) {
                    e.preventDefault();
                    const input = this.closest('.comment-input-container').querySelector(
                        'input[type="text"]');
                    if (input) {
                        showEmojiPicker(input);
                    }
                });
                btn.dataset.initialized = 'true';
            }
        });
    }

    // Emoji picker function using shared emoji config
    function showEmojiPicker(input) {
        const picker = document.createElement('div');
        picker.className = 'emoji-picker-popup';
        picker.innerHTML = DEFAULT_EMOJIS.map(e => `<span class="emoji-option">${e}</span>`).join('');

        picker.style.cssText = `
        position: absolute;
        background: white;
        border: 1px solid #e4e6eb;
        border-radius: 8px;
        padding: 8px;
        display: flex;
        gap: 4px;
        flex-wrap: wrap;
        max-width: 200px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        z-index: 1000;
    `;

        const inputRect = input.getBoundingClientRect();
        picker.style.top = (inputRect.bottom + window.scrollY + 5) + 'px';
        picker.style.left = inputRect.left + 'px';

        picker.querySelectorAll('.emoji-option').forEach(emoji => {
            emoji.style.cssText = 'cursor: pointer; padding: 4px; font-size: 20px;';
            emoji.addEventListener('click', function() {
                input.value += this.textContent;
                input.dispatchEvent(new Event('input'));
                input.focus();
                picker.remove();
            });
        });

        document.body.appendChild(picker);

        setTimeout(() => {
            document.addEventListener('click', function closeOnClickOutside(e) {
                if (!picker.contains(e.target) && e.target !== input) {
                    picker.remove();
                    document.removeEventListener('click', closeOnClickOutside);
                }
            });
        }, 100);
    }

    // Re-initialize emoji pickers when comments are loaded
    window.addEventListener('commentsLoaded', initializeCommentEmojiPickers);
</script>
@endsection
