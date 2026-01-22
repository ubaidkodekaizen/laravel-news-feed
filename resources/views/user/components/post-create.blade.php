<div class="card mb-4" id="postCreateSec">
    <div class="card-body">
        <div class="postCreateBtn">

                @if ($authUserData['user_has_photo'] && $authUserData['photo'])
                    <img src="{{ $authUserData['photo'] }}"
                        alt="{{ trim($authUserData['first_name'] . ' ' . $authUserData['last_name']) }}"
                        class="img-fluid userProfilePic"
                        onerror="this.onerror=null; this.style.display='none'; this.nextElementSibling.style.display='flex';">
                    <div class="profile-initials-avatar" style="display: none;">
                        {{ $authUserData['user_initials'] }}
                    </div>
                @else
                    <div class="profile-initials-avatar">
                        {{ $authUserData['user_initials'] }}
                    </div>
                @endif


            <button class="form-control" id="openPostModal">Start a post</button>
        </div>



        <div class="divider"></div>
        <div class="postCreateActions">
            <button type="button" class="addPhoto">
                <img src="{{ asset('assets/images/postPhoto.svg') }}" class="img-fluid" alt="">
                Photo
            </button>
            <button type="button" class="addVideo">
                <img src="{{ asset('assets/images/postVideo.svg') }}" class="img-fluid" alt="">
                Video
            </button>
        </div>
    </div>

</div>
