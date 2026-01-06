<div class="post-text-wrapper">
    <div class="post-text" id="postTextBlock-{{ $post['id'] ?? '' }}">
        {{ $post['content'] ?? 'Default content' }}
    </div>
    @if(strlen($post['content'] ?? '') > 200)
        <span class="post-text-toggle" onclick="togglePostText('{{ $post['id'] ?? '' }}')">See more</span>
    @endif
</div>

@php
    // Extract image URLs from media array (media_url should already be full S3 URL)
    $imageUrls = [];
    if (isset($post['media']) && is_array($post['media'])) {
        foreach ($post['media'] as $media) {
            if (isset($media['media_type']) && $media['media_type'] === 'image' && isset($media['media_url'])) {
                // Use helper to ensure URL is correct (handles both S3 and local)
                $imageUrls[] = getImageUrl($media['media_url']) ?? $media['media_url'];
            }
        }
    } elseif (isset($post->images) && is_array($post->images)) {
        // Fallback for old structure
        foreach ($post->images as $image) {
            $imageUrls[] = getImageUrl($image) ?? $image;
        }
    }
@endphp

@if(!empty($imageUrls))
    <div class="post-images">
        @foreach($imageUrls as $imageUrl)
        <img src="{{ $imageUrl }}" alt="Post image" class="post-image">
        @endforeach
    </div>
@endif
