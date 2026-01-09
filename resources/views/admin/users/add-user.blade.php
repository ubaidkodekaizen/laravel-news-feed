@extends('admin.layouts.main')
<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');
</style>
<style>

    body{
        background: #fafbff !important;
    }

    .card {
        border: none !important;
        border-radius: 0 !important;
        overflow: hidden;
    }

    .card-body{
        background: #fafbff !important;
        padding: 30px 0px !important;
    }

    .card-header:first-child {
        border: 0;
        background: #fafbff !important;
        padding: 30px 0px !important;
    }

    h4.card-title {
        border-radius: 0;
        color: #333;
        margin: 0;
        font-size: 28px;
        font-family: "Inter";
        font-weight: 500;
    }

    label.form-label {
        font-family: "inter";
        font-weight: 400;
        font-size: 18px;
    }

    label.form-check-label {
        font-family: "Inter", sans-serif;
        font-weight: 400;
        font-size: 16px;
        line-height: 160%;
    }

    

    /* Chrome, Edge, Safari, Brave */
    input:-webkit-autofill,
    input:-webkit-autofill:hover,
    input:-webkit-autofill:focus,
    textarea:-webkit-autofill,
    select:-webkit-autofill {
        -webkit-box-shadow: 0 0 0px 1000px #ffffff inset !important;
        box-shadow: 0 0 0px 1000px #ffffff inset !important;
        -webkit-text-fill-color: #333 !important;
        transition: background-color 5000s ease-in-out 0s;
    }

    /* Firefox */
    input:-moz-autofill,
    textarea:-moz-autofill,
    select:-moz-autofill {
        box-shadow: 0 0 0px 1000px #ffffff inset !important;
        -moz-text-fill-color: #333 !important;
    }

    button.btn.btn-primary {
        border-radius: 9.77px;
        padding: 15px 56px;
        font-family: "Poppins", sans-serif;
        font-weight: 500;
        font-size: 22px;
        line-height: 100%;
        letter-spacing: 0px;
        text-align: center;
        margin: 0 0 0 0;
    }

    .card .card-header .card-title a img {
        width: 14px !important;
        margin-top: -6px;
        margin-right: 16px;
        border: none !important;
    }
</style>
@section('content')
    <main class="main-content">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">
                                <a href="{{ url('/admin/users') }}"><img src=" {{ asset('assets/images/dashboard/dashboardBackChevron.svg') }}" alt=""></a>
                                    Add User
                            </h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.create.user') }}" id="add-user" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label for="amcob_member" class="form-label w-100">AMCOB Member:</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="amcob_member"
                                                id="mcob-member-yes" value="Yes">
                                            <label class="form-check-label" for="mcob-member-yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="amcob_member"
                                                id="mcob-member-no" value="No">
                                            <label class="form-check-label" for="mcob-member-no">No</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="duration" class="form-label">Membership Duration:</label>
                                        <select name="duration" id="duration" class="form-select">
                                            <option value="">Select Membership Duration</option>
                                            <option value="30">30 Days</option>
                                            <option value="60">60 Days</option>
                                            <option value="90">90 Days</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="first_name" class="form-label">First Name:</label>
                                        <input type="text" class="form-control" name="first_name" id="first_name"
                                            required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="last_name" class="form-label">Last Name:</label>
                                        <input type="text" class="form-control" name="last_name" id="last_name" required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="email" class="form-label">Email:</label>
                                        <input type="email" class="form-control" name="email" id="email" required>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="password" class="form-label">Password:</label>
                                        <div class="password-field">
                                            <input type="password" class="form-control" name="password" id="password"
                                                required>
                                            <span class="icon">
                                                <i class='bx bx-show' id="togglePassword"></i>
                                            </span>
                                        </div>
                                    </div>
                                    {{-- <div class="col-lg-6 mb-3">
                                    <label for="confirm_password" class="form-label">Confirm Password:</label>
                                    <div class="password-field">
                                        <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                                        <span class="icon">
                                            <i class='bx bx-show' id="togglePasswordConfirmation"></i>
                                        </span>
                                    </div>
                                </div> --}}
                                    <div class="col-lg-12 mt-4">
                                        <button class="btn btn-primary">Submit</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('#add-user').validate({
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
                        required: "Please provide a password"
                    }
                },
            });
        });
    </script>
@endsection
