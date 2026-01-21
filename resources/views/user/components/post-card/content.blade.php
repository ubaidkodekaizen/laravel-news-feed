<div class="post-text-wrapper">
    <div class="post-text" id="postTextBlock-{{ $post['id'] ?? '' }}">
        {!! nl2br(e($post['content'] ?? '')) !!}
    </div>
    @if(strlen($post['content'] ?? '') > 300)
        <span class="post-text-toggle" onclick="togglePostText('{{ $post['id'] ?? '' }}')">...see more</span>
    @endif
</div>

@php
    // Extract media URLs
    $images = [];
    $videos = [];

    if (isset($post['media']) && is_array($post['media'])) {
        foreach ($post['media'] as $media) {
            $mediaUrl = getImageUrl($media['media_url']) ?? $media['media_url'];

            if (isset($media['media_type'])) {
                if ($media['media_type'] === 'image') {
                    $images[] = [
                        'url' => $mediaUrl,
                        'alt' => $media['file_name'] ?? 'Post image'
                    ];
                } elseif ($media['media_type'] === 'video') {
                    $videos[] = [
                        'url' => $mediaUrl,
                        'thumbnail' => $media['thumbnail_url'] ?? null,
                        'mime_type' => $media['mime_type'] ?? 'video/mp4',
                        'duration' => $media['duration'] ?? null
                    ];
                }
            }
        }
    }
@endphp

{{-- Display Videos First --}}
@if(!empty($videos))
    <div class="post-videos">
        @foreach($videos as $video)
            <div class="post-video-container">
                <video controls class="post-video" preload="metadata">
                    <source src="{{ $video['url'] }}" type="{{ $video['mime_type'] }}">
                    Your browser does not support the video tag.
                </video>
                @if($video['duration'])
                    <div class="video-duration">{{ gmdate('i:s', $video['duration']) }}</div>
                @endif
            </div>
        @endforeach
    </div>
@endif

{{-- Display Images --}}
@if(!empty($images))
    <div class="post-images" data-image-count="{{ count($images) }}">
        @if(count($images) === 1)
            {{-- Single image layout --}}
            <div class="post-images-single">
                <img src="{{ $images[0]['url'] }}" alt="{{ $images[0]['alt'] }}" class="post-image">
            </div>
        @elseif(count($images) === 2)
            {{-- Two images side by side --}}
            <div class="post-images-grid post-images-two">
                @foreach($images as $image)
                    <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" class="post-image">
                @endforeach
            </div>
        @elseif(count($images) === 3)
            {{-- Three images: 1 large + 2 small --}}
            <div class="post-images-grid post-images-three">
                <img src="{{ $images[0]['url'] }}" alt="{{ $images[0]['alt'] }}" class="post-image post-image-large">
                <div class="post-images-small">
                    <img src="{{ $images[1]['url'] }}" alt="{{ $images[1]['alt'] }}" class="post-image">
                    <img src="{{ $images[2]['url'] }}" alt="{{ $images[2]['alt'] }}" class="post-image">
                </div>
            </div>
        @elseif(count($images) === 4)
            {{-- Four images in grid --}}
            <div class="post-images-grid post-images-four">
                @foreach($images as $image)
                    <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" class="post-image">
                @endforeach
            </div>
        @else
            {{-- 5+ images: show first 4 with +N overlay on last --}}
            <div class="post-images-grid post-images-four">
                @foreach(array_slice($images, 0, 4) as $index => $image)
                    @if($index === 3 && count($images) > 4)
                        <div class="post-image-wrapper">
                            <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" class="post-image">
                            <div class="post-image-overlay">+{{ count($images) - 4 }}</div>
                        </div>
                    @else
                        <img src="{{ $image['url'] }}" alt="{{ $image['alt'] }}" class="post-image">
                    @endif
                @endforeach
            </div>
        @endif
    </div>
@endif

<style>
/* Video Styles */
.post-videos {
    margin: 16px 0;
}

.post-video-container {
    position: relative;
    width: 100%;
    background-color: #000;
    border-radius: 8px;
    overflow: hidden;
    margin-bottom: 8px;
}

.post-video-container:last-child {
    margin-bottom: 0;
}

.post-video {
    width: 100%;
    max-height: 500px;
    display: block;
}

.video-duration {
    position: absolute;
    bottom: 8px;
    right: 8px;
    background-color: rgba(0, 0, 0, 0.8);
    color: white;
    padding: 2px 6px;
    border-radius: 4px;
    font-size: 12px;
    font-weight: 600;
}

/* Image Grid Styles */
.post-images {
    margin: 16px 0;
    overflow: hidden;
    border-radius: 8px;
}

.post-images-single {
    width: 100%;
}

.post-image {
    width: 100%;
    height: auto;
    display: block;
    object-fit: cover;
}

.post-images-single .post-image {
    max-height: 600px;
}

.post-images-grid {
    display: grid;
    gap: 4px;
}

.post-images-two {
    grid-template-columns: repeat(2, 1fr);
}

.post-images-two .post-image {
    height: 300px;
}

.post-images-three {
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: repeat(2, 1fr);
}

.post-images-three .post-image-large {
    grid-row: 1 / 3;
    height: 100%;
    min-height: 400px;
}

.post-images-three .post-images-small {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.post-images-three .post-images-small .post-image {
    height: 198px;
}

.post-images-four {
    grid-template-columns: repeat(2, 1fr);
    grid-template-rows: repeat(2, 1fr);
}

.post-images-four .post-image {
    height: 250px;
}

.post-image-wrapper {
    position: relative;
}

.post-image-overlay {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.6);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 32px;
    font-weight: bold;
}

/* Responsive */
@media (max-width: 768px) {
    .post-images-two .post-image,
    .post-images-three .post-image-large,
    .post-images-three .post-images-small .post-image,
    .post-images-four .post-image {
        height: 200px;
    }

    .post-video {
        max-height: 300px;
    }
}
</style>
