<div class="post-text-wrapper">
    <div class="post-text" id="postTextBlock-{{ $post['id'] ?? '' }}">
        {{ $post['content'] ?? 'Default content' }}
    </div>
    @if(strlen($post['content'] ?? '') > 200)
        <span class="post-text-toggle" onclick="togglePostText('{{ $post['id'] ?? '' }}')">See more</span>
    @endif
</div>

@if(!empty($post->images) && count($post->images) > 0)
    <div class="post-images">
        @foreach($post->images as $image)
        <img src="{{ $image }}" alt="Post image" class="post-image">
        @endforeach
    </div>
@endif
