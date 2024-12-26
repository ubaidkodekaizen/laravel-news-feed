@extends('layouts.main')
@section('content')



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
                        <button class="nav-link" id="company-details-tab" data-bs-toggle="tab"
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
                        <div class="card_profile_first new_user_details">
                            <form action="{{ route('user.details.update') }}" method="POST" enctype="multipart/form-data"
                                id="user_details">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
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
                                        <h1 class="profile_heading text-center">
                                            Personal Information
                                        </h1>
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
                                                <input type="checkbox" class="btn-check" id="business_owner"
                                                    name="are_you[]" value="Business Owner"
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
                                                <input type="checkbox" class="btn-check" id="job_seeker"
                                                    name="are_you[]" value="Job Seeker"
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

                                </div>
                                <div class="row">

                                    <div class="col-lg-6">
                                        <label for="first_name">First Name<span class="text-danger">*</span></label>
                                        <input type="text" name="first_name" id="first_name" class="form-control"
                                            value="{{ old('first_name', $user->first_name) }}">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="last_name">Last Name<span class="text-danger">*</span></label>
                                        <input type="text" name="last_name" id="last_name" class="form-control"
                                            value="{{ old('last_name', $user->last_name) }}">
                                    </div>

                                    <div class="col-lg-6">
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
                                        <input type="tel" name="phone" id="phone" class="form-control"
                                            value="{{ old('phone', $user->phone) }}">
                                    </div>

                                    <div class="col-lg-6">
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


                                    <div class="col-lg-6">
                                        <label for="city">City<span class="text-danger">*</span></label>
                                        <input type="text" name="city" id="city" class="form-control"
                                            value="{{ old('city', $user->city) }}">
                                        {{-- {!! \App\Helpers\DropDownHelper::renderCityDropdownForUser($user->state, $user->city) !!} --}}
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="county">County</label>
                                        <input type="text" name="county" id="county" class="form-control"
                                            value="{{ old('county', $user->county) }}">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="state">State</label>
                                        <input type="text" name="state" id="state" class="form-control"
                                            value="{{ old('state', $user->state) }}">
                                        {{-- {!! \App\Helpers\DropDownHelper::renderStateDropdownForUser($user->country, $user->state) !!} --}}
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="country">Country<span class="text-danger">*</span></label>
                                        <input type="text" name="country" id="country" class="form-control"
                                            value="{{ old('country', $user->country) }}">
                                        {{-- {!! \App\Helpers\DropDownHelper::renderCountryDropdownForUser($user->country) !!} --}}
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="gender">Gender</label>
                                        <select name="gender" id="gender" class="form-select">
                                            <option value="Male" {{ $user->gender === 'Male' ? 'selected' : '' }}>Male
                                            </option>
                                            <option value="Female" {{ $user->gender === 'Female' ? 'selected' : '' }}>
                                                Female</option>

                                            <option value="Prefer not to disclose"
                                                {{ $user->gender === 'Prefer not to disclose' ? 'selected' : '' }}>Prefer
                                                not to disclose</option>
                                            <option value="Other" {{ $user->gender === 'Other' ? 'selected' : '' }}>Other
                                            </option>
                                        </select>
                                    </div>


                                    <div class="col-lg-6">
                                        <label for="age_group">Age Group</label>
                                        <select name="age_group" id="age_group" class="form-select">
                                            <option value="20-30" {{ $user->age_group === '20-30' ? 'selected' : '' }}>
                                                20-30</option>
                                            <option value="31-40" {{ $user->age_group === '31-40' ? 'selected' : '' }}>
                                                31-40</option>
                                            <option value="41-50" {{ $user->age_group === '41-50' ? 'selected' : '' }}>
                                                41-50</option>
                                            <option value="51-60" {{ $user->age_group === '51-60' ? 'selected' : '' }}>
                                                51-60</option>
                                            <option value="60+" {{ $user->age_group === '60+' ? 'selected' : '' }}>60+
                                            </option>
                                            <option value="Prefer not to disclose"
                                                {{ $user->age_group === 'Prefer not to disclose' ? 'selected' : '' }}>
                                                Prefer not to disclose</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="ethnicity">Ethnicity</label>
                                        <fieldset>
                                            <select class="form-select dropdown" id="ethnicity" name="ethnicity">
                                                <option value="" disabled="disabled">-- select one --</option>
                                                <optgroup label="African">
                                                    <option value="North African (e.g., Arab, Berber, Nubian)"
                                                        {{ $user->ethnicity === 'North African (e.g., Arab, Berber, Nubian)' ? 'selected' : '' }}>
                                                        North African (e.g., Arab, Berber, Nubian)</option>
                                                    <option value="Sub-Saharan African (e.g., Hausa, Yoruba, Somali, Zulu)"
                                                        {{ $user->ethnicity === 'Sub-Saharan African (e.g., Hausa, Yoruba, Somali, Zulu)' ? 'selected' : '' }}>
                                                        Sub-Saharan African (e.g., Hausa, Yoruba, Somali, Zulu)</option>
                                                    <option value="Afro-Caribbean"
                                                        {{ $user->ethnicity === 'Afro-Caribbean' ? 'selected' : '' }}>
                                                        Afro-Caribbean</option>
                                                    <option value="African-American"
                                                        {{ $user->ethnicity === 'African-American' ? 'selected' : '' }}>
                                                        African-American</option>
                                                </optgroup>
                                                <optgroup label="Asian">
                                                    <option value="South Asian"
                                                        {{ $user->ethnicity === 'South Asian' ? 'selected' : '' }}>
                                                        South Asian (e.g., Indian, Pakistani, Bangladeshi, Sri Lankan,
                                                        Nepali)</option>
                                                    <option value="East Asian"
                                                        {{ $user->ethnicity === 'East Asian' ? 'selected' : '' }}>
                                                        East Asian (e.g., Chinese, Japanese, Korean)</option>
                                                    <option value="Southeast Asian"
                                                        {{ $user->ethnicity === 'Southeast Asian' ? 'selected' : '' }}>
                                                        Southeast Asian (e.g., Malay, Filipino, Indonesian, Thai, Burmese)
                                                    </option>
                                                    <option value="Central Asian"
                                                        {{ $user->ethnicity === 'Central Asian' ? 'selected' : '' }}>
                                                        Central Asian (e.g., Kazakh, Uzbek, Turkmen, Tajik, Kyrgyz)</option>
                                                    <option value="West Asian/Middle Eastern"
                                                        {{ $user->ethnicity === 'West Asian/Middle Eastern' ? 'selected' : '' }}>
                                                        West Asian/Middle Eastern (e.g., Arab, Persian, Kurdish, Turkish,
                                                        Assyrian)</option>
                                                </optgroup>
                                                <optgroup label="European">
                                                    <option value="Eastern European"
                                                        {{ $user->ethnicity === 'Eastern European' ? 'selected' : '' }}>
                                                        Eastern European (e.g., Russian, Polish, Ukrainian, Romanian)
                                                    </option>
                                                    <option value="Western European"
                                                        {{ $user->ethnicity === 'Western European' ? 'selected' : '' }}>
                                                        Western European (e.g., British, French, German, Dutch)</option>
                                                    <option value="Southern European"
                                                        {{ $user->ethnicity === 'Southern European' ? 'selected' : '' }}>
                                                        Southern European (e.g., Italian, Spanish, Greek, Portuguese)
                                                    </option>
                                                    <option value="Balkan (e.g., Bosnian, Albanian)"
                                                        {{ $user->ethnicity === 'Balkan (e.g., Bosnian, Albanian)' ? 'selected' : '' }}>
                                                        Balkan (e.g., Bosnian, Albanian)</option>
                                                </optgroup>
                                                <optgroup label="Latino/Hispanic">
                                                    <option value="Latino/Hispanic"
                                                        {{ $user->ethnicity === 'Latino/Hispanic' ? 'selected' : '' }}>
                                                        Latino/Hispanic (e.g., Mexican, Brazilian, Colombian, Cuban)
                                                    </option>
                                                </optgroup>
                                                <optgroup label="Mixed/Multiracial">
                                                    <option value="Multiracial/Mixed Heritage"
                                                        {{ $user->ethnicity === 'Multiracial/Mixed Heritage' ? 'selected' : '' }}>
                                                        Multiracial/Mixed Heritage</option>
                                                </optgroup>

                                                <option value="Prefer not to disclose"
                                                    {{ $user->ethnicity === 'Prefer not to disclose' ? 'selected' : '' }}>
                                                    Prefer not to disclose</option>
                                                <optgroup label="Other">
                                                    <option value="Other"
                                                        {{ $user->ethnicity === 'Other' ? 'selected' : '' }}>Other</option>
                                                </optgroup>
                                            </select>
                                        </fieldset>
                                    </div>

                                    <div class="col-lg-6" id="other-ethnicity-div"
                                        style="{{ $user->ethnicity === 'Other' ? '' : 'display: none;' }}">
                                        <label for="other-ethnicity">Please specify your ethnicity</label>
                                        <input type="text" class="form-control" id="other-ethnicity"
                                            name="other_ethnicity" placeholder="Enter ethnicity"
                                            value="{{ $user->other_ethnicity }}">
                                    </div>


                                    <div class="col-lg-6">
                                        <label for="nationality">Nationality</label>
                                        {!! \App\Helpers\DropDownHelper::nationalityDropdown($user->nationality) !!}
                                    </div>

                                    <div class="col-lg-6">
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




                                    <div class="col-lg-6">
                                        <label for="marital_status">Marital Status</label>
                                        <select name="marital_status" id="marital_status" class="form-select">
                                            <option value="Single"
                                                {{ $user->marital_status === 'Single' ? 'selected' : '' }}>Single</option>
                                            <option value="Married"
                                                {{ $user->marital_status === 'Married' ? 'selected' : '' }}>Married
                                            </option>
                                            <option value="Divorced"
                                                {{ $user->marital_status === 'Divorced' ? 'selected' : '' }}>Divorced
                                            </option>

                                            <option value="Prefer not to disclose"
                                                {{ $user->marital_status === 'Prefer not to disclose' ? 'selected' : '' }}>
                                                Prefer not to disclose</option>
                                            <option value="Other"
                                                {{ $user->marital_status === 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>

                                    <div class="col-lg-6" id="other-marital-status-div"
                                        style="{{ $user->marital_status === 'Other' ? '' : 'display: none;' }}">
                                        <label for="other-marital-status">Please specify your marital status</label>
                                        <input type="text" class="form-control" id="other-marital-status"
                                            name="other_marital_status" placeholder="Enter marital status"
                                            value="{{ $user->other_marital_status }}">
                                    </div>


                                    <div class="col-12">
                                        <label for="education">Education</label>
                                        <div class="row" id="education-row">
                                            <div class="col-lg-4">
                                                <label for="college_name" class="mt-2">Name of College/University
                                                    Attended</label>
                                                <input type="text" name="college_name[]" id="college_name"
                                                    class="form-control" placeholder="Enter college/university name"
                                                    value="{{ $user->userEducations[0]->college_university ?? '' }}">
                                            </div>
                                            <div class="col-lg-4">
                                                <label for="degree" class="mt-2">Degree/Diploma</label>
                                                <input type="text" name="degree[]" id="degree"
                                                    class="form-control" placeholder="Enter degree/diploma"
                                                    value="{{ $user->userEducations[0]->degree_diploma ?? '' }}">
                                            </div>
                                            <div class="col-lg-3">
                                                <label for="year_graduated" class="mt-2">Year Graduated</label>
                                                <input type="text" name="year_graduated[]" id="year_graduated"
                                                    class="form-control" placeholder="Enter year"
                                                    value="{{ $user->userEducations[0]->year ?? '' }}">
                                            </div>
                                            <div class="col-lg-1">
                                                <div class="flex_field_btn h-100">
                                                    <button type="button" id="add-education"
                                                        class="btn btn-primary mt-4">
                                                        <i class="fas fa-plus"></i> Add
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <div class="col-lg-6">
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
                                    <div class="col-lg-6">
                                        <label for="facebook_url">Facebook</label>
                                        <input type="text" name="facebook_url" id="facebook_url" class="form-control"
                                            value="{{ old('facebook_url', $user->facebook_url) }}" placeholder="Link">
                                    </div>

                                    <div class="col-lg-6">
                                        <label for="x_url">X (Formerly Twitter)</label>
                                        <input type="text" name="x_url" id="x_url" class="form-control"
                                            value="{{ old('x_url', $user->x_url) }}" placeholder="Link">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="instagram">Instagram</label>
                                        <input type="text" name="instagram_url" id="instagram" class="form-control"
                                            placeholder="Link" value="{{ old('x_url', $user->instagram_url) }}">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="tikTok">TikTok</label>
                                        <input type="text" name="tiktok_url" id="tikTok" class="form-control"
                                            placeholder="Link" value="{{ old('x_url', $user->tiktok_url) }}">
                                    </div>
                                    <div class="col-lg-6">
                                        <label for="youTube">YouTube</label>
                                        <input type="text" name="youtube_url" id="youTube" class="form-control"
                                            placeholder="Link" value="{{ old('x_url', $user->youtube_url) }}">
                                    </div>

                                    <div class="col-12 mt-5">
                                        <button type="submit" class="btn btn-primary w-100">Save</button>
                                    </div>
                                </div>
                            </form>
                        </div>

                    </div>
                    <div class="tab-pane fade" id="company-details-tab-pane" role="tabpanel"
                        aria-labelledby="company-details-tab" tabindex="0">
                        <div class="card_profile_first new_user_details">
                            <form action="{{ route('user.company.update') }}" method="POST"
                                enctype="multipart/form-data" id="user_company">
                                @csrf
                                <div class="row">
                                    <div class="col-12">
                                        <div class="profile_pic">
                                            <div class="avatar-upload mb-3">
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
                                        <h1 class="profile_heading text-center mt-0">
                                            Professional Information
                                        </h1>
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
                                </div>
                                <div class="row align-items-end">
                                    <!-- Company Name -->
                                    <div class="col-lg-6">
                                        <label for="company_name">Company Name</label>
                                        <input type="text" name="company_name" id="company_name" class="form-control"
                                            value="{{ old('company_name', $company->company_name ?? '') }}" required>
                                    </div>


                                    <div class="col-lg-6">
                                        <label for="company_web_url">Company URL</label>
                                        <input type="text" name="company_web_url" id="company_web_url"
                                            class="form-control"
                                            value="{{ old('company_web_url', $company->company_web_url ?? '') }}"
                                            required>
                                    </div>



                                    <div class="col-lg-6 custom-select-dropdown">
                                        <label for="company_position">Title/Designation</label>

                                        <div class="selected-tags my-2">
                                            {{ $company->company_position ?? '' }}
                                        </div>
                                        <input type="hidden" id="company_position_hidden" name="company_position"
                                            value="{{ old('company_position', $company->company_position ?? '') }}" />
                                        {!! \App\Helpers\DropDownHelper::designationDropdown($company->company_position ?? '') !!}

                                    </div>

                                    <div class="col-lg-6 company_position_other_div d-none">
                                        <label for="company_position_other">Title/Designation Other</label>
                                        <input type="text" name="company_position_other" id="company_position_other"
                                            class="form-control" value="" required>
                                    </div>

                                    <div class="col-lg-6 custom-select-dropdown mt-0">
                                        <label for="company_experience">Years of Experience</label>
                                        <select name="company_experience" id="company_experience" class="form-select">
                                            <option value="Under 1"
                                                {{ optional($company)->company_experience == 'Under 1' ? 'selected' : '' }}>
                                                Under 1
                                            </option>
                                            <option value="1-5 years"
                                                {{ optional($company)->company_experience == '1-5 years' ? 'selected' : '' }}>
                                                1-5 years
                                            </option>
                                            <option value="5-10 years"
                                                {{ optional($company)->company_experience == '5-10 years' ? 'selected' : '' }}>
                                                5-10 years
                                            </option>
                                            <option value="10-20 years"
                                                {{ optional($company)->company_experience == '10-20 years' ? 'selected' : '' }}>
                                                10-20 years
                                            </option>
                                            <option value="20+ years"
                                                {{ optional($company)->company_experience == '20+ years' ? 'selected' : '' }}>
                                                20+ years
                                            </option>
                                        </select>

                                    </div>


                                    <div class="col-lg-6">
                                        <label for="work_phone_num">Work Phone Number</label>
                                        <input type="tel" name="company_phone" id="company_phone"
                                            class="form-control"
                                            value="{{ old('company_phone', $company->company_phone ?? '') }}" required>
                                    </div>
                                    <!-- Company Linkedin URL -->
                                    <div class="col-lg-6">
                                        <label for="company_linkedin_user">Company LinkedIn Page</label>
                                        <div class="input-group">
                                            <div class="input-group-text">https://www.linkedin.com/company/</div>
                                            <input type="text" name="company_linkedin_user" id="company_linkedin_user"
                                                class="form-control"
                                                value="{{ old('company_linkedin_user', str_replace('https://www.linkedin.com/company/', '', $company->company_linkedin_url ?? '')) }}"
                                                required>
                                        </div>
                                        <input type="hidden" name="company_linkedin_url"
                                            id="company_linkedin_url_hidden" value="">

                                    </div>

                                    <div class="col-lg-6">
                                        <label for="company_industry">Industry</label>
                                        {!! \App\Helpers\DropDownHelper::renderIndustryDropdown(
                                            $company->company_industry ?? '',
                                            $company->company_sub_category ?? '',
                                        ) !!}
                                        <div id="industry_other_field" style="display: none;">
                                            <label for="industry_other">Other Industry</label>
                                            <input type="text" name="company_industry_other" id="industry_other"
                                                class="form-control" placeholder="Enter other industry">
                                        </div>
                                    </div>

                                    <!-- Business Type Dropdown -->
                                    <div class="col-lg-6">
                                        <label for="company_business_type">Company Type</label>
                                        {!! \App\Helpers\DropDownHelper::renderBusinessTypeDropdown($company->company_business_type ?? '') !!}
                                        <div id="business_type_other_field" style="display: none;">
                                            <label for="business_type_other">Other Company Type</label>
                                            <input type="text" name="company_business_type_other"
                                                id="business_type_other" class="form-control"
                                                placeholder="Enter other business type">
                                        </div>
                                    </div>

                                    <!-- Company Revenue -->
                                    <div class="col-lg-6">
                                        <label for="company_revenue">Company Revenue:</label>
                                        {!! \App\Helpers\DropDownHelper::renderRevenueDropdown($company->company_revenue ?? '') !!}
                                    </div>

                                    <!-- Number of Employees Dropdown -->
                                    <div class="col-lg-6">
                                        <label for="company_no_of_employee">Company No. of Employees</label>
                                        {!! \App\Helpers\DropDownHelper::renderEmployeeSizeDropdown($company->company_no_of_employee ?? '') !!}
                                    </div>


                                    <!-- Products/Services -->
                                    <div class="col-12">
                                        <label for="product_service_name">List of Services/Products</label>
                                        <div class="row" id="product-service-row">
                                            <div class="col-lg-5">
                                                <label for="product_service_name" class="mt-2">Name</label>
                                                <input type="text" name="product_service_name[]"
                                                    id="product_service_name" class="form-control"
                                                    value="{{ old('product_service_name[]', $company->productServices[0]->product_service_name ?? '') }}"
                                                    required>
                                            </div>
                                            <div class="col-lg-6">
                                                <label for="product_service_description"
                                                    class="mt-2">Description</label>
                                                <input type="text" name="product_service_description[]"
                                                    id="product_service_description"
                                                    value="{{ old('product_service_description[]', $company->productServices[0]->product_service_description ?? '') }}"class="form-control"
                                                    required>
                                            </div>
                                            <div class="col-lg-1">
                                                <div class="flex_field_btn h-100">
                                                    <button type="button" id="add-product-service"
                                                        class="btn btn-primary">
                                                        <i class="fas fa-plus"></i> Add
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="col-12 mt-4 text-end">
                                        <button type="submit" class="btn theme-btn">Submit</button>
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
    <script>
        let eduCount = 1;

        document.getElementById('add-education').addEventListener('click', function() {
            eduCount++;
            const rowContainer = document.getElementById('education-row');
            const newRow = document.createElement('div');
            newRow.classList.add('row', 'mt-2');

            // Name of College/University Attended Column
            const collegeCol = document.createElement('div');
            collegeCol.classList.add('col-lg-4');
            const collegeLabel = document.createElement('label');
            collegeLabel.setAttribute('for', 'college_name_' + eduCount);
            collegeLabel.innerText = 'Name of College/University Attended ' + eduCount;
            const collegeInput = document.createElement('input');
            collegeInput.type = 'text';
            collegeInput.name = 'college_name[]';
            collegeInput.id = 'college_name_' + eduCount;
            collegeInput.classList.add('form-control');
            collegeInput.placeholder = 'Enter college/university name';
            collegeInput.required = true;
            collegeCol.appendChild(collegeLabel);
            collegeCol.appendChild(collegeInput);

            // Degree/Diploma Column
            const degreeCol = document.createElement('div');
            degreeCol.classList.add('col-lg-4');
            const degreeLabel = document.createElement('label');
            degreeLabel.setAttribute('for', 'degree_' + eduCount);
            degreeLabel.innerText = 'Degree/Diploma ' + eduCount;
            const degreeInput = document.createElement('input');
            degreeInput.type = 'text';
            degreeInput.name = 'degree[]';
            degreeInput.id = 'degree_' + eduCount;
            degreeInput.classList.add('form-control');
            degreeInput.placeholder = 'Enter degree/diploma';
            degreeInput.required = true;
            degreeCol.appendChild(degreeLabel);
            degreeCol.appendChild(degreeInput);

            // Year Graduated Column
            const yearCol = document.createElement('div');
            yearCol.classList.add('col-lg-3');
            const yearLabel = document.createElement('label');
            yearLabel.setAttribute('for', 'year_graduated_' + eduCount);
            yearLabel.innerText = 'Year Graduated ' + eduCount;
            const yearInput = document.createElement('input');
            yearInput.type = 'text';
            yearInput.name = 'year_graduated[]';
            yearInput.id = 'year_graduated_' + eduCount;
            yearInput.classList.add('form-control');
            yearInput.placeholder = 'Enter year';
            yearInput.required = true;
            yearCol.appendChild(yearLabel);
            yearCol.appendChild(yearInput);

            // Delete Button Column
            const actionCol = document.createElement('div');
            actionCol.classList.add('col-lg-1');
            const flexFieldBtn = document.createElement('div');
            flexFieldBtn.classList.add('flex_field_btn', 'h-100');
            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.classList.add('btn', 'btn-danger', 'mt-4');
            deleteButton.innerHTML = '<i class="fas fa-trash-alt"></i>';
            deleteButton.onclick = function() {
                rowContainer.removeChild(newRow);
                eduCount--;
            };
            flexFieldBtn.appendChild(deleteButton);
            actionCol.appendChild(flexFieldBtn);

            // Append all columns to the new row
            newRow.appendChild(collegeCol);
            newRow.appendChild(degreeCol);
            newRow.appendChild(yearCol);
            newRow.appendChild(actionCol);

            // Add new row to container
            rowContainer.appendChild(newRow);
        });


        document.addEventListener('DOMContentLoaded', function() {
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
                closeBtn.classList.add('btn-close', 'btn-close-white', 'ms-2');
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
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelector('select[name="industry_to_connect"]').addEventListener('change', function() {
                toggleOtherField(this, 'industry_other_field_2');
            });

            document.querySelector('select[name="sub_category_to_connect"]').addEventListener('change', function() {
                toggleOtherField(this, 'subcategory_other_field');
            });

            document.querySelector('select[name="community_interest"]').addEventListener('change', function() {
                toggleOtherField(this, 'community_interest_other_field');
            });

            function toggleOtherField(dropdown, fieldId) {
                const otherField = document.getElementById(fieldId);
                if (dropdown.value.toLowerCase() === 'other') {
                    otherField.style.display = 'block';
                } else {
                    otherField.style.display = 'none';
                    otherField.querySelector('input').value = '';
                }
            }
        });


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
        document.addEventListener("DOMContentLoaded", () => {
            const checkboxes = document.querySelectorAll('.form-check-input');
            const hiddenInput = document.getElementById('company_position_hidden');
            const otherCheckbox = document.getElementById('company_position_other_select');
            const otherFieldDiv = document.querySelector('.company_position_other_div');
            const otherInput = document.getElementById('company_position_other');
            const tagContainer = document.querySelector('.selected-tags'); // Container for tags
            const searchInput = document.getElementById('search-dropdown'); // Search input for dropdown

            const selectedPositions = hiddenInput.value.split(',').map(pos => pos
                .trim()); // Split saved positions into an array

            const updateHiddenInput = () => {
                const selected = Array.from(document.querySelectorAll('.form-check-input:checked'))
                    .filter(cb => cb !== otherCheckbox)
                    .map(cb => cb.labels[0].textContent.trim());

                if (otherCheckbox.checked && otherInput.value.trim()) {
                    selected.push(otherInput.value.trim());
                }

                hiddenInput.value = selected.join(', '); // Update hidden input with selected positions
                updateTags(); // Reflect changes in tags
            };

            const updateTags = () => {
                tagContainer.innerHTML = ''; // Clear existing tags
                const selectedCheckboxes = Array.from(document.querySelectorAll('.form-check-input:checked'));
                selectedCheckboxes.forEach((checkbox) => {
                    const label = checkbox.labels[0]; // Get associated label
                    if (label) { // Check if label exists
                        const tagText = label.textContent.trim();
                        createTag(tagText, checkbox);
                    }
                });

                // Handle "Other" tag if selected
                if (otherCheckbox.checked && otherInput.value.trim()) {
                    createTag(otherInput.value.trim(), null, true); // Add "Other" as a tag
                }
            };

            const createTag = (text, checkbox, isOther = false) => {
                const tag = document.createElement('span');
                tag.className = 'tag';
                tag.textContent = text;

                const closeButton = document.createElement('span');
                closeButton.className = 'tag-close';
                closeButton.textContent = '×';
                closeButton.addEventListener('click', () => {
                    tag.remove();
                    if (isOther) {
                        otherCheckbox.checked = false;
                        otherInput.value = '';
                        otherFieldDiv.classList.add('d-none');
                        otherFieldDiv.classList.remove('d-block');
                    } else if (checkbox) {
                        checkbox.checked = false;
                    }
                    updateHiddenInput();
                });

                tag.appendChild(closeButton);
                tagContainer.appendChild(tag);
            };



            selectedPositions.forEach((position) => {
                const checkbox = Array.from(checkboxes).find(cb => {
                    const label = cb.labels[0];
                    return label && label.textContent.trim() === position;
                });

                if (checkbox) {
                    checkbox.checked = true;
                } else if (position === "Other") {
                    otherCheckbox.checked = true;
                    otherFieldDiv.classList.remove('d-none');
                    otherFieldDiv.classList.add('d-block');
                    otherInput.value = position;
                }
            });



            checkboxes.forEach((checkbox) => {
                checkbox.addEventListener('change', () => {
                    if (otherCheckbox.checked) {
                        otherFieldDiv.classList.remove('d-none');
                        otherFieldDiv.classList.add('d-block');
                    } else {
                        otherFieldDiv.classList.add('d-none');
                        otherFieldDiv.classList.remove('d-block');
                    }
                    updateHiddenInput();
                });
            });


            otherInput.addEventListener('keyup', updateHiddenInput);


            searchInput.addEventListener('input', () => {
                const dropdownMenu = document.querySelector('.position-dropdown-menu');
                const searchTerm = searchInput.value.toLowerCase().trim();
                const listItems = dropdownMenu.querySelectorAll('li');

                listItems.forEach((item) => {
                    const label = item.querySelector('.form-check-label');
                    if (label) {
                        const labelText = label.textContent
                            .toLowerCase();


                        if (labelText.includes(searchTerm)) {
                            item.style.display = '';
                        } else {
                            item.style.display = 'none';
                        }
                    }
                });
            });
        });
    </script>



    <script>
        // document.addEventListener("DOMContentLoaded", function() {
        //     const industryDropdown = document.getElementById("company_industry");
        //     // const subCategoryDropdown = document.getElementById("company_sub_category");
        //     // const selectedSubcategory = "{{ $company->company_sub_category ?? '' }}";

        //     // industryDropdown.addEventListener("change", function() {
        //     //     loadSubcategories(industryDropdown.value);
        //     // });

        //     // function loadSubcategories(industryName) {
        //     //     subCategoryDropdown.innerHTML = "<option value=\'\'>Select Company Sub Industry</option>";

        //     //     if (industryName) {
        //     //         fetch("{{ route('get-category', ['industryId' => '__industryName__']) }}".replace(
        //     //                 '__industryName__', industryName))
        //     //             .then(response => response.json())
        //     //             .then(data => {
        //     //                 data.forEach(function(subCategory) {
        //     //                     let option = document.createElement("option");
        //     //                     option.value = subCategory.name;
        //     //                     option.text = subCategory.name;
        //     //                     option.selected = (subCategory.name === selectedSubcategory);
        //     //                     subCategoryDropdown.appendChild(option);
        //     //                 });
        //     //             })
        //     //             .catch(error => {
        //     //                 console.error("Error fetching subcategories:", error);
        //     //             });
        //     //     }
        //     // }
        //     loadSubcategories(industryDropdown.value);
        // });



        document.addEventListener('DOMContentLoaded', function() {
            document.querySelector('select[name="company_business_type"]').addEventListener('change', function() {
                toggleOtherField(this, 'business_type_other_field');
            });

            document.querySelector('select[name="company_industry"]').addEventListener('change', function() {
                toggleOtherField(this, 'industry_other_field');
            });

            // document.querySelector('select[name="company_sub_category"]').addEventListener('change', function() {
            //     toggleOtherField(this, 'subcategory_other_field');
            // });

            // document.querySelector('select[name="company_contribute_to_muslim_community"]').addEventListener(
            //     'change',
            //     function() {
            //         toggleOtherField(this, 'contribution_other_field');
            //     });

            // document.querySelector('select[name="company_affiliation_to_muslim_org"]').addEventListener('change',
            //     function() {
            //         toggleOtherField(this, 'affiliation_other_field');
            //     });

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




        let count = 1;

        document.getElementById('add-product-service').addEventListener('click', function() {
            count++;
            const rowContainer = document.getElementById('product-service-row');
            const newRow = document.createElement('div');
            newRow.classList.add('row', 'mt-2');

            // Product/Service Name Column
            const nameCol = document.createElement('div');
            nameCol.classList.add('col-lg-5');
            const nameLabel = document.createElement('label');
            nameLabel.setAttribute('for', 'product_service_name_' + count);
            nameLabel.innerText = 'Name ' + count;
            const nameInput = document.createElement('input');
            nameInput.type = 'text';
            nameInput.name = 'product_service_name[]';
            nameInput.id = 'product_service_name_' + count;
            nameInput.classList.add('form-control');
            nameCol.appendChild(nameLabel);
            nameCol.appendChild(nameInput);

            // Product/Service Description Column
            const descCol = document.createElement('div');
            descCol.classList.add('col-lg-6');
            const descLabel = document.createElement('label');
            descLabel.setAttribute('for', 'product_service_description_' + count);
            descLabel.innerText = 'Description ' + count;
            const descInput = document.createElement('input');
            descInput.type = 'text';
            descInput.name = 'product_service_description[]';
            descInput.id = 'product_service_description_' + count;
            descInput.classList.add('form-control');
            descCol.appendChild(descLabel);
            descCol.appendChild(descInput);

            // Product Service Area Column with Delete Button inside flex_field_btn
            const areaCol = document.createElement('div');
            areaCol.classList.add('col-lg-1');

            const flexFieldBtn = document.createElement('div');
            flexFieldBtn.classList.add('flex_field_btn');
            flexFieldBtn.classList.add('h-100');

            // const fieldDiv = document.createElement('div');
            // fieldDiv.classList.add('field', 'w-75');

            // const areaLabel = document.createElement('label');
            // areaLabel.setAttribute('for', 'product_service_area_' + count);
            // areaLabel.innerText = 'Product Service Area ' + count;

            // const areaInput = document.createElement('input');
            // areaInput.type = 'text';
            // areaInput.name = 'product_service_area[]';
            // areaInput.id = 'product_service_area_' + count;
            // areaInput.classList.add('form-control');

            // fieldDiv.appendChild(areaLabel);
            // fieldDiv.appendChild(areaInput);
            // flexFieldBtn.appendChild(fieldDiv);

            // Delete Button
            const deleteButton = document.createElement('button');
            deleteButton.type = 'button';
            deleteButton.classList.add('btn', 'btn-danger', 'ml-2');
            deleteButton.innerHTML = '<i class="fas fa-trash-alt"></i>';
            deleteButton.onclick = function() {
                rowContainer.removeChild(newRow);
                count--;
            };
            flexFieldBtn.appendChild(deleteButton);

            areaCol.appendChild(flexFieldBtn);

            // Append all columns to the new row
            newRow.appendChild(nameCol);
            newRow.appendChild(descCol);
            newRow.appendChild(areaCol);

            // Add new row to container
            rowContainer.appendChild(newRow);
        });
    </script>
@endsection
