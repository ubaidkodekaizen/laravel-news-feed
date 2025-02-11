@extends('admin.layouts.main')
@section('content')
    <main class="main-content">

        <div class="container">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">Add User</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('admin.create.user') }}" id="add-user" method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 mb-3">
                                        <label for="first_name" class="form-label w-100">AMCOB Member:</label>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="amcob_member"
                                                id="mcob-member-yes" value="MCOB Member Yes">
                                            <label class="form-check-label" for="mcob-member-yes">Yes</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="amcob_member"
                                                id="mcob-member-no" value="MCOB Member No">
                                            <label class="form-check-label" for="mcob-member-no">No</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 mb-3">
                                        <label for="membership_duration" class="form-label">Membership Duration:</label>
                                        <select name="membership_duration" id="membership_duration" class="form-select">
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
