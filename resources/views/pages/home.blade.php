@extends('layouts.main')

@section('title', config('app.name', 'NewsFeed'))

@section('content')
<div style="min-height: calc(100vh - 200px); display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%);">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card" style="padding: var(--spacing-3xl); text-align: center;">
                    <div style="width: 80px; height: 80px; margin: 0 auto var(--spacing-xl); background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; color: white; font-size: 36px;">
                        <i class="fas fa-newspaper"></i>
                    </div>
                    <h1 style="font-size: var(--font-size-4xl); font-weight: var(--font-weight-bold); color: var(--color-text-primary); margin-bottom: var(--spacing-md);">
                        Welcome to {{ config('app.name', 'NewsFeed') }}
                    </h1>
                    <p style="font-size: var(--font-size-lg); color: var(--color-text-secondary); margin-bottom: var(--spacing-2xl);">
                        Connect, share, and engage with your community
                    </p>
                    <div style="display: flex; gap: var(--spacing-md); justify-content: center; flex-wrap: wrap;">
                        <a href="{{ route('login.form') }}" class="btn btn-primary" style="min-width: 140px;">
                            <i class="fas fa-sign-in-alt me-2"></i> Login
                        </a>
                        <a href="{{ route('register.form') }}" class="btn btn-outline-primary" style="min-width: 140px;">
                            <i class="fas fa-user-plus me-2"></i> Sign Up
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
