<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | Muslim Linker</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <style>
        *,html{
            padding: 0;
            margin: 0;
        }
        body{
            background: url('./assets/images/login_bg.jpg');
            background-position: center;
            background-size: cover;
            color: #fff;
        }
        .login{
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100svh;
            margin: 50px 0px;
        }
        .login input {
            background: transparent;
            padding: 10px 20px;
            color: #fff;
        }
        .login_card{
            background: rgb(243 104 31 / 4%);
            backdrop-filter: blur(100px);
            border-radius: 15px;
            padding: 40px;
            box-shadow: 4px 4px 4px 0 rgb(243 104 31);
            /* max-width: 500px; */
            width: 100%;
            color: #fff;
            text-align: center;
            border: 1px solid rgb(243 104 31);
        }
        .login_heading {
            border-bottom: 2px solid #e96420;
            padding-bottom: 10px;
            width: fit-content;
            margin: 0px auto 30px auto;
        }
        .form-control:focus {
            background: transparent;
            color: #fff;
            border-color: rgb(243 104 31);
            box-shadow: 0 0 0 .25rem rgb(224, 114, 66)
        }

        .form-floating>.form-control:focus~label {
        color: #fff;
        }
        .form-floating>.form-control:focus~label::after{
            background: transparent!important;
        }
        .custom-btn {
            width: 130px;
            height: 40px;
            color: #fff;
            border-radius: 5px;
            padding: 10px 25px;
            font-family: 'Lato', sans-serif;
            font-weight: 500;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: inline-block;
            box-shadow:inset 2px 2px 2px 0px rgba(255,255,255,.5),
            7px 7px 20px 0px rgba(0,0,0,.1),
            4px 4px 5px 0px rgba(0,0,0,.1);
            outline: none;
        }
        .btn-14 {
            background: rgb(255,151,0);
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
            background-image: linear-gradient(315deg, #eaf818 0%, #f6fc9c 74%);
            box-shadow:inset 2px 2px 2px 0px rgba(255,255,255,.5);
            transition: all 0.3s ease;
        }
        .btn-14:hover {
            color: #000;
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
            color: #ff9700;
        }
        .account_signup {
            margin-top: 10px;
        }

        .account_signup a {
            color: #fff;
            text-decoration: none;
        }

        .theme-color {
            color: #ff9700;
        }
        @media only screen and (max-width: 600px) {
            .mobile_hide {
                display: none;
            }
        }
        .error {
            width: 100%;
            text-align: start;
            display: block;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <section class="login">
        <div class="container">
            <div class="login_card">
                <div class="row align-items-center">
                    <div class="col-lg-12">
                        <h1 class="login_heading">
                            Signup
                        </h1>
                        <div class="login_div">
                            <form action="{{ route('register') }}" method="POST" id="user_register">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="first_name" placeholder="First Name" class="form-control" required>
                                            <label for="first_name">First Name</label>
                                         </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="text" name="last_name" placeholder="Last Name" class="form-control" required>
                                            <label for="last_name">Last Name</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="email" name="email" placeholder="Email" class="form-control" required>
                                            <label for="email">Email</label>
                                         </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="password" name="password" placeholder="Password" class="form-control" required>
                                            <label for="password">Password</label>
                                         </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="form-floating mb-3">
                                            <input type="password" name="password_confirmation" placeholder="Confirm Password" class="form-control" required>
                                            <label for="password_confirmation">Confirm Password</label>
                                         </div>
                                    </div>
                                    <div class="col-12">
                                        <button type="submit" class="custom-btn btn-14">Register</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </section>
    
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.5.1/gsap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/js/bootstrap.min.js"></script>
    <script src="https://ajax.aspnetcdn.com/ajax/jquery.validate/1.14.0/jquery.validate.js"></script>
    <script>
        const input = document.getElementById('animated-input');
        input.addEventListener('focus', () => {
           gsap.to(input, { borderColor: '#007bff', duration: 0.5 });
        });
        input.addEventListener('blur', () => {
           gsap.to(input, { borderColor: '#ccc', duration: 0.5 });
        });
     </script>
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
                errorElement: "span",
                errorPlacement: function (error, element) {
                    error.addClass("text-danger");
                    element.closest(".form-floating").append(error);
                }
            });
        });
    </script>
    
</body>
</html>

