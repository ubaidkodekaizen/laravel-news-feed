<div class="post-text-wrapper">
    <div class="post-text" id="postTextBlock-{{ $post['id'] ?? '' }}">
        {!! nl2br(e($post['content'] ?? 'Default content')) !!}
    </div>
    @if(strlen($post['content'] ?? '') > 200)
        <span class="post-text-toggle" onclick="togglePostText('{{ $post['id'] ?? '' }}')">See more</span>
    @endif
</div>

@php
    // Extract image URLs from media array
    $imageUrls = [];
    if (isset($post['media']) && is_array($post['media'])) {
        foreach ($post['media'] as $media) {
            if (isset($media['media_type']) && $media['media_type'] === 'image' && isset($media['media_url'])) {
                // Use helper to ensure URL is correct (handles both S3 and local)
                $imageUrls[] = getImageUrl($media['media_url']) ?? $media['media_url'];
            }
        }
    }
@endphp

@if(!empty($imageUrls))
    <div class="post-images" data-image-count="{{ count($imageUrls) }}">
        @if(count($imageUrls) === 1)
            {{-- Single image layout --}}
            <div class="post-images-single">
                <img src="{{ $imageUrls[0] }}" alt="Post image" class="post-image">
            </div>
        @elseif(count($imageUrls) === 2)
            {{-- Two images side by side --}}
            <div class="post-images-grid post-images-two">
                @foreach($imageUrls as $imageUrl)
                    <img src="{{ $imageUrl }}" alt="Post image" class="post-image">
                @endforeach
            </div>
        @elseif(count($imageUrls) === 3)
            {{-- Three images: 1 large + 2 small --}}
            <div class="post-images-grid post-images-three">
                <img src="{{ $imageUrls[0] }}" alt="Post image" class="post-image post-image-large">
                <div class="post-images-small">
                    <img src="{{ $imageUrls[1] }}" alt="Post image" class="post-image">
                    <img src="{{ $imageUrls[2] }}" alt="Post image" class="post-image">
                </div>
            </div>
        @elseif(count($imageUrls) === 4)
            {{-- Four images in grid --}}
            <div class="post-images-grid post-images-four">
                @foreach($imageUrls as $imageUrl)
                    <img src="{{ $imageUrl }}" alt="Post image" class="post-image">
                @endforeach
            </div>
        @else
            {{-- 5+ images: show first 4 with +N overlay on last --}}
            <div class="post-images-grid post-images-four">
                @foreach(array_slice($imageUrls, 0, 4) as $index => $imageUrl)
                    @if($index === 3 && count($imageUrls) > 4)
                        <div class="post-image-wrapper">
                            <img src="{{ $imageUrl }}" alt="Post image" class="post-image">
                            <div class="post-image-overlay">+{{ count($imageUrls) - 4 }}</div>
                        </div>
                    @else
                        <img src="{{ $imageUrl }}" alt="Post image" class="post-image">
                    @endif
                @endforeach
            </div>
        @endif
    </div>
@endif
