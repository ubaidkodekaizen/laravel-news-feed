<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Muslim Lynk | Founders and CEO Database</title>
    <link rel="icon" href="{{ asset('assets/images/logo_bg.png') }}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <style>
        body {
            background: #b8c034;
            margin: 0;
            padding: 0;
        }

        .input-box {
            position: relative;
            width: 100%;
            height: 50px;
            border-bottom: 2px solid #fff;
            margin: 0px 0px 50px 0px;
        }

        .input-box label:last-child {
            position: absolute;
            top: 50%;
            left: 5px;
            transform: translateY(-50%);
            color: #fff;
            font-weight: 500;
            pointer-events: none;
            transition: .5s;
        }

        .input-box input:focus~label,
        .input-box input:valid~label {
            top: -5px;
        }

        .input-box input {
            width: 100%;
            height: 100%;
            background: transparent;
            border: none;
            outline: none;
            font-size: 1em;
            color: #fff;
            font-weight: 600;
            padding: 0 35px 0 5px;
        }

        .input-box .icon {
            position: absolute;
            right: 8px;
            font-size: 1.2rem;
            color: #fff;
            line-height: 57px;
        }

        .form_container {
            max-width: 1000px;
            margin: 0 auto;

        }

        .form_flex {
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100svh;
        }

        .form_container .row:first-child {
            align-items: stretch;
            /* height: 80vh; */
            border-radius: 50px;
            overflow: hidden;
        }

        .form_container .row:first-child>.col-lg-6 {
            padding: 0;
            margin: 0;
        }

        .form-section {
            background: #273572;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 20px;
            color: #fff;
            height: 100%;
            min-width: 450px;
        }

        .custom-btn {
            width: 130px;
            height: 40px;
            color: #fff;
            border-radius: 5px;
            padding: 10px 25px;
            font-family: 'Lato', sans-serif;
            font-weight: 600;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: inline-block;
            box-shadow: inset 2px 2px 2px 0px rgba(255, 255, 255, .5),
                7px 7px 20px 0px rgba(0, 0, 0, .1),
                4px 4px 5px 0px rgba(0, 0, 0, .1);
            outline: none;
        }

        .btn-14 {
            background: #b8c034;
            border: none;
            z-index: 1;
        }

        .btn-14:after {
            position: absolute;
            content: "";
            width: 100%;
            height: 0;
            top: 0;
            left: 0;
            z-index: -1;
            border-radius: 5px;
            background-color: rgb(243 104 31);
            background-image: linear-gradient(315deg, #273572 0%, #273572 74%);
            box-shadow: inset 2px 2px 2px 0px rgba(255, 255, 255, .5);
            transition: all 0.3s ease;
        }

        .btn-14:hover:after {
            top: auto;
            bottom: 0;
            height: 100%;
        }

        .btn-14:active {
            top: 2px;
        }

        .forget_pass {
            display: flex;
            justify-content: end;
            margin-bottom: 20px;
        }

        .forget_pass a {
            color: #b8c034;
        }

        .account_signup {
            margin-top: 10px;
        }

        .account_signup a {
            color: #fff;
            text-decoration: none;
        }

        .theme-color {
            color: #b8c034;
        }

        @media only screen and (max-width: 600px) {
            .mobile_hide {
                display: none;
            }
        }

        .img_side_width {
            width: 530px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #fff;
            height: 100%;
            position: relative;
            max-width: 100%;
        }

        .img_side_width img {
            max-width: 200px;
            height: auto;
        }

        .account_signup span {
            font-weight: 600;
            font-size: 18px;
        }

        .account_signup a:hover span {
            text-decoration: underline;
        }

        /* Disable default autofill styling */
        input:-webkit-autofill,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0px 0 #273572 inset !important;
            box-shadow: 0px 6px 0px 30px #273572 inset !important;
            color: #fff !important;
        }

        input:-webkit-autofill {
            -webkit-text-fill-color: #fff !important;
        }

        .error {
            width: 100%;
            text-align: start;
            display: block;
            padding-top: 5px;
            color: #b8c034;
        }

        select {
            /* Reset Select */
            appearance: none;
            outline: 10px red;
            border: 0;
            box-shadow: none;
            /* Personalize */
            flex: 1;
            padding: 0 1em;
            color: #fff;
            background-color: #2c3e50;
            background-image: none;
            cursor: pointer;
        }

        /* Remove IE arrow */
        select::-ms-expand {
            display: none;
        }

        /* Custom Select wrapper */
        .select {
            position: relative;
            display: flex;
            width: 100%;
            height: 3em;
            border-radius: .25em;
            overflow: hidden;
        }

        /* Arrow */
        .select::after {
            content: '\25BC';
            position: absolute;
            top: 0;
            right: 0;
            padding: 1em;
            background-color: #34495e;
            transition: .25s all ease;
            pointer-events: none;
        }

        /* Transition */
        .select:hover::after {
            color: #fff;
        }
    </style>
