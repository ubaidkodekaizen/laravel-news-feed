@extends('layouts.dashboard-layout')


@section('dashboard-content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.23.0/sweetalert2.min.css" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />
    <style>
        /* Validation Error Styles */
        label.error {
            font-weight: 400 !important;
        }

        .error {
            color: #dc3545 !important;
            font-size: 14px !important;
            margin-top: 0 !important;
            display: block !important;
        }

        .form-control.error,
        .form-select.error {
            border-color: #dc3545 !important;
        }

        .select2-container--bootstrap-5.error .select2-selection {
            border-color: #dc3545 !important;
        }

        .noticeText {
            width: 100%;
            max-width: 100%;
        }

        .noticeText strong {
            font-family: "Inter", sans-serif;
            font-weight: 600;
            font-size: 16px;
            line-height: 100%;
            color: #273572;
            margin: 0px 0px 0px 0;
        }

        .noticeText p {
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 14px;
            line-height: 140%;
            color: #333333;
            margin: 5px 0 0 0;
            width: 100%;
            max-width: 700px;
        }

        .profileMainHeading {
            font-family: "Inter", sans-serif;
            font-weight: 600;
            font-size: 28px;
            line-height: 1;
            margin: 0px 0px 22px 0;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
            flex-direction: row-reverse;
            background: #B8C034;
            gap: 10px;
            border-radius: 4px;
            border: none;
            margin: 0px 8px 8px 0px;

            padding: 5px 10px 5px 10px;
        }

        .select2-container--bootstrap-5 .select2-selection {
            background: #FFFFFF;
            border: 2px solid #E9EBF0;
            box-shadow: none !important;
            border-radius: 9.77px;
            padding: 13px 13px 13px 13px;
            min-height: 61.5px;
            display: flex;
            flex-direction: column;
            align-items: start;
            justify-content: center;
        }


        .select2-container .select2-selection--single .select2-selection__rendered {
            max-width: 200px;
        }

        /* Apply ellipsis to the text ONLY */
        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__choice__display {
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 16px;
            line-height: 1.2em;
            overflow: hidden;
            text-overflow: ellipsis;
            max-width: 150px;
            width: 100%;
            text-wrap-mode: nowrap;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice .select2-selection__choice__remove {
            color: #000000;
            filter: brightness(100) invert(1);
        }

        .select2-container--bootstrap-5 .select2-dropdown .select2-results__options .select2-results__option.select2-results__option--selected,
        .select2-container--bootstrap-5 .select2-dropdown .select2-results__options .select2-results__option[aria-selected=true]:not(.select2-results__option--highlighted) {
            color: black;
            background-color: #B8C034;
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 16px;
            line-height: 100%;
            padding: 10px 10px;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-search {
            height: unset;
        }



        .select2-container--bootstrap-5 .select2-dropdown .select2-search .select2-search__field {
            border: 2px solid #E9EBF0;
            width: 100% !important;
            padding: 5px 6px;
            height: 36px;
            border-radius: 4px;
        }

        .select2-container--bootstrap-5 .select2-dropdown .select2-search .select2-search__field:focus {
            border: 2px solid #E9EBF0;
            box-shadow: none;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border: 2px solid #E9EBF0;
            box-shadow: none;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border: 2px solid #E9EBF0;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__clear,
        .select2-container--bootstrap-5 .select2-selection--single .select2-selection__clear {
            top: 30px;
            right: 15px;
        }

        .select2-container--bootstrap-5 .select2-selection::after {
            content: "";
            position: absolute;
            right: 20px;
            top: 28px;
            background-image: url("{{ asset('assets/images/selectAnchorIcon.png') }}");
            height: 16px;
            width: 16px;
            background-size: contain;
            background-repeat: no-repeat;
        }

        .new_user_details .form-control {
            background: #FFFFFF;
            border-radius: 9.77px;
            border: 2px solid #E9EBF0;
            padding: 19px 16px;
            font-family: Inter;
            font-weight: 400;
            font-size: 16px;
            line-height: 100%;
            color: #000;
        }

        .new_user_details .input-group {
            min-height: 61px;
            flex-wrap: unset;
            overflow: hidden;
        }

        .new_user_details .input-group .input-group-text {
            width: 100%;
            max-width: 60%;
        }

        .new_user_details .input-group input {
            max-width: 40%;
            width: 100%;
            background: #fff;
        }

        .new_user_details #company_linkedin_user {
            position: absolute;
            top: 50%;
            right: 0;
            height: 100%;
            width: 40%;
            background: #fff;
            z-index: 1;
            transform: translateY(-50%);
        }

        .new_user_details .accordion-item {
            background: #F9F9F9;
            margin: 0px 0px 14px 0px;
            border-radius: 9.77px;
        }

        .new_user_details .accordion-item:last-child {
            margin: 0;
        }

        .new_user_details .accordion-button {
            background: transparent !important;
            font-family: Poppins;
            font-weight: 600 !important;
            font-size: 20px !important;
            line-height: 100%;
            color: #273572 !important;
            padding: 25px 35px 22px 33px;
            border-top-left-radius: 9.77px !important;
            border-top-right-radius: 9.77px !important;
        }



        .new_user_details .accordion-button::before {
            height: 36px;
            width: 36px;
            content: "";
            background-color: #273572;
            border-radius: 50%;
            position: absolute;
            right: 35px;
        }

        .new_user_details .accordion-button::after {
            position: absolute;
            right: 35px;
            height: 36px;
            width: 36px;
            z-index: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-position: center center;
            filter: invert(1) brightness(100);
        }

        .new_user_details .accordion-body {
            padding: 25px 30px 79px 30px;
        }

        .new_user_details .accordion-item:last-of-type>.accordion-header .accordion-button.collapsed {
            border-top: 1px solid #E9EBF0;
        }


        .main-content {
            width: 100%;
            flex: 1;
            height: calc(100vh - 109px);
            overflow-x: hidden;
        }

        .new_user_details .form-check-row {
            display: flex;
            align-items: center;
            justify-content: start;
            gap: 18px;
            min-height: 57px;
        }

        .new_user_details .form-check-row .form-check {
            display: flex;
            align-items: center;
            justify-content: start;
            gap: 12px;
            padding: 0;
        }

        .new_user_details .form-check-row label {
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-size: 18px;
            line-height: 100%;
            color: #000000;
            margin: 0;
            position: relative;
            padding-left: 26px;
            cursor: pointer;
        }



        .new_user_details .form-check-row .form-check input[type="radio"] {
            display: none;
        }


        .new_user_details .form-check-row .form-check label::before {
            content: "";
            position: absolute;
            left: 0;
            top: 50%;
            transform: translateY(-50%);
            width: 18px;
            height: 18px;
            border: 1px solid #273572;
            border-radius: 50%;
        }

        .form-check-row .form-check input[type="radio"]:checked+label::after {
            content: "";
            position: absolute;
            left: 3px;
            top: 50%;
            transform: translateY(-50%);
            width: 12px;
            height: 12px;
            background: #B8C034;
            border-radius: 50%;
        }

        .profileSaveBtn {
            border-radius: 9.77px;
            padding: 15px 56px;
            font-family: "Poppins", sans-serif;
            font-weight: 500;
            font-size: 22px;
            line-height: 100%;
            letter-spacing: 0px;
            text-align: center;
            margin: 0 0 0 0;
            width: 100%;
            max-width: 167px;
        }

        .profileTooltip {
            margin: 0px 0px 0px 5px;
            cursor: pointer;
            position: relative;
        }

        .profileTooltipText {
            visibility: hidden;
            opacity: 0;
            transition: .3s;
            position: absolute;
            background: #273572;
            width: 238px;
            bottom: 35px;
            right: -75px;
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-size: 14px;
            line-height: 131%;
            color: #FFFFFF;
            padding: 12px 14px;
            border-radius: 7px;
            user-select: none;
            z-index: 9999;
        }

        .profileTooltip:hover .profileTooltipText {
            visibility: visible;
            opacity: 1;
            transition: .3s;
        }

        .profileTooltipTextCurve {
            background: #273572;
            border-radius: 4px;
            width: 40px;
            height: 40px;
            transform: rotate(46deg) skew(3deg, 360deg);
            position: absolute;
            bottom: -5px;
            right: 63px;
            z-index: -1;
        }

        @media(max-width: 1720px) {
            .new_user_details_inner_box_form_group_box {
                max-width: 48%;
                align-items: stretch;
            }
        }

        @media (max-width: 1400px) {

            .col-lg-3 {
                width: 50% !important;
            }
        }

        @media(max-width: 1310px) {
            .new_user_details_inner_box_form_group_box {
                max-width: 100%;
            }
        }

        @media(max-width: 850px) {
            .new_user_details_inner {
                flex-direction: column;
            }

            .profile_pic {
                height: unset;
                margin: 0 0 0 0;
            }

            .new_user_details_inner_box_form_group_box {
                max-width: 100%;
            }

            .select2-container .select2-selection--single .select2-selection__rendered {
                max-width: 90%;
            }

            .avatar-upload .avatar-preview {
                height: 224px;
                width: 236px;
            }
        }

        @media (max-width: 768px) {
            .col-lg-3 {
                width: 100% !important;
            }

            input#linkedin_user {
                margin-top: 0;
            }

            .new_user_details #company_linkedin_user {
                transform: translateY(-68%);
            }
        }

        @media(max-width: 480px) {
            .new_user_details_inner_col:first-child {
                display: flex;
                align-items: center;
                justify-content: center;
                text-align: center;
            }

            .custom_card_profile .nav-tabs .nav-item {
                max-width: 135px;
                width: 100%;
            }

            .custom_card_profile .nav-tabs .nav-link {
                font-size: 16px;
                min-height: 55px;
            }

            .profileSaveBtn {
                width: 100%;
                max-width: 100%;
            }
        }

        @media (max-width: 486px) {
            .main-content {
                padding: 70px 14px 20px 14px !important;
            }
        }

        @media (max-width: 544px) {


            .container {
                padding: 0;
            }

            .profileTooltip {
                position: absolute;
                right: 0;
            }

            .profileTooltipText {
                right: -21px;
            }

            .profileTooltipTextCurve {
                right: 9px;
            }

            .new_user_details .accordion-body {
                padding: 25px 20px 79px 20px;
            }

            .user_company_profile .new_user_details label:not(.form-check-label) {
                font-size: 15px;
                position: relative;
                padding-right: 20px;
            }

            .new_user_details .form-control {
                font-size: 14px;
            }

            .profileSaveBtn {
                border-radius: 9.77px;
                padding: 15px 56px;
                font-family: "Poppins", sans-serif;
                font-weight: 500;
                font-size: 16px;
            }

            .toggle__label {
                padding-right: 0 !important;
            }


        }
    </style>
    <section class="user_company_profile">
        <div class="container">
            <div class="custom_card_profile">

                <h2 class="profileMainHeading">My Profile</h2>

                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="user-details" data-bs-toggle="tab"
                            data-bs-target="#user-details-pane" type="button" role="tab"
                            aria-controls="user-details-pane" aria-selected="true">Personal</button>
                    </li>
                    <!-- Professional/Company tab removed - not part of newsfeed boilerplate -->
                </ul>

                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif


                <div class="tab-content" id="myTabContent">
                    <div class="tab-pane fade show active" id="user-details-pane" role="tabpanel"
                        aria-labelledby="user-details" tabindex="0">
                        <div class="new_user_details">
                            <form action="{{ route('user.details.update') }}" method="POST" enctype="multipart/form-data"
                                id="user_details">
                                @csrf

                                <div class="new_user_details_inner">
                                    <div class="new_user_details_inner_col">


                                        <div class="profile_pic">
                                            <h4 class="profile_pic_head">
                                                Upload Picture
                                            </h4>
                                            <div class="avatar-upload">
                                                <div class="avatar-edit">
                                                    <input type='file' id="imageUpload" name="photo"
                                                        accept=".png, .jpg, .jpeg" />
                                                </div>
                                                <div class="avatar-preview">
                                                    <div id="imagePreview">
                                                        @if ($user->user_has_photo)
                                                            <img src="{{ getImageUrl($user->photo) }}"
                                                                alt="">
                                                        @else
                                                            <div class="avatar-initials"
                                                                style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: #394a93; color: white; font-size: 34px; font-weight: 600;">
                                                                {{ $user->user_initials }}
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <button type="button" class="profile_pic_btn personalProfilePicBtn">
                                                <img src="{{ asset('assets/images/editIcon.png') }}" class="img-fluid"
                                                    alt="">
                                                Edit Picture
                                            </button>
                                        </div>
                                    </div>
                                    <div class="new_user_details_inner_col">

                                        @if ($errors->any())
                                            <div class="alert alert-danger">
                                                <ul>
                                                    @foreach ($errors->all() as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        @endif


                                        <div class="new_user_details_inner_box">
                                            <h4>Profile Information</h4>
                                            <div class="new_user_details_inner_box_form_group_row">
                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="first_name">First Name<span class="text-danger">*</span></label>
                                                    <input type="text" name="first_name" id="first_name" class="form-control" value="{{ old('first_name', $user->first_name) }}" required>
                                                </div>
                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="last_name">Last Name<span class="text-danger">*</span></label>
                                                    <input type="text" name="last_name" id="last_name" class="form-control" value="{{ old('last_name', $user->last_name) }}" required>
                                                </div>
                                            </div>
                                            <!-- 'Are You?' section removed - user_position field doesn't exist in users table -->
                                        </div>
                                        <div class="new_user_details_inner_box">
                                            <h4>Contact Information</h4>
                                            <div class="new_user_details_inner_box_form_group_row">
                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="phone">Phone<span class="text-danger">*</span></label>
                                                    <input type="tel" name="phone" id="phone" class="form-control phone_number" value="{{ old('phone', $user->phone) }}" required>
                                                    <!-- phone_public toggle removed - field doesn't exist in users table -->
                                                </div>
                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="email">Email<span class="text-danger">*</span></label>
                                                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                                                    <!-- email_public toggle removed - field doesn't exist in users table -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="new_user_details_inner_box">
                                            <h4>About & Location</h4>
                                            <div class="new_user_details_inner_box_form_group_row">
                                                <div class="new_user_details_inner_box_form_group_box" style="width: 100%;">
                                                    <label for="bio">Bio</label>
                                                    <textarea name="bio" id="bio" class="form-control" rows="4" placeholder="Tell us about yourself...">{{ old('bio', $user->bio) }}</textarea>
                                                </div>
                                            </div>
                                            <div class="new_user_details_inner_box_form_group_row">
                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="location">Location</label>
                                                    <input type="text" name="location" id="location" class="form-control" value="{{ old('location', $user->location) }}" placeholder="City, Country">
                                                </div>
                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="website">Website</label>
                                                    <input type="url" name="website" id="website" class="form-control" value="{{ old('website', $user->website) }}" placeholder="https://example.com">
                                                </div>
                                            </div>
                                        </div>


                                        <!-- Personal Details section removed - fields (gender, age_group, ethnicity, nationality, marital_status, languages) don't exist in users table -->



                                        <!-- Community & Giving section removed - not part of newsfeed boilerplate -->
                                        <div class="new_user_details_inner_box">
                                            <h4>Social Details</h4>
                                            <div class="new_user_details_inner_box_form_group_row">

                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="linkedin_url">LinkedIn<span
                                                            class="text-danger">*</span></label>
                                                    <input type="url" name="linkedin_url" id="linkedin_url"
                                                        class="form-control"
                                                        placeholder="https://www.linkedin.com/company/your-company"
                                                        value="{{ old('linkedin_url', $user->linkedin_url ?? '') }}">
                                                </div>
                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="facebook_url">Facebook</label>
                                                    <input type="text" name="facebook_url" id="facebook_url"
                                                        class="form-control"
                                                        value="{{ old('facebook_url', $user->facebook_url) }}"
                                                        placeholder="Link">
                                                </div>

                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="x_url">X (Formerly Twitter)</label>
                                                    <input type="text" name="x_url" id="x_url"
                                                        class="form-control" value="{{ old('x_url', $user->x_url) }}"
                                                        placeholder="Link">
                                                </div>
                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="instagram">Instagram</label>
                                                    <input type="text" name="instagram_url" id="instagram"
                                                        class="form-control" placeholder="Link"
                                                        value="{{ old('x_url', $user->instagram_url) }}">
                                                </div>
                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="tikTok">TikTok</label>
                                                    <input type="text" name="tiktok_url" id="tikTok"
                                                        class="form-control" placeholder="Link"
                                                        value="{{ old('x_url', $user->tiktok_url) }}">
                                                </div>
                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="youTube">YouTube</label>
                                                    <input type="text" name="youtube_url" id="youTube"
                                                        class="form-control" placeholder="Link"
                                                        value="{{ old('x_url', $user->youtube_url) }}">
                                                </div>
                                            </div>






                                        </div>

                                        <button type="submit" class="btn btn-primary profileSaveBtn">Save</button>

                                    </div>
                                </div>



                            </form>
                        </div>

                    </div>
                    <!-- Company tab removed - not part of newsfeed boilerplate -->
                    <div class="tab-pane fade d-none" id="company-details-tab-pane" role="tabpanel"
                        aria-labelledby="company-details-tab" tabindex="0" style="display: none !important;">
                        <!-- Company form removed -->
                                @csrf

                                <div class="new_user_details_inner">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div class="new_user_details_inner_col">
                                        <div class="profile_pic">
                                            <h4 class="profile_pic_head">
                                                Upload Picture
                                            </h4>
                                            <div class="avatar-upload">
                                                <div class="avatar-edit">
                                                    <input type='file' id="imageUploadCompany" name="company_logo"
                                                        accept=".png, .jpg, .jpeg" />

                                                </div>
                                                <div class="avatar-preview">
                                                    @php
                                                        // Use helper function that handles both S3 URLs and local storage
                                                        $companyLogoPreview = isset($company) && $company->company_logo 
                                                            ? getImageUrl($company->company_logo) 
                                                            : asset('assets/images/servicePlaceholderImg.png');
                                                    @endphp

                                                    <div id="imagePreviewCompany">
                                                        <img src="{{ $companyLogoPreview }}" alt="Company Logo Preview">
                                                    </div>

                                                </div>
                                            </div>
                                            <button type="button" class="profile_pic_btn companyProfilePicBtn">
                                                <img src="{{ asset('assets/images/editIcon.png') }}" class="img-fluid"
                                                    alt="">
                                                Edit Picture
                                            </button>
                                        </div>
                                    </div>
                                    <div class="new_user_details_inner_col">
                                        <div class="new_user_details_inner_box">
                                            <h4>Company Information</h4>
                                            <div class="new_user_details_inner_box_form_group_row">

                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="company_name">Company Name</label>
                                                    <input type="text" name="company_name" id="company_name"
                                                        class="form-control"
                                                        value="{{ old('company_name', $company->company_name ?? '') }}">
                                                </div>


                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="company_web_url">Company Website / URL</label>
                                                    <input type="text" name="company_web_url" id="company_web_url"
                                                        class="form-control"
                                                        value="{{ old('company_web_url', $company->company_web_url ?? '') }}">
                                                </div>

                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="company_linkedin_url">Company LinkedIn Page</label>
                                                    <input type="url" name="company_linkedin_url"
                                                        id="company_linkedin_url" class="form-control"
                                                        placeholder="https://www.linkedin.com/company/your-company"
                                                        value="{{ old('company_linkedin_url', $company->company_linkedin_url ?? '') }}">
                                                </div>


                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="company_business_type">Company Type</label>
                                                    {!! \App\Helpers\DropDownHelper::renderBusinessTypeDropdown($company->company_business_type ?? '') !!}
                                                    <div id="business_type_other_field" style="display: none;">
                                                        <label for="business_type_other">Other Company Type</label>
                                                        <input type="text" name="company_business_type_other"
                                                            id="business_type_other" class="form-control"
                                                            placeholder="Enter other business type">
                                                    </div>
                                                </div>


                                            </div>
                                        </div>

                                        <div class="new_user_details_inner_box">
                                            <h4>Role & Work Information</h4>
                                            <div class="new_user_details_inner_box_form_group_row">

                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="company_position">Title/Designation</label>
                                                    <select name="company_position_display" id="company_position"
                                                        class="form-select" multiple>
                                                        <!-- Options will be loaded via JavaScript -->
                                                    </select>
                                                    <input type="hidden" id="company_position_hidden"
                                                        name="company_position"
                                                        value="{{ old('company_position', $company->company_position ?? '') }}" />
                                                </div>


                                                <div
                                                    class="new_user_details_inner_box_form_group_box company_position_other_div d-none">
                                                    <label for="company_position_other">Title/Designation Other</label>
                                                    <input type="text" name="company_position_other"
                                                        id="company_position_other" class="form-control" value="">
                                                </div>

                                                <div
                                                    class="new_user_details_inner_box_form_group_box custom-select-dropdown">
                                                    <label for="company_experience">Years of Experience</label>
                                                    {!! \App\Helpers\DropDownHelper::renderCompanyExperienceDropdown($company->company_experience ?? '') !!}

                                                </div>

                                                <div class="new_user_details_inner_box_form_group_box">
                                                    <label for="work_phone_num">Work Phone Number</label>
                                                    <input type="tel" name="company_phone" id="company_phone"
                                                        class="form-control phone_number"
                                                        value="{{ old('company_phone', $company->company_phone ?? '') }}">
                                                </div>

                                            </div>
                                        </div>

                                        <div class="new_user_details_inner_box">
                                            <h4>Business Profile & Matching Preferences</h4>
                                            <div class="new_user_details_inner_box_form_group_row">

                                                <div class="new_user_details_inner_box_form_group_box mb-3">
                                                    <label for="company_industry">Industry <span
                                                            class="text-danger">*</span>
                                                        <div class="profileTooltip">
                                                            <div class="profileTooltipText">
                                                                Select the industry your company primarily operates in.
                                                                <div class="profileTooltipTextCurve"></div>
                                                            </div>
                                                            <img src="{{ asset('assets/images/noticeIcon.png') }}"
                                                                class="img-fluid" alt="">
                                                        </div>
                                                    </label>
                                                    <select name="company_industry_display" id="company_industry"
                                                        class="form-select" required>
                                                        <!-- Options will be loaded via JavaScript -->
                                                    </select>
                                                    <input type="hidden" id="company_industry_hidden"
                                                        name="company_industry"
                                                        value="{{ old('company_industry', $company->company_industry ?? '') }}" />
                                                </div>


                                                <div
                                                    class="new_user_details_inner_box_form_group_box mb-3 company_industry_other_div d-none">
                                                    <label for="company_industry_other">Industry Other</label>
                                                    <input type="text" name="company_industry_other"
                                                        id="company_industry_other" class="form-control" value="">
                                                </div>

                                                <div class="new_user_details_inner_box_form_group_box mb-3">
                                                    <label for="business_location">Business Location <span
                                                            class="text-danger">*</span>
                                                        <div class="profileTooltip">
                                                            <div class="profileTooltipText">
                                                                Select the primary market or region where your business
                                                                operates.
                                                                <div class="profileTooltipTextCurve"></div>
                                                            </div>
                                                            <img src="{{ asset('assets/images/noticeIcon.png') }}"
                                                                class="img-fluid" alt="">
                                                        </div>
                                                    </label>
                                                    <select name="business_location_display" id="business_location"
                                                        class="form-select" required>
                                                        <!-- Options will be loaded via JavaScript -->
                                                    </select>
                                                    <input type="hidden" id="business_location_hidden"
                                                        name="business_location"
                                                        value="{{ old('business_location', $userIcp->business_location ?? '') }}" />
                                                </div>

                                                <div class="new_user_details_inner_box_form_group_box mb-3">
                                                    <label for="company_no_of_employee">Company Size <span
                                                            class="text-danger">*</span>
                                                        <div class="profileTooltip">
                                                            <div class="profileTooltipText">
                                                                Choose the approximate size of your organization.
                                                                <div class="profileTooltipTextCurve"></div>
                                                            </div>
                                                            <img src="{{ asset('assets/images/noticeIcon.png') }}"
                                                                class="img-fluid" alt="">
                                                        </div>
                                                    </label>
                                                    {!! \App\Helpers\DropDownHelper::renderEmployeeSizeDropdown($company->company_no_of_employee ?? '') !!}
                                                </div>

                                                <div class="new_user_details_inner_box_form_group_box mb-3">
                                                    <label class="">Are You a Decision Maker? <span
                                                            class="text-danger">*</span>
                                                        <div class="profileTooltip">
                                                            <div class="profileTooltipText">
                                                                Indicate whether you have authority to approve
                                                                purchases.
                                                                <div class="profileTooltipTextCurve"></div>
                                                            </div>
                                                            <img src="{{ asset('assets/images/noticeIcon.png') }}"
                                                                class="img-fluid" alt="">
                                                        </div>

                                                    </label>
                                                    <div class="form-check-row">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="is_decision_maker" id="decision_maker_yes"
                                                                value="Yes"
                                                                {{ old('is_decision_maker', $userIcp->is_decision_maker ?? '') == 1 || old('is_decision_maker', $userIcp->is_decision_maker ?? '') == 'Yes' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="decision_maker_yes">
                                                                Yes
                                                            </label>
                                                        </div>

                                                        <div class="form-check">
                                                            <input class="form-check-input" type="radio"
                                                                name="is_decision_maker" id="decision_maker_no"
                                                                value="No"
                                                                {{ old('is_decision_maker', $userIcp->is_decision_maker ?? '') == 0 || old('is_decision_maker', $userIcp->is_decision_maker ?? '') == 'No' ? 'checked' : '' }}>
                                                            <label class="form-check-label" for="decision_maker_no">
                                                                No
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="new_user_details_inner_box_form_group_box mb-3">
                                                    <label for="company_revenue">Company Revenue:</label>
                                                    {!! \App\Helpers\DropDownHelper::renderRevenueDropdown($company->company_revenue ?? '') !!}
                                                </div>



                                                <div class="new_user_details_inner_box_form_group_box mb-3">
                                                    <label for="company_attributes">Company Attributes
                                                        <div class="profileTooltip">
                                                            <div class="profileTooltipText">
                                                                Select attributes that best describe your company.
                                                                <div class="profileTooltipTextCurve"></div>
                                                            </div>
                                                            <img src="{{ asset('assets/images/noticeIcon.png') }}"
                                                                class="img-fluid" alt="">
                                                        </div>
                                                    </label>
                                                    <select name="company_attributes_display" id="company_attributes"
                                                        class="form-select" multiple>
                                                        <!-- Options will be loaded via JavaScript -->
                                                    </select>
                                                    <input type="hidden" id="company_attributes_hidden"
                                                        name="company_attributes"
                                                        value="{{ old('company_attributes', $userIcp->company_attributes ?? '') }}" />
                                                </div>
                                                <div class="new_user_details_inner_box_form_group_box mb-3">
                                                    <label for="company_current_business_challenges">Current Business
                                                        Challenges <span class="text-danger">*</span>
                                                        <div class="profileTooltip">
                                                            <div class="profileTooltipText">
                                                                Select the key challenges your business is currently
                                                                facing.
                                                                <div class="profileTooltipTextCurve"></div>
                                                            </div>
                                                            <img src="{{ asset('assets/images/noticeIcon.png') }}"
                                                                class="img-fluid" alt="">
                                                        </div>
                                                    </label>
                                                    <select name="company_current_business_challenges_display"
                                                        id="company_current_business_challenges" class="form-select"
                                                        multiple required>
                                                        <!-- Options will be loaded via JavaScript -->
                                                    </select>
                                                    <input type="hidden" id="company_current_business_challenges_hidden"
                                                        name="company_current_business_challenges"
                                                        value="{{ old('company_current_business_challenges', $userIcp->company_current_business_challenges ?? '') }}" />

                                                </div>
                                                <div class="new_user_details_inner_box_form_group_box mb-3">
                                                    <label for="company_business_goals">Business Goals <span
                                                            class="text-danger">*</span>
                                                        <div class="profileTooltip">
                                                            <div class="profileTooltipText">
                                                                Select the goals you want to achieve in the next 6–12
                                                                months.
                                                                <div class="profileTooltipTextCurve"></div>
                                                            </div>
                                                            <img src="{{ asset('assets/images/noticeIcon.png') }}"
                                                                class="img-fluid" alt="">
                                                        </div>
                                                    </label>
                                                    <select name="company_business_goals_display"
                                                        id="company_business_goals" class="form-select" multiple required>
                                                        <!-- Options will be loaded via JavaScript -->
                                                    </select>
                                                    <input type="hidden" id="company_business_goals_hidden"
                                                        name="company_business_goals"
                                                        value="{{ old('company_business_goals', $userIcp->company_business_goals ?? '') }}" />

                                                </div>


                                            </div>
                                        </div>

                                        <button type="submit" class="btn btn-primary profileSaveBtn">Save</button>
                                    </div>
                                </div>










                            </form>
                        </div>
                    </div>
                </div>
            </div>




        </div>
    </section>
@endsection
@section('scripts')
    {{-- Company-related dropdown arrays removed - not part of newsfeed boilerplate --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.23.0/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>
    <script>
        jQuery(document).ready(function($) {




            let typingTimer;
            const typingDelay = 1000;

            // ============================================
            // CUSTOM VALIDATION METHODS
            // ============================================

            // Custom method for Select2 fields
            $.validator.addMethod("select2Required", function(value, element) {
                return $(element).val() && $(element).val().length > 0;
            }, "This field is required");

            // Custom method for phone validation
            $.validator.addMethod("validPhone", function(value, element) {
                if (!value) return true; // Skip if empty (use required separately)
                const iti = $(element).data('iti');
                return iti ? iti.intlTelInput("isValidNumber") : true;
            }, "Please enter a valid phone number");

            // Custom method for LinkedIn URL
            $.validator.addMethod("linkedinUrl", function(value, element) {
                if (!value) return true;
                return /^https?:\/\/(www\.)?linkedin\.com\/(in|company)\/[\w-]+\/?$/.test(value);
            }, "Please enter a valid LinkedIn URL");

            // Custom method for URL
            $.validator.addMethod("validUrl", function(value, element) {
                if (!value) return true;
                return /^https?:\/\/.+\..+/.test(value);
            }, "Please enter a valid URL");

            // ============================================
            // FORM VALIDATION - PERSONAL DETAILS
            // ============================================

            $("#user_details").validate({
                rules: {
                    'are_you[]': {
                        required: true,
                        minlength: 1
                    },
                    first_name: {
                        required: true,
                        minlength: 2
                    },
                    last_name: {
                        required: true,
                        minlength: 2
                    },
                    phone: {
                        required: true,
                        validPhone: true
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    // city, country validation removed - fields don't exist in users table (only 'location' exists)
                    // Mosque validation rules removed - not part of newsfeed boilerplate
                    // linkedin_url validation removed - field doesn't exist in users table
                },
                messages: {
                    // 'are_you[]' validation message removed - field doesn't exist in users table
                    first_name: {
                        required: "First name is required",
                        minlength: "First name must be at least 2 characters"
                    },
                    last_name: {
                        required: "Last name is required",
                        minlength: "Last name must be at least 2 characters"
                    },
                    phone: {
                        required: "Phone number is required"
                    },
                    email: {
                        required: "Email is required",
                        email: "Please enter a valid email address"
                    },
                    // city, country validation messages removed - fields don't exist in users table
                    // Mosque validation messages removed - not part of newsfeed boilerplate
                    // linkedin_url validation message removed - field doesn't exist in users table
                },
                errorPlacement: function(error, element) {
                    if (element.attr("name") === "are_you[]") {
                        error.insertAfter(element.closest('.list_check_flex'));
                    } else if (element.hasClass('select2-hidden-accessible')) {
                        error.insertAfter(element.next('.select2-container'));
                    } else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element) {
                    $(element).addClass('error');
                    if ($(element).hasClass('select2-hidden-accessible')) {
                        $(element).next('.select2-container').addClass('error');
                    }
                },
                unhighlight: function(element) {
                    $(element).removeClass('error');
                    if ($(element).hasClass('select2-hidden-accessible')) {
                        $(element).next('.select2-container').removeClass('error');
                    }
                },
                submitHandler: function(form) {
                    // Update phone number before submission
                    $('.phone_number').each(function() {
                        const iti = $(this).data('iti');
                        if (iti && $(this).val().trim() && iti.intlTelInput("isValidNumber")) {
                            $(this).val(iti.intlTelInput("getNumber"));
                        }
                    });
                    form.submit();
                }
            });

            // ============================================
            // FORM VALIDATION - COMPANY DETAILS
            // ============================================

            // Company form validation removed - not part of newsfeed boilerplate
            if (false && $("#user_company").length) {
                $("#user_company").validate({
                    rules: {},
                    messages: {},
                    errorPlacement: function(error, element) {
                        if (element.attr("type") === "radio") {
                            error.insertAfter(element.closest('.form-check-row'));
                        } else if (element.hasClass('select2-hidden-accessible')) {
                            error.insertAfter(element.next('.select2-container'));
                        } else {
                            error.insertAfter(element);
                        }
                    },
                highlight: function(element) {
                    $(element).addClass('error');
                    if ($(element).hasClass('select2-hidden-accessible')) {
                        $(element).next('.select2-container').addClass('error');
                    }
                },
                unhighlight: function(element) {
                    $(element).removeClass('error');
                    if ($(element).hasClass('select2-hidden-accessible')) {
                        $(element).next('.select2-container').removeClass('error');
                    }
                },
                submitHandler: function(form) {
                    // Update phone number before submission
                    $('.phone_number').each(function() {
                        const iti = $(this).data('iti');
                        if (iti && $(this).val().trim() && iti.intlTelInput("isValidNumber")) {
                            $(this).val(iti.intlTelInput("getNumber"));
                        }
                    });
                    form.submit();
                }
            });

            // ============================================
            // TRIGGER VALIDATION ON SELECT2 CHANGE
            // ============================================

            $('.select2-hidden-accessible').on('change', function() {
                $(this).valid();
            });

            // Mosque-related JavaScript completely removed - not part of newsfeed boilerplate

            $('#search_form').on('submit', function() {
                $(this).find('input').each(function() {
                    if (!$(this).val().trim()) {
                        $(this).prop('disabled', true);
                    }
                });

                $(this).find('select').each(function() {
                    if (!$(this).val()) {
                        $(this).prop('disabled', true);
                    }
                });
            });

            // ============================================
            // PHONE NUMBER INITIALIZATION
            // ============================================

            $(".phone_number").each(function() {
                const phoneInput = $(this);
                const iti = phoneInput.intlTelInput({
                    separateDialCode: true,
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/utils.js"
                });
                phoneInput.data('iti', iti);
            });

            // Company-related JavaScript removed - not part of newsfeed boilerplate:
            // SELECT2 initialization for company_position, company_industry, business_location,
            // company_current_business_challenges, company_business_goals, company_attributes
            // Ethnicity and marital status handlers removed - fields don't exist in users table
            // Company business type handler removed

        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (window.languageScriptLoaded) {
                return;
            }
            window.languageScriptLoaded = true;

            const languageInput = document.getElementById('language-input');
            const languagesList = document.getElementById('languages-list');
            const languageHiddenInput = document.getElementById('languages-hidden');

            if (!languageInput || !languagesList || !languageHiddenInput) return;

            let languages = '{{ $user->languages }}'.split(',').map(lang => lang.trim()).filter(lang => lang);

            languages.forEach(language => {
                const tag = createLanguageTag(language);
                languagesList.appendChild(tag);
            });

            function createLanguageTag(language) {
                const tag = document.createElement('span');
                tag.classList.add('badge', 'bg-primary', 'me-2', 'mb-2');
                tag.textContent = language;

                const closeBtn = document.createElement('button');
                closeBtn.classList.add('btn-close', 'btn-close', 'ms-2');
                closeBtn.setAttribute('aria-label', 'Remove');
                closeBtn.style.fontSize = '0.7rem';
                closeBtn.style.verticalAlign = 'middle';

                closeBtn.addEventListener('click', () => {
                    languages = languages.filter(l => l !== language);
                    tag.remove();
                    updateHiddenInput();
                });

                tag.appendChild(closeBtn);
                return tag;
            }

            languageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const language = languageInput.value.trim();

                    if (language && !languages.includes(language)) {
                        languages.push(language);
                        const tag = createLanguageTag(language);
                        languagesList.appendChild(tag);
                        languageInput.value = '';
                        updateHiddenInput();
                    }
                }
            });

            function updateHiddenInput() {
                languageHiddenInput.value = languages.join(',');
            }

            const parentForm = languageInput.closest('form');
            if (parentForm) {
                parentForm.addEventListener('submit', function(e) {
                    updateHiddenInput();
                });
            }
        });
    </script>
@endsection
