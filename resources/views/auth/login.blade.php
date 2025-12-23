<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | MuslimLynk</title>
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
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-style: normal;
        }

        .loginInner {
            flex-direction: row-reverse;
            gap: 0;
            --bs-gutter-x: 0;
            height: 100vh;
        }

        div#innerImage {
            display: flex;
            width: fit-content;
            height: fit-content;
            align-self: center;
            margin: 60px 0;
        }

        div#innerImage img {
            max-width: 140px;
        }

        h2 {
            font-size: 58.7px;
            font-weight: 500;
        }

        p {
            color: #4F4F4F;
            font-size: 29.35px;
            font-weight: 400;
            margin-top: 10px;
            font-family: "Inter", sans-serif !important;
            font-optical-sizing: auto !important;
            font-style: normal !important;
        }

        h2,
        p {
            text-align: center;
        }

        h2,
        .input-box .icon,
        input:-webkit-autofill,
        .input-box label:last-child {
            color: #000;
            -webkit-text-fill-color: #000 !important;
        }

        .form-section {
            background: #fff;
            color: #000;
        }

        input:-webkit-autofill,
        input:-webkit-autofill:focus,
        input:-webkit-autofill:hover,
        input:-webkit-autofill:active {
            -webkit-box-shadow: 0 0 0px 0 #ffffff inset !important;
            box-shadow: 0px 6px 0px 30px #FAFAFA inset !important;
            color: #fff !important;
        }

        .input-box {
            margin: 60px 0px 0px 0px;
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

        ::placeholder {
            color: #898F9B;
            font-size: 18.37px;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }

        form.form {
            max-width: 684.18px;
            width: 100%;
            margin: auto;
        }

        .account_signup {
            margin-top: 10px;
            display: flex;
            justify-content: space-between;
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

        .custom-btn {
            width: 100%;
            height: 66px;
            font-size: 18.37px;
            font-family: "Poppins", sans-serif;
            font-weight: 600;
            font-style: normal;
            padding: 19px 16px !important;
            margin-top: 20px;
            color: #fff;
            border-radius: 9.77px;
            background: #273572;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: inline-block;
            box-shadow: none !important;
            outline: none !important;
        }

        label.remember {
            color: #000000;
            font-size: 18.37px;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
        }

        .accountSignup {
            justify-content: center !important;
        }

        .accountSignup a {
            color: #898F9B !important;
        }

        .remember input[type="checkbox"] {
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

        /* Checked state */
        .remember input[type="checkbox"]:checked {
            background-color: #273572;
            /* fill color */
            border-color: #273572;
        }

        /* Add checkmark */
        .remember input[type="checkbox"]:checked::after {
            content: "✔";
            color: white;
            font-size: 12px;
            position: absolute;
            top: -2px;
            left: 2px;
        }

        #togglePassword {
            display: inline-block;
            position: relative;
            top: 5px;
            right: 10px;
            font-size: 18.37px;
            font-family: "Inter", sans-serif;
            font-optical-sizing: auto;
            font-weight: 400;
            font-style: normal;
            cursor: pointer;
            user-select: none;
        }

.signInImage{
    width: 100%;
    height: 100%;
    object-fit: cover;
    object-position: center;
}

        @media(max-width: 768px) {
            h2 {
                font-size: 30px;
            }

            p {
                font-size: 18px;
            }

            form.form {
                margin: 20px auto;
            }
        }
    </style>

</head>

<body>

    <div class="row loginInner">
        <div class="col-lg-5 mobile_hide">

            <img src="{{ asset('assets/images/1dbb64827f461da7b2ac20501ad9781821e1d278.jpg') }}" alt=""
                class="signInImage img-fluid">

        </div>
        <div class="col-lg-7">
            <div class="form-section">
                <div id="innerImage" class="img_side_width">
                    <a href="{{ route('home') }}">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid">
                    </a>
                </div>
                <h2 class="heading">Login to your account</h2>
                <p class="subheading">Welcome back! enter your details</p>
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('login') }}" class="form" method="POST" autocomplete="off">
                    @csrf
                    <div class="input-box">
                        <!-- <span class="icon"><i class='bx bx-envelope'></i></span> -->
                        <input type="email" id="email" name="email" autocomplete="off" placeholder="Enter Email"
                            required>
                        <label>Email Address</label>
                    </div>
                    <div class="input-box">
                        <span class="icon">
                            <!-- <i class='bx bx-show' id="togglePassword"></i> -->
                            <span id="togglePassword">Show</span>
                        </span>
                        <input type="password" id="password" name="password" autocomplete="off"
                            placeholder="Enter Password" required>
                        <label>Password</label>
                    </div>

                    <div class="account_signup text-end">
                        <label class="remember">
                            <input type="checkbox" id="rememberMe">
                            Remember Me
                        </label>
                        <a href="{{ route('password.request') }}"> <span class="theme-color">forget password?</span>
                        </a>
                    </div>
                    <button type="submit" class="custom-btn">Login</button>
                    <div class="account_signup accountSignup">
                        <a href="{{ route('register.form') }}">Don't have an account? <span class="theme-color">Create
                                account</span> </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- </div>
    </div> -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    <script>
        // $(document).ready(function () {
        //     // Toggle the visibility of the password field
        //     $('#togglePassword').on('click', function () {
        //         const passwordField = $('#password');

        //         // Check the current type of the input field
        //         if (passwordField.attr('type') === 'password') {
        //             passwordField.attr('type', 'text'); // Change to text to show password
        //         } else {
        //             passwordField.attr('type', 'password'); // Change back to password
        //         }
        //     });
        // });

        $(document).ready(function() {

            $('#togglePassword').on('click', function() {
                const passwordField = $('#password');

                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text');
                    $(this).text('Hide'); // change icon to "hide"
                } else {
                    passwordField.attr('type', 'password');
                    $(this).text('Show'); // change icon to "show"
                }
            });

        });

        // remember me
        window.onload = () => {
            const savedEmail = localStorage.getItem("savedEmail");
            if (savedEmail) {
                document.getElementById("email").value = savedEmail;
                document.getElementById("rememberMe").checked = true;
            }
        };

        document.getElementById("signupForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const email = document.getElementById("email").value;
            const remember = document.getElementById("rememberMe").checked;

            if (remember) {
                localStorage.setItem("savedEmail", email);
            } else {
                localStorage.removeItem("savedEmail");
            }

            alert("Sign-up successful!");
        });
    </script>
</body>

</html>
