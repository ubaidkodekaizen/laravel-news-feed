@extends('layouts.main')

@section('title', 'Inbox - Messages')

@section('styles')
    @php
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        $inboxCss = $manifest['resources/css/inbox.css']['file'] ?? null;
    @endphp

    @if($inboxCss)
        <link rel="stylesheet" href="{{ asset('build/' . $inboxCss) }}">
    @endif
@endsection

@section('content')
<div class="container-fluid px-0">
    <div id="inbox-root"></div>
</div>
@endsection

@section('scripts')
    @php
        $manifest = json_decode(file_get_contents(public_path('build/manifest.json')), true);
        $inboxJs = $manifest['resources/js/inbox.jsx']['file'] ?? null;
    @endphp

    @if($inboxJs)
        <script type="module" src="{{ asset('build/' . $inboxJs) }}"></script>
    @endif
@endsection
