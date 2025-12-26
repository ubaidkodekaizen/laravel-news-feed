@extends('layouts.main')
@section('content')

    <style>
        .feedbackSec {
            padding: 100px 20px;
        }

        #feedback_form label {
            margin-bottom: .5rem;
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 18px;
        }

        #feedback_form .form-control,
        #feedback_form .form-select {
            font-family: "Inter", sans-serif;
            font-weight: 400 !important;
            font-size: 18px !important;
            padding: 14.5px .75rem !important;
            background: #FFFFFF;
            border-radius: 9.77px !important;
            border: 2px solid #E9EBF0;
        }

        #feedback_form .submitBtn {
            font-family: "poppins";
            font-weight: 500;
            font-size: 18px;
            padding: 15px 66px;
            border-radius: 9.77px;
        }

        .feedbackSec .card {
            border: 2px solid #E9EBF0;
            border-radius: 10.66px;
        }

        .feedbackSec .card-body {
            background: #27357205;
            padding: 26px 33px 33px 33px;
        }

        .feedbackSec h1 {
            font-family: "Inter", sans-serif;
            font-weight: 600;
            font-size: 24px;
            line-height: 100%;
            color: #273572;
        }

        .feedbackSec p {
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 16px;
            line-height: 140%;
            color: #333333;
            margin: 5px 0 20px 0;
            width: 100%;
        }
    </style>

    <link rel="stylesheet" href="{{ asset('assets/css/footer.css') }}">


    <div class="container py-5 feedbackSec">
        <div class="row justify-content-center">
            <div class="col-lg-8">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>There were some problems with your input:</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $err)
                                <li>{{ $err }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card shadow-sm">
                    <div class="card-body">
                        <h1 class="h4">Support</h1>
                        <p>We’re here to help — share your question or feedback and our team will get back to you shortly.
                        </p>
                        <form method="POST" action="{{ route('support.submit') }}" id="feedback_form" novalidate>
                            @csrf

                            <div class="mb-3">
                                <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                <input id="name" name="name" type="text"
                                    class="form-control @error('name') is-invalid @enderror" value="{{ old('name') }}"
                                    required maxlength="255">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                <input id="email" name="email" type="email"
                                    class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}"
                                    required maxlength="255">
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="message" class="form-label">Message <small class="text-muted">(max 1000
                                        chars)</small> <span class="text-danger">*</span></label>
                                <textarea id="message" name="message" rows="7" class="form-control @error('message') is-invalid @enderror"
                                    required maxlength="1000">{{ old('message') }}</textarea>
                                <div class="d-flex justify-content-between mt-1">
                                    <div>
                                        @error('message')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    <div>
                                        <small id="charCount" class="text-muted">0 / 1000</small>
                                    </div>
                                </div>
                            </div>

                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary submitBtn">Submit</button>

                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @include('layouts.home-footer')
@endsection


@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const textarea = document.getElementById('message');
            const charCount = document.getElementById('charCount');
            const max = 1000;

            function updateCount() {
                const len = textarea.value.length;
                charCount.textContent = len + ' / ' + max;
                if (len > max) {
                    textarea.value = textarea.value.substring(0, max);
                    charCount.textContent = max + ' / ' + max;
                }
            }

            // init
            updateCount();

            textarea.addEventListener('input', updateCount);

            // reset handler to update counter
            document.getElementById('resetBtn').addEventListener('click', function() {
                setTimeout(updateCount, 0);
            });
        });
    </script>
@endsection