</head>

<body>
    <div class="form_container">
        <div class="form_flex">
            <div class="row">
                <div class="col-lg-6">
                    <div class="img_side_width">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid">
                    </div>
                    {{-- <img src="https://img.freepik.com/free-vector/mobile-login-concept-illustration_114360-83.jpg" alt="" class="img-fluid"> --}}
                </div>
                <div class="col-lg-6">
                    <div class="form-section w-100">
                        <h2 class="heading mb-4">Sign Up</h2>

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
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-user'></i></span>
                                        <input id="first_name" type="text"
                                            class=" @error('first_name') is-invalid @enderror" name="first_name"
                                            required autocomplete="off" maxlength="50">
                                        @error('first_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>{{ __('First Name') }}</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">

                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-user'></i></span>
                                        <input id="last_name" type="text"
                                            class="@error('last_name') is-invalid @enderror" name="last_name" required
                                            autocomplete="off" maxlength="50">
                                        @error('last_name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                        <label>{{ __('Last Name') }}</label>
                                    </div>
                                </div>
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class='bx bx-envelope'></i></span>
                                <input id="email" type="email" class="@error('email') is-invalid @enderror"
                                    name="email" required autocomplete="off" maxlength="100">
                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label>{{ __('Email Address') }}</label>
                            </div>

                            <div class="input-box">
                                {{-- <label for="plan_id">Select Plan</label> --}}
                                <div class="select">
                                    <select id="plan_id" name="plan_id" required>
                                        {!! \App\Helpers\DropdownHelper::getPlanDropdown() !!}
                                    </select>
                                </div>
                                <input type="hidden" name="amount" id="amount">
                            </div>

                            <div class="input-box">
                                <span class="icon"><i class='bx bx-credit-card'></i></span>
                                <input id="card_number" type="text"
                                    class="@error('card_number') is-invalid @enderror" name="card_number" required
                                    autocomplete="off" maxlength="16">
                                @error('card_number')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <label>{{ __('Card Number (16 digits)') }}</label>
                            </div>

                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-calendar'></i></span>
                                        <input id="expiration_date" type="text"
                                            class="@error('expiration_date') is-invalid @enderror"
                                            name="expiration_date" required autocomplete="off" maxlength="5">
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
                                            class="@error('cvv') is-invalid @enderror" name="cvv" required
                                            autocomplete="off" maxlength="3">
                                        @error('cvv')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror 
                                        <label>{{ __('CVV (123)') }}</label>
                                    </div>
                                </div>
                            </div>



                            <button type="submit" class="custom-btn btn-14">
                                {{ __('Sign Up') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>

    <script>
        document.getElementById('expiration_date').addEventListener('input', function (e) {
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
                let amount = selectedText.split(' /')[0].trim();
                $('#amount').val(amount);
            });
        });
    </script>
    <script>
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
    </script>

    <script>
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
                    password: {
                        required: true,
                        minlength: 8
                    },
                    password_confirmation: {
                        required: true,
                        minlength: 8,
                        equalTo: '[name="password"]'
                    }
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
                    password: {
                        required: "Please provide a password",
                        minlength: "Password must be at least 8 characters"
                    },
                    password_confirmation: {
                        required: "Please confirm your password",
                        minlength: "Password confirmation must be at least 8 characters",
                        equalTo: "Password confirmation does not match the password"
                    }
                },
            });
        });
    </script>
</body>

</html>
