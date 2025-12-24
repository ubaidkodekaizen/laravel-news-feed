@extends('layouts.dashboard-layout')


@section('dashboard-content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.23.0/sweetalert2.min.css" />

    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css"
        rel="stylesheet" />


    <style>
        .subscription_form .form-control {
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 18px;
            padding: 14.5px .75rem;
            background-color: #F6F7FC;
            border: 2px solid #E9EBF0;
            border-radius: 9.77px;
        }

        .subscription_form label {
            margin-bottom: .5rem;
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 18px;
        }

        .subscription_form .submitBtn {
            font-family: "poppins";
            font-weight: 500;
            font-size: 18px;
            padding: 15px 66px;
            border-radius: 9.77px;
        }

        .input-box {
            display: flex;
            flex-direction: column;
        }

        span.select2-selection.select2-selection--single {
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 18px;
            padding: 14.5px .75rem;
            background-color: #F6F7FC;
            border: 2px solid #E9EBF0;
            border-radius: 9.77px;
            min-height: 60px;
        }

        .section_heading_flex {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 20px 0;
        }

        .section_heading_flex h2 {
            border-radius: 0;
            color: #333;
            margin: 0;
            font-size: 28px;
            font-family: "Inter";
            font-weight: 500;
        }

        .section_heading_flex h2 a {
            text-decoration: none;
        }

        .section_heading_flex h2 a img {
            width: 14px;
            margin-top: -6px;
            margin-right: 16px;
        }

        @media(max-width: 768px) {
            .subscription_form .submitBtn {
                width: 100%;
            }
        }
    </style>


    <form action="" method="POST" class="subscription_form">
        @csrf

        <div class="row">
            <div class="section_heading_flex">
                <h2>
                    <a href="{{ route('user.subscriptions') }}">
                        <img src="{{ asset('assets/images/dashboard/dashboardBackChevron.svg') }}" alt="">
                    </a>
                    Add Subscription
                </h2>
            </div>
            <div class="col-lg-12 mb-4">
                <div class="input-box">
                    <label>Choose plan</label>
                    <div class="select">
                        <select id="plan_id" name="plan_id" class="form-select" required>
                            {!! \App\Helpers\DropdownHelper::getPlanDropdown() !!}
                        </select>
                    </div>
                    <input type="hidden" name="amount" id="amount" value="{{ old('amount') }}">
                    <input type="hidden" name="type" id="type" value="{{ old('type') }}">

                </div>
            </div>
            <div class="col-lg-12 mb-4">
                <div class="input-box">
                    <label>Card Number (16 digits)</label>

                    <input id="card_number" type="number" class="form-control @error('card_number') is-invalid @enderror"
                        name="card_number" placeholder="Enter Card 16 digit Number" value="{{ old('card_number') }}"
                        required autocomplete="off" maxlength="16">
                    @error('card_number')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>
            </div>
            <div class="col-lg-12 mb-4">
                <div class="input-box">
                    <label>Expiration Date (MM/YY)</label>
                    <input id="expiration_date" type="text"
                        class="form-control @error('expiration_date') is-invalid @enderror" name="expiration_date"
                        value="{{ old('expiration_date') }}" required placeholder="Enter Expiration Date (MM/YY)"
                        autocomplete="off" maxlength="5">
                    @error('expiration_date')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>
            </div>

            <div class="col-lg-12 mb-4">
                <div class="input-box">
                    <label>CVV</label>
                    <input id="cvv" type="text" class="form-control @error('cvv') is-invalid @enderror"
                        name="cvv" placeholder="Enter CVV" value="{{ old('cvv') }}" required autocomplete="off"
                        maxlength="4">
                    @error('cvv')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror

                </div>
            </div>

            <div class="col-12">
                <button class="btn btn-primary submitBtn" type="submit">Submit</button>
            </div>
        </div>
    </form>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/js/intlTelInput-jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.23.0/sweetalert2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/additional-methods.min.js"></script>


    <script>
        $('#plan_id').select2({
            theme: 'bootstrap-5',
            placeholder: 'Choose Plan',
            multiple: false,
            tags: false,
            tokenSeparators: [','],
            width: '100%',
            minimumResultsForSearch: Infinity
        });
    </script>
@endsection
