@extends('layouts.dashboard-layout')


@section('dashboard-content')
    <style>
        .subscriptionBoxRow {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 20px;
        }

        .subscriptionBoxCol {
            width: 100%;
            max-width: 50%;
        }

        #subscriptionSec .subscriptionBox {
            background: #2735721f;
            border-radius: 10px;

            padding: 20px 15px 20px 27px;
            width: 100%;
            max-width: 100%;
        }

        #subscriptionSec .subscriptionBox h6 {
            font-family: "Poppins", sans-serif;
            font-weight: 700;
            font-size: 34px;
            line-height: 140%;
            letter-spacing: 0px;

            color: #848484;
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
            color: #848484;
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
            color: #848484;
            list-style: none;
            margin: 0px 0px 5px 0;
        }

        #subscriptionSec .subscriptionBox ul li img {
            filter: grayscale(1);
        }

        #subscriptionSec .subscriptionBox ul li span {
            margin-left: 5px;
        }

        #subscriptionSec .subscriptionBox a {
            text-decoration: none;
            background: #848484;
            font-family: "Inter", sans-serif;
            font-weight: 500;
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

        #subscriptionSec .subscriptionBox.active h6 {
            color: #b8c035;
        }

        #subscriptionSec .subscriptionBoxCol.active h4 {
            color: #333333;
        }

        #subscriptionSec .subscriptionBox.active a {
            background: #B8C034;
            transition: .3s;
        }

        #subscriptionSec .subscriptionBox.active p {
            color: #fff;
        }

        #subscriptionSec .subscriptionBox.active ul li {
            color: #fff;
        }

        #subscriptionSec .subscriptionBox.active ul li img {
            filter: unset;
        }

        #subscriptionSec .subscriptionBox.active a:hover {
            background: #fff;
            color: #333;
            transition: .3s;
        }

        #subscriptionSec .subscriptionSecHeading {
            border-radius: 0;
            color: #333;
            font-size: 28px;
            font-family: "Inter";
            font-weight: 500;
            margin: 0px 0px 30px 0;
        }

        @media(max-width: 768px) {
            .subscriptionBoxRow {
                flex-direction: column;
            }

            .subscriptionBoxCol {
                width: 100%;
                max-width: 100%;
            }
        }
    </style>

    <section id="subscriptionSec">
        <h2 class="subscriptionSecHeading">Subscriptions</h2>



        <div class="subscriptionBoxRow">
            <div class="subscriptionBoxCol">
                <h4>Premium Monthly</h4>
                <div class="subscriptionBox active">
                    <h6>$4.99 / month</h6>
                    <p>Access the full power of the MuslimLynk App and make meaningful connections with ease.</p>
                    <ul>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>Access to all advanced filters</span>
                        </li>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>View full user profiles, including contact information</span>
                        </li>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>In-app messaging to connect directly with other users</span>
                        </li>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>Add and promote your products and services within the app</span>
                        </li>
                    </ul>

                    <a href="{{ route('user.add.subscriptions') }}">Choose Plan</a>
                </div>
            </div>
            <div class="subscriptionBoxCol">
                <h4>Premium Yearly</h4>
                <div class="subscriptionBox">
                    <h6>$49.99 / year</h6>
                    <p>Access the full power of the MuslimLynk App and make meaningful connections with ease.</p>
                    <ul>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>Access to all advanced filters</span>
                        </li>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>View full user profiles, including contact information</span>
                        </li>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>In-app messaging to connect directly with other users</span>
                        </li>
                        <li>
                            <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid" alt="">
                            <span>Add and promote your products and services within the app</span>
                        </li>
                    </ul>

                    <a href="{{ route('user.add.subscriptions') }}">Choose Plan</a>
                </div>

            </div>

        </div>
    </section>
@endsection
@section('scripts')
@endsection
