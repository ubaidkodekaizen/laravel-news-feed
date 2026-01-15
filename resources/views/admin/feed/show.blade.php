@extends('admin.layouts.main')
<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
<style>
    body {
        background: #fafbff !important;
    }

    .card {
        border: 2px solid #e9ebf0 !important;
        border-radius: 14.66px !important;
        overflow: hidden;
        margin-bottom: 20px;
    }

    .card-body {
        background: #27357208;
        padding: 30px 30px 30px !important;
    }

    .card-header:first-child {
        border: 0;
        background: #2735721c;
        padding: 30px 30px 30px !important;
    }

    h4.card-title {
        font-family: "Inter";
        font-weight: 600;
        font-size: 24px;
        line-height: 100%;
        color: #273572;
        margin: 0;
    }

    label.form-label {
        margin: 0 0px 14px;
        position: relative;
        color: #333333;
        display: flex;
        align-items: center;
        justify-content: start;
        font-family: "Inter";
        font-weight: 600;
        font-size: 18px;
        line-height: 100%;
    }

    .view-field {
        background: #FFFFFF;
        border-radius: 9.77px !important;
        border: 2px solid #E9EBF0 !important;
        font-family: Inter !important;
        font-weight: 400 !important;
        font-size: 16px !important;
        padding: 12px 15px;
        color: #333;
        margin-bottom: 20px;
    }

    .card .card-header .card-title a img {
        width: 14px !important;
        margin-top: -6px;
        margin-right: 16px;
        border: none !important;
    }

    .post-content {
        background: #FFFFFF;
        border-radius: 9.77px !important;
        border: 2px solid #E9EBF0 !important;
        font-family: Inter !important;
        font-weight: 400 !important;
        font-size: 16px !important;
        padding: 20px;
        color: #333;
        margin-bottom: 0;
        min-height: 60px;
        white-space: pre-wrap;
        word-wrap: break-word;
        line-height: 1.7;
    }

    /* Post Media Styles - Matching News Feed */
    .post-media {
        margin-top: 12px;
        margin-bottom: 12px;
        border-radius: 8px;
        overflow: hidden;
    }

    /* Single Image/Video */
    .post-media-single {
        width: 100%;
    }

    .post-media-single img,
    .post-media-single video {
        width: 100%;
        height: auto;
        max-height: 500px;
        object-fit: cover;
        display: block;
        border-radius: 8px;
    }

    /* Multiple Images Grid */
    .post-media-grid {
        display: grid;
        gap: 4px;
    }

    .post-media-grid.grid-2 {
        grid-template-columns: 1fr 1fr;
    }

    .post-media-grid.grid-3 {
        grid-template-columns: 2fr 1fr;
    }

    .post-media-grid.grid-4 {
        grid-template-columns: 1fr 1fr;
        grid-template-rows: repeat(2, 1fr);
    }

    .post-media-item {
        position: relative;
        overflow: hidden;
        border-radius: 0;
    }

    .post-media-item img {
        width: 100%;
        height: auto;
        display: block;
        object-fit: cover;
    }

    /* Two Images */
    .post-media-grid.grid-2 .post-media-item img {
        height: 300px;
    }

    /* Three Images */
    .post-media-grid.grid-3 .post-media-item:first-child {
        grid-row: 1 / 3;
    }

    .post-media-grid.grid-3 .post-media-item:first-child img {
        height: 100%;
        max-height: 400px;
    }

    .post-media-grid.grid-3 .post-images-small {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .post-media-grid.grid-3 .post-images-small .post-media-item img {
        height: calc((400px - 4px) / 2);
        width: 100%;
    }

    /* Four Images */
    .post-media-grid.grid-4 .post-media-item img {
        height: 250px;
    }

    /* Video Styles */
    .post-media-item video {
        width: 100%;
        max-height: 500px;
        display: block;
        object-fit: cover;
    }

    /* Responsive */
    @media (max-width: 768px) {
        .post-media-grid.grid-2 .post-media-item img,
        .post-media-grid.grid-4 .post-media-item img {
            height: 200px;
        }

        .post-media-grid.grid-3 .post-media-item:first-child img {
            max-height: 300px;
        }

        .post-media-grid.grid-3 .post-media-item:not(:first-child) img {
            height: calc((300px - 4px) / 2);
        }

        .post-media-single img,
        .post-media-single video,
        .post-media-item video {
            max-height: 300px;
        }
    }

    .btn {
        border-radius: 9.77px !important;
        padding: 15px 56px !important;
        font-family: "Poppins", sans-serif !important;
        font-weight: 500 !important;
        font-size: 22px !important;
        line-height: 100% !important;
        letter-spacing: 0px !important;
        text-align: center !important;
    }

    .btn:hover {
        color: #000;
    }


    .comment-item {
        background: #FFFFFF;
        border: 2px solid #E9EBF0;
        border-radius: 9.77px;
        padding: 18px;
        margin-bottom: 18px;
        transition: box-shadow 0.2s ease;
    }

    .comment-item:hover {
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
    }

    .comment-item.deleted-comment {
        opacity: 0.7;
        background: #f8f9fa;
        border-color: #dee2e6;
    }

    .comment-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 0;
        padding-bottom: 12px;
        border-bottom: 1px solid #E9EBF0;
    }

    .comment-user-info {
        flex: 1;
    }

    .comment-user {
        font-weight: 600;
        color: #273572;
        font-family: "Inter";
        font-size: 15px;
        margin-bottom: 4px;
        display: block;
    }

    .comment-content {
        color: #333;
        font-family: "Inter";
        font-size: 15px;
        margin-top: 15px;
        white-space: pre-wrap;
        word-wrap: break-word;
        line-height: 1.7;
        padding: 12px 15px;
        background: #fafbff;
        border-radius: 8px;
        border-left: 3px solid #273572;
    }

    .comment-meta {
        font-size: 12px;
        color: #999;
        font-family: "Inter";
        display: block;
    }

    .comment-actions {
        margin-left: 15px;
        flex-shrink: 0;
    }

    .btn-sm-custom {
        padding: 6px 12px !important;
        font-size: 13px !important;
        border-radius: 6px !important;
    }

    .restore-post-btn,
    .restore-comment-btn {
        background-color: transparent !important;
        border-color: transparent !important;
        color: #28a745 !important;
        padding: 6px 10px !important;
    }

    .restore-post-btn:hover,
    .restore-comment-btn:hover {
        background-color: rgba(40, 167, 69, 0.1) !important;
        border-color: transparent !important;
        color: #28a745 !important;
    }

    .restore-post-btn i,
    .restore-comment-btn i {
        margin-right: 5px;
    }

    .comment-reply {
        margin-left: 30px;
        margin-top: 10px;
        margin-bottom: 10px;
        padding: 12px;
        padding-left: 15px;
        border-left: 3px solid #E9EBF0;
        background: #fafbff;
        border-radius: 6px;
        border: 1px solid #E9EBF0;
        position: relative;
    }

    .comment-reply::before {
        display: none;
    }

    .comment-reply .comment-content {
        background: #ffffff;
        border-left-color: #E9EBF0;
        margin-top: 10px;
        padding: 10px 12px;
    }
    
    .comment-reply .comment-header {
        margin-bottom: 0;
        padding-bottom: 8px;
        border-bottom: 1px solid #E9EBF0;
    }
    
    .comment-reply .comment-user {
        font-size: 14px;
    }
    
    .comment-reply .comment-meta {
        font-size: 11px;
    }

    .comment-reply.deleted-comment {
        opacity: 0.7;
        background: #f8f9fa;
    }

    .stats-section {
        background: #FFFFFF;
        border-radius: 9.77px;
        border: 2px solid #E9EBF0;
        padding: 20px;
        margin-bottom: 20px;
    }

    .stats-item {
        text-align: center;
        padding: 15px;
        border-right: 1px solid #E9EBF0;
    }

    .stats-item:last-child {
        border-right: none;
    }

    .stats-item i {
        color: #273572;
        margin-right: 8px;
        font-size: 20px;
    }

    .stats-item .stats-value {
        font-size: 24px;
        font-weight: 600;
        color: #273572;
        font-family: "Inter";
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 8px;
    }

    .stats-item .stats-label {
        font-size: 14px;
        color: #666;
        font-family: "Inter";
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-weight: 500;
    }

    .empty-state {
        text-align: center;
        padding: 40px 20px;
        color: #999;
        font-family: "Inter";
    }

    .empty-state i {
        font-size: 48px;
        margin-bottom: 15px;
        opacity: 0.5;
    }

    .post-image-wrapper {
        position: relative;
    }

    .post-image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.6);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 32px;
        font-weight: bold;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .post-image-overlay:hover {
        background: rgba(0, 0, 0, 0.75);
    }

    @media (max-width: 768px) {
        .row .col-12.mt-4 {
            display: flex;
            gap: 10px;
            flex-direction: column;
        }
        .btn {
            padding: 15px 40px;
            font-size: 16px !important;
        }
        .post-media-grid.grid-2,
        .post-media-grid.grid-4 {
            grid-template-columns: 1fr;
        }
        
        .post-media-grid.grid-2 .post-media-item img,
        .post-media-grid.grid-4 .post-media-item img {
            height: 250px;
        }
        
        .post-media-grid.grid-3 {
            grid-template-columns: 1fr;
        }
        
        .post-media-grid.grid-3 .post-media-item:first-child {
            grid-row: auto;
        }
        
        .post-media-grid.grid-3 .post-media-item:first-child img {
            max-height: 300px;
        }
        
        .post-media-grid.grid-3 .post-images-small {
            flex-direction: row;
        }
        
        .post-media-grid.grid-3 .post-images-small .post-media-item img {
            height: 150px;
            width: calc(50% - 2px);
        }
        .comment-reply {
            margin-left: 20px !important;
        }
        
        .comment-reply::before {
            display: none;
        }
        .comment-header {
            flex-direction: column;
        }
        .comment-actions {
            margin-left: 0;
            margin-top: 10px;
        }
    }
