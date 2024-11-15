<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up | Muslim Link</title>
    <link rel="icon" href="{{asset('assets/images/logo_bg.png')}}" type="image/x-icon">
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

        .form_container .row:first-child > .col-lg-6 {
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
                        <h2 class="heading">Sign In</h2>
                        <p class="subheading">Sign In to continue to your application. </p>
                        <form action="{{ route('register') }}" method="POST" id="user_register" autocomplete="off" class="form">
                            @csrf
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-user'></i></span>
                                        <input type="text" name="first_name" id="first_name" autocomplete="off" required>
                                        <label>First Name</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-user'></i></span>
                                        <input type="text" id="last_name" name="last_name" autocomplete="off" required>
                                        <label>Last Name</label>
                                    </div>
                                </div>
                            </div>
                            <div class="input-box">
                                <span class="icon"><i class='bx bx-envelope'></i></span>
                                <input type="email" id="email" name="email" autocomplete="off" required>
                                <label>Email</label>
                            </div>
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-lock-alt'></i></span>
                                        <input type="password" id="password" name="password" autocomplete="off" required>
                                        <label>Password</label>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="input-box">
                                        <span class="icon"><i class='bx bx-lock-alt'></i></span>
                                        <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="off" required>
                                        <label>Confirm Password</label>
                                    </div>
                                </div>
                            </div>
                            
                           
                            <button type="submit" class="custom-btn btn-14">Register</button>
                            <div class="account_signup">
                                <a href="{{route('login.form')}}">Already have an account? <span class="theme-color">Sign
                                        In</span> </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
    <script>
        $(document).ready(function () {
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
                        equalTo: '[name="password"]' // Password confirmation must match the password field
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
                // errorElement: "span",
                // errorPlacement: function (error, element) {
                //     error.addClass("text-danger");
                //     element.closest(".form-floating").append(error);
                // }
            });
        });
    </script>
</body>

</html>
