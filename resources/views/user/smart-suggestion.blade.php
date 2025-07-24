@extends('layouts.main')
@section('content')



    <section class="industry_specialist">
        <div class="container">
            <div class="industry-heading text-center mb-5">
                <h1>Smart Suggestion</h1>
            </div>
            <div class="row g-4">
                <div class="col-md-3">
                    <div class="industry-profile-card">
                        <div class="profile-pic text-center">
                            <img src="https://muslimlynk.com/storage/profile_photos/kWJOgIFTLRdqTGSOd2QgaCJ4TbqkLaqtfq0BERTK.jpg"
                                alt="Hassan Abbas's Profile Picture" class="img-fluid rounded-circle">
                        </div>

                        <div class="profile_details text-center mt-3">
                            <h4 class="mb-1">Hassan Abbas</h4>
                            <p class="text-muted mb-1">N/A</p>
                            <p class="text-muted mb-1"><i class="fas fa-map-marker-alt"></i>
                                United States
                            </p>
                            <p class="text-muted mb-1">Finance</p>
                            <p class="text-muted mb-3">Member since: Nov 2024</p>
                        </div>

                        <div class="action-buttons d-flex justify-content-center gap-3">
                            <a href="https://muslimlynk.com/user/profile/hassan-abbas"
                                class="btn btn-outline-primary btn-sm" title="View Profile">
                                <i class="fas fa-eye"></i>
                            </a>
                            <a href="https://www.linkedin.com/in/https://www.linkedin.com/in/hassan-abbas-ea-ba080a21/"
                                class="btn btn-outline-info btn-sm" title="LinkedIn" target="_blank">
                                <i class="fab fa-linkedin"></i>
                            </a>
                        </div>
                    </div>
                </div>
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
@endsection
