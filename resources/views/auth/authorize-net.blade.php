<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muslim Lynk | Founders and CEO Database</title>
    <link rel="icon" href="{{ asset('assets/images/logo_bg.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/css/auth-style.css') }}">
    <style>
        body {
            background: #fff;
            margin: 0;
            padding: 0;
        }

        .img_side_width {
            min-width: 530px;
            width: 100%;
        }

        .form_flex {
            display: flex;
            align-items: center;
            justify-content: center;
            /* height: 100vh; */
            width: 100%;
        }

        .form_container .row:first-child {
            border-radius: 50px;
            overflow: hidden;
            width: 100%;
        }

        /* h2, p, label{
            color: #000;
            -webkit-text-fill-color: #000 !important;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
        } */

        h2 {
            font-size: 58.7px;
            font-weight: 500;
            color: #000;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
        }

        .para {
            color: #4F4F4F !important;
            font-size: 22.35px;
            margin-left: 4px;
            font-weight: 400;
            margin-top: 10px;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
        }

        .input-box {
            margin: 80px 0px 0px 0px;
            border: none;
        }

        .input-box input {
            color: #000000ff;
            font-size: 18.37px;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }

        .input-box label:last-child {
            position: absolute;
            top: -40% !important;
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
            font-size: 18.37px;
        }

        .input-box input {
            border-radius: 9.77px;
            border: 3.91px solid #F4F4F4 !important;
            color: #000 !important;
            padding: 30px 16px;
        }

        select {
            background-color: #FFF;
            border-radius: 9.77px;
            border: 3.91px solid #F4F4F4 !important;
            color: #000 !important;
            padding: 10px 16px;
            font-size: 18.37px;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }

        ::placeholder {
            color: #898F9B;
            font-size: 18.37px;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }

        /* form.form{
    width: 684.18px;
    margin: auto;
    } */

        .form-section {
            background: #ffffff;
            width: 90%;
            margin: auto;
        }

        .form-check .form-check-input {
            margin-right: 0.51em;
            margin-left: 0.1em;
        }

        .input-box label:last-child {
            position: absolute;
            top: -40% !important;
            font-family: "Poppins", sans-serif;
            font-weight: 400;
            font-style: normal;
            font-size: 18.37px;
            color: #000;
        }

        .input-box .icon {
            color: #000000;
        }

        .account_signup {
            margin-top: 10px;
            /* display: flex; */
            /* justify-content: space-between; */
            padding: 20px 3px 0px 3px;
        }

        .account_signup span {
            font-weight: 400;
            font-size: 18.39px;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
            text-transform: capitalize !important;
            color: #434343;
        }

        .accountSignup {
            text-align: center !important;
            padding: 0;
            margin: 0;
        }

        .accountSignup a {
            color: #898F9B !important;
        }

        .form-check-label {
            color: #000000;
            font-size: 18.37px;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }

        .custom-btn {
            width: 40%;
            height: 66px;
            font-size: 18.37px;
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            font-style: normal;
            padding: 19px 16px !important;
            margin: 20px auto;
            color: #fff;
            border-radius: 9.77px;
            background: #273572;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: flex;
            justify-content: center;
            box-shadow: none !important;
            outline: none !important;
        }


        .mainHeadingCon {
            display: flex;
            justify-content: space-between;
        }

        .mainHeadingConInner {
            align-content: center;
        }

        div#innerImage {
            display: flex;
            width: fit-content;
            height: fit-content;
            justify-content: end;
            margin-bottom: 60px;
        }

        div#innerImage img {
            max-width: 140px;
        }

        .select {
            border-radius: 9.77px;
            /* border: 3.91px solid #F4F4F4 !important; */
            height: 4em;
        }

        .form-check input[type="checkbox"] {
            -webkit-appearance: none;
            appearance: none;
            width: 20px;
            height: 20px;
            margin-bottom: -2.4px;
            border: 3px solid #273572;
            border-radius: 3.91px;
            /* rounded corners */
            cursor: pointer;
            position: relative;
            outline: none;
            transition: 0.25s;
        }

        .select::after {
            content: '';
            color: transparent;
            background-color: transparent;
        }

        /* Checked state */
        .form-check input[type="checkbox"]:checked {
            background-color: #273572;
            /* fill color */
            border-color: #273572;
        }

        /* Add checkmark */
        /* .form-check input[type="checkbox"]:checked::after {
    content: "✔";
    color: white;
    font-size: 12px;
    position: absolute;
    top: -2px;
    left: 2px;
} */

        .bx-chevron-down:before {
            content: "\ea4a";
            font-size: 32px;
            top: 9px;
            right: 9px;
            color: #273572;
            position: relative;
        }

        .bxs-credit-card:before {
            content: "\ed6b";
            font-size: 22px;
            top: 6px;
            right: 9px;
            color: #273572;
            position: relative;
            background: #fff;
        }

        .bxs-calendar:before {
            content: "\ed00";
            font-size: 22px;
            top: 6px;
            right: 9px;
            color: #273572;
            position: relative;
            background: #fff;
        }

        @media(max-width: 992px) {
            .mainHeadingCon {
                flex-direction: column-reverse;
                align-items: center;
                justify-content: center;
                text-align: center;
            }

            div#innerImage {
                justify-content: center;
            }
            .custom-btn {
                width: 100%;
            }
        }
         @media(max-width: 768px) {
            h2 {
                font-size: 30px;
            }
            p {
                font-size: 18px;
            }
        }
    </style>
