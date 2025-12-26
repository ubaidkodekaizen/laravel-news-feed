@extends('layouts.dashboard-layout')

@section('dashboard-content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.23.0/sweetalert2.min.css" />
    <style>
        .subscriptionBoxRow {
            display: flex;
            align-items: start;
            justify-content: center;
            gap: 20px;
        }

        .subscriptionBoxCol {
            width: 100%;
            max-width: 50%;

        }

        #subscriptionSec .subscriptionBox {
            background: #E9EBF0;
            border-radius: 10px;

            padding: 20px 15px 20px 27px;
            width: 100%;
            max-width: 100%;
        }

        #subscriptionSec .subscriptionBox.inactive {

            background-image: linear-gradient(#e9ebf0d1, #e9ebf0eb), url("{{ asset('assets/images/pricingSecComponent.png') }}");
        }

        #subscriptionSec .subscriptionBox h6 {
            font-family: "Poppins", sans-serif;
            font-weight: 700;
            font-size: 34px;
            line-height: 140%;
            letter-spacing: 0px;

            color: #333;
        }


        #subscriptionSec .subscriptionBoxCol h4 {
            font-family: "Inter", sans-serif;
            font-weight: 700;
            font-size: 30px;
            line-height: 100%;
            letter-spacing: 0px;
            color: #333;
            margin: 30px 0 20px 0px;
        }

        #subscriptionSec .subscriptionBox p {
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 18px;
            line-height: 140%;
            letter-spacing: 0px;
            color: #333;
            margin: 0 0 20px 0;
        }

        #subscriptionSec .subscriptionBox ul {
            padding-left: 0px;
        }

        #subscriptionSec .subscriptionBox ul li {
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 18px;
            line-height: 140%;
            letter-spacing: 0px;
            color: #333;
            list-style: none;
            margin: 0px 0px 5px 0;
        }

        #subscriptionSec .subscriptionBox ul li img {
            filter: invert(1) brightness(0);
            border: 1px solid #333;
            border-radius: 50%;
            padding: 5px;
        }

        #subscriptionSec .subscriptionBox ul li span {
            margin-left: 5px;
        }

        #subscriptionSec .subscriptionBox a {
            text-decoration: none;
            background: #848484;
            font-family: "Inter", sans-serif;
            font-weight: 700;
            font-size: 18px;
            line-height: 140%;
            letter-spacing: 0px;
            color: #FFFFFF;
            width: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 12px 0;
            border-radius: 10px;
            margin: 24px 0 0 0;
        }

        #subscriptionSec .subscriptionBox a:hover {
            background: #b8c035;
            color: #fff;
            transition: .3s;
        }

        #subscriptionSec .subscriptionBox.active {
            background: #273572;
        }

        #subscriptionSec .subscriptionBox.blue-active {
            /* background: #273572; */
            background-image: linear-gradient(#000000c7, #213baed1), url("{{ asset('assets/images/pricingSecComponent.png') }}");
        }

        #subscriptionSec .subscriptionBox.active h6 {
            color: #b8c035;
        }

        #subscriptionSec .subscriptionBox.blue-active h6 {
            color: #b8c035;
        }

        #subscriptionSec .subscriptionBoxCol.active h4 {
            color: #333333;
        }

        #subscriptionSec .subscriptionBox.active a {
            background: #B8C034;
            transition: .3s;
        }

        #subscriptionSec .subscriptionBox.blue-active a {
            background: #B8C034;
            color: #333;
            transition: .3s;
            cursor: default;
        }

        #subscriptionSec .subscriptionBox.blue-active a:hover {
            background: #B8C034;
            color: #FFFFFF;
        }

        #subscriptionSec .subscriptionBox.inactive a {
            background: #273572;
        }

        #subscriptionSec .subscriptionBox.active p {
            color: #fff;
        }

        #subscriptionSec .subscriptionBox.blue-active p {
            color: #fff;
        }

        #subscriptionSec .subscriptionBox.active ul li {
            color: #fff;
        }

        #subscriptionSec .subscriptionBox.blue-active ul li {
            color: #fff;
        }

        #subscriptionSec .subscriptionBox.active ul li img {
            filter: unset;
        }

        #subscriptionSec .subscriptionBox.blue-active ul li img {
            filter: unset;
            border: 1px solid #b8c035;
            border-radius: 50%;
            padding: 5px;
        }

        #subscriptionSec .subscriptionBox.active a:hover {
            background: #fff;
            color: #333;
            transition: .3s;
        }

        #subscriptionSec .subscriptionBox .renewal-date {
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 18px;
            line-height: 140%;
            letter-spacing: 0px;
            color: #fff;
            margin: 0 0 10px 0;
        }

        #subscriptionSec .subscriptionBox .renewal-date .renewal-date-label {
            font-weight: 700;
            color: #b8c035;
        }

        #subscriptionSec .subscriptionBox .platform-info {
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 18px;
            line-height: 140%;
            letter-spacing: 0px;
            color: #fff;
            margin: 0 0 20px 0;
        }

        #subscriptionSec .subscriptionBox .platform-info .platform-label {
            font-weight: 700;
            color: #b8c035;
        }



        #subscriptionSec .subscriptionSecHeading {
            border-radius: 0;
            color: #333;
            font-size: 28px;
            font-family: "Inter";
            font-weight: 500;
            margin: 0px 0px 30px 0;
        }

        .swal2-popup.swal2-modal.swal2-icon-info.swal2-show {
            max-width: 700px;
            width: 100%;
            border-radius: 10px;
        }

        h2#swal2-title {
            font-family: "Poppins", sans-serif;
            font-weight: 700;
            font-size: 34px;
            line-height: 140%;
            letter-spacing: 0px;
            color: #b8c035;
        }

        div#swal2-html-container {
            font-family: "Inter", sans-serif;
            font-weight: 400;
            font-size: 18px;
            line-height: 140%;
            letter-spacing: 0px;
            color: #333;
            margin: 0 0 0px 0;
        }

        .swal2-actions {
            width: 94%;
        }

        div:where(.swal2-icon).swal2-info {
            border-color: #878787;
            color: #878787;
        }

        button.swal2-confirm.swal2-styled {
            width: 100%;
            border-radius: 10px;
            padding: 15px 20px;
        }

        @media(max-width: 768px) {
            .subscriptionBoxRow {
                flex-direction: column;
            }

            .subscriptionBoxCol {
                width: 100%;
                max-width: 100%;
            }

            h2#swal2-title {
                font-size: 18px;
            }

            div#swal2-html-container {
                font-size: 14px;
            }
        }
    </style>

    <section id="subscriptionSec">
        <h2 class="subscriptionSecHeading">Subscriptions</h2>

        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="subscriptionBoxRow">
            <div class="subscriptionBoxCol {{ $isMonthlyActive ? 'active' : '' }}">
                <h4>Premium Monthly</h4>
                <div class="subscriptionBox {{ $isMonthlyActive ? 'blue-active' : 'inactive' }}">
                    <h6>$4.99 / month</h6>
                    @if ($isMonthlyActive && $renewalDate)
                        <div class="renewal-date">
                            <span class="renewal-date-label">Renews on:</span>
                            <span
                                class="renewal-date-value">{{ \Carbon\Carbon::parse($renewalDate)->format('F d, Y') }}</span>
                        </div>
                    @endif
                    @if ($isMonthlyActive && $platform)
                        <div class="platform-info">
                            <span class="platform-label">Platform:</span>
                            <span class="platform-value">{{ ucfirst($platform) }}</span>
                        </div>
                    @endif
                    <p>Connect with Muslim professionals, entrepreneurs, and community leaders.</p>
                    <ul>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>Unlimited connections</span>
                        </li>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>Direct messaging</span>
                        </li>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>Promote products & services</span>
                        </li>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>Advanced search filters</span>
                        </li>
                    </ul>

                    @if ($isMonthlyActive)
                        <a href="javascript:void(0);" style="pointer-events: none;">Current Active</a>
                    @elseif($isYearlyActive)
                        <a href="{{ route('user.add.subscriptions', ['plan_id' => $monthlyPlanId]) }}"
                            class="choose-plan-link" data-platform="{{ $platform ?? '' }}">Change Plan</a>
                    @else
                        <a href="{{ route('user.add.subscriptions', ['plan_id' => $monthlyPlanId]) }}"
                            class="choose-plan-link" data-platform="{{ $platform ?? '' }}">Choose Plan</a>
                    @endif
                </div>
            </div>
            <div class="subscriptionBoxCol {{ $isYearlyActive ? 'active' : '' }}">
                <h4>Premium Yearly</h4>
                <div class="subscriptionBox {{ $isYearlyActive ? 'blue-active' : 'inactive' }}">
                    <h6>$49.99 / year</h6>
                    @if ($isYearlyActive && $renewalDate)
                        <div class="renewal-date">
                            <span class="renewal-date-label">Renews on:</span>
                            <span
                                class="renewal-date-value">{{ \Carbon\Carbon::parse($renewalDate)->format('F d, Y') }}</span>
                        </div>
                    @endif
                    @if ($isYearlyActive && $platform)
                        <div class="platform-info">
                            <span class="platform-label">Platform:</span>
                            <span class="platform-value">{{ ucfirst($platform) }}</span>
                        </div>
                    @endif
                    <p>Connect with Muslim professionals, entrepreneurs, and community leaders.</p>
                    <ul>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>Unlimited connections</span>
                        </li>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>Direct messaging</span>
                        </li>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>Promote products & services</span>
                        </li>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>Advanced search filters</span>
                        </li>
                    </ul>

                    @if ($isYearlyActive)
                        <a href="javascript:void(0);" style="pointer-events: none;">Current Active</a>
                    @elseif($isMonthlyActive)
                        <a href="{{ route('user.add.subscriptions', ['plan_id' => $yearlyPlanId]) }}"
                            class="choose-plan-link" data-platform="{{ $platform ?? '' }}">Change Plan</a>
                    @else
                        <a href="{{ route('user.add.subscriptions', ['plan_id' => $yearlyPlanId]) }}"
                            class="choose-plan-link" data-platform="{{ $platform ?? '' }}">Choose Plan</a>
                    @endif
                </div>

            </div>

        </div>
    </section>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert2/11.23.0/sweetalert2.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const choosePlanLinks = document.querySelectorAll('.choose-plan-link');
            const activePlatform = '{{ $platform ?? '' }}';

            choosePlanLinks.forEach(function(link) {
                link.addEventListener('click', function(e) {
                    if (activePlatform && (activePlatform.toLowerCase() === 'apple' ||
                            activePlatform.toLowerCase() === 'google')) {
                        e.preventDefault();

                        const platformDisplay = activePlatform.toLowerCase() === 'apple' ?
                            'Apple App Store' : 'Google Play Store';
                        const platformName = activePlatform.toLowerCase() === 'apple' ? 'Apple' :
                            'Google';

                        Swal.fire({
                            icon: 'info',
                            title: 'Manage Subscription on ' + platformName,
                            html: 'Your subscription is managed through ' +
                                platformDisplay +
                                '. You cannot subscribe or change your plan here.<br><br>Please go to ' +
                                platformDisplay + ' to manage your subscription.',
                            confirmButtonText: 'OK',
                            confirmButtonColor: '#273572'
                        });
                    }
                });
            });
        });
    </script>
@endsection