</style>
@section('content')
    <main class="main-content">
        @php
            $user = Auth::user();
            $isAdmin = $user && $user->role_id == 1;
            $canView = $isAdmin || ($user && $user->hasPermission('feed.view'));
            $canDelete = $isAdmin || ($user && $user->hasPermission('feed.delete'));
            $canRestore = $isAdmin || ($user && $user->hasPermission('feed.restore'));
        @endphp
        @if(!$canView)
            @php
                abort(403, 'Unauthorized action.');
            @endphp
        @endif

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <!-- Post Details Card -->
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <a href="{{ route('admin.feed') }}">
                                    <img src="{{ asset('assets/images/dashboard/dashboardBackChevron.svg') }}" alt="Back">
                                </a>
                                View Post
                            </h4>
                        </div>
                        <div class="card-body">
                            <!-- Post Media - Show at Top -->
                            @if($post->media && $post->media->count() > 0)
                            <div class="mb-4">
                                <label class="form-label" style="font-size: 20px; margin-bottom: 15px;">
                                    <i class="fa-solid fa-images"></i> Media ({{ $post->media->count() }})
                                </label>
                                    @php
                                        $images = $post->media->filter(function($m) { return ($m->media_type ?? 'image') === 'image'; });
                                        $videos = $post->media->filter(function($m) { return ($m->media_type ?? 'image') === 'video'; });
                                        $allMedia = $post->media;
                                        $mediaCount = $allMedia->count();
                                    @endphp
                                    
                                    @if($mediaCount == 1)
                                        {{-- Single Media --}}
                                        @php
                                            $media = $allMedia->first();
                                            $mediaUrl = getImageUrl($media->media_url) ?? $media->media_url;
                                            $mediaType = $media->media_type ?? 'image';
                                        @endphp
                                        <div class="post-media-single">
                                            @if($mediaType === 'video')
                                                <video controls class="post-video">
                                                    <source src="{{ $mediaUrl }}" type="video/mp4">
                                                    Your browser does not support the video tag.
                                                </video>
                                            @else
                                                <img src="{{ $mediaUrl }}" alt="Post media" class="post-image">
                                            @endif
                                        </div>
                                    @elseif($mediaCount == 2)
                                        {{-- Two Images --}}
                                        <div class="post-media post-media-grid grid-2">
                                            @foreach($allMedia as $media)
                                                @php
                                                    $mediaUrl = getImageUrl($media->media_url) ?? $media->media_url;
                                                    $mediaType = $media->media_type ?? 'image';
                                                @endphp
                                                <div class="post-media-item">
                                                    @if($mediaType === 'video')
                                                        <video controls class="post-video">
                                                            <source src="{{ $mediaUrl }}" type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    @else
                                                        <img src="{{ $mediaUrl }}" alt="Post media" class="post-image">
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @elseif($mediaCount == 3)
                                        {{-- Three Images: 1 large + 2 small --}}
                                        <div class="post-media post-media-grid grid-3">
                                            @php
                                                $firstMedia = $allMedia->first();
                                                $firstMediaUrl = getImageUrl($firstMedia->media_url) ?? $firstMedia->media_url;
                                                $firstMediaType = $firstMedia->media_type ?? 'image';
                                            @endphp
                                            <div class="post-media-item">
                                                @if($firstMediaType === 'video')
                                                    <video controls class="post-video">
                                                        <source src="{{ $firstMediaUrl }}" type="video/mp4">
                                                        Your browser does not support the video tag.
                                                    </video>
                                                @else
                                                    <img src="{{ $firstMediaUrl }}" alt="Post media" class="post-image post-image-large">
                                                @endif
                                            </div>
                                            <div class="post-images-small">
                                                @foreach($allMedia->skip(1) as $media)
                                                    @php
                                                        $mediaUrl = getImageUrl($media->media_url) ?? $media->media_url;
                                                        $mediaType = $media->media_type ?? 'image';
                                                    @endphp
                                                    <div class="post-media-item">
                                                        @if($mediaType === 'video')
                                                            <video controls class="post-video">
                                                                <source src="{{ $mediaUrl }}" type="video/mp4">
                                                                Your browser does not support the video tag.
                                                            </video>
                                                        @else
                                                            <img src="{{ $mediaUrl }}" alt="Post media" class="post-image">
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @else
                                        {{-- Four or More Images --}}
                                        <div class="post-media post-media-grid grid-4">
                                            @foreach($allMedia->take(4) as $index => $media)
                                                @php
                                                    $mediaUrl = getImageUrl($media->media_url) ?? $media->media_url;
                                                    $mediaType = $media->media_type ?? 'image';
                                                @endphp
                                                <div class="post-media-item {{ $index === 3 && $mediaCount > 4 ? 'post-image-wrapper' : '' }}">
                                                    @if($mediaType === 'video')
                                                        <video controls class="post-video">
                                                            <source src="{{ $mediaUrl }}" type="video/mp4">
                                                            Your browser does not support the video tag.
                                                        </video>
                                                    @else
                                                        <img src="{{ $mediaUrl }}" alt="Post media" class="post-image">
                                                    @endif
                                                    @if($index === 3 && $mediaCount > 4)
                                                        <div class="post-image-overlay">+{{ $mediaCount - 4 }}</div>
                                                    @endif
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                            </div>
                            @endif

                            <!-- Post Content -->
                            <div class="mb-4">
                                <label class="form-label">Post Content</label>
                                <div class="post-content">
                                    @if(!empty(trim($post->content ?? '')))
                                        {{ $post->content }}
                                    @else
                                        <span style="color: #999; font-style: italic;">No content</span>
                                    @endif
                                </div>
                            </div>

                            <!-- Post Details Section -->
                            <div style="border-top: 2px solid #E9EBF0; padding-top: 20px; margin-top: 20px;">
                                <h5 style="font-family: 'Inter'; font-weight: 600; font-size: 18px; color: #273572; margin-bottom: 20px;">
                                    <i class="fa-solid fa-info-circle"></i> Post Details
                                </h5>
                                <div class="row">
                                <!-- Post ID & Status -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Post ID</label>
                                    <div class="view-field">{{ $post->id }}</div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Status</label>
                                    <div class="view-field">
                                        @if($post->deleted_at)
                                            <span class="badge bg-danger">Deleted</span>
                                        @else
                                            <span class="badge bg-success">Active</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- User Information -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">User</label>
                                    <div class="view-field">
                                        @if($post->user)
                                            {{ trim($post->user->first_name . ' ' . $post->user->last_name) ?: 'N/A' }}
                                        @else
                                            <span style="color: #999;">N/A</span>
                                        @endif
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Email</label>
                                    <div class="view-field">
                                        @if($post->user && $post->user->email)
                                            {{ $post->user->email }}
                                        @else
                                            <span style="color: #999;">N/A</span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Post Statistics -->
                                <div class="col-12 mb-3">
                                    <label class="form-label">Statistics</label>
                                    <div class="stats-section">
                                        <div class="row g-0">
                                            <div class="col-md-4">
                                                <div class="stats-item">
                                                    <div class="stats-value">
                                                        <i class="fa-solid fa-heart"></i>
                                                        {{ $post->reactions_count ?? 0 }}
                                                    </div>
                                                    <div class="stats-label">Reactions</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="stats-item">
                                                    <div class="stats-value">
                                                        <i class="fa-solid fa-comment"></i>
                                                        {{ $post->comments_count ?? 0 }}
                                                    </div>
                                                    <div class="stats-label">Comments</div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="stats-item">
                                                    <div class="stats-value">
                                                        <i class="fa-solid fa-share"></i>
                                                        {{ $post->shares_count ?? 0 }}
                                                    </div>
                                                    <div class="stats-label">Shares</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Dates -->
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Created At</label>
                                    <div class="view-field">
                                        {{ $post->created_at ? $post->created_at->format('F d, Y h:i A') : 'N/A' }}
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Updated At</label>
                                    <div class="view-field">
                                        {{ $post->updated_at ? $post->updated_at->format('F d, Y h:i A') : 'N/A' }}
                                    </div>
                                </div>

                                @if($post->deleted_at)
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Deleted At</label>
                                    <div class="view-field">
                                        {{ $post->deleted_at ? \Carbon\Carbon::parse($post->deleted_at)->format('F d, Y h:i A') : 'N/A' }}
                                    </div>
                                </div>
                                @endif

                                </div>

                                <!-- Action Buttons -->
                                <div class="col-12 mt-4" style="border-top: 1px solid #E9EBF0; padding-top: 20px;">
                                    @if($post->deleted_at)
                                        @if($canRestore)
                                            <button type="button" class="btn btn-success restore-post-btn" data-post-id="{{ $post->id }}">
                                                <i class="fa-solid fa-rotate-left"></i> Restore Post
                                            </button>
                                        @endif
                                    @else
                                        @if($canDelete)
                                            <button type="button" class="btn btn-danger delete-post-btn" data-post-id="{{ $post->id }}">
                                                <i class="fa-solid fa-trash"></i> Delete Post
                                            </button>
                                        @endif
                                    @endif
                                    <a href="{{ route('admin.feed') }}" class="btn btn-secondary">Back to Feed</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Comments Section -->
                    <div class="card" style="margin-top: 20px;">
                        <div class="card-header">
                            <h4 class="card-title">
                                <i class="fa-solid fa-comments"></i> Comments 
                                <span style="font-size: 0.85em; color: #666; font-weight: 400;">
                                    ({{ $post->comments->whereNull('deleted_at')->count() }} active 
                                    @if($post->comments->whereNotNull('deleted_at')->count() > 0)
                                        / {{ $post->comments->count() }} total
                                    @endif
                                    )
                                </span>
                            </h4>
                        </div>
                        <div class="card-body">
                            @forelse($post->comments as $comment)
                                <div class="comment-item {{ $comment->deleted_at ? 'deleted-comment' : '' }}">
                                    <div class="comment-header">
                                        <div class="comment-user-info">
                                            <div class="comment-user">
                                                @if($comment->user)
                                                    {{ trim($comment->user->first_name . ' ' . $comment->user->last_name) ?: 'N/A' }}
                                                @else
                                                    <span style="color: #999;">Deleted User</span>
                                                @endif
                                            </div>
                                            <div class="comment-meta">
                                                {{ $comment->created_at ? $comment->created_at->format('F d, Y h:i A') : 'N/A' }}
                                                @if($comment->deleted_at)
                                                    | <span class="text-danger">Deleted</span>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="comment-actions">
                                            @if($comment->deleted_at)
                                                @if($canRestore)
                                                    <button type="button" class="btn btn-success btn-sm-custom restore-comment-btn" data-comment-id="{{ $comment->id }}" title="Restore">
                                                        <i class="fa-solid fa-rotate-left"></i> Restore
                                                    </button>
                                                @endif
                                            @else
                                                @if($canDelete)
                                                    <button type="button" class="btn btn-danger btn-sm-custom delete-comment-btn" data-comment-id="{{ $comment->id }}" title="Delete">
                                                        <i class="fa-solid fa-trash"></i> Delete
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </div>
                                    <div class="comment-content">
                                        @if(!empty(trim($comment->content ?? '')))
                                            {{ $comment->content }}
                                        @else
                                            <span style="color: #999; font-style: italic;">No content</span>
                                        @endif
                                    </div>

                                    <!-- Replies -->
                                    @if($comment->replies && $comment->replies->count() > 0)
                                        @php
                                            $activeReplies = $comment->replies->whereNull('deleted_at')->count();
                                            $totalReplies = $comment->replies->count();
                                            $deletedReplies = $totalReplies - $activeReplies;
                                        @endphp
                                        <div style="margin-top: 12px; padding-top: 12px; border-top: 1px solid #E9EBF0;">
                                            <small style="color: #666; font-weight: 500; margin-bottom: 10px; display: block; font-size: 11px;">
                                                <i class="fa-solid fa-reply"></i> 
                                                {{ $activeReplies }} {{ $activeReplies == 1 ? 'Reply' : 'Replies' }}
                                                @if($deletedReplies > 0)
                                                    <span style="color: #999;">({{ $totalReplies }} total)</span>
                                                @endif
                                            </small>
                                            @foreach($comment->replies as $reply)
                                                <div class="comment-reply {{ $reply->deleted_at ? 'deleted-comment' : '' }}" style="margin-bottom: 10px;">
                                                    <div class="comment-header">
                                                        <div class="comment-user-info">
                                                            <div class="comment-user">
                                                                @if($reply->user)
                                                                    {{ trim($reply->user->first_name . ' ' . $reply->user->last_name) ?: 'N/A' }}
                                                                @else
                                                                    <span style="color: #999;">Deleted User</span>
                                                                @endif
                                                            </div>
                                                            <div class="comment-meta">
                                                                {{ $reply->created_at ? $reply->created_at->format('F d, Y h:i A') : 'N/A' }}
                                                                @if($reply->deleted_at)
                                                                    | <span class="text-danger">Deleted</span>
                                                                @endif
                                                            </div>
                                                        </div>
                                                        <div class="comment-actions">
                                                            @if($reply->deleted_at)
                                                                @if($canRestore)
                                                                    <button type="button" class="btn btn-success btn-sm-custom restore-comment-btn" data-comment-id="{{ $reply->id }}" title="Restore">
                                                                        <i class="fa-solid fa-rotate-left"></i> Restore
                                                                    </button>
                                                                @endif
                                                            @else
                                                                @if($canDelete)
                                                                    <button type="button" class="btn btn-danger btn-sm-custom delete-comment-btn" data-comment-id="{{ $reply->id }}" title="Delete">
                                                                        <i class="fa-solid fa-trash"></i> Delete
                                                                    </button>
                                                                @endif
                                                            @endif
                                                        </div>
                                                    </div>
                                                    <div class="comment-content">
                                                        @if(!empty(trim($reply->content ?? '')))
                                                            {{ $reply->content }}
                                                        @else
                                                            <span style="color: #999; font-style: italic;">No content</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    @endif
                                </div>
                            @empty
                                <div class="empty-state">
                                    <i class="fa-regular fa-comment"></i>
                                    <p>No comments yet.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection

