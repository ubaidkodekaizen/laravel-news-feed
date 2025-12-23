<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password | MuslimLynk</title>
    <link rel="icon" href="{{asset('assets/images/logo_bg.png')}}" type="image/x-icon">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.3/css/bootstrap.min.css" />
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="{{ asset('assets/css/auth-style.css') }}">

</head>

<body>
    <div class="form_container">
        <div class="form_flex">
            <div class="row">
                <div class="col-lg-6 mobile_hide">
                    <div class="img_side_width">
                        <img src="{{ asset('assets/images/logo.png') }}" alt="" class="img-fluid">
                    </div>
                    {{-- <img src="https://img.freepik.com/free-vector/mobile-login-concept-illustration_114360-83.jpg" alt="" class="img-fluid"> --}}
                </div>
                <div class="col-lg-6">
                    <div class="form-section">
                        <h2>Reset Password</h2>
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
                        <form action="{{ route('password.update') }}" method="POST" id="reset_password">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <div class="input-box">
                                <span class="icon"><i class='bx bx-envelope'></i></span>
                                <input type="email" id="email" name="email" autocomplete="off" required>
                                <label>Email</label>
                            </div>
                            <div class="input-box">
                                <span class="icon"><i class='bx bx-show' id="togglePassword"></i></span>
                                <input type="password" id="password" name="password" autocomplete="off" required>
                                <label>New Password</label>
                            </div>
                            <div class="input-box">
                                <span class="icon"><i class='bx bx-show' id="toggleConfirmPassword"></i></span>
                                <input type="password" id="password_confirmation" name="password_confirmation" autocomplete="off" required>
                                <label>Confirm Password</label>
                            </div>

                            <button type="submit" class="custom-btn btn-14 mt-4">Reset Password</button>
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
            $('#reset_password').validate({
                rules: {
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
                errorPlacement: function (error, element) {
                    // Check if the input field is inside a `.input-box` container
                    if (element.closest('.input-box').length) {
                        element.closest('.input-box').after(error); // Place error after `.input-box`
                    } else {
                        error.insertAfter(element); // Default placement for inputs outside `.input-box`
                    }
                }
            });
        });
    </script>

<script>
     $(document).ready(function () {
            // Toggle the visibility of the password field
            $('#togglePassword').on('click', function () {
                const passwordField = $('#password');
                const icon = $(this);

                // Check the current type of the input field
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text'); // Change to text to show password
                    icon.removeClass('bx-show').addClass('bx-hide'); // Change icon to 'eye open'
                } else {
                    passwordField.attr('type', 'password'); // Change back to password
                    icon.removeClass('bx-hide').addClass('bx-show'); // Change icon back to 'eye closed'
                }
            });
            $('#toggleConfirmPassword').on('click', function () {
                const passwordField = $('#password_confirmation');
                const icon = $(this);

                // Check the current type of the input field
                if (passwordField.attr('type') === 'password') {
                    passwordField.attr('type', 'text'); // Change to text to show password
                    icon.removeClass('bx-show').addClass('bx-hide'); // Change icon to 'eye open'
                } else {
                    passwordField.attr('type', 'password'); // Change back to password
                    icon.removeClass('bx-hide').addClass('bx-show'); // Change icon back to 'eye closed'
                }
            });
        });
</script>
</body>

</html>


