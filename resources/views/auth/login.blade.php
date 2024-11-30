<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In | Muslim Link</title>
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
                        <h2 class="heading">Sign In</h2>
                        <p class="subheading">Sign In to continue. </p>
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
                        <form action="{{route('login')}}" class="form" method="POST" autocomplete="off">
                            @csrf
                            <div class="input-box">
                                <span class="icon"><i class='bx bx-envelope'></i></span>
                                <input type="email" id="email" name="email" autocomplete="off" required>
                                <label>Email</label>
                            </div>
                            <div class="input-box">
                                <span class="icon">
                                    <i class='bx bx-show' id="togglePassword"></i>
                                </span>
                                <input type="password" id="password" name="password" autocomplete="off" required>
                                <label>Password</label>
                            </div>
                            
                            <div class="account_signup text-end">
                                <a href="{{route('password.request')}}"> <span class="theme-color">forget password?</span> </a>
                            </div>
                            <button type="submit" class="custom-btn btn-14">Login</button>
                            <div class="account_signup">
                                <a href="{{route('register.form')}}">Don't have an account? <span class="theme-color">Sign
                                        Up</span> </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

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
        });
    </script>
</body>

</html>
