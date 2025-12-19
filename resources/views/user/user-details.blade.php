@extends('layouts.dashboard-layout')


@section('dashboard-content')

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.23.0/sweetalert2.min.css" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
    <style>
        body {
            overflow-y: hidden;
        }

        .select2-container--bootstrap-5 .select2-selection--multiple .select2-selection__rendered .select2-selection__choice {
            flex-direction: row-reverse;
            background: #B8C034;
            gap: 10px;
            border-radius: 4px;
            border: none;
            margin: 0px 8px 8px 0px;
        }

        .select2-container--bootstrap-5 .select2-selection {
            background: #27357205;
            border: 2px solid #E9EBF0;
            box-shadow: none !important;
            border-radius: 9.77px;
            padding: 13px 13px 13px 13px;
            min-height: 57px;
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


        .select2-container--bootstrap-5 .select2-selection--multiple .select2-search .select2-search__field {
            border: 2px solid #E9EBF0;
            width: 100% !important;
            padding: 5px 6px;
            height: 36px;
            border-radius: 4px;
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

        .new_user_details .form-control {
            background: #27357205;
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
        }

        .new_user_details #company_linkedin_user {
            position: absolute;
            top: 50%;
            right: 0;
            height: 100%;
            width: 30%;
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

        /* .sidebar {
            width: 100%;
            max-width: 16%;
        } */

        /* .navbar_d_flex {
            margin: 140px 0 0 0;
        } */

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
            margin: 26px 0 0 0;
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
            width: 283px;
            z-index: 9999;
            bottom: 35px;
            left: -33px;
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-size: 16px;
            line-height: 100%;
            color: #FFFFFF;
            padding: 12px 14px;
            border-radius: 7px;
            user-select: none;
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
            left: 20px;
            z-index: -1;
        }

        @media (max-width: 1400px) {

            body{
                overflow: hidden !important;
            }
            .col-lg-3 {
                width: 50% !important;
            }
        }

         @media (max-width: 768px) {
            .col-lg-3 {
                width: 100% !important;
            }
        }

        
        @media (max-width: 486px) {
            .main-content {
                padding: 70px 14px 20px 14px !important;
            }
        }

        @media (max-width: 398px) {
            .user_company_profile .new_user_details label:not(.form-check-label) {
                font-size: 15px;
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
        }

    </style>
    <section class="user_company_profile">
        <div class="container">
            <div class="custom_card_profile">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="user-details" data-bs-toggle="tab"
                            data-bs-target="#user-details-pane" type="button" role="tab"
                            aria-controls="user-details-pane" aria-selected="true">Personal</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link " id="company-details-tab" data-bs-toggle="tab"
                            data-bs-target="#company-details-tab-pane" type="button" role="tab"
                            aria-controls="company-details-tab-pane" aria-selected="false">Professional</button>
                    </li>
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


                                <div class="profile_pic">
                                    <div class="avatar-upload mb-3">
                                        <div class="avatar-edit">
                                            <input type='file' id="imageUpload" name="photo"
                                                accept=".png, .jpg, .jpeg" />
                                            <label for="imageUpload"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imagePreview">
                                                <img src="{{ $user->photo ? asset('storage/' . $user->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>
                                <div class="col-12">
                                    <label for="list_check_flex">Are You?<span class="text-danger">*</span> (Select all
                                        that Apply)</label>
                                    <ul class="list_check_flex">
                                        @php
                                            $selectedAreYou = explode(', ', $user->user_position ?? ''); // Split stored values into an array
                                        @endphp
                                        <li>
                                            <input type="checkbox" class="btn-check" id="accredited_investor"
                                                name="are_you[]" value="Accredited Investor"
                                                {{ in_array('Accredited Investor', $selectedAreYou) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-secondary custom_btn"
                                                for="accredited_investor">Accredited Investor</label>
                                        </li>
                                        <li>
                                            <input type="checkbox" class="btn-check" id="business_owner" name="are_you[]"
                                                value="Business Owner"
                                                {{ in_array('Business Owner', $selectedAreYou) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-secondary custom_btn"
                                                for="business_owner">Business Owner</label>
                                        </li>
                                        <li>
                                            <input type="checkbox" class="btn-check" id="board_member_advisor"
                                                name="are_you[]" value="Board Member / Advisor"
                                                {{ in_array('Board Member / Advisor', $selectedAreYou) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-secondary custom_btn"
                                                for="board_member_advisor">Board Member / Advisor</label>
                                        </li>
                                        <li>
                                            <input type="checkbox" class="btn-check" id="corporate_executive"
                                                name="are_you[]" value="Corporate Executive"
                                                {{ in_array('Corporate Executive', $selectedAreYou) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-secondary custom_btn"
                                                for="corporate_executive">Corporate Executive</label>
                                        </li>
                                        <li>
                                            <input type="checkbox" class="btn-check" id="educator_academia"
                                                name="are_you[]" value="Educator / Academia"
                                                {{ in_array('Educator / Academia', $selectedAreYou) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-secondary custom_btn"
                                                for="educator_academia">Educator / Academia</label>
                                        </li>
                                        <li>
                                            <input type="checkbox" class="btn-check" id="govt_public_sector_leader"
                                                name="are_you[]" value="Govt/Public Sector Leader"
                                                {{ in_array('Govt/Public Sector Leader', $selectedAreYou) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-secondary custom_btn"
                                                for="govt_public_sector_leader">Govt/Public Sector Leader</label>
                                        </li>
                                        <li>
                                            <input type="checkbox" class="btn-check" id="industry_expert"
                                                name="are_you[]" value="Industry Expert"
                                                {{ in_array('Industry Expert', $selectedAreYou) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-secondary custom_btn"
                                                for="industry_expert">Industry Expert</label>
                                        </li>
                                        <li>
                                            <input type="checkbox" class="btn-check" id="job_seeker" name="are_you[]"
                                                value="Job Seeker"
                                                {{ in_array('Job Seeker', $selectedAreYou) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-secondary custom_btn" for="job_seeker">Job
                                                Seeker</label>
                                        </li>
                                        <li>
                                            <input type="checkbox" class="btn-check" id="non_profit_leader"
                                                name="are_you[]" value="Non-Profit Leader"
                                                {{ in_array('Non-Profit Leader', $selectedAreYou) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-secondary custom_btn"
                                                for="non_profit_leader">Non-Profit Leader</label>
                                        </li>
                                        <li>
                                            <input type="checkbox" class="btn-check" id="investment_seeker"
                                                name="are_you[]" value="Investment Seeker"
                                                {{ in_array('Investment Seeker', $selectedAreYou) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-secondary custom_btn"
                                                for="investment_seeker">Investment Seeker</label>
                                        </li>
                                        <li>
                                            <input type="checkbox" class="btn-check" id="student_intern"
                                                name="are_you[]" value="Student / Intern"
                                                {{ in_array('Student / Intern', $selectedAreYou) ? 'checked' : '' }}>
                                            <label class="btn btn-outline-secondary custom_btn"
                                                for="student_intern">Student / Intern</label>
                                        </li>
                                    </ul>
                                </div>


                                <div class="row">

                                    <div class="col-lg-3">
                                        <label for="first_name">First Name<span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" id="first_name" class="form-control"
                                            value="{{ old('first_name', $user->first_name) }}">
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="last_name">Last Name<span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" id="last_name" class="form-control"
                                            value="{{ old('last_name', $user->last_name) }}">
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="phone" class="toggle_flex">Cell / Mobile<span
                                                class="text-danger">* </span>
                                            <div class="cont">
                                                (Private
                                                <div class="toggle">
                                                    <input type="checkbox" id="mobile_public" class="toggle__input"
                                                        name="phone_public" value="Yes"
                                                        @if ($user->phone_public == 'Yes') checked @endif>
                                                    <label for="mobile_public" class="toggle__label mt-0"></label>
                                                </div>
                                                Public)
                                            </div>
                                        </label>
                                        <input type="tel" name="phone" id="phone"
                                            class="form-control phone_number w-100"
                                            value="{{ old('phone', $user->phone) }}">
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="email">Email<span class="text-danger">*</span>
                                            <div class="cont">
                                                (Private
                                                <div class="toggle">
                                                    <input type="checkbox" id="email_public" class="toggle__input"
                                                        name="email_public" value="Yes"
                                                        @if ($user->email_public == 'Yes') checked @endif>
                                                    <label for="email_public" class="toggle__label mt-0"></label>
                                                </div>

                                                Public)
                                            </div>
                                        </label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ old('email', $user->email) }}">
                                    </div>


                                    <div class="col-lg-3">
                                        <label for="city">City<span class="text-danger">*</span></label>
                                        <input type="text" name="city" id="city" class="form-control"
                                            value="{{ old('city', $user->city) }}">
                                        {{-- {!! \App\Helpers\DropDownHelper::renderCityDropdownForUser($user->state, $user->city) !!} --}}
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="county">County</label>
                                        <input type="text" name="county" id="county" class="form-control"
                                            value="{{ old('county', $user->county) }}">
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="state">State</label>
                                        <input type="text" name="state" id="state" class="form-control"
                                            value="{{ old('state', $user->state) }}">
                                        {{-- {!! \App\Helpers\DropDownHelper::renderStateDropdownForUser($user->country, $user->state) !!} --}}
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="zip_code">Zip Code</label>
                                        <input type="text" name="zip_code" id="zip_code" class="form-control"
                                            value="{{ old('zip_code', $user->zip_code) }}">
                                        {{-- {!! \App\Helpers\DropDownHelper::renderStateDropdownForUser($user->country, $user->state) !!} --}}
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="country">Country<span class="text-danger">*</span></label>
                                        <input type="text" name="country" id="country" class="form-control"
                                            value="{{ old('country', $user->country) }}">
                                        {{-- {!! \App\Helpers\DropDownHelper::renderCountryDropdownForUser($user->country) !!} --}}
                                    </div>




                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="mosque_id">Mosque<span class="text-danger">*</span></label>
                                        <select name="mosque_id" id="mosque_id" class="form-select">
                                            <option value="">Select Mosque</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-3 newMosqueCol d-none">
                                        <label for="mosque">Suggest New Mosque<span class="text-danger">*</span></label>
                                        <input type="text" name="mosque" id="mosque" class="form-control"
                                            value="{{ old('mosque', $user->mosque) }}">
                                        {{-- {!! \App\Helpers\DropDownHelper::renderCountryDropdownForUser($user->country) !!} --}}
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-lg-3">
                                        <label for="gender">Gender</label>
                                        {!! \App\Helpers\DropDownHelper::renderGenderDropdown($user->gender) !!}
                                    </div>


                                    <div class="col-lg-3">
                                        <label for="age_group">Age Group</label>
                                        {!! \App\Helpers\DropDownHelper::renderAgeGroupDropdown($user->age_group) !!}
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="ethnicity">Ethnicity</label>
                                        <fieldset>
                                            {!! \App\Helpers\DropDownHelper::renderEthnicityDropdown($user->ethnicity) !!}
                                        </fieldset>
                                    </div>

                                    <div class="col-lg-3" id="other-ethnicity-div"
                                        style="{{ $user->ethnicity === 'Other' ? '' : 'display: none;' }}">
                                        <label for="other-ethnicity">Please specify your ethnicity</label>
                                        <input type="text" class="form-control" id="other-ethnicity"
                                            name="other_ethnicity" placeholder="Enter ethnicity"
                                            value="{{ $user->other_ethnicity }}">
                                    </div>


                                    <div class="col-lg-3">
                                        <label for="nationality">Nationality</label>
                                        {!! \App\Helpers\DropDownHelper::nationalityDropdown($user->nationality) !!}
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="languages">Languages</label>
                                        <div class="languages-input-container">
                                            <input type="text" id="language-input" name="language-input"
                                                class="form-control" placeholder="Type a language and press Enter">
                                            <input type="hidden" name="languages" id="languages-hidden"
                                                value="{{ $user->languages }}">
                                            <!-- This is where the selected languages will go -->

                                            <div id="languages-list" class="mt-2">
                                                <!-- Added languages will appear here as tags -->
                                            </div>
                                        </div>
                                    </div>




                                    <div class="col-lg-3">
                                        <label for="marital_status">Marital Status</label>
                                        {!! \App\Helpers\DropDownHelper::renderMaritalStatusDropdown($user->marital_status) !!}
                                    </div>

                                    <div class="col-lg-3" id="other-marital-status-div"
                                        style="{{ $user->marital_status === 'Other' ? '' : 'display: none;' }}">
                                        <label for="other-marital-status">Please specify your marital status</label>
                                        <input type="text" class="form-control" id="other-marital-status"
                                            name="other_marital_status" placeholder="Enter marital status"
                                            value="{{ $user->other_marital_status }}">
                                    </div>



                                    <div class="col-lg-3">
                                        <label for="linkedin_url">LinkedIn<span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-text">https://www.linkedin.com/in/</div>
                                            <input type="text" name="linkedin_user" id="linkedin_user"
                                                class="form-control"
                                                value="{{ old('linkedin_url', str_replace('https://www.linkedin.com/in/', '', $user->linkedin_url)) }}">
                                        </div>
                                        <input type="hidden" name="linkedin_url" id="linkedin_url_hidden"
                                            value="">

                                    </div>
                                    <div class="col-lg-3">
                                        <label for="facebook_url">Facebook</label>
                                        <input type="text" name="facebook_url" id="facebook_url" class="form-control"
                                            value="{{ old('facebook_url', $user->facebook_url) }}" placeholder="Link">
                                    </div>

                                    <div class="col-lg-3">
                                        <label for="x_url">X (Formerly Twitter)</label>
                                        <input type="text" name="x_url" id="x_url" class="form-control"
                                            value="{{ old('x_url', $user->x_url) }}" placeholder="Link">
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="instagram">Instagram</label>
                                        <input type="text" name="instagram_url" id="instagram" class="form-control"
                                            placeholder="Link" value="{{ old('x_url', $user->instagram_url) }}">
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="tikTok">TikTok</label>
                                        <input type="text" name="tiktok_url" id="tikTok" class="form-control"
                                            placeholder="Link" value="{{ old('x_url', $user->tiktok_url) }}">
                                    </div>
                                    <div class="col-lg-3">
                                        <label for="youTube">YouTube</label>
                                        <input type="text" name="youtube_url" id="youTube" class="form-control"
                                            placeholder="Link" value="{{ old('x_url', $user->youtube_url) }}">
                                    </div>

                                    <div class="col-12 ">
                                        <button type="submit" class="btn btn-primary profileSaveBtn">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="tab-pane fade " id="company-details-tab-pane" role="tabpanel"
                        aria-labelledby="company-details-tab" tabindex="0">
                        <div class="new_user_details">
                            <form action="{{ route('user.company.update') }}" method="POST"
                                enctype="multipart/form-data" id="user_company">
                                @csrf



                                <div class="profile_pic">
                                    <div class="avatar-upload">
                                        <div class="avatar-edit">
                                            <input type='file' id="imageUploadCompany" name="company_logo"
                                                accept=".png, .jpg, .jpeg" />
                                            <label for="imageUploadCompany"></label>
                                        </div>
                                        <div class="avatar-preview">
                                            <div id="imagePreviewCompany">
                                                <img src="{{ isset($company) && $company->company_logo ? asset('storage/' . $company->company_logo) : 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcTcd5J_YDIyLfeZCHcsBpcuN8irwbIJ_VDl0Q&s' }}"
                                                    alt="">
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                <div class="col-12">
                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                </div>


                                <div class="accordion" id="professionalTabs">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseOne" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                Basic Details
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#professionalTabs">
                                            <div class="accordion-body">
                                                <div class="row">
                                                    <!-- Company Name -->
                                                    <div class="col-lg-3">
                                                        <label for="company_name">Company Name</label>
                                                        <input type="text" name="company_name" id="company_name"
                                                            class="form-control"
                                                            value="{{ old('company_name', $company->company_name ?? '') }}">
                                                    </div>


                                                    <div class="col-lg-3">
                                                        <label for="company_web_url">Company URL</label>
                                                        <input type="text" name="company_web_url" id="company_web_url"
                                                            class="form-control"
                                                            value="{{ old('company_web_url', $company->company_web_url ?? '') }}">
                                                    </div>




                                                    <div class="col-lg-3">
                                                        <label for="company_position">Title/Designation</label>
                                                        <select name="company_position_display" id="company_position"
                                                            class="form-select" multiple>
                                                            <!-- Options will be loaded via JavaScript -->
                                                        </select>
                                                        <input type="hidden" id="company_position_hidden"
                                                            name="company_position"
                                                            value="{{ old('company_position', $company->company_position ?? '') }}" />
                                                    </div>



                                                    <div class="col-lg-3 company_position_other_div d-none">
                                                        <label for="company_position_other">Title/Designation Other</label>
                                                        <input type="text" name="company_position_other"
                                                            id="company_position_other" class="form-control"
                                                            value="">
                                                    </div>
                                                    <div class="col-lg-3 custom-select-dropdown mt-0">
                                                        <label for="company_experience">Years of Experience</label>
                                                        {!! \App\Helpers\DropDownHelper::renderCompanyExperienceDropdown($company->company_experience ?? '') !!}

                                                    </div>


                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-3">
                                                        <label for="work_phone_num">Work Phone Number</label>
                                                        <input type="tel" name="company_phone" id="company_phone"
                                                            class="form-control phone_number"
                                                            value="{{ old('company_phone', $company->company_phone ?? '') }}">
                                                    </div>
                                                    <!-- Company Linkedin URL -->
                                                    <div class="col-lg-3">
                                                        <label for="company_linkedin_user">Company LinkedIn Page</label>
                                                        <div class="input-group">
                                                            <div class="input-group-text">https://www.linkedin.com/company/
                                                            </div>
                                                            <input type="text" name="company_linkedin_user"
                                                                id="company_linkedin_user" class="form-control"
                                                                value="{{ old('company_linkedin_user', str_replace('https://www.linkedin.com/company/', '', $company->company_linkedin_url ?? '')) }}">
                                                        </div>
                                                        <input type="hidden" name="company_linkedin_url"
                                                            id="company_linkedin_url_hidden" value="">

                                                    </div>



                                                    {{-- <div class="col-lg-3">
                                                        <label for="company_industry">Industry</label>
                                                        <select name="company_industry_display" id="company_industry"
                                                            class="form-select" multiple>
                                                            <!-- Options will be loaded via JavaScript -->
                                                        </select>
                                                        <input type="hidden" id="company_industry_hidden"
                                                            name="company_industry"
                                                            value="{{ old('company_industry', $company->company_industry ?? '') }}" />
                                                    </div>

                                                    <div class="col-lg-3 company_industry_other_div d-none">
                                                        <label for="company_industry_other">Industry Other</label>
                                                        <input type="text" name="company_industry_other"
                                                            id="company_industry_other" class="form-control"
                                                            value="">
                                                    </div> --}}

                                                    <!-- Business Type Dropdown -->
                                                    <div class="col-lg-3">
                                                        <label for="company_business_type">Company Type</label>
                                                        {!! \App\Helpers\DropDownHelper::renderBusinessTypeDropdown($company->company_business_type ?? '') !!}
                                                        <div id="business_type_other_field" style="display: none;">
                                                            <label for="business_type_other">Other Company Type</label>
                                                            <input type="text" name="company_business_type_other"
                                                                id="business_type_other" class="form-control"
                                                                placeholder="Enter other business type">
                                                        </div>
                                                    </div>

                                                    <div class="col-lg-3">
                                                        <label for="company_revenue">Company Revenue:</label>
                                                        {!! \App\Helpers\DropDownHelper::renderRevenueDropdown($company->company_revenue ?? '') !!}
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <!-- Company Revenue -->


                                                    <!-- Number of Employees Dropdown -->
                                                    {{-- <div class="col-lg-3">
                                                        <label for="company_no_of_employee">Company No. of
                                                            Employees</label>
                                                        {!! \App\Helpers\DropDownHelper::renderEmployeeSizeDropdown($company->company_no_of_employee ?? '') !!}
                                                    </div> --}}



                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse" data-bs-target="#collapseTwo"
                                                aria-expanded="false" aria-controls="collapseTwo">
                                                ICP Details
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse"
                                            aria-labelledby="headingTwo" data-bs-parent="#professionalTabs">
                                            <div class="accordion-body">
                                                <div class="row">

                                                    <!-- Industry Dropdown -->
                                                    <div class="col-lg-3">
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

                                                    <div class="col-lg-3 company_industry_other_div d-none">
                                                        <label for="company_industry_other">Industry Other</label>
                                                        <input type="text" name="company_industry_other"
                                                            id="company_industry_other" class="form-control"
                                                            value="">
                                                    </div>

                                                    <!-- Business Location Dropdown -->
                                                    <div class="col-lg-3">
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

                                                    <!-- Company Size Dropdown -->
                                                    <div class="col-lg-3">
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


                                                    <!-- Decision Maker -->
                                                    <div class="col-lg-3">
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

                                                </div>

                                                <div class="row">

                                                    <!-- Your Role -->
                                                    {{-- <div class="col-lg-3">
                                                        <label for="company_role">Your Role <span
                                                                class="text-danger">*</span></label>
                                                        <select name="company_role_display" id="company_role"
                                                            class="form-select" required>
                                                            <!-- Options will be loaded via JavaScript -->
                                                        </select>
                                                        <input type="hidden" id="company_role_hidden"
                                                            name="company_role"
                                                            value="{{ old('company_role', $company->company_role ?? '') }}" />
                                                    </div> --}}

                                                    <!-- Current Business Challenges -->
                                                    <div class="col-lg-3">
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
                                                        <input type="hidden"
                                                            id="company_current_business_challenges_hidden"
                                                            name="company_current_business_challenges"
                                                            value="{{ old('company_current_business_challenges', $userIcp->company_current_business_challenges ?? '') }}" />

                                                    </div>

                                                    <!-- Business Goals -->
                                                    <div class="col-lg-3">
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
                                                            id="company_business_goals" class="form-select" multiple
                                                            required>
                                                            <!-- Options will be loaded via JavaScript -->
                                                        </select>
                                                        <input type="hidden" id="company_business_goals_hidden"
                                                            name="company_business_goals"
                                                            value="{{ old('company_business_goals', $userIcp->company_business_goals ?? '') }}" />

                                                    </div>

                                                    <!-- Technologies You Use -->
                                                    {{-- <div class="col-lg-3">
                                                        <label for="company_technologies_you_use">Technologies You
                                                            Use</label>
                                                        <select name="company_technologies_you_use_display"
                                                            id="company_technologies_you_use" class="form-select" multiple
                                                            required>
                                                            <!-- Options will be loaded via JavaScript -->
                                                        </select>
                                                        <input type="hidden" id="company_technologies_you_use_hidden"
                                                            name="company_technologies_you_use"
                                                            value="{{ old('company_technologies_you_use', $company->company_technologies_you_use ?? '') }}" />

                                                    </div> --}}


                                                </div>
                                                <div class="row">

                                                    <!-- Buying Process -->
                                                    {{-- <div class="col-lg-3">
                                                        <label for="company_buying_process">Buying Process</label>
                                                        <select name="company_buying_process_display"
                                                            id="company_buying_process" class="form-select">
                                                            <!-- Options will be loaded via JavaScript -->
                                                        </select>
                                                        <input type="hidden" id="company_buying_process_hidden"
                                                            name="company_buying_process"
                                                            value="{{ old('company_buying_process', $company->company_buying_process ?? '') }}" />

                                                    </div> --}}

                                                    <!-- Company Attributes -->
                                                    <div class="col-lg-3">
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

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 text-start">
                                    <button type="submit" class="btn btn-primary profileSaveBtn">Save</button>
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
    @php
        $business_locations = \App\Helpers\DropDownHelper::getBusinessLocationsArray();
        $business_challenges = \App\Helpers\DropDownHelper::getCurrentBusinessChallengesArray();
        $business_goals = \App\Helpers\DropDownHelper::getBusinessGoalsArray();
        $company_attributes = \App\Helpers\DropDownHelper::getCompanyAttributesArray();
    @endphp
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.23.0/sweetalert2.min.js"></script>



    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        jQuery(document).ready(function($) {

            let typingTimer;
            const typingDelay = 1000; // ms delay after typing stops

            // --- Search Mosque API ---
            function searchMosque() {
                let zip_code = $("#zip_code").val();
                let city = $("#city").val();

                $.ajax({
                    url: "{{ route('user.mosque.search') }}",
                    method: "GET",
                    data: {
                        zip: zip_code,
                        city: city,
                    },
                    success: function(data) {
                        let $mosqueSelect = $("#mosque_id");
                        $mosqueSelect.empty();

                        if (data.status && data.data.length > 0) {
                            $mosqueSelect.append('<option value="">-- Select Mosque --</option>');
                            $.each(data.data, function(index, mosque) {
                                $mosqueSelect.append(
                                    '<option value="' + mosque.id + '">' +
                                    mosque.mosque + '</option>'
                                );
                            });
                            $mosqueSelect.append('<option value="other">-- Other --</option>');
                        } else {
                            $mosqueSelect.append('<option value="">No mosques found</option>');
                            $mosqueSelect.append('<option value="other">-- Other --</option>');
                        }
                    },
                    error: function(xhr) {
                        console.error("Error:", xhr.responseText);
                        $("#mosque_id").empty().append(
                            '<option value="">Error loading mosques</option>'
                        );
                    }
                });
            }

            // --- Store Mosque API ---
            function storeMosque({
                mosqueId = null,
                newMosque = null
            }) {
                let amount = 100;

                $.ajax({
                    url: "{{ route('user.mosque.store') }}",
                    method: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        mosque_id: mosqueId,
                        mosque: newMosque,
                        amount: amount,
                    },
                    success: function(response) {
                        console.log("Stored:", response);
                        Swal.fire({
                            icon: response.status ? "success" : "error",
                            title: response.message,
                            showConfirmButton: false,
                            timer: 2000
                        });
                    },
                    error: function(xhr) {
                        console.error("Error saving mosque:", xhr.responseText);
                        Swal.fire({
                            icon: "error",
                            title: "Error saving mosque",
                            text: xhr.responseJSON?.message || "Something went wrong",
                            confirmButtonText: "OK"
                        });
                    }
                });
            }

            // --- Debounce for zip & city ---
            $("#zip_code, #city").on("input", function() {
                clearTimeout(typingTimer);
                typingTimer = setTimeout(searchMosque, typingDelay);
            });

            // --- Dropdown change ---
            $("#mosque_id").on("change", function() {
                let selected = $(this).val();

                if (selected === "other") {
                    $(".newMosqueCol").removeClass("d-none");
                } else if (selected) {
                    $(".newMosqueCol").addClass("d-none");
                    // ✅ Store existing mosque immediately
                    storeMosque({
                        mosqueId: selected
                    });
                } else {
                    $(".newMosqueCol").addClass("d-none");
                }
            });

            // --- Free-text mosque field ---
            $("#mosque").on("input", function() {
                clearTimeout(typingTimer);
                let newMosque = $(this).val();

                typingTimer = setTimeout(function() {
                    if (newMosque.length > 2) {
                        // ✅ Store new mosque only when typing stops
                        storeMosque({
                            newMosque: newMosque
                        });
                    }
                }, typingDelay);
            });

            // Run search once on page load
            searchMosque();






            $('#search_form').on('submit', function() {
                // Disable empty input fields
                $(this).find('input').each(function() {
                    if (!$(this).val().trim()) {
                        $(this).prop('disabled', true);
                    }
                });

                // Disable unselected select fields
                $(this).find('select').each(function() {
                    if (!$(this).val()) { // Check if no value is selected
                        $(this).prop('disabled', true);
                    }
                });
            });


            // Initialize intlTelInput on all elements with class 'phone_number'
            $(".phone_number").each(function() {
                const phoneInput = $(this);
                const iti = phoneInput.intlTelInput({
                    separateDialCode: true,
                    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/utils.js" // Ensure this is loaded for utils functionality
                });

                // Before form submission, update the input value to include the full number
                phoneInput.closest("form").on("submit", function() {
                    if (iti.intlTelInput("isValidNumber")) {
                        const fullNumber = iti.intlTelInput(
                            "getNumber"); // Get full number with country code
                        phoneInput.val(fullNumber); // Update input value with the full number
                    } else {
                        alert("Invalid phone number entered!");
                        return false; // Prevent form submission if the number is invalid
                    }
                });
            });

            // ============================================
            // SELECT2 INITIALIZATION FOR DESIGNATION
            // ============================================

            // Get designations from Blade (passed from controller)
            const designations = @json($designations ?? []);

            // Build options HTML
            let designationOptions = '<option value="">Select Title/Designation</option>';
            designations.forEach(function(item) {
                designationOptions += `<option value="${item}">${item}</option>`;
            });
            designationOptions += '<option value="Other">Other</option>';

            // Populate the select
            $('#company_position').html(designationOptions);

            // Initialize Select2
            $('#company_position').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select or type Title/Designation',
                allowClear: true,
                multiple: true,
                tags: true,
                tokenSeparators: [','],
                width: '100%'
            });

            // Set existing values
            let existingDesignation = $('#company_position_hidden').val();
            if (existingDesignation) {
                let designationArray = existingDesignation.split(', ').filter(v => v.trim());
                $('#company_position').val(designationArray).trigger('change');
            }

            // Handle changes
            $('#company_position').on('change', function() {
                let selectedValues = $(this).val();
                $('#company_position_hidden').val(selectedValues ? selectedValues.join(', ') : '');

                if (selectedValues && selectedValues.includes('Other')) {
                    $('.company_position_other_div').removeClass('d-none');
                } else {
                    $('.company_position_other_div').addClass('d-none');
                }
            });

            // ============================================
            // SELECT2 INITIALIZATION FOR INDUSTRY
            // ============================================

            // Get industries from Blade (passed from controller)

            const industries = @json($industries ?? []);
            console.log('Industries:', industries);
            // Build options HTML
            let industryOptions = '<option value="">Select Industry</option>';
            industries.forEach(function(item) {
                industryOptions += `<option value="${item}">${item}</option>`;
            });
            industryOptions += '<option value="Other">Other</option>';

            // Populate the select
            $('#company_industry').html(industryOptions);

            // Initialize Select2
            $('#company_industry').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select or type Industry',
                allowClear: true,
                multiple: true,
                tags: true,
                tokenSeparators: [','],
                width: '100%'
            });

            // Set existing values
            let existingIndustry = $('#company_industry_hidden').val();
            if (existingIndustry) {
                let industryArray = existingIndustry.split(', ').filter(v => v.trim());
                $('#company_industry').val(industryArray).trigger('change');
            }

            // Handle changes
            $('#company_industry').on('change', function() {
                let selectedValues = $(this).val();
                $('#company_industry_hidden').val(selectedValues ? selectedValues.join(', ') : '');

                if (selectedValues && selectedValues.includes('Other')) {
                    $('.company_industry_other_div').removeClass('d-none');
                } else {
                    $('.company_industry_other_div').addClass('d-none');
                }
            });


            // ============================================
            // SELECT2 INITIALIZATION FOR BUSINESS LOCATION
            // ============================================

            const businessLocations = @json($business_locations ?? []);

            let locationOptions = '<option value="">Select Business Location</option>';
            businessLocations.forEach(function(item) {
                locationOptions += `<option value="${item}">${item}</option>`;
            });

            $('#business_location').html(locationOptions);

            $('#business_location').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Business Location',
                allowClear: true,
                width: '100%'
            });

            let existingLocation = $('#business_location_hidden').val();
            if (existingLocation) {
                // For single select, just set the value directly (no split needed)
                $('#business_location').val(existingLocation).trigger('change');
            }

            $('#business_location').on('change', function() {
                let selectedValue = $(this).val();
                // For single select, just store the value directly (no join needed)
                $('#business_location_hidden').val(selectedValue || '');
            });

            // ============================================
            // SELECT2 INITIALIZATION FOR COMPANY ROLE
            // ============================================

            // const companyRoles = @json($company_roles ?? []);

            // let roleOptions = '<option value="">Select Your Role</option>';
            // companyRoles.forEach(function(item) {
            //     roleOptions += `<option value="${item}">${item}</option>`;
            // });

            // $('#company_role').html(roleOptions);

            // $('#company_role').select2({
            //     theme: 'bootstrap-5',
            //     placeholder: 'Select Your Role',
            //     allowClear: true,
            //     width: '100%'
            // });

            // let existingRole = $('#company_role_hidden').val();
            // if (existingRole) {
            //     $('#company_role').val(existingRole).trigger('change');
            // }

            // $('#company_role').on('change', function() {
            //     let selectedValue = $(this).val();
            //     $('#company_role_hidden').val(selectedValue || '');
            // });

            // ============================================
            // SELECT2 INITIALIZATION FOR BUSINESS CHALLENGES
            // ============================================

            const businessChallenges = @json($business_challenges ?? []);

            let challengesOptions = '<option value="">Select Current Business Challenges</option>';
            businessChallenges.forEach(function(item) {
                challengesOptions += `<option value="${item}">${item}</option>`;
            });

            $('#company_current_business_challenges').html(challengesOptions);

            $('#company_current_business_challenges').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Current Business Challenges',
                allowClear: true,
                multiple: true,
                tags: true,
                tokenSeparators: [','],
                width: '100%'
            });

            let existingChallenges = $('#company_current_business_challenges_hidden').val();
            if (existingChallenges) {
                let challengesArray = existingChallenges.split(', ').filter(v => v.trim());
                $('#company_current_business_challenges').val(challengesArray).trigger('change');
            }

            $('#company_current_business_challenges').on('change', function() {
                let selectedValues = $(this).val();
                $('#company_current_business_challenges_hidden').val(selectedValues ? selectedValues.join(
                    ', ') : '');
            });

            // ============================================
            // SELECT2 INITIALIZATION FOR BUSINESS GOALS
            // ============================================

            const businessGoals = @json($business_goals ?? []);

            let goalsOptions = '<option value="">Select Business Goals</option>';
            businessGoals.forEach(function(item) {
                goalsOptions += `<option value="${item}">${item}</option>`;
            });

            $('#company_business_goals').html(goalsOptions);

            $('#company_business_goals').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Business Goals',
                allowClear: true,
                multiple: true,
                tags: true,
                tokenSeparators: [','],
                width: '100%'
            });

            let existingGoals = $('#company_business_goals_hidden').val();
            if (existingGoals) {
                let goalsArray = existingGoals.split(', ').filter(v => v.trim());
                $('#company_business_goals').val(goalsArray).trigger('change');
            }

            $('#company_business_goals').on('change', function() {
                let selectedValues = $(this).val();
                $('#company_business_goals_hidden').val(selectedValues ? selectedValues.join(', ') : '');
            });

            // ============================================
            // SELECT2 INITIALIZATION FOR TECHNOLOGIES
            // ============================================

            // const technologies = @json($technologies ?? []);

            // let techOptions = '<option value="">Select Technologies You Use</option>';
            // technologies.forEach(function(item) {
            //     techOptions += `<option value="${item}">${item}</option>`;
            // });

            // $('#company_technologies_you_use').html(techOptions);

            // $('#company_technologies_you_use').select2({
            //     theme: 'bootstrap-5',
            //     placeholder: 'Select Technologies You Use',
            //     allowClear: true,
            //     multiple: true,
            //     tags: true,
            //     tokenSeparators: [','],
            //     width: '100%'
            // });

            // let existingTech = $('#company_technologies_you_use_hidden').val();
            // if (existingTech) {
            //     let techArray = existingTech.split(', ').filter(v => v.trim());
            //     $('#company_technologies_you_use').val(techArray).trigger('change');
            // }

            // $('#company_technologies_you_use').on('change', function() {
            //     let selectedValues = $(this).val();
            //     $('#company_technologies_you_use_hidden').val(selectedValues ? selectedValues.join(', ') :
            //         '');
            // });

            // ============================================
            // SELECT2 INITIALIZATION FOR BUYING PROCESS
            // ============================================

            // const buyingProcess = @json($buying_process ?? []);

            // let buyingOptions = '<option value="">Select Buying Process</option>';
            // buyingProcess.forEach(function(item) {
            //     buyingOptions += `<option value="${item}">${item}</option>`;
            // });

            // $('#company_buying_process').html(buyingOptions);

            // $('#company_buying_process').select2({
            //     theme: 'bootstrap-5',
            //     placeholder: 'Select Buying Process',
            //     allowClear: true,
            //     width: '100%'
            // });

            // let existingBuying = $('#company_buying_process_hidden').val();
            // if (existingBuying) {

            //     $('#company_buying_process').val(existingBuying).trigger('change');
            // }

            // $('#company_buying_process').on('change', function() {
            //     let selectedValue = $(this).val();
            //     $('#company_buying_process_hidden').val(selectedValue || '');
            // });

            // ============================================
            // SELECT2 INITIALIZATION FOR COMPANY ATTRIBUTES
            // ============================================

            const companyAttributes = @json($company_attributes ?? []);

            let attributesOptions = '<option value="">Select Company Attributes</option>';
            companyAttributes.forEach(function(item) {
                attributesOptions += `<option value="${item}">${item}</option>`;
            });

            $('#company_attributes').html(attributesOptions);

            $('#company_attributes').select2({
                theme: 'bootstrap-5',
                placeholder: 'Select Company Attributes',
                allowClear: true,
                multiple: true,
                tags: true,
                tokenSeparators: [','],
                width: '100%'
            });

            let existingAttributes = $('#company_attributes_hidden').val();
            if (existingAttributes) {
                let attributesArray = existingAttributes.split(', ').filter(v => v.trim());
                $('#company_attributes').val(attributesArray).trigger('change');
            }

            $('#company_attributes').on('change', function() {
                let selectedValues = $(this).val();
                $('#company_attributes_hidden').val(selectedValues ? selectedValues.join(', ') : '');
            });


            // ============================================
            // SELECT2 FOR OTHER SINGLE DROPDOWNS
            // ============================================
            $('#mosque_id, #gender, #age_group, #ethnicity, #nationality, #marital_status, #company_experience, #company_business_type, #company_revenue, #company_no_of_employee')
                .select2({
                    theme: 'bootstrap-5',
                    width: '100%',
                    allowClear: true
                });



        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Check if the script has already run
            if (window.languageScriptLoaded) {
                return;
            }
            window.languageScriptLoaded = true;

            const languageInput = document.getElementById('language-input');
            const languagesList = document.getElementById('languages-list');
            const languageHiddenInput = document.getElementById(
                'languages-hidden'); // Get the existing hidden input

            // Initialize the languages array with the values from the backend
            let languages = '{{ $user->languages }}'.split(',').map(lang => lang.trim()).filter(lang =>
                lang); // Trim and split into an array

            // Display the existing languages as tags
            languages.forEach(language => {
                const tag = createLanguageTag(language);
                languagesList.appendChild(tag);
            });

            // Function to create a tag for each language
            function createLanguageTag(language) {
                const tag = document.createElement('span');
                tag.classList.add('badge', 'bg-primary', 'me-2', 'mb-2');
                tag.textContent = language;

                const closeBtn = document.createElement('button');
                closeBtn.classList.add('btn-close', 'btn-close', 'ms-2');
                closeBtn.setAttribute('aria-label', 'Remove');
                closeBtn.style.fontSize = '0.7rem';
                closeBtn.style.verticalAlign = 'middle';

                // Remove language from array and UI on close button click
                closeBtn.addEventListener('click', () => {
                    languages = languages.filter(l => l !== language);
                    tag.remove();
                    updateHiddenInput(); // Update hidden input after removal
                });

                tag.appendChild(closeBtn);
                return tag;
            }

            // Handle the input field for adding languages
            languageInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    const language = languageInput.value.trim();

                    // Ensure the input is not empty and not already added
                    if (language && !languages.includes(language)) {
                        languages.push(language);
                        const tag = createLanguageTag(language);
                        languagesList.appendChild(tag);
                        languageInput.value = ''; // Clear the input field
                        updateHiddenInput(); // Update hidden input after adding a new language
                    }
                }
            });

            // Update the hidden input field with the selected languages
            function updateHiddenInput() {
                languageHiddenInput.value = languages.join(','); // Set the value as a comma-separated list
            }

            // Optionally, add event listener for form submission (if needed)
            document.querySelector('form').addEventListener('submit', function(e) {
                // Ensure the hidden input has the languages before submission
                updateHiddenInput();
            });
        });




        document.addEventListener('DOMContentLoaded', function() {
            const ethnicitySelect = document.getElementById('ethnicity');
            const otherEthnicityDiv = document.getElementById('other-ethnicity-div');
            const otherEthnicityInput = document.getElementById('other-ethnicity');
            const maritalStatusSelect = document.getElementById('marital_status');
            const otherMaritalStatusDiv = document.getElementById('other-marital-status-div');
            const otherMaritalStatusInput = document.getElementById('other-marital-status');

            maritalStatusSelect.addEventListener('change', function() {
                if (maritalStatusSelect.value === 'Other') {
                    otherMaritalStatusDiv.style.display = 'block';
                    otherMaritalStatusInput.required =
                        true; // Make the input required when "Other" is selected
                } else {
                    otherMaritalStatusDiv.style.display = 'none';
                    otherMaritalStatusInput.required = false; // Remove the required attribute otherwise
                    otherMaritalStatusInput.value = ''; // Clear the input value
                }
            });

            ethnicitySelect.addEventListener('change', function() {
                if (ethnicitySelect.value === 'Other') {
                    otherEthnicityDiv.style.display = 'block';
                    otherEthnicityInput.required = true; // Make the input required when "Other" is selected
                } else {
                    otherEthnicityDiv.style.display = 'none';
                    otherEthnicityInput.required = false; // Remove the required attribute otherwise
                    otherEthnicityInput.value = ''; // Clear the input value
                }
            });
        });
    </script>

    <script>
        document.querySelector('form').addEventListener('submit', function(event) {
            event.preventDefault();

            const userInput = document.getElementById('linkedin_user').value.trim();
            if (userInput) {
                const combinedUrl = `https://www.linkedin.com/in/${userInput}`;
                document.getElementById('linkedin_url_hidden').value = combinedUrl;
                event.target.submit();
            } else {
                alert('Please enter a valid LinkedIn username.');
            }
        });
    </script>





    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('select[name="company_business_type"]').addEventListener('change', function() {
                toggleOtherField(this, 'business_type_other_field');
            });




            function toggleOtherField(dropdown, fieldId) {
                const otherField = document.getElementById(fieldId);
                if (dropdown.value.toLowerCase() === 'other') {
                    otherField.style.display = 'block';
                } else {
                    otherField.style.display = 'none';
                    otherField.querySelector('input').value = ''; // Clear the input field
                }
            }
        });

        document.querySelector('form').addEventListener('submit', function() {
            const companyInput = document.getElementById('company_linkedin_user').value.trim();
            const combinedCompanyUrl = `https://www.linkedin.com/company/${companyInput}`;
            document.getElementById('company_linkedin_url_hidden').value = combinedCompanyUrl;
        });
    </script>
@endsection
