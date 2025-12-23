@forelse($users as $user)
    <div class="col-lg-3 user-results">
        <div class="user-card">
            <div class="user-card-flex">
                <div class="profile_name">
                    <div class="profile">
                        @if ($user->user_has_photo)
                            <img src="{{ asset('storage/' . $user->photo) }}" alt="{{ $user->first_name }}">
                        @else
                            <div class="avatar-initials">
                                {{ $user->user_initials }}
                            </div>
                        @endif
                    </div>
                    <div class="profile_details mt-3">
                        <a href="{{ route('user.profile', ['slug' => $user->slug]) }}" target="_blank"
                            class="user_name">{{ $user->first_name }} {{ $user->last_name }}</a>
                        <p class="user_position">{{ $user->company->company_position }} at
                            {{ $user->company->company_name }}</p>
                        <p class="user_location">{{ $user->city }} {{ $user->state }} {{ $user->country }}</p>
                    </div>
                </div>
            </div>
            <div class="indus_member">
                <p class="user_about"><strong>Industry: </strong> {{ $user->company->company_industry }}</p>
                <p class="user_exp"><strong>Member since: </strong>
                    {{ $user->created_at ? $user->created_at->format('M d, Y') : 'Not available' }}
                </p>
            </div>
            <div class="btn_flex gap-2">
                <a href="javascript:void(0)" class="btn btn-secondary  btn-sm direct-message-btn"
                    data-receiver-id="{{ $user->id }}">
                    <i class="fa-solid fa-comment-dots"></i>
                </a>
                <a href="{{ route('user.profile', ['slug' => $user->slug]) }}" target="_blank"
                    class="btn btn-success btn-sm">
                    <i class="fa-solid fa-eye"></i>
                </a>
                <a href="{{ $user->linkedin_url }}" target="_blank" class="btn btn-primary btn-sm">
                    <i class="fa-brands fa-linkedin"></i>
                </a>
            </div>
        </div>
    </div>
@empty
    <div class="col-12">
        <div class="user-card">
            <h2>No User Found</h2>
        </div>
    </div>
@endforelse

<!-- Pagination Section with Per Page Selector -->
<div class="col-12">
    <div class="pagination-container">
        <div class="d-flex align-items-center justify-content-between flex-wrap gap-3 pagination-container-inner">
            {{-- Left: Records per page dropdown --}}
            <div class="d-flex align-items-center gap-2 recordPerPageBox">
                <span>Records per page</span>
                <select class="form-select form-select-sm" style="width: auto;" id="perPageSelect">
                    <option value="12" {{ request('per_page', 12) == 12 ? 'selected' : '' }}>12</option>
                    <option value="24" {{ request('per_page', 12) == 24 ? 'selected' : '' }}>24</option>
                    <option value="50" {{ request('per_page', 12) == 50 ? 'selected' : '' }}>50</option>
                    <option value="100" {{ request('per_page', 12) == 100 ? 'selected' : '' }}>100</option>
                </select>
            </div>

            {{-- Center: Pagination controls --}}
            <div class="pagination-wrapper">
                {{ $users->links('partial.search-pagination') }}
            </div>

            {{-- Right: Record count display --}}
            <span id="recordsInfo">
                {{ $users->firstItem() ?? 0 }} - {{ $users->lastItem() ?? 0 }} of {{ $users->total() }} records
            </span>
        </div>
    </div>

    {{-- Hidden data for JavaScript to read pagination info --}}
    <div id="paginationData" style="display:none;" data-first-item="{{ $users->firstItem() ?? 0 }}"
        data-last-item="{{ $users->lastItem() ?? 0 }}" data-total="{{ $users->total() }}">
    </div>
</div>
