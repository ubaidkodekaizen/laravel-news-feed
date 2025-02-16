@extends('layouts.main')
@section('content')



    <section class="industry_specialist">
        <div class="container">
            <div class="industry-heading text-center mb-5">
                <h1>Industry Experts</h1>
            </div>
            <div class="row g-4">

                @if ($users->isEmpty())
                    <p>No experts found for {{ $industry }}.</p>
                @else
                    @foreach ($users as $user)
                        <div class="col-md-3">
                            <div class="industry-profile-card">
                                <!-- Profile Picture -->
                                
                                <div class="profile-pic text-center">
                                    <img src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://placehold.co/150' }}"
                                        alt="{{ $user->first_name }}'s Profile Picture" class="img-fluid rounded-circle">
                                </div>

                                <!-- Profile Details -->
                                <div class="profile-details text-center mt-3">
                                    <h4 class="mb-1">{{ $user->first_name }} {{ $user->last_name }}</h4>
                                    <p class="text-muted mb-1">{{ $user->company->company_position ?? 'N/A' }}</p>
                                    <p class="text-muted mb-1"><i class="fas fa-map-marker-alt"></i>
                                        {{ $user->country ?? 'N/A' }}</p>
                                    <p class="text-muted mb-1">{{ $user->company->company_industry ?? 'N/A' }}</p>
                                    <p class="text-muted mb-3">Member since: {{ $user->created_at->format('M Y') }}</p>
                                </div>

                                <!-- Action Buttons -->
                                <div class="action-buttons d-flex justify-content-center gap-3">
                                    <a href="{{ route('user.profile', ['slug' => $user->slug]) }}"
                                        class="btn btn-outline-primary btn-sm" title="View Profile">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="javascript:void(0)"
                                        class="btn btn-outline-success btn-sm" title="Message">
                                        <i class="fas fa-envelope"></i>
                                    </a>
                                    <a href="https://www.linkedin.com/in/{{ $user->linkedin_url ?? '' }}" class="btn btn-outline-info btn-sm"
                                        title="LinkedIn" target="_blank">
                                        <i class="fab fa-linkedin"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>
        </div>
    </section>

    <section class="lp_footer">
        <div class="container">
            <div class="row">
                <div class="col">
                    <h3>STAYS</h3>
                    <ul class="footer_list">
                        <li>
                            <a href="javascript:void(0);">
                                Hotels
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Resorts
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Villas
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Farm Stays
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Appartments
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    <h3>ABOUT US</h3>
                    <ul class="footer_list">
                        <li>
                            <a href="javascript:void(0);">
                                Our team
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Our branches
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Join us
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                For a sustainable world
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Campaigns
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    <h3>SERVICES</h3>
                    <ul class="footer_list">
                        <li>
                            <a href="javascript:void(0);">
                                Holidays stays
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Conferences
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Conventions
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Presentations
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Team building
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="col">
                    <h3>POLICY</h3>
                    <ul class="footer_list">
                        <li>
                            <a href="javascript:void(0);">
                                Terms and conditions
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Privacy
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Cookies
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Legal information
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Sustainablility
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);">
                                Safety Resources Center
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <p class="powered_by">
                Powered By <a href="https://amcob.org/" target="_blank" rel="noopener noreferrer">AMCOB</a>
            </p>
        </div>
    </section>
@endsection

