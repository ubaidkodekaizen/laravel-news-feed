@extends('layouts.main')
@section('content')
@section('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.13/cropper.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/css/components/news-feed.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/profile-card.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/post/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/post/reactions.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components/post/comments.css') }}">

    <style>
        #feedProfileCard {
            padding: 10.67px 10.67px 10.67px 10.67px;
            border-radius: 8px;
            border: 1px solid #E9EBF0;
            background: #FFFFFF;
            position: sticky;
            top: 120px;
        }

        #sidebarWidgetWrapper {
            position: sticky;
            top: 120px;
        }

        .profile_card_details_inner {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: start;
            gap: 19.67px;
        }

        .profile_card_details_inner .profile_card_details_inner_box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 100%;
        }

        .profile_card_details_inner .profile_card_details_inner_box h4 {
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 18.67px;
            line-height: 100%;
            color: #17272F;
            margin: 0;
        }

        .profile_card_details_inner .profile_card_details_inner_box p {
            font-family: "Inter", sans-serif;
            font-weight: 500;
            font-size: 18.67px;
            line-height: 100%;
            color: #1C3395;
            margin: 0;
        }


        .profile_card_details .divider {
            background: #EDF0F5;
            height: 1.33px;
            width: 100%;
            margin: 19.67px 0 21.33px 0;
        }
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
    <div class="container">
        <div class="row">
            <!-- Left Sidebar - Profile Card -->
            <div class="col-lg-3 mb-3">
                @include('user.components.profile-card')
            </div>

            <!-- Main Feed -->
            <div class="col-lg-6 mb-3">
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
            <div class="col-lg-3 mb-3">
                @include('user.components.sidebar-widgets')
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
        applyReaction
    } from "{{ asset('assets/js/components/post/reactions.js') }}";
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

    // Make functions available globally
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
