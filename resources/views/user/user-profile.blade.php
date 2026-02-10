@extends('layouts.main')
@section('content')
    <style>
        .user-profile-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 40px 20px;
        }

        .profile-header {
            background: white;
            border-radius: 12px;
            padding: 40px;
            margin-bottom: 30px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .profile-header-content {
            display: flex;
            gap: 30px;
            align-items: center;
        }

        .profile-avatar {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            object-fit: cover;
            border: 4px solid var(--primary);
        }

        .profile-avatar-placeholder {
            width: 150px;
            height: 150px;
            border-radius: 50%;
            background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 48px;
            font-weight: 600;
            border: 4px solid var(--primary);
        }

        .profile-info h1 {
            font-size: 32px;
            font-weight: 700;
            color: var(--text-primary);
            margin: 0 0 10px 0;
        }

        .profile-info .bio {
            font-size: 16px;
            color: var(--text-secondary);
            margin: 15px 0;
            line-height: 1.6;
        }

        .profile-details {
            background: white;
            border-radius: 12px;
            padding: 40px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .profile-details h2 {
            font-size: 24px;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 30px;
            padding-bottom: 15px;
            border-bottom: 2px solid var(--border-color);
        }

        .detail-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            margin-bottom: 25px;
        }

        .detail-item label {
            display: block;
            font-size: 14px;
            font-weight: 600;
            color: var(--text-secondary);
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .detail-item p {
            font-size: 16px;
            color: var(--text-primary);
            margin: 0;
        }

        .detail-item a {
            color: var(--primary);
            text-decoration: none;
        }

        .detail-item a:hover {
            text-decoration: underline;
        }

        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-secondary);
        }

        .empty-state i {
            font-size: 64px;
            margin-bottom: 20px;
            opacity: 0.5;
        }

        @media (max-width: 768px) {
            .profile-header-content {
                flex-direction: column;
                text-align: center;
            }

            .detail-row {
                grid-template-columns: 1fr;
            }
        }
    </style>

    <div class="user-profile-container">
        <!-- Profile Header -->
        <div class="profile-header">
            <div class="profile-header-content">
                @if($user->photo)
                    <img src="{{ $user->photo }}" alt="{{ $user->first_name }} {{ $user->last_name }}" class="profile-avatar" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="profile-avatar-placeholder" style="display: none;">
                        {{ strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}
                    </div>
                @else
                    <div class="profile-avatar-placeholder">
                        {{ strtoupper(substr($user->first_name ?? '', 0, 1) . substr($user->last_name ?? '', 0, 1)) }}
                    </div>
                @endif

                <div class="profile-info">
                    <h1>{{ $user->first_name ?? '' }} {{ $user->last_name ?? '' }}</h1>
                    @if($user->bio)
                        <p class="bio">{{ $user->bio }}</p>
                    @endif
                </div>
            </div>
        </div>

        <!-- Profile Details -->
        <div class="profile-details">
            <h2>Contact Information</h2>
            
            <div class="detail-row">
                @if($user->email)
                    <div class="detail-item">
                        <label>Email</label>
                        <p><a href="mailto:{{ $user->email }}">{{ $user->email }}</a></p>
                    </div>
                @endif

                @if($user->phone)
                    <div class="detail-item">
                        <label>Phone</label>
                        <p><a href="tel:{{ $user->phone }}">{{ $user->phone }}</a></p>
                    </div>
                @endif

                @if($user->location)
                    <div class="detail-item">
                        <label>Location</label>
                        <p>{{ $user->location }}</p>
                    </div>
                @endif

                @if($user->website)
                    <div class="detail-item">
                        <label>Website</label>
                        <p><a href="{{ $user->website }}" target="_blank" rel="noopener noreferrer">{{ $user->website }}</a></p>
                    </div>
                @endif
            </div>

            @if(!$user->email && !$user->phone && !$user->location && !$user->website)
                <div class="empty-state">
                    <i class="fa-regular fa-circle-info"></i>
                    <p>No contact information available.</p>
                </div>
            @endif
        </div>

        <!-- User Posts Section -->
        <div class="profile-details" style="margin-top: 30px;">
            <h2>Posts</h2>
            <div id="userPostsContainer">
                <!-- Posts will be loaded here via JavaScript -->
                <div class="empty-state">
                    <i class="fa-regular fa-newspaper"></i>
                    <p>Loading posts...</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Load user posts
        document.addEventListener('DOMContentLoaded', function() {
            const container = document.getElementById('userPostsContainer');
            const userId = {{ $user->id }};

            fetch(`/news-feed/posts?user_id=${userId}&per_page=10`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.data && data.data.length > 0) {
                        container.innerHTML = '';
                        data.data.forEach(post => {
                            const postElement = document.createElement('div');
                            postElement.className = 'post-item';
                            postElement.innerHTML = `
                                <div style="background: white; padding: 20px; border-radius: 8px; margin-bottom: 20px; border: 1px solid var(--border-color);">
                                    <p>${post.content || ''}</p>
                                    <div style="display: flex; gap: 20px; margin-top: 15px; color: var(--text-secondary); font-size: 14px;">
                                        <span><i class="fa-regular fa-heart"></i> ${post.likes_count || 0}</span>
                                        <span><i class="fa-regular fa-comment"></i> ${post.comments_count || 0}</span>
                                        <span><i class="fa-regular fa-share"></i> ${post.shares_count || 0}</span>
                                    </div>
                                </div>
                            `;
                            container.appendChild(postElement);
                        });
                    } else {
                        container.innerHTML = `
                            <div class="empty-state">
                                <i class="fa-regular fa-newspaper"></i>
                                <p>No posts yet.</p>
                            </div>
                        `;
                    }
                })
                .catch(error => {
                    console.error('Error loading posts:', error);
                    container.innerHTML = `
                        <div class="empty-state">
                            <i class="fa-regular fa-circle-exclamation"></i>
                            <p>Unable to load posts.</p>
                        </div>
                    `;
                });
        });
    </script>
@endsection
