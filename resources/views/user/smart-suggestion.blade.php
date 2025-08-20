@extends('layouts.main')
@section('content')
    <section class="industry_specialist">
        <div class="container">
            <div class="industry-heading text-center mb-5">
                <h1>Smart Suggestion</h1>
            </div>
            <div class="row g-4">

                @forelse($suggestions as $suggestion)
                    @php
                        $user = $suggestion['user'];
                        $company = $suggestion['company'];
                        $education = $user->userEducations->first(); // first education if exists
                    @endphp

                    <div class="col-md-3">
                        <div class="industry-profile-card">
                            <div class="profile-pic text-center">
                                <img src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://via.placeholder.com/150' }}"
                                    alt="{{ $user->first_name }} {{ $user->last_name }}'s Profile Picture"
                                    class="img-fluid rounded-circle">
                            </div>

                            <div class="profile_details text-center mt-3">
                                <h4 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
                                <p class="text-muted mb-1">{{ $company->company_position ?? 'N/A' }}</p>
                                <p class="text-muted mb-1">
                                    <i class="fas fa-map-marker-alt"></i>
                                    {{ $user->country ?? 'N/A' }}
                                </p>
                                <p class="text-muted mb-1">
                                    {{ $company->company_industry ?? 'N/A' }}
                                </p>

                                {{-- Show education if available --}}
                                @if ($education)
                                    <p class="text-muted small">
                                        🎓 {{ $education->college_university }} ({{ $education->degree_diploma }})
                                    </p>
                                @endif

                                <p class="text-muted mb-3">Member since: {{ $user->created_at->format('M Y') }}</p>

                                {{-- Match Score with hover tooltip --}}
                                <p class="fw-bold text-primary">
                                    Match Score: {{ $suggestion['score'] }}
                                    <i class="fas fa-info-circle text-muted" data-bs-toggle="tooltip"
                                        title="This score is based on industry, role, location & education match."></i>
                                </p>
                            </div>

                            <div class="action-buttons d-flex justify-content-center gap-3">
                                <a href="{{ url('user/profile/' . $user->slug) }}" class="btn btn-outline-primary btn-sm"
                                    title="View Profile">
                                    <i class="fas fa-eye"></i>
                                </a>
                                @if ($user->linkedin_url)
                                    <a href="{{ $user->linkedin_url }}" class="btn btn-outline-info btn-sm"
                                        title="LinkedIn" target="_blank">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>No smart suggestions available right now.</p>
                    </div>
                @endforelse

            </div>
        </div>
    </section>

    <section class="lp_footer">
        <div class="container">
            <p class="powered_by">
                Powered By <a href="https://amcob.org/" target="_blank" rel="noopener noreferrer">AMCOB</a>
            </p>
        </div>
    </section>

    {{-- Enable Bootstrap tooltips --}}
    @push('scripts')
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
                var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl)
                })
            });
        </script>
    @endpush
@endsection
