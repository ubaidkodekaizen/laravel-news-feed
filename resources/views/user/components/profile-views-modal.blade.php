<div class="modal fade" id="profileViewsModal" tabindex="-1" aria-labelledby="profileViewsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="profileViewsModalLabel">Profile Views</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
            <div class="modal-body">
                <!-- Loading State -->
                <div id="profileViewsLoading" class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="text-muted mt-2">Loading profile views...</p>
                </div>

                <!-- Profile Views List -->
                <div id="profileViewsList" class="profile-views-list d-none">
                    <!-- Views will be inserted here -->
                </div>

                <!-- Empty State -->
                <div id="profileViewsEmpty" class="text-center py-4 d-none">
                    <i class="fa-regular fa-eye fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No profile views yet</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .profile-views-list {
        max-height: 400px;
        overflow-y: auto;
        padding: 0;
    }

    .profile-view-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 0;
        border-bottom: 1.34px solid #CAD9FF99;
        transition: background-color 0.2s;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
    }

    .profile-view-item:last-child {
        border-bottom: none;
    }

    .profile-view-item:hover {
        background-color: #f0f2f5;
        text-decoration: none;
        color: inherit;
    }

    .profile-view-item .user-avatar {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        object-fit: cover;
        flex-shrink: 0;
    }

    .profile-view-item .user-initials {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 600;
        font-size: 14px;
        text-transform: uppercase;
        flex-shrink: 0;
    }

    .profile-view-item .user-info {
        flex: 1;
        min-width: 0;
    }

    .profile-view-item .user-name {
        color: #17272F;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-family: Inter;
        font-weight: 600;
        font-size: 14.5px;
        line-height: 100%;
        margin: 0 0 4px 0;
    }

    .profile-view-item .user-position {
        color: #5D5D5D;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        font-family: Roboto;
        font-weight: 400;
        font-size: 12.93px;
        line-height: 100%;
    }

    .profile-view-item .view-time {
        color: #65676b;
        font-size: 12px;
        white-space: nowrap;
    }

    /* Mobile responsive */
    @media (max-width: 576px) {
        .modal-dialog {
            margin: auto;
            max-width: 95%;
        }

        .profile-views-list {
            max-height: 60vh;
        }
    }
</style>

<script>
    // Function to format time ago
    function formatTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffInSeconds = Math.floor((now - date) / 1000);

        if (diffInSeconds < 60) return 'Just now';
        if (diffInSeconds < 3600) return Math.floor(diffInSeconds / 60) + 'm ago';
        if (diffInSeconds < 86400) return Math.floor(diffInSeconds / 3600) + 'h ago';
        if (diffInSeconds < 604800) return Math.floor(diffInSeconds / 86400) + 'd ago';
        if (diffInSeconds < 2592000) return Math.floor(diffInSeconds / 604800) + 'w ago';
        if (diffInSeconds < 31536000) return Math.floor(diffInSeconds / 2592000) + 'mo ago';
        return Math.floor(diffInSeconds / 31536000) + 'y ago';
    }

    // Function to load profile views
    function loadProfileViews() {
        const loadingEl = document.getElementById('profileViewsLoading');
        const listEl = document.getElementById('profileViewsList');
        const emptyEl = document.getElementById('profileViewsEmpty');

        // Show loading, hide others
        loadingEl.classList.remove('d-none');
        listEl.classList.add('d-none');
        emptyEl.classList.add('d-none');

        fetch('{{ route("feed.profile.views") }}', {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json',
            }
        })
        .then(response => response.json())
        .then(data => {
            loadingEl.classList.add('d-none');

            if (data.success && data.views && data.views.length > 0) {
                listEl.innerHTML = '';
                data.views.forEach(view => {
                    const item = document.createElement('a');
                    item.href = view.user.slug ? `/user/profile/${view.user.slug}` : '#';
                    item.className = 'profile-view-item';
                    
                    const avatar = view.user.has_photo && view.user.avatar
                        ? `<img src="${view.user.avatar}" alt="${view.user.name}" class="user-avatar" onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';"><div class="user-initials" style="display: none;">${view.user.initials}</div>`
                        : `<div class="user-initials">${view.user.initials}</div>`;

                    item.innerHTML = `
                        ${avatar}
                        <div class="user-info">
                            <div class="user-name">${view.user.name}</div>
                            ${view.user.position ? `<div class="user-position">${view.user.position}</div>` : ''}
                        </div>
                        <div class="view-time">${formatTimeAgo(view.viewed_at)}</div>
                    `;
                    listEl.appendChild(item);
                });
                listEl.classList.remove('d-none');
            } else {
                emptyEl.classList.remove('d-none');
            }
        })
        .catch(error => {
            console.error('Error loading profile views:', error);
            loadingEl.classList.add('d-none');
            emptyEl.classList.remove('d-none');
        });
    }

    // Load profile views when modal is shown
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('profileViewsModal');
        if (modal) {
            modal.addEventListener('show.bs.modal', function() {
                loadProfileViews();
            });
        }
    });
</script>
