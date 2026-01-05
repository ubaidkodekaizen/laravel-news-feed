<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>MuslimLynk</title>
    <link rel="icon" href="{{asset('assets/images/logo_bg.png')}}" type="image/x-icon">

    <style>
        *,body{
            margin: 0;
            padding: 0;
        }
        .home_page{
            width: 100%;
            height: 100vh;
            background: #b8c034;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            gap: 20px
        }
        .home_page img{
            max-height: 300px;
            max-width: 100%;
        }
        .custom-btn {
            width: 130px;
            height: 40px;
            color: #fff;
            border-radius: 5px;
            padding: 0px 5px;
            font-family: 'Lato', sans-serif;
            font-weight: 600;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            display: inline-block;
            box-shadow: inset 2px 2px 2px 0px rgba(255, 255, 255, .5), 7px 7px 20px 0px rgba(0, 0, 0, .1), 4px 4px 5px 0px rgba(0, 0, 0, .1);
            outline: none;
            justify-content: center;
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .btn-14 {
            background: #273572;
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
            background-image: linear-gradient(315deg, #b8c034 0%, #b8c034 74%);
            box-shadow: inset 2px 2px 2px 0px rgba(255, 255, 255, .5);
            transition: all 0.3s ease;
        }

        .btn-14:hover:after {
            top: auto;
            bottom: 0;
            height: 100%;
        }

        .btn-14:active {
            top: 2px;
        }

    </style>
</head>
<body>
    <div class="home_page">
        <img src="{{asset('assets/images/logo_bg.png')}}" alt="">
        <a href="{{route('register.form')}}" class="custom-btn btn-14">Join Now</a>
    </div>
</body>
</html>

