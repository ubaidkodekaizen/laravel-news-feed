{{-- <div class="col-lg-12 user-results"> --}}
    @forelse($users as $user)
        <div class="user-card">
            <div class="user-card-flex">
                <div class="profile_name">
                    <div class="profile">
                        <img src="{{ $user->photo ? Storage::url($user->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}" alt="User Image">
                    </div>
                    <div class="profile_details">
                        <a href="{{ route('user.profile', ['slug' => $user->slug]) }}" target="_blank" class="user_name">{{ $user->first_name }} {{ $user->last_name }}</a>
                        <p class="user_position">{{ $user->company->company_position }} at {{ $user->company->company_name }}</p>
                        <p class="user_location">{{ $user->city }}, {{ $user->state }}, {{ $user->country }}</p>
                    </div>
                </div>
                <div class="btn_flex">
                    <a href="{{ route('user.profile', ['slug' => $user->slug]) }}" target="_blank" class="btn btn-success btn-sm">View Profile</a>
                    <a href="{{ $user->linkedin_url }}" target="_blank" class="btn btn-primary btn-sm">Connect on LinkedIn</a>
                </div>
            </div>
            <div class="indus_member">
                <p class="user_about"><strong>Industry: </strong> {{ $user->company->company_sub_category }}, {{ $user->company->company_industry }}</p>
                <p class="user_exp"><strong>Member since: </strong> {{ $user->created_at->format('M d, Y') }}</p>
            </div>
        </div>
    @empty
        <div class="user-card">
            <h2>No User Found</h2>
        </div>
    @endforelse
{{-- </div> --}}

<!-- Pagination Links -->
<div id="pagination" class="pagination justify-content-center">
    {{ $users->links() }}
</div>
