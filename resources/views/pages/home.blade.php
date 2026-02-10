@extends('layouts.main')

@section('title', config('app.name', 'NewsFeed'))

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card-modern p-4">
                    <h1 class="h4 mb-3">Welcome to {{ config('app.name', 'NewsFeed') }}</h1>
                    <p class="text-muted mb-4">Sign in or create an account to continue.</p>
                    <div class="d-flex gap-2">
                        <a href="{{ route('login.form') }}" class="btn btn-primary w-50">Login</a>
                        <a href="{{ route('register.form') }}" class="btn btn-outline-primary w-50">Sign Up</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