@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(document).ready(function() {
            // Delete post
            $('.delete-post-btn').on('click', function() {
                const postId = $(this).data('post-id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/feed/post/' + postId,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message || 'Post deleted successfully.',
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.error || 'Failed to delete post.',
                                    showConfirmButton: true
                                });
                            }
                        });
                    }
                });
            });

            // Restore post
            $('.restore-post-btn').on('click', function() {
                const postId = $(this).data('post-id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This post will be restored!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, restore it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/feed/post/' + postId + '/restore',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Restored!',
                                    text: response.message || 'Post restored successfully.',
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.error || 'Failed to restore post.',
                                    showConfirmButton: true
                                });
                            }
                        });
                    }
                });
            });

            // Delete comment
            $('.delete-comment-btn').on('click', function() {
                const commentId = $(this).data('comment-id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Yes, delete it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/feed/comment/' + commentId,
                            type: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Deleted!',
                                    text: response.message || 'Comment deleted successfully.',
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.error || 'Failed to delete comment.',
                                    showConfirmButton: true
                                });
                            }
                        });
                    }
                });
            });

            // Restore comment
            $('.restore-comment-btn').on('click', function() {
                const commentId = $(this).data('comment-id');
                
                Swal.fire({
                    title: 'Are you sure?',
                    text: "This comment will be restored!",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#28a745',
                    cancelButtonColor: '#6c757d',
                    confirmButtonText: 'Yes, restore it!',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: '/admin/feed/comment/' + commentId + '/restore',
                            type: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}'
                            },
                            success: function(response) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Restored!',
                                    text: response.message || 'Comment restored successfully.',
                                    showConfirmButton: false,
                                    timer: 2000
                                }).then(() => {
                                    location.reload();
                                });
                            },
                            error: function(xhr) {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error!',
                                    text: xhr.responseJSON?.error || 'Failed to restore comment.',
                                    showConfirmButton: true
                                });
                            }
                        });
                    }
                });
            });
        });
    </script>
@endsection
