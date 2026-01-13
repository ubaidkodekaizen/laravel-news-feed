<div class="card" id="feedProfileCard">
    <div class="profile_cover_bg"></div>

    <div class="profile_card_pic">
        <img src="{{ getImageUrl(auth()->user()->photo) ?? (auth()->user()->avatar ?? 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png') }}"
            class="rounded-circle border border-2 border-white" width="60" height="60"
            alt="{{ auth()->user()->name ?? 'Profile' }}">
    </div>

    <div class="profile_card_details">
        <h6>{{ auth()->user()->name ?? (auth()->user()->first_name ?? '') . ' ' . (auth()->user()->last_name ?? '') }}
        </h6>
        <p>{{ auth()->user()->bio ?? (auth()->user()->headline ?? 'Welcome to MuslimLynk - your gateway to empowerment, collaboration, and success within the Muslim community.') }}
        </p>
        <div class="divider"></div>
        <div class="profile_card_details_inner">
            <div class="profile_card_details_inner_box">
                <h4>Profile views</h4>
                <p>{{ $profileViews ?? 0 }}</p>
            </div>
            <div class="profile_card_details_inner_box">
                <h4>Post impressions</h4>
                <p>{{ $postImpressions ?? 0 }}</p>
            </div>
        </div>
    </div>
</div>

<div class="card" id="feedAdCard">
    <div class="card-img">
        <img src="{{ asset('assets/images/postAdImg.png') }}" class="img-fluid" alt="">
        <img src="{{ asset('assets/images/postAdIcon.png') }}" class="img-fluid feedAdWatermarkImg" alt="">
    </div>
    <a href="#" class="feedAdCardLinkedin">
        <i class="fa-brands fa-linkedin-in"></i>
    </a>
    <h4>Transform Your LinkedIn Outreach with <span>KodeReach</span></h4>
    <p>KodeReach is an advanced automation tool built to simplify LinkedIn prospecting. With just a LinkedIn or Sales
        Navigator URL.</p>
    <a href="#" class="feedAdCardCta">Request a Demo</a>
</div>
