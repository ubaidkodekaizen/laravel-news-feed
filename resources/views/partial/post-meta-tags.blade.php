{{--
    Meta Tags for Social Sharing
    Include this in your post detail page <head> section
    File: resources/views/partials/post-meta-tags.blade.php
--}}

@if(isset($post))
    {{-- Open Graph Meta Tags for Facebook/LinkedIn --}}
    <meta property="og:type" content="article">
    <meta property="og:url" content="{{ url('/feed/posts/' . ($post['slug'] ?? '')) }}">
    <meta property="og:title" content="{{ $post['user']['name'] ?? 'MuslimLynk' }} shared a post">
    <meta property="og:description" content="{{ Str::limit(strip_tags($post['content'] ?? ''), 200) }}">

    @if(!empty($post['media']) && count($post['media']) > 0)
        @php
            $firstImage = null;
            foreach ($post['media'] as $media) {
                if ($media['media_type'] === 'image') {
                    $firstImage = $media['media_url'];
                    break;
                }
            }
        @endphp
        @if($firstImage)
            <meta property="og:image" content="{{ $firstImage }}">
            <meta property="og:image:width" content="1200">
            <meta property="og:image:height" content="630">
        @else
            <meta property="og:image" content="{{ asset('assets/images/og-default.jpg') }}">
        @endif
    @else
        <meta property="og:image" content="{{ asset('assets/images/og-default.jpg') }}">
    @endif

    <meta property="og:site_name" content="MuslimLynk">
    <meta property="article:published_time" content="{{ $post['created_at'] ?? now() }}">
    <meta property="article:author" content="{{ $post['user']['name'] ?? '' }}">

    {{-- Twitter Card Meta Tags --}}
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@MuslimLynk">
    <meta name="twitter:title" content="{{ $post['user']['name'] ?? 'MuslimLynk' }} shared a post">
    <meta name="twitter:description" content="{{ Str::limit(strip_tags($post['content'] ?? ''), 200) }}">

    @if(!empty($post['media']) && count($post['media']) > 0 && $firstImage)
        <meta name="twitter:image" content="{{ $firstImage }}">
    @else
        <meta name="twitter:image" content="{{ asset('assets/images/og-default.jpg') }}">
    @endif

    {{-- Page Title --}}
    <title>{{ $post['user']['name'] ?? 'Post' }} on MuslimLynk</title>

    {{-- Canonical URL --}}
    <link rel="canonical" href="{{ url('/feed/posts/' . ($post['slug'] ?? '')) }}">
@else
    {{-- Default meta tags --}}
    <meta property="og:title" content="MuslimLynk - Professional Muslim Networking">
    <meta property="og:description" content="Connect, collaborate, and grow with the Muslim professional community.">
    <meta property="og:image" content="{{ asset('assets/images/og-default.jpg') }}">
    <meta property="og:url" content="{{ url()->current() }}">
    <meta name="twitter:card" content="summary_large_image">
    <title>MuslimLynk - Professional Muslim Networking</title>
@endif