</head>

<body>
    <div class="form_container">
        <div class="form_flex">
            <div class="row">
                <!-- <div class="col-lg-6 mobile_hide">
                    <div class="img_side_width">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid">
                    </div>

                </div> -->
                <div class="col-lg-12">
                    <div class="form-section">
                        <div class="mainHeadingCon">
                            <div class="mainHeadingConInner">
                                <h2 class="heading mb-2">Create your account</h2>
                                <p class="para">Enter your details to create account</p>
                            </div>

                            <div id="innerImage" class="img_side_width mainHeadingConInner">
                                <a href="{{route('home')}}">
                                    <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid">
                                </a>
                            </div>
                        </div>

                        @if (session('success'))
                            <div class="alert alert-success" role="alert">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="alert alert-danger" role="alert">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form method="POST" action="{{ route('authorize.payment') }}" id="user_register"
                            autocomplete="off" class="form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="input-box">
                                        <!-- <span class="icon"><i class='bx bx-user'></i></span> -->
                                        <input id="first_name" type="text"
                                            class="@error('first_name') is-invalid @enderror" name="first_name"
                                            value="{{ old('first_name') }}" required autocomplete="off"
                                            placeholder="Enter First Name" maxlength="50">
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>{{ __('First Name') }}</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-box">
                                        <!-- <span class="icon"><i class='bx bx-user'></i></span> -->
                                        <input id="last_name" type="text"
                                            class="@error('last_name') is-invalid @enderror" name="last_name"
                                            value="{{ old('last_name') }}" required autocomplete="off"
                                            placeholder="Enter Last Name" maxlength="50">
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>{{ __('Last Name') }}</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-box">
                                        <!-- <span class="icon"><i class='bx bx-envelope'></i></span> -->
                                        <input id="email" type="email"
                                            class="@error('email') is-invalid @enderror" name="email"
                                            value="{{ old('email') }}" required autocomplete="off"
                                            placeholder="Enter Email Address" maxlength="100">
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>{{ __('Email Address') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="input-box">
                                        <!-- <span class="icon"><i class='bx bx-phone'></i></span> -->
                                        <input id="phone" type="text"
                                            class="@error('phone') is-invalid @enderror" name="phone"
                                            value="{{ old('phone') }}" required autocomplete="off"
                                            placeholder="Enter Phone Number" maxlength="100">
                                        @error('phone')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>{{ __('Phone Number') }}</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-box">
                                        <!-- <span class="icon"><i class='bx bx-map'></i></span> -->
                                        <input id="address" type="text" name="billing_address"
                                            value="{{ old('billing_address') }}" required>
                                        <label>Card Billing Address</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-box">
                                        <!-- <span class="icon"><i class='bx bx-map'></i></span> -->
                                        <input id="country" type="text" name="country"
                                            placeholder="Enter Country" value="{{ old('country') }}" required>
                                        <label>Country</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="input-box">
                                        <!-- <span class="icon"><i class='bx bx-map'></i></span> -->
                                        <input id="state" type="text" name="state"
                                            placeholder="Enter State " value="{{ old('state') }}" required>
                                        <label>State</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-box">
                                        <!-- <span class="icon"><i class='bx bx-map'></i></span> -->
                                        <input id="city" type="text" name="city" placeholder="Enter City"
                                            value="{{ old('city') }}" required>
                                        <label>City</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-box">
                                        <!-- <span class="icon"><i class='bx bx-map-pin'></i></span> -->
                                        <input id="zipcode" type="text" name="zip_code"
                                            placeholder="Enter Zip Code" value="{{ old('zip_code') }}" required>
                                        <label>Zip Code</label>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="input-box">
                                <span class="icon"><i class='bx bx-envelope'></i></span>
                                <input id="email" type="email" class="@error('email') is-invalid @enderror"
                                    name="email" value="{{ old('email') }}" required autocomplete="off"
                                    maxlength="100">
                                @error('email')
    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
@enderror
                                <label>{{ __('Email Address') }}</label>
                            </div> -->

                            <!-- <div class="input-box">
                                <span class="icon"><i class='bx bx-phone'></i></span>
                                <input id="phone" type="text" class="@error('phone') is-invalid @enderror"
                                    name="phone" value="{{ old('phone') }}" required autocomplete="off"
                                    maxlength="100">
                                @error('phone')
    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
@enderror
                                <label>{{ __('Phone Number') }}</label>
                            </div> -->

                            <!-- <div class="input-box">
                                <span class="icon"><i class='bx bx-map'></i></span>
                                <input id="address" type="text" name="billing_address"
                                    value="{{ old('billing_address') }}" required>
                                <label>Card Billing Address</label>
                            </div> -->

                            <!-- <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-map'></i></span>
                                        <input id="country" type="text" name="country"
                                            value="{{ old('country') }}" required>
                                        <label>Country</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-map'></i></span>
                                        <input id="state" type="text" name="state"
                                            value="{{ old('state') }}" required>
                                        <label>State</label>
                                    </div>
                                </div> -->
                            <!--<div class="col-lg-6">-->
                            <!--    <div class="input-box">-->
                            <!--        <input id="country" type="text" name="country" value="{{ old('country') }}" required>-->
                            <!--<div class="select">-->

                            <!--    <select id="country" name="country" required>-->
                            <!--        <option value="">Select Country</option>-->
                            <!--        <option value="{{ old('country') }}" selected>{{ old('country') }}</option>-->
                            <!--    </select>-->
                            <!--</div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!--<div class="col-lg-6">-->
                            <!--    <div class="input-box">-->
                            <!--         <input id="state" type="text" name="state" value="{{ old('state') }}" required>-->
                            <!--<div class="select">-->
                            <!--    <select id="state" name="state" required>-->
                            <!--        <option value="">Select State/Region</option>-->
                            <!--        <option value="{{ old('state') }}" selected>{{ old('state') }}</option>-->
                            <!--    </select>-->
                            <!--</div>-->
                            <!--    </div>-->
                            <!--</div>-->
                            <!-- </div> -->

                            <!-- <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-map'></i></span>
                                        <input id="city" type="text" name="city"
                                            value="{{ old('city') }}" required>
                                        <label>City</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-map-pin'></i></span>
                                        <input id="zipcode" type="text" name="zip_code"
                                            value="{{ old('zip_code') }}" required>
                                        <label>Zip Code</label>
                                    </div>
                                </div>
                            </div> -->

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="input-box">
                                        <div class="select">
                                            <span class="icon"><i class='bx bx-chevron-down'></i></span>
                                            <select id="plan_id" name="plan_id" required>
                                                {!! \App\Helpers\DropdownHelper::getPlanDropdown() !!}
                                            </select>
                                        </div>
                                        <input type="hidden" name="amount" id="amount"
                                            value="{{ old('amount') }}">
                                        <input type="hidden" name="type" id="type"
                                            value="{{ old('type') }}">
                                        <label>{{ __('Choose plan') }}</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bxs-credit-card'></i></span>
                                        <input id="card_number" type="number"
                                            class="@error('card_number') is-invalid @enderror" name="card_number"
                                            placeholder="Enter Card 16 digit Number" value="{{ old('card_number') }}"
                                            required autocomplete="off" maxlength="16">
                                        @error('card_number')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>{{ __('Card Number (16 digits)') }}</label>
                                    </div>
                                </div>
                                <div class="col-lg-4">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bxs-calendar'></i></span>
                                        <input id="expiration_date" type="text"
                                            class="@error('expiration_date') is-invalid @enderror"
                                            name="expiration_date" value="{{ old('expiration_date') }}" required
                                            placeholder="Enter Expiration Date (MM/YY)" autocomplete="off"
                                            maxlength="5">
                                        @error('expiration_date')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>{{ __('Expiration Date (MM/YY)') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4">
                                    <div class="input-box">
                                        <!-- <span class="icon"><i class='bx bx-shield-alt-2'></i></span> -->
                                        <input id="cvv" type="text"
                                            class="@error('cvv') is-invalid @enderror" name="cvv"
                                            placeholder="Enter CVV" value="{{ old('cvv') }}" required
                                            autocomplete="off" maxlength="4">
                                        @error('cvv')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>{{ __('CVV') }}</label>
                                    </div>
                                </div>
                            </div>

                            <!-- <div class="input-box">
                                <div class="select">
                                    <select id="plan_id" name="plan_id" required>
                                        {!! \App\Helpers\DropdownHelper::getPlanDropdown() !!}
                                    </select>
                                </div>
                                <input type="hidden" name="amount" id="amount" value="{{ old('amount') }}">
                                <input type="hidden" name="type" id="type" value="{{ old('type') }}">
                            </div> -->

                            <!-- <div class="input-box">
                                <span class="icon"><i class='bx bx-credit-card'></i></span>
                                <input id="card_number" type="number"
                                    class="@error('card_number') is-invalid @enderror" name="card_number"
                                    value="{{ old('card_number') }}" required autocomplete="off" maxlength="16">
                                @error('card_number')
    <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
@enderror
                                <label>{{ __('Card Number (16 digits)') }}</label>
                            </div> -->

                            <!-- <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-calendar'></i></span>
                                        <input id="expiration_date" type="text"
                                            class="@error('expiration_date') is-invalid @enderror"
                                            name="expiration_date" value="{{ old('expiration_date') }}" required
                                            autocomplete="off" maxlength="5">
                                        @error('expiration_date')
    <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
@enderror
                                        <label>{{ __('Expiration Date (MM/YY)') }}</label>
                                    </div>
                                </div>

                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-shield-alt-2'></i></span>
                                        <input id="cvv" type="text"
                                            class="@error('cvv') is-invalid @enderror" name="cvv"
                                            value="{{ old('cvv') }}" required autocomplete="off" maxlength="4">
                                        @error('cvv')
    <span class="invalid-feedback" role="alert">
                                                            <strong>{{ $message }}</strong>
                                                        </span>
@enderror
                                        <label>{{ __('CVV') }}</label>
                                    </div>
                                </div>
                            </div> -->


                            <div class="form-check mt-3 account_signup">
                                <input class="form-check-input" type="checkbox" name="agree_terms" id="agree_terms"
                                    required>

                                <label class="form-check-label" for="agree_terms">
                                    I have read and agree to the <a href="{{ route('terms.of.service') }}"
                                        target="_blank"> <span class="theme-color">Terms of service</span> </a>
                                </label>
                            </div>

                            <button type="submit" class="custom-btn">{{ __('Create account') }}</button>
                        </form>

                        <div class="account_signup accountSignup">
                            <a href="{{ route('login.form') }}">Already have an account? <span
                                    class="theme-color">Login</span> </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCJmm5iuEx2gVM3qj9a1zAWI_Y_C4Judnc&libraries=places&callback=initBillingAddressAutocomplete">
    </script>
    <script>
        // Google Maps Autocomplete for Billing Address
        function initBillingAddressAutocomplete() {
            const billingMapping = {
                inputId: 'address', // Billing Address Input Field
                fields: {
                    country: 'country',
                    state: 'state',
                    city: 'city',
                    zip_code: 'zipcode'
                }
            };

            const input = document.getElementById(billingMapping.inputId);
            if (!input) {
                console.error('Billing address input field not found!');
                return;
            }

            const autocomplete = new google.maps.places.Autocomplete(input, {
                types: ['geocode'], // Focus on geographic data
                fields: ['address_components', 'geometry']
            });

            autocomplete.addListener('place_changed', () => {
                const place = autocomplete.getPlace();

                if (place.address_components) {
                    const addressComponents = parseBillingAddressComponents(place.address_components);
                    populateBillingFields(billingMapping.fields, addressComponents);
                }
            });
        }

        // Parse Address Components for Billing Address
        function parseBillingAddressComponents(components) {
            const addressComponents = {
                country: '',
                state: '',
                city: '',
                zip_code: ''
            };

            components.forEach(component => {
                const types = component.types;

                if (types.includes('country')) {
                    addressComponents.country = component.long_name; // Full Country Name
                } else if (types.includes('administrative_area_level_1')) {
                    addressComponents.state = component.long_name; // State/Province
                } else if (types.includes('locality')) {
                    addressComponents.city = component.long_name; // City
                } else if (types.includes('postal_code')) {
                    addressComponents.zip_code = component.long_name; // Zip Code
                }
            });

            return addressComponents;
        }

        // Populate Fields with Extracted Address Data
        function populateBillingFields(fieldMapping, addressComponents) {
            for (const [key, elementId] of Object.entries(fieldMapping)) {
                const field = document.getElementById(elementId);
                if (addressComponents[key] && field) {
                    field.value = addressComponents[key];
                }
            }
        }

        // Initialize Autocomplete on Window Load
        window.onload = initBillingAddressAutocomplete;

        $(document).ready(function() {

            $('#user_register').validate({
                rules: {
                    first_name: {
                        required: true,
                        minlength: 2
                    },
                    last_name: {
                        required: true,
                        minlength: 2
                    },
                    email: {
                        required: true,
                        email: true
                    },
                    agree_terms: {
                        required: true
                    },
                },
                messages: {
                    first_name: {
                        required: "Please enter your first name",
                        minlength: "First name must be at least 2 characters"
                    },
                    last_name: {
                        required: "Please enter your last name",
                        minlength: "Last name must be at least 2 characters"
                    },
                    email: {
                        required: "Please enter your email",
                        email: "Please enter a valid email address"
                    },
                    agree_terms: {
                        required: "You must agree to the terms of service"
                    }
                },
                errorPlacement: function(error, element) {
                    // Append the error message after the closest `.input-box` container
                    if (element.closest('.input-box').length) {
                        error.insertAfter(element.closest('.input-box'));
                    } else if (element.closest('.account_signup').length) {
                        error.insertAfter(element.closest('.account_signup'));
                    } else {
                        // Default placement if `.input-box` is not found
                        error.insertAfter(element);
                    }
                }
            });


        });
    </script>

    <script>
        const countriesData = {


            "AE": {
                "name": "United Arab Emirates",
                "states": [
                    "Abu Dhabi", "Ajman", "Dubai", "Fujairah", "Ras Al Khaimah", "Sharjah", "Umm Al-Quwain"
                ]
            },
            "AU": {
                "name": "Australia",
                "states": [
                    "New South Wales", "Queensland", "South Australia", "Tasmania", "Victoria",
                    "Western Australia"
                ]
            },
            "CA": {
                "name": "Canada",
                "states": [
                    "Alberta", "British Columbia", "Manitoba", "New Brunswick", "Newfoundland and Labrador",
                    "Nova Scotia", "Ontario", "Prince Edward Island", "Quebec", "Saskatchewan"
                ]
            },
            "DE": {
                "name": "Germany",
                "states": [
                    "Baden-Württemberg", "Bavaria", "Berlin", "Brandenburg", "Bremen", "Hamburg", "Hesse",
                    "Lower Saxony", "Mecklenburg-Vorpommern", "North Rhine-Westphalia",
                    "Rhineland-Palatinate",
                    "Saarland", "Saxony", "Saxony-Anhalt", "Schleswig-Holstein", "Thuringia"
                ]
            },
            "FR": {
                "name": "France",
                "states": [
                    "Île-de-France", "Provence-Alpes-Côte d'Azur", "Auvergne-Rhône-Alpes",
                    "Nouvelle-Aquitaine",
                    "Occitanie", "Hauts-de-France", "Bretagne", "Normandie", "Grand Est",
                    "Centre-Val de Loire",
                    "Pays de la Loire", "Bourgogne-Franche-Comté", "Corse"
                ]
            },
            "GB": {
                "name": "United Kingdom",
                "states": [
                    "England", "Scotland", "Wales", "Northern Ireland"
                ]
            },
            "IN": {
                "name": "India",
                "states": [
                    "Andhra Pradesh", "Arunachal Pradesh", "Assam", "Bihar", "Chhattisgarh", "Goa",
                    "Gujarat",
                    "Haryana", "Himachal Pradesh", "Jharkhand", "Karnataka", "Kerala", "Madhya Pradesh",
                    "Maharashtra", "Manipur", "Meghalaya", "Mizoram", "Nagaland", "Odisha", "Punjab",
                    "Rajasthan", "Sikkim", "Tamil Nadu", "Telangana", "Tripura", "Uttar Pradesh",
                    "Uttarakhand",
                    "West Bengal"
                ]
            },
            "PK": {
                "name": "Pakistan",
                "states": [
                    "Balochistan", "Khyber Pakhtunkhwa", "Punjab", "Sindh", "Azad Jammu and Kashmir",
                    "Gilgit-Baltistan", "Islamabad Capital Territory"
                ]
            },
            "SA": {
                "name": "Saudi Arabia",
                "states": [
                    "Al Bahah", "Al Jawf", "Al Madinah", "Al-Qassim", "Asir", "Eastern Province", "Ha'il",
                    "Mecca", "Najran", "Northern Borders", "Riyadh", "Tabuk", "Jizan", "Saudi Capital"
                ]
            },
            "TR": {
                "name": "Turkey",
                "states": [
                    "Adana", "Adiyaman", "Afyonkarahisar", "Agri", "Aksaray", "Amasya", "Ankara", "Antalya",
                    "Ardahan", "Artvin", "Aydin", "Balikesir", "Bilecik", "Bingol", "Bitlis", "Bolu",
                    "Burdur", "Bursa",
                    "Canakkale", "Cankiri", "Corum", "Denizli", "Diyarbakir", "Edirne", "Elazig",
                    "Erzincan", "Erzurum",
                    "Eskisehir", "Gaziantep", "Giresun", "Gumushane", "Hakkari", "Hatay", "Igdir",
                    "Isparta", "Istanbul",
                    "Izmir", "Kahramanmaras", "Karabuk", "Karaman", "Kastamonu", "Kayseri", "Kirikkale",
                    "Kirklareli",
                    "Kirsehir", "Kocaeli", "Konya", "Kuyucak", "Malatya", "Manisa", "Mardin", "Mugla",
                    "Mus", "Nevsehir",
                    "Nigde", "Ordu", "Osmaniye", "Rize", "Sakarya", "Samsun", "Sanliurfa", "Siirt", "Sinop",
                    "Sirnak",
                    "Sivas", "Tekirdag", "Tokat", "Trabzon", "Tunceli", "Usak", "Van", "Yalova", "Yozgat"
                ]
            },
            "US": {
                "name": "United States",
                "states": [
                    "Alabama", "Alaska", "Arizona", "Arkansas", "California", "Colorado", "Connecticut",
                    "Delaware",
                    "Florida", "Georgia", "Hawaii", "Idaho", "Illinois", "Indiana", "Iowa", "Kansas",
                    "Kentucky",
                    "Louisiana", "Maine", "Maryland", "Massachusetts", "Michigan", "Minnesota",
                    "Mississippi",
                    "Missouri", "Montana", "Nebraska", "Nevada", "New Hampshire", "New Jersey",
                    "New Mexico",
                    "New York", "North Carolina", "North Dakota", "Ohio", "Oklahoma", "Oregon",
                    "Pennsylvania",
                    "Rhode Island", "South Carolina", "South Dakota", "Tennessee", "Texas", "Utah",
                    "Vermont",
                    "Virginia", "Washington", "West Virginia", "Wisconsin", "Wyoming"
                ]
            },




        };

        function populateCountryState() {
            const countrySelect = document.getElementById("country");
            const stateSelect = document.getElementById("state");

            Object.keys(countriesData).forEach(function(countryCode) {
                let option = document.createElement("option");
                option.value = countryCode;
                option.textContent = countriesData[countryCode].name;
                countrySelect.appendChild(option);
            });

            countrySelect.addEventListener("change", function() {
                const selectedCountry = countrySelect.value;
                const states = countriesData[selectedCountry]?.states || [];
                stateSelect.innerHTML = "<option value=''>Select State/Region</option>";
                states.forEach(function(state) {
                    let option = document.createElement("option");
                    option.value = state;
                    option.textContent = state;
                    stateSelect.appendChild(option);
                });
            });
        }

        window.onload = populateCountryState;
    </script>



    <script>
        document.getElementById('expiration_date').addEventListener('input', function(e) {
            let value = e.target.value;
            value = value.replace(/[^0-9/]/g, '');
            if (value.length === 2 && e.inputType !== 'deleteContentBackward' && !value.includes('/')) {
                value = value + '/';
            }
            if (value.length > 5) {
                value = value.slice(0, 5);
            }

            e.target.value = value;
        });
    </script>
    <script>
        $(document).ready(function() {
            $('#plan_id').on('change', function() {
                let selectedText = $(this).find('option:selected').text();
                let [amount, type] = selectedText.split(' /').map(part => part.trim());
                $('#amount').val(amount);
                $('#type').val(type);
            });
        });
    </script>
    {{-- <script>
        $(document).ready(function() {
            $('#togglePassword').on('click', function() {
                const passwordField = $('#password');
                const icon = $(this);

                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    icon.removeClass('bx-show').addClass('bx-hide');
                } else {
                    passwordField.attr('type', 'password');
                    icon.removeClass('bx-hide').addClass('bx-show');
                }
            });

            $('#togglePasswordConfirmation').on('click', function() {
                const passwordConfirmationField = $('#password_confirmation');
                const icon = $(this);

                if (passwordConfirmationField.attr('type') === 'password') {
                    passwordConfirmationField.attr('type', 'text');
                    icon.removeClass('bx-show').addClass('bx-hide');
                } else {
                    passwordConfirmationField.attr('type', 'password');
                    icon.removeClass('bx-hide').addClass('bx-show');
                }
            });
        });
    </script> --}}


</body>

</html>
