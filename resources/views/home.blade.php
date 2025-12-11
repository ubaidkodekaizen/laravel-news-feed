     <!DOCTYPE html>
     <html lang="en">

     <head>
         <meta charset="UTF-8">
         <meta name="viewport" content="width=device-width, initial-scale=1.0">
         <meta http-equiv="X-UA-Compatible" content="ie=edge">
         <title>Muslim Lynk | Empowering Connections, Amplifying Impact</title>
         <meta name="description"
             content="Join Muslim Lynk to connect, collaborate, and grow. A dynamic network for Muslim professionals and entrepreneurs, driving success and community impact.">
         <meta property="og:type" content="website">
         <meta property="og:title" content="Muslim Lynk – Where Connections Create Impact">
         <meta property="og:description"
             content="Discover opportunities, build powerful networks, and strengthen our community’s economic future. Join the movement and let’s grow together!">
         <meta property="og:url" content="{{ url('/') }}">
         <meta property="og:image" content="{{ asset('assets/images/logo_bg.png') }}">
         <meta property="og:site_name" content="{{ config('app.name') }}">
         <link rel="icon" href="{{ asset('assets/images/logo_bg.png') }}" type="image/x-icon">
         <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
             integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
             crossorigin="anonymous">
         <link rel="preconnect" href="https://fonts.googleapis.com">
         <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
         <link
             href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=PT+Sans:ital,wght@0,400;0,700;1,400;1,700&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
             rel="stylesheet">
         <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.0/css/all.min.css" />
         <style>
             *,
             body {
                 margin: 0;
                 padding: 0;
                 box-sizing: border-box;
             }

             /* mobile navbar  */
             /* Existing Navbar Styles */


             /* .mainNavbarInner {
                 display: flex;
                 justify-content: space-between;
                 align-items: center;
                 padding: 15px 30px;
                 max-width: 1400px;
                 margin: 0 auto;
             } */

             .mainNavbarBrand img {
                 vertical-align: middle;
             }

             .mainNavbarMenu {
                display: flex;
                align-items: center;
                gap: 50px;
                width: 100%;
                justify-content: space-between;
             }

             .mainNavbarListCenter,
             .mainNavbarListRight {
                 display: flex;
                 list-style: none;
                 margin: 0;
                 padding: 0;
                 gap: 30px;
                 align-items: center;
             }

             .mainNavbarLink {
                 text-decoration: none;
                 color: #333;
                 font-weight: 500;
                 transition: color 0.3s;
             }

             .mainNavbarLink:hover {
                 color: #007bff;
             }

             .loginBtn {
                 background: #007bff;
                 color: #fff !important;
                 padding: 10px 25px;
                 border-radius: 5px;
             }

             .loginBtn:hover {
                 background: #0056b3;
             }

             .userProfilePic {
                 border-radius: 50%;
                 object-fit: cover;
             }

             .profile_name_dd {
                 text-decoration: none;
                 color: #333;
                 font-weight: 500;
                 margin-left: 10px;
             }

             .dropdown-menu {
                 min-width: 150px;
             }

             #bigDeviceLogo{
                display: block;
             }

             #smallDeviceLogo{
                display: none;
             }

             /* Mobile Menu Toggle Button */
             .mobileMenuToggle {
                 display: none;
                 flex-direction: column;
                 background: transparent;
                 border: none;
                 cursor: pointer;
                 padding: 5px;
                 z-index: 1001;
             }

             .hamburgerLine {
                 width: 25px;
                 height: 3px;
                 background-color: #333;
                 margin: 3px 0;
                 transition: 0.3s;
                 border-radius: 3px;
             }

             /* Mobile Menu Toggle Active State */
             .mobileMenuToggle.active .hamburgerLine:nth-child(1) {
                 transform: rotate(-45deg) translate(-5px, 6px);
             }

             .mobileMenuToggle.active .hamburgerLine:nth-child(2) {
                 opacity: 0;
             }

             .mobileMenuToggle.active .hamburgerLine:nth-child(3) {
                 transform: rotate(45deg) translate(-5px, -6px);
             }

             /* Mobile Responsive Styles */
             @media (max-width: 991px) {
                 .mainNavbarInner {
                     padding: 15px 20px;
                 }

                 .mobileMenuToggle {
                     display: flex;
                 }

                 .mainNavbarMenu {
                     position: fixed;
                     top: 0;
                     right: -100%;
                     width: 280px;
                     height: 100vh;
                     background: #fff;
                     flex-direction: column;
                     padding: 80px 30px 30px;
                     gap: 30px;
                     box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
                     transition: right 0.3s ease;
                     overflow-y: auto;
                 }

                 .mainNavbarMenu.active {
                     right: 0;
                 }

                 .mainNavbarListCenter,
                 .mainNavbarListRight {
                     flex-direction: column;
                     gap: 20px;
                     width: 100%;
                 }

                 .mainNavbarListItem {
                     width: 100%;
                     text-align: center;
                 }

                 .mainNavbarLink {
                     display: block;
                     padding: 10px;
                     font-size: 16px;
                 }

                 .loginBtn {
                     display: inline-block;
                     width: auto;
                 }

                 .dropdown {
                     width: 100%;
                     text-align: center;
                 }

                 .dropdown-menu {
                     width: 100%;
                 }

                 .userProfilePic {
                     margin: 0 auto;
                     display: block;
                 }

                 .profile_name_dd {
                     display: block;
                     margin: 10px 0;
                 }
             }

             @media (max-width: 480px) {
                 .mainNavbarInner {
                     padding: 10px 15px;
                 }

                 .mainNavbarBrand img {
                     width: 60px;
                 }

                 .mainNavbarMenu {
                     width: 100%;
                     right: -100%;
                 }
             }

             /* Mobile Menu Overlay */
             .menuOverlay {
                 display: none;
                 position: fixed;
                 top: 0;
                 left: 0;
                 width: 100%;
                 height: 100%;
                 background: rgba(0, 0, 0, 0.5);
                 z-index: 999;
             }

             .menuOverlay.active {
                 display: block;
             }


             /* mobile navbar  */

             .mainNavbar {
                 background-color: #fff;
                 padding: 10px 20px;
                 border-radius: 0px 0px 24px 24px;
                 box-shadow: 0 4px 19.1px rgba(0, 0, 0, 12%);
                 position: fixed;
                 top: -1px;
                 left: -2px;
                 width: 101%;
                 z-index: 9999;
             }


             .mainNavbarInner {
                 max-width: 1516px;
                 margin: auto;
                 display: flex;
                 align-items: center;
                 justify-content: space-between;
             }

             .mainNavbarList {
                 display: flex;
                 align-items: center;
                 justify-content: center;
                 padding: 0;
                 margin: 0;
             }

             .mainNavbarListItem {
                 list-style: none;
                 display: flex;
                 align-items: center;
                 justify-content: center;
             }

             .mainNavbarListCenter {
                 max-width: max-content;
                 width: 100%;
                 padding: 0;
                 margin: 0;
                 display: flex;
                 align-items: center;
                 justify-content: center;
                 gap: 30px;
             }

             .mainNavbarListRight {
                 padding: 0;
                 margin: 0;
             }

             .userProfilePic {
                 width: 50px;
                 border-radius: 50%;
                 height: 50px;
                 object-fit: cover;
             }

             .profile_name_dd {
                 text-decoration: none;
                 color: #232323;
                 margin-left: 10px;
                 transition: .3s;
                 font-weight: 600;
                 font-family: "Inter", sans-serif;
                 font-optical-sizing: auto;
                 font-style: normal;
                 font-size: 16px;
             }

             .profile_name_dd:hover {
                 color: #233273;
                 transition: .3s;
             }

             .mainNavbarLink {
                 text-decoration: none;
             }

             .mainNavbar .loginBtn {
                 background-color: #263473;
                 font-family: "Inter", Sans-serif;
                 font-size: 18px;
                 font-weight: 700;
                 color: #FFFFFF !important;
                 border-radius: 50px;
                 padding: 15px 68px 15px 68px !important;
                 display: flex;
                 align-items: center;
                 justify-content: center;
                 min-height: 61px;
                 line-height: 34.67px;
                 letter-spacing: 0.7px;
             }

             .mainNavbarListCenter .mainNavbarListItem .mainNavbarLink {
                 font-family: Inter;
                 font-weight: 400;
                 font-size: 20px;
                 line-height: 25.6px;
                 text-transform: capitalize;
                 color: #333333;
             }

             #homeHeroSec {
                 background-image: linear-gradient(#000000c7, #213baed1), url(/assets/images/heroBanner.png);
                 /* background: linear-gradient(180deg, #0E1948 0%, #213BAE 100%); */
                 padding: 171px 20px 75px 20px;
             }

             #homeHeroSec .homeHeroSecInner {
                 max-width: 1340px;
                 width: 100%;
                 margin: auto;
                 text-align: center;
             }

             #homeHeroSec .homeHeroSecInner h2 {
                 font-family: "Bebas Neue", sans-serif;
                 font-weight: 400;
                 font-size: 128px;
                 line-height: 100%;
                 text-align: center;
                 text-transform: capitalize;
                 color: #FFFFFF;
             }

             #homeHeroSec .homeHeroSecInner p {
                 font-family: "Inter", sans-serif;
                 font-weight: 300;
                 font-size: 22.21px;
                 text-align: center;
                 vertical-align: middle;
                 text-transform: capitalize;
                 color: #FFFFFF;
             }

             #homeHeroSec .homeHeroSecInner p strong {
                 font-weight: 700;
             }

             #homeHeroSec .homeHeroSecInnerActionBtns {
                 display: flex;
                 align-items: center;
                 justify-content: center;
                 gap: 20px;
                 margin-top: 50px;
             }

             #homeHeroSec .homeHeroSecInnerActionBtns .homeHeroSecInnerActionBtn {
                 max-width: 245px;
                 width: 100%;
                 min-height: 84px;
                 opacity: 1;
                 border: 1px solid #fff;
                 border-radius: 50px;
                 text-decoration: none;
                 font-family: "Inter", sans-serif;
                 font-weight: 700;
                 font-size: 24px;
                 display: flex;
                 align-items: center;
                 justify-content: center;
                 color: #fff;
                 letter-spacing: 1.7px;
             }

             #homeHeroSec .homeHeroSecInnerActionBtns .homeHeroSecInnerActionBtn.active {
                 background: #B8C034;
                 border: 1px solid #B8C034;
                 color: #273572;
             }

             .homeHeroSecInnerMobileStoreActionBtns {
                 display: flex;
                 flex-direction: column;
                 margin: 65px 0 0 0;
                 gap: 30px;
             }

             .homeHeroSecInnerMobileStoreActionBtns h4 {
                 font-family: "Inter", sans-serif;
                 font-weight: 500;
                 font-size: 32px;
                 text-align: center;
                 vertical-align: middle;
                 text-transform: capitalize;
                 color: #fff;
                 margin: 0;
             }

             .homeHeroSecInnerMobileStoreActionBtns .homeHeroSecInnerMobileStoreActionBtnsInner {
                 display: flex;
                 align-items: center;
                 justify-content: center;
                 gap: 30px;
             }

             .homeHeroSecInnerMobileStoreActionBtns .homeHeroSecInnerMobileStoreActionBtn {}

             #advantageSec {
                 padding: 120px 20px;
             }

             #advantageSec .advantageSecInnerHeading {
                 font-family: "Bebas Neue", sans-serif;
                 font-weight: 400;
                 font-size: 64px;
                 text-align: center;
                 text-transform: capitalize;
                 color: #273572;
             }

             #advantageSec .advantageSecInnerHeading span {
                 color: #B8C034;
             }

             #advantageSec .advantageSecInner {
                 max-width: 1516px;
                 width: 100%;
                 display: flex;
                 flex-direction: column;
                 margin: auto;
             }

             .advantageSecInnerBoxOuterRow {
                 display: flex;
                 align-items: start;
                 justify-content: center;
                 gap: 30px;
                 margin: 80px 0 0 0;
             }

             #advantageSec .advantageSecInnerBoxRow:first-child {
                 max-width: 768px;
                 display: flex;
                 align-items: start;
                 justify-content: start;
                 flex-wrap: wrap;
             }

             #advantageSec .advantageSecInnerBoxRow:last-child {
                 max-width: 734px;
             }

             #advantageSec .advantageSecInnerBoxes {
                 max-width: 50%;
                 width: 100%;
                 height: -webkit-fill-available;
                 padding: 26px 23px 26px 32px;
                 border: 1px solid #F2F2F2;
                 transition: .3s ease-in-out;
             }

             #advantageSec .advantageSecInnerBoxes span {
                 font-family: "Poppins", sans-serif;
                 font-weight: 900;
                 font-size: 31.22px;
                 text-transform: capitalize;
                 color: #848baf;
                 transition: .3s ease-in-out;

             }

             #advantageSec .advantageSecInnerBoxes h4 {
                 font-family: "Bebas Neue", sans-serif;
                 font-weight: 400;
                 font-size: 42.15px;
                 text-transform: capitalize;
                 color: #273572;
                 transition: .3s ease-in-out;
             }

             #advantageSec .advantageSecInnerBoxes p {
                 font-family: "Inter", sans-serif;
                 font-weight: 400;
                 font-size: 15.61px;
                 text-transform: capitalize;
                 color: #273572;
                 transition: .3s ease-in-out;
             }

             #advantageSec .advantageSecInnerBoxes:last-child {
                 max-width: 100%;
             }

             #advantageSec .advantageSecInnerBoxes.active {
                 background: #273572;
                 transition: .3s ease-in-out;
             }

             #advantageSec .advantageSecInnerBoxes.active span,
             #advantageSec .advantageSecInnerBoxes.active h4,
             #advantageSec .advantageSecInnerBoxes.active p {
                 color: #fff;
                 transition: .3s ease-in-out;
             }

             #advantageSec .advantageSecInnerBoxes:hover {
                 background: #273572;
                 transition: .3s ease-in-out;
             }

             /* #advantageSec .advantageSecInnerBoxes:hover span, */
             #advantageSec .advantageSecInnerBoxes:hover h4,
             #advantageSec .advantageSecInnerBoxes:hover p {
                 color: #fff;
                 transition: .3s ease-in-out;
             }

             #pricingSec {
                 background-image: linear-gradient(#000000c7, #213baed1), url("/assets/images/pricingSecComponent.png");
                 /* background: linear-gradient(180deg, rgba(14, 25, 72, 0.87) 0%, rgba(33, 59, 174, 0.87) 100%); */

                 padding: 92px 20px 90px 20px;
             }

             #pricingSec .pricingSecHead h2 {
                 font-family: "Bebas Neue", sans-serif;
                 font-weight: 400;
                 font-size: 64px;
                 text-align: center;
                 text-transform: capitalize;
                 color: #fff;
             }

             #pricingSec .pricingSecHead p {
                 font-family: "Inter", sans-serif;
                 font-weight: 400;
                 font-size: 22.21px;
                 text-align: center;
                 text-transform: capitalize;
                 color: #fff;
             }

             #pricingSec .pricingSecInner {
                 max-width: 1220px;
                 width: 100%;
                 margin: auto;
             }

             #pricingSec .nav.nav-pills {
                 align-items: center;
                 justify-content: center;
                 gap: 20px;
                 margin: 30px 0 47px 0;
             }

             #pricingSec .nav.nav-pills .nav-item .nav-link {
                 font-family: "Inter", sans-serif;
                 font-weight: 400;
                 font-size: 20px;
                 line-height: 24px;
                 color: #fff;
                 max-width: 189px;
                 min-height: 53px;
                 border-radius: 50px;
                 padding: 14px 60px;
                 border: 1px solid #fff;
                 background: transparent;
             }

             #pricingSec .nav.nav-pills .nav-item .nav-link.active {
                 background: rgba(59, 88, 212, 1);
                 border: 1px solid rgba(59, 88, 212, 1);
             }

             .pricingSecBox {
                 max-width: 600px;
                 border-radius: 25px;
                 border: 12px solid #7881aa;
                 padding: 39px 42px 28px 42px;
                 background: rgba(255, 255, 255, 1);
                 margin: auto;
             }

             .pricingSecBox h4 {
                 max-width: 141px;
                 min-height: 81px;
                 border-radius: 10px;
                 background: rgba(39, 53, 114, 1);
                 display: flex;
                 align-items: center;
                 justify-content: center;
                 color: #fff;
                 font-family: "Bebas Neue", sans-serif;
                 font-weight: 400;
                 font-size: 48px;
                 text-align: center;
             }

             .pricingSecBox h2 {
                 font-family: "Bebas Neue", sans-serif;
                 font-weight: 400;
                 font-size: 84px;
                 line-height: 50px;
                 color: rgba(39, 53, 114, 1);
                 margin: 45px 0 36px 0;
             }

             .pricingSecBox p {
                 font-family: Inter;
                 font-weight: 400;
                 font-size: 20px;
                 color: rgba(39, 53, 114, 1);
                 margin: 0 0 30px 0;
             }

             .pricingSecBox ul {
                 padding: 0;
             }

             .pricingSecBox ul li {
                 list-style: none;
                 font-family: Inter;
                 font-weight: 400;
                 font-size: 18px;
                 color: rgba(39, 53, 114, 1);
                 margin: 5px 0;
             }

             .pricingSecBox a {
                 width: 100%;
                 min-height: 75px;
                 border-radius: 50px;
                 display: flex;
                 align-items: center;
                 justify-content: center;
                 background: rgba(39, 53, 114, 1);
                 font-family: Inter;
                 font-weight: 700;
                 font-size: 24.85px;
                 letter-spacing: 1.5px;
                 color: #fff;
                 text-decoration: none;
                 margin: 30px 0 0 0;
             }

             #faqSec {
                 padding: 120px 20px;
             }

             #faqSec .faqSecInnerHeading {
                 font-family: "Bebas Neue", sans-serif;
                 font-weight: 400;
                 font-size: 64px;
                 text-align: center;
                 text-transform: capitalize;
                 color: #273572;
             }

             #faqSec .faqSecInnerHeading span {
                 color: #B8C034;
             }

             #faqSec .faqSecInner {
                 max-width: 1516px;
                 width: 100%;
                 display: flex;
                 flex-direction: column;
                 margin: auto;
             }

             #faqSec .faqSecInnerRow {
                 display: flex;
                 /* align-items: center; */
                 justify-content: center;
                 gap: 30px;
                 margin: 38px 0 0 0;
             }

             #faqSec .faqSecInnerBox {
                 max-width: 50%;
                 width: 100%;
             }

             #faqSec .accordion-flush .accordion-item {
                 margin: 0 0 34px 0;
                 box-shadow: 0px 27.8px 37.06px -16.68px rgba(149, 149, 149, 0.25);
                 border: none;
                 padding: 37px 46px 24px 46px;
                 border-radius: 5px;
                 position: relative;
             }

             #faqSec .accordion-item:has(.accordion-button:not(.collapsed)) {
                 background: linear-gradient(180deg, #0E1948 0%, #213BAE 100%) !important;
             }

             #faqSec .accordion-item:has(.accordion-button:not(.collapsed)) .accordion-body {
                 color: #fff;
             }

             #faqSec .accordion-flush .accordion-item .accordion-button {
                 font-family: "Inter", sans-serif;
                 font-weight: 600;
                 font-size: 20.37px;
                 line-height: 130%;
                 letter-spacing: 0px;
                 border: none !important;
                 background: transparent;
                 outline: none;
             }

             #faqSec .accordion-button::after {
                 position: absolute;
                 left: -20px;
                 top: 18px;
                 background-image: url('assets/images/plusIcon.png');
             }

             #faqSec .accordion-button:not(.collapsed)::after {
                 background-image: url('assets/images/minusIcon.png');
                 transform: rotate(-180deg);
                 top: 10px;
             }

             #faqSec .accordion-button:focus {
                 box-shadow: none;
             }

             #faqSec .accordion-button:not(.collapsed) {
                 color: #fff;
             }


             #footer {
                 background-color: #B8C034;
             }

             #footer p {
                 text-align: center;
                 color: #273572;
                 font-family: "Inter", Sans-serif;
                 font-size: 18px;
                 font-weight: 700;
                 margin: 0;
                 padding: 20px 0;
             }

             /* Download Banner css */
             .home_banner_sec {
                 min-height: 580px;
                 width: 100%;
                 background-position: center;
                 background-size: cover;
                 overflow-x: hidden;
                 padding: 40px 0;
                 align-content: center;
             }

             .home_banner_sec .banner_container .banner_right_image img {
                 width: 100%;
                 height: 100%;
                 max-width: 100%;
                 object-fit: contain;
             }

             .home_banner_sec .banner_container img {
                 max-width: 200px;
             }

             .banner_right_image {
                 height: 500px;
                 width: 100%;
             }

             .home_banner_sec .content h1 {
                 font-family: "Bebas Neue", sans-serif;
                 color: white;
                 font-size: 109.19px !important;
                 font-weight: 400;
                 line-height: 110px;
                 text-transform: uppercase;
                 width: 55%;
                 margin: auto;
             }

             .home_banner_sec .content p {
                 color: white;
                 font-size: 22.21px !important;
                 text-transform: capitalize;
                 font-weight: 400;
             }

             .home_banner_sec .btn_flex {
                 display: flex;
                 flex-direction: row;
                 gap: 10px;
                 justify-content: center;
             }

             .theme_color {
                 color: #b4be32;
             }

             .banner_container {
                 width: 100%
             }

             img#advantageMainImg {
                width: 734px;
                height: 756px;
                object-fit: cover;
                object-position: center;
            }

             @media (min-width: 100%) {
                 .banner_container {
                     max-width: 100%
                 }
             }

             @media (min-width: 767px) {
                 .banner_container {
                     max-width: 767px
                 }
             }

             @media (max-width: 992px) {

             #bigDeviceLogo{

                display: none;
             }

             #smallDeviceLogo{
                display: block;
             }
            }

             @media (min-width: 992px) {
                 .banner_container {
                     max-width: 992px
                 }




             }

             @media (min-width: 1200px) {
                 .banner_container {
                     max-width: 1200px
                 }
             }

             @media (min-width: 1440px) {
                 .banner_container {
                     max-width: 1440px
                 }

                 .home_banner_sec .content h1 {
                     font-size: 5rem;
                 }

                 .home_banner_sec .content p {
                     font-size: 1.75rem;
                 }


             }

             .banner_container {
                 margin-left: auto;
                 margin-right: auto;
                 padding-left: .5rem;
                 padding-right: .5rem
             }

             @media (min-width: 575.98px) {
                 .banner_container {
                     padding-left: .5rem;
                     padding-right: .5rem
                 }
             }

             @media (min-width: 767.98px) {
                 .banner_container {
                     padding-left: 1.25rem;
                     padding-right: 1.25rem
                 }
             }

             /* Download Banner css  */

             @media(max-width: 992px) {
                 .homeFirstSecRow {
                     flex-direction: column-reverse;
                 }

                 .homeSec2Row2 {
                     align-items: center;
                     max-width: 100%;
                     flex-direction: column-reverse;
                 }

                 .stickySec {
                     position: unset;
                     width: 50%;
                 }

                 .homeFirstSecRow .homeFirstSecCol:last-child {
                     display: flex;
                     align-items: center;
                     justify-content: center;
                 }

                 .homeSec2Col:last-child {
                     max-width: 100%;
                     display: flex;
                     align-items: center;
                     justify-content: center;
                 }

                 .homeSec2Col:last-child .stickySec {
                     width: 100%;
                 }

                 .tabs {
                     padding: 35px;
                 }

                 .tabs h2 {
                     font-size: 20px;
                 }

                 .tabs h4 {
                     font-size: 30px;
                 }

                 .tabs p {
                     font-size: 16px;
                 }

                 .tabs ul li {
                     font-size: 16px;
                 }

                 .homeSec3Pricing .signUpBtn {
                     font-size: 18px;
                     border-radius: 10px;
                     padding: 8px 20px 8px 20px;
                 }
             }

             @media(max-width: 1399px) {
                 #advantageSec .advantageSecInnerBoxRow {
                     max-width: 50% !important;
                 }

             }

             @media(max-width: 1029px) {
                 #advantageSec .advantageSecInnerBoxRow {
                     max-width: 100% !important;
                 }

                 .advantageSecInnerBoxOuterRow {
                     flex-direction: column-reverse;
                 }

                 .advantageSecInnerBoxOuterRow {
                     flex-direction: column-reverse;
                 }

                 #advantageSec .advantageSecInnerBoxRow:last-child {
                     max-width: 100% !important;
                     width: 100%;
                     text-align: center;
                 }

                 #advantageSec .advantageSecInnerBoxRow img {
                     width: 100%;
                     height: 327px;
                     object-fit: cover;
                     object-position: 0% 35%;
                 }

                 .home_banner_sec .content h1 {
                     width: 65%;
                 }

             }

             @media(max-width: 768px) {

                 #homeHeroSec .homeHeroSecInner h2 {
                     font-size: 5rem;
                 }

                 #homeHeroSec .homeHeroSecInner p {
                     font-size: 16.21px;
                 }

                 #homeHeroSec .homeHeroSecInnerActionBtns .homeHeroSecInnerActionBtn {
                     font-size: 16px;
                     min-height: 60px;
                 }

                 .homeHeroSecInnerMobileStoreActionBtns {
                     margin: 40px 0 0 0;
                 }

                 .homeHeroSecInnerMobileStoreActionBtns h4 {
                     font-size: 24px;
                 }

                 #advantageSec {
                     padding: 80px 20px;
                 }

                 .advantageSecInnerBoxOuterRow {
                     margin: 40px 0 0 0;
                 }

                 #advantageSec .advantageSecInnerBoxRow:first-child {
                     flex-direction: column;
                 }

                 #advantageSec .advantageSecInnerBoxes {
                     max-width: 100%;
                     margin-top: -1px;
                 }

                 #pricingSec .pricingSecHead h2 {
                     font-size: 44px;
                 }

                 #pricingSec .pricingSecHead p {
                     font-size: 16.21px;
                 }

                 #pricingSec .nav.nav-pills .nav-item .nav-link {
                     font-size: 18px;
                 }

                 .pricingSecBox {
                     padding: 28px;
                 }

                 .pricingSecBox h4 {
                     max-width: 90px;
                     min-height: 62px;
                     font-size: 38px;
                 }

                 .pricingSecBox h2 {
                     font-size: 64px;
                     line-height: 63px;
                     margin: 30px 0 20px 0;
                 }

                 .pricingSecBox p {
                     font-size: 16px;
                 }

                 .pricingSecBox ul li {
                     font-size: 16px;
                 }

                 .pricingSecBox a {
                     width: 100%;
                     min-height: 55px;
                     font-size: 18.85px;
                 }

                 #faqSec .faqSecInnerRow {
                     flex-direction: column;
                     gap: 0px;

                 }

                 #faqSec .faqSecInnerBox {
                     max-width: 100%;
                 }

                 #faqSec .accordion-flush .accordion-item {
                     margin: 0 0 26px 0;
                 }



                 .banner_right_image {
                     display: none
                 }

                 .home_banner_sec .content h1 {
                     font-size: 58px !important;
                     line-height: 70px;
                     width: 100%;
                 }

                 .home_banner_sec .content p {
                     font-size: 18px !important;
                     margin-top: 10px;
                 }

                 .home_banner_sec {
                     min-height: fit-content;
                     text-align: center;
                 }

                 .home_banner_sec .banner_container .content {
                     padding: 20px 10px 10px 10px;
                 }

                 .home_banner_sec .btn_flex {
                     padding-bottom: 30px;
                 }

                 .home_banner_sec .banner_container img {
                     max-width: 136px;
                 }

                 #homeFirstSec .title {
                     font-size: 24px;
                     line-height: 1.3em;
                 }

                 #homeFirstSec {
                     padding: 20px 20px;
                 }

                 #homeFirstSec .para {
                     font-size: 16px;
                     line-height: 26px;
                 }

                 #readMoreAccordion .accordion-button {
                     border-radius: 20px !important;
                 }

                 #readMoreAccordion .accordion-body {
                     border-radius: 20px;
                 }

                 .homeFirstSecRow .homeFirstSecCol {
                     text-align: center;
                 }

                 .homeSec2Head {
                     font-size: 24px;
                     text-align: center;
                 }

                 .homeSec2ColInner h4 {
                     text-align: center;
                     font-size: 18px;
                 }

                 .homeSec2ColInner p {
                     text-align: center;
                     font-size: 16px;
                 }

                 #homeSec3 .homeSec3Head {
                     font-size: 24px;
                 }

                 #homeSec3 .homeSec3Para {
                     font-size: 16px;
                     line-height: 26px;
                 }

                 .tabs.active h2 {
                     font-size: 24px;
                     margin: 0;
                 }

                 .tabs.active h4 {
                     font-size: 18px;
                 }

                 .tabs.active span {
                     font-size: 18px;
                 }

                 .homeSec3Pricing .signUpBtn {
                     font-size: 18px;
                     padding: 10px 20px 10px 20px;
                 }

                 .homeSec3Pricing .pricingOffer {
                     font-size: 14px;
                 }

                 #readMoreAccordion h2 {
                     font-size: 20px;
                 }

                 #readMoreAccordion h3 {
                     font-size: 18px;
                 }

                 #readMoreAccordion p {
                     font-size: 16px;
                 }

                 #footer p {
                     font-size: 16px;
                 }

                 .tab-container.active {
                     flex-direction: column;
                     align-items: center;
                     gap: 0px;
                 }

                 .tabs {
                     width: 100%;
                     max-width: 70%;
                 }
             }

             @media(max-width: 500px) {
                 #footer p {
                     font-size: 12px;
                 }

                 .homeSec2Head {
                     font-size: 18px;
                     max-width: max-content;
                     margin: 0 auto 20px auto;
                     padding: 10px 20px;
                     border-radius: 12px;
                 }

                 #homeSec3 .homeSec3Head {
                     font-size: 18px;
                     max-width: max-content;
                     margin: 0 auto 20px auto;
                     padding: 10px 20px;
                     border-radius: 12px;
                 }

                 #readMoreAccordion .accordion-button {
                     border-radius: 12px !important;
                     max-width: max-content;
                     padding: 10px 20px;
                     margin: auto;
                     display: flex;
                     align-items: center;
                     justify-content: center;
                     gap: 10px;
                 }

                 #readMoreAccordion .accordion-body {
                     text-align: center;
                 }

                 .mainNavbar .loginBtn {
                     padding: 12px 30px 12px 30px !important;
                 }

                 .tabs {
                     width: 100%;
                     max-width: 100%;
                     padding: 25px;
                 }

                 .tabs h4 {
                     font-size: 24px;
                 }

                 .tabs h2 {
                     font-size: 18px;
                     margin: 0;
                 }

                 .tabs ul li i {
                     font-size: 14px;
                     color: #B4BE32;
                 }

                 .tabs p {
                     font-size: 14px;
                     line-height: 1.3em;
                 }

                 .tabs ul li {
                     font-size: 14px;
                     line-height: 1.3em;
                 }

                 .homeSec3Pricing .signUpBtn {
                     font-size: 16px;
                     padding: 8px 15px 8px 15px;
                 }
             }
         </style>
     </head>

     <body>

         <nav class="mainNavbar">
             <div class="mainNavbarInner">
                 <a id="smallDeviceLogo" class="mainNavbarBrand" href="{{ route('home') }}">
                     <img src="{{ asset('assets/images/logo.png') }}" width="70" class="img-fluid" alt="">
                 </a>

                 <!-- Hamburger Menu Button (Mobile Only) -->
                 <button class="mobileMenuToggle" type="button" aria-label="Toggle navigation">
                     <span class="hamburgerLine"></span>
                     <span class="hamburgerLine"></span>
                     <span class="hamburgerLine"></span>
                 </button>

                 <!-- Navigation Menu -->
                 <div  class="mainNavbarMenu">
                    <a id="bigDeviceLogo" class="mainNavbarBrand" href="{{ route('home') }}">
                     <img src="{{ asset('assets/images/logo.png') }}" width="70" class="img-fluid" alt="">
                 </a>
                     <ul class="mainNavbarListCenter">
                         <li class="mainNavbarListItem">
                             <a class="mainNavbarLink" href="{{ route('home') }}">Home</a>
                         </li>
                         <li class="mainNavbarListItem">
                             <a class="mainNavbarLink" href="#advantageSec">Advantage</a>
                         </li>
                         <li class="mainNavbarListItem">
                             <a class="mainNavbarLink" href="#pricingSec">Pricing</a>
                         </li>
                         <li class="mainNavbarListItem">
                             <a class="mainNavbarLink" href="#faqSec">FAQs</a>
                         </li>
                     </ul>

                     <ul class="mainNavbarListRight">
                         <li class="mainNavbarListItem">
                             @if (Auth::check())
                                 <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
                                     width="50" class="img-fluid userProfilePic" alt="">
                                 <div class="dropdown">
                                     <a href="javascript:void(0);" class="profile_name_dd dropdown-toggle"
                                         data-bs-toggle="dropdown" aria-expanded="false">
                                         {{ Auth::user()->first_name }}
                                     </a>
                                     <ul class="dropdown-menu">
                                         <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a>
                                         </li>
                                         <li><a class="dropdown-item logoutBtn" href="{{ route('logout') }}">Logout</a>
                                         </li>
                                     </ul>
                                 </div>
                             @else
                                 <a class="mainNavbarLink loginBtn" href="{{ route('login.form') }}">Login</a>
                             @endif
                         </li>
                     </ul>
                 </div>
             </div>
         </nav>



         {{-- <nav class="mainNavbar">
             <div class="mainNavbarInner">
                 <a class="mainNavbarBrand" href="{{ route('home') }}">
                     <img src="{{ asset('assets/images/logo.png') }}" width="70" class="img-fluid" alt="">
                 </a>
                 <ul class="mainNavbarListCenter">
                     <li class="mainNavbarListItem">
                         <a class="mainNavbarLink" href="{{ route('login.form') }}">Home</a>
                     </li>
                     <li class="mainNavbarListItem">
                         <a class="mainNavbarLink" href="{{ route('login.form') }}">Advantage</a>
                     </li>
                     <li class="mainNavbarListItem">
                         <a class="mainNavbarLink" href="{{ route('login.form') }}">Pricing</a>
                     </li>
                     <li class="mainNavbarListItem">
                         <a class="mainNavbarLink" href="{{ route('login.form') }}">FAQs</a>
                     </li>
                 </ul>
                 <ul class="mainNavbarListRight">
                     <li class="mainNavbarListItem">
                         @if (Auth::check())
                             <img src="{{ Auth::user()->photo ? asset('storage/' . Auth::user()->photo) : 'https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_640.png' }}"
                                 width="50" class="img-fluid userProfilePic" alt="">
                             <div class="dropdown">
                                 <a href="javascript:void(0);" class="profile_name_dd dropdown-toggle"
                                     data-bs-toggle="dropdown" aria-expanded="false">
                                     {{ Auth::user()->first_name }}
                                 </a>
                                 <ul class="dropdown-menu">
                                     <li><a class="dropdown-item" href="{{ route('dashboard') }}">Dashboard</a></li>
                                     <li><a class="dropdown-item logoutBtn" href="{{ route('logout') }}">Logout</a>
                                     </li>
                                 </ul>
                             </div>
                         @else
                             <a class="mainNavbarLink loginBtn" href="{{ route('login.form') }}">Login</a>
                         @endif

                     </li>
                 </ul>




             </div>
         </nav> --}}
         <div id="homeHeroSec">
             <div class="homeHeroSecInner">
                 <h2>Connecting Muslims Worldwide</h2>
                 <p><strong>Muslim Lynk</strong> is your gateway to empowerment, collaboration, and success within the
                     Muslim
                     community.
                     Whether you’re an educator, student, entrepreneur, or professional, we are here to unlock a world
                     of
                     opportunity for you. Built on the trusted foundation of AMCOB’s network, Muslim Lynk is more than a
                     platform – it’s a movement to help you thrive in your career, business, and life.</p>
                 <p>
                     Together, we can ensure that every dollar spent in our community multiplies its impact at least
                     sevenfold before it leaves. By connecting, collaborating, and supporting one another, we can foster
                     economic empowerment and strengthen our shared values.
                 </p>
                 <div class="homeHeroSecInnerActionBtns">
                     <a href="{{ route('login.form') }}" class="homeHeroSecInnerActionBtn active">Login</a>
                     <a href="{{ route('register.form') }}" class="homeHeroSecInnerActionBtn">Sign Up</a>
                 </div>
                 <div class="homeHeroSecInnerMobileStoreActionBtns">
                     <h4>Download the Muslim Lynk App</h4>
                     <div class="homeHeroSecInnerMobileStoreActionBtnsInner">
                         <a href="https://play.google.com/store/apps/details?id=com.MuslimLynk"
                             class="homeHeroSecInnerMobileStoreActionBtn">
                             <img src="{{ asset('assets/images/playStoreIcon.png') }}" class="img-fluid"
                                 alt="">
                         </a>
                         <a href="https://apps.apple.com/pk/app/muslimlynk/id6746872077"
                             class="homeHeroSecInnerMobileStoreActionBtn">
                             <img src="{{ asset('assets/images/appleStoreIcon.png') }}" class="img-fluid"
                                 alt="">
                         </a>
                     </div>
                 </div>

             </div>
         </div>

         <div id="advantageSec">
             <div class="advantageSecInner">
                 <h4 class="advantageSecInnerHeading">The Muslim Lynk <span>Advantage</span></h4>
                 <div class="advantageSecInnerBoxOuterRow">
                     <div class="advantageSecInnerBoxRow">
                         <div class="advantageSecInnerBoxes active" data-img="{{ asset('assets/images/homeAdvantageSecImg.png') }}">
                             <span>01</span>
                             <h4>Connect & Network</h4>
                             <p>Discover and connect with professionals who share your vision, industry, or goals,
                                 organized
                                 by expertise and location.</p>
                         </div>
                         <div class="advantageSecInnerBoxes" data-img="{{ asset('assets/images/homeAdvantageSecImg.png') }}">
                             <span>02</span>
                             <h4>Direct Messaging</h4>
                             <p>Build meaningful relationships with seamless, direct communication – no barriers, no
                                 delays.
                             </p>
                         </div>
                         <div class="advantageSecInnerBoxes" data-img="{{ asset('assets/images/homeAdvantageSecImg.png') }}">
                             <span>03</span>
                             <h4>Smart Suggestions</h4>
                             <p>Let our technology guide you to valuable connections, resources, and opportunities
                                 tailored
                                 to your needs.</p>
                         </div>
                         <div class="advantageSecInnerBoxes" data-img="{{ asset('assets/images/homeAdvantageSecImg.png') }}">
                             <span>04</span>
                             <h4>Marketplace</h4>
                             <p>A vibrant space to buy and sell services and products, driving growth and prosperity
                                 within
                                 the Muslim community.</p>
                         </div>
                         <div class="advantageSecInnerBoxes" data-img="{{ asset('assets/images/homeAdvantageSecImg.png') }}">
                             <span>05</span>
                             <h4>Mobile Access</h4>
                             <p>Build meaningful relationships with seamless, direct communication – no barriers, no
                                 delays.
                             </p>
                         </div>
                     </div>
                     <div class="advantageSecInnerBoxRow">
                         <img id="advantageMainImg" src="{{ asset('assets/images/homeAdvantageSecImg.png') }}" class="img-fluid"
                             alt="">
                     </div>
                 </div>

             </div>
         </div>

         <div id="pricingSec">
             <div class="pricingSecInner">
                 <div class="pricingSecHead">
                     <h2>Join the Movement</h2>
                     <p>Join Muslim Lynk today, and let’s shape a future where the collective strength of our community
                         uplifts
                         every individual. Together, we’ll ensure the principle of “a dollar revolves seven times within
                         the
                         community before it goes out” becomes a reality. Let’s Lynk and grow!</p>
                 </div>


                 <ul class="nav nav-pills" id="pills-tab" role="tablist">
                     <li class="nav-item" role="presentation">
                         <button class="nav-link active" id="pills-monthly-tab" data-bs-toggle="pill"
                             data-bs-target="#pills-monthly" type="button" role="tab"
                             aria-controls="pills-monthly" aria-selected="true">Monthly</button>
                     </li>
                     <li class="nav-item" role="presentation">
                         <button class="nav-link" id="pills-yearly-tab" data-bs-toggle="pill"
                             data-bs-target="#pills-yearly" type="button" role="tab"
                             aria-controls="pills-yearly" aria-selected="false">Yearly</button>
                     </li>

                 </ul>
                 <div class="tab-content" id="pills-tabContent">
                     <div class="tab-pane fade show active" id="pills-monthly" role="tabpanel"
                         aria-labelledby="pills-monthly-tab">
                         <div class="pricingSecBox">
                             <h4>PRO</h4>
                             <h2>$4.99 / month</h2>
                             <p>Access the full power of the Muslim Lynk App and make meaningful connections with ease.
                             </p>
                             <ul>
                                 <li>
                                     <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid"
                                         alt="">
                                     <span>Access to all advanced filters</span>
                                 </li>
                                 <li>
                                     <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid"
                                         alt="">
                                     <span>View full user profiles, including contact information</span>
                                 </li>
                                 <li>
                                     <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid"
                                         alt="">
                                     <span>In-app messaging to connect directly with other users</span>
                                 </li>
                                 <li>
                                     <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid"
                                         alt="">
                                     <span>Add and promote your products and services within the app</span>
                                 </li>
                             </ul>
                             <a href="{{ route('register.form') }}">Sign Up</a>
                         </div>
                     </div>
                     <div class="tab-pane fade" id="pills-yearly" role="tabpanel"
                         aria-labelledby="pills-yearly-tab">
                         <div class="pricingSecBox">
                             <h4>PRO</h4>
                             <h2>$49.99 / year</h2>
                             <p>Access the full power of the Muslim Lynk App and make meaningful connections with ease.
                             </p>
                             <ul>
                                 <li>
                                     <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid"
                                         alt="">
                                     <span>Access to all advanced filters</span>
                                 </li>
                                 <li>
                                     <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid"
                                         alt="">
                                     <span>View full user profiles, including contact information</span>
                                 </li>
                                 <li>
                                     <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid"
                                         alt="">
                                     <span>In-app messaging to connect directly with other users</span>
                                 </li>
                                 <li>
                                     <img src="{{ asset('assets/images/checkIcon.png') }}" class="img-fluid"
                                         alt="">
                                     <span>Add and promote your products and services within the app</span>
                                 </li>
                             </ul>
                             <a href="{{ route('register.form') }}">Sign Up</a>
                         </div>
                     </div>

                 </div>
             </div>
         </div>

         <div id="faqSec">
             <div class="faqSecInner">
                 <h2 class="faqSecInnerHeading">Got Questions? <span>We’ve Got Answers!</span></h2>

                 <div class="faqSecInnerRow">
                     <div class="faqSecInnerBox">
                         <div class="accordion accordion-flush" id="faqSecAccordion1">
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="flush-headingOne">
                                     <button class="accordion-button collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#flush-collapseOne"
                                         aria-expanded="false" aria-controls="flush-collapseOne">
                                         What is Muslim Lynk?
                                     </button>
                                 </h2>
                                 <div id="flush-collapseOne" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingOne" data-bs-parent="#faqSecAccordion1">
                                     <div class="accordion-body">Muslim Lynk is a networking platform designed to
                                         connect Muslim professionals,
                                         entrepreneurs, business owners, and service providers across the world. It’s
                                         your community-powered business network. </div>
                                 </div>
                             </div>
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="flush-headingTwo">
                                     <button class="accordion-button collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#flush-collapseTwo"
                                         aria-expanded="false" aria-controls="flush-collapseTwo">
                                         Who is Muslim Lynk for?
                                     </button>
                                 </h2>
                                 <div id="flush-collapseTwo" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingTwo" data-bs-parent="#faqSecAccordion1">
                                     <div class="accordion-body">
                                         Anyone looking to connect with Muslim talent, founders, specialists, and
                                         business leaders. Whether you want opportunities, clients, collaborations,
                                         mentors, or a stronger professional circle, this platform is for you.</div>
                                 </div>
                             </div>
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="flush-headingThree">
                                     <button class="accordion-button collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#flush-collapseThree"
                                         aria-expanded="false" aria-controls="flush-collapseThree">
                                         How is Muslim Lynk different from LinkedIn?
                                     </button>
                                 </h2>
                                 <div id="flush-collapseThree" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingThree" data-bs-parent="#faqSecAccordion1">
                                     <div class="accordion-body">
                                         LinkedIn is broad. Muslim Lynk is focused. It gives you direct access to Muslim
                                         business leaders and professionals inside a curated, values-aligned ecosystem.
                                     </div>
                                 </div>
                             </div>
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="flush-headingFour">
                                     <button class="accordion-button collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#flush-collapseFour"
                                         aria-expanded="false" aria-controls="flush-collapseFour">
                                         Who built Muslim Lynk?
                                     </button>
                                 </h2>
                                 <div id="flush-collapseFour" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingFour" data-bs-parent="#faqSecAccordion1">
                                     <div class="accordion-body">Muslim Lynk is created by AMCOB (Allied Muslim Chamber
                                         of Business), a premium ecosystem for Muslim entrepreneurs in the US, UK,
                                         Canada, the Gulf, and beyond.
                                     </div>
                                 </div>
                             </div>
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="flush-headingFive">
                                     <button class="accordion-button collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#flush-collapseFive"
                                         aria-expanded="false" aria-controls="flush-collapseFive">
                                         What can I do on Muslim Lynk?
                                     </button>
                                 </h2>
                                 <div id="flush-collapseFive" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingFive" data-bs-parent="#faqSecAccordion1">
                                     <div class="accordion-body">
                                         <ul>
                                             <li>Create your profile</li>
                                             <li>Connect with other members</li>
                                             <li>Search by industry, expertise, or location</li>
                                             <li>Send and receive messages</li>
                                             <li>Use the mobile app for on-the-go networking</li>
                                             <li>Access new features as the platform grows</li>
                                         </ul>
                                     </div>
                                 </div>
                             </div>
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="flush-headingsix">
                                     <button class="accordion-button collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#flush-collapsesix"
                                         aria-expanded="false" aria-controls="flush-collapsesix">
                                         Is Muslim Lynk free?
                                     </button>
                                 </h2>
                                 <div id="flush-collapsesix" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingsix" data-bs-parent="#faqSecAccordion1">
                                     <div class="accordion-body">Muslim Lynk works on a simple subscription model with
                                         monthly and annual plans. This keeps the platform clean, ad-free, and focused
                                         on real value.
                                     </div>
                                 </div>
                             </div>
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="flush-headingSeven">
                                     <button class="accordion-button collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#flush-collapseSeven"
                                         aria-expanded="false" aria-controls="flush-collapseSeven">
                                         What are the benefits for business owners?
                                     </button>
                                 </h2>
                                 <div id="flush-collapseSeven" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingSeven" data-bs-parent="#faqSecAccordion1">
                                     <div class="accordion-body">
                                         <ul>
                                             <li>Promote your services</li>
                                             <li>Discover vetted Muslim professionals</li>
                                             <li>Build business relationships faster</li>
                                             <li>Create a pipeline of clients and collaborators</li>
                                             <li>Be part of a trust-based, values-driven ecosystem</li>
                                         </ul>
                                     </div>
                                 </div>
                             </div>
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="flush-headingEight">
                                     <button class="accordion-button collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#flush-collapseEight"
                                         aria-expanded="false" aria-controls="flush-collapseEight">
                                         What are the benefits for professionals?
                                     </button>
                                 </h2>
                                 <div id="flush-collapseEight" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingEight" data-bs-parent="#faqSecAccordion1">
                                     <div class="accordion-body">
                                         <ul>
                                             <li>Grow your network</li>
                                             <li>Find opportunities within a community that shares your values</li>
                                             <li>Collaborate on projects and ventures</li>
                                             <li>Become discoverable by founders and companies</li>
                                             <li>Connect globally within your industry</li>
                                         </ul>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                     <div class="faqSecInnerBox">
                         <div class="accordion accordion-flush" id="faqSecAccordion2">
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="flush-headingNine">
                                     <button class="accordion-button collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#flush-collapseNine"
                                         aria-expanded="false" aria-controls="flush-collapseNine">
                                         Is Muslim Lynk global?
                                     </button>
                                 </h2>
                                 <div id="flush-collapseNine" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingNine" data-bs-parent="#faqSecAccordion2">
                                     <div class="accordion-body">Yes. Members join from the US, UK, Canada, Europe, the
                                         Middle East, and South Asia. It is a global community with strong local
                                         relevance.</div>
                                 </div>
                             </div>
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="flush-headingTen">
                                     <button class="accordion-button collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#flush-collapseTen"
                                         aria-expanded="false" aria-controls="flush-collapseTen">
                                         Is my information secure?
                                     </button>
                                 </h2>
                                 <div id="flush-collapseTen" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingTen" data-bs-parent="#faqSecAccordion2">
                                     <div class="accordion-body">Muslim Lynk does not sell or share your personal data.
                                         Your profile is visible only inside the platform. Additional security
                                         enhancements are part of upcoming releases.</div>
                                 </div>
                             </div>
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="flush-headingEleven">
                                     <button class="accordion-button collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#flush-collapseEleven"
                                         aria-expanded="false" aria-controls="flush-collapseEleven">
                                         How do I join?
                                     </button>
                                 </h2>
                                 <div id="flush-collapseEleven" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingEleven" data-bs-parent="#faqSecAccordion2">
                                     <div class="accordion-body">You can join through the Muslim Lynk mobile app on
                                         Android or iOS.
                                         You can also sign up directly on muslimlynk.com.
                                         Create your profile, choose your plan, and you’re all set. It takes less than
                                         two minutes.</div>
                                 </div>
                             </div>
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="flush-headingtwelve">
                                     <button class="accordion-button collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#flush-collapsetwelve"
                                         aria-expanded="false" aria-controls="flush-collapsetwelve">
                                         Is the Marketplace available yet?
                                     </button>
                                 </h2>
                                 <div id="flush-collapsetwelve" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingtwelve" data-bs-parent="#faqSecAccordion2">
                                     <div class="accordion-body">The Marketplace is in development. Once launched,
                                         members will be able to list services, hire talent, and buy from Muslim-owned
                                         businesses directly within the app.</div>
                                 </div>
                             </div>
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="flush-headingThirteen">
                                     <button class="accordion-button collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#flush-collapseThirteen"
                                         aria-expanded="false" aria-controls="flush-collapseThirteen">
                                         Can I use Muslim Lynk on my phone?
                                     </button>
                                 </h2>
                                 <div id="flush-collapseThirteen" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingThirteen" data-bs-parent="#faqSecAccordion2">
                                     <div class="accordion-body">Yes. The mobile app is available for both Android and
                                         iOS for easy, on-the-go networking.</div>
                                 </div>
                             </div>
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="flush-headingFourteen">
                                     <button class="accordion-button collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#flush-collapseFourteen"
                                         aria-expanded="false" aria-controls="flush-collapseFourteen">
                                         Can non-Muslims join?
                                     </button>
                                 </h2>
                                 <div id="flush-collapseFourteen" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingFourteen" data-bs-parent="#faqSecAccordion2">
                                     <div class="accordion-body">Yes. While the platform is created for the Muslim
                                         business community, non-Muslims who want to collaborate or support the
                                         ecosystem are welco</div>
                                 </div>
                             </div>
                             <div class="accordion-item">
                                 <h2 class="accordion-header" id="flush-headingFifteen">
                                     <button class="accordion-button collapsed" type="button"
                                         data-bs-toggle="collapse" data-bs-target="#flush-collapseFifteen"
                                         aria-expanded="false" aria-controls="flush-collapseFifteen">
                                         How do I get the most out of Muslim Lynk?
                                     </button>
                                 </h2>
                                 <div id="flush-collapseFifteen" class="accordion-collapse collapse"
                                     aria-labelledby="flush-headingFifteen" data-bs-parent="#faqSecAccordion2">
                                     <div class="accordion-body">
                                         <ul>
                                             <li>Complete your profile</li>
                                             <li>Add clear expertise and interests</li>
                                             <li>Connect actively</li>
                                             <li>Check the app regularly</li>
                                             <li>Share what you offer</li>
                                             <li>Your next client, collaborator, or mentor might already be here</li>
                                         </ul>
                                     </div>
                                 </div>
                             </div>
                         </div>
                     </div>
                 </div>

             </div>
         </div>


         <div class="home_banner_sec"
             style="background-image: linear-gradient(#000000bf, #213baec4), url(http://127.0.0.1:8000/assets/images/downloadMuslimLynkBanner.jpg);">
             <div class="banner_container">
                 <div class="row align-items-center">
                     <div class="col-lg-12 text-center">
                         <div class="content">
                             <h1>Download the Muslim Lynk App</h1>
                             <p>Now available on the App Store and Play Store</p>
                         </div>
                         <div class="btn_flex">
                             {{-- <a href="https://play.google.com/store/apps/details?id=com.MuslimLynk" target="_blank">
                                 <img src="{{ asset('assets/images/home_banner_playstore.png') }}" alt="Playstore"
                                     class="img-fluid">
                             </a>
                             <a href="https://apps.apple.com/pk/app/muslimlynk/id6746872077" target="_blank">
                                 <img src="{{ asset('assets/images/home_banner_appstore.png') }}" alt="App Store"
                                     class="img-fluid">
                             </a> --}}
                             <a href="https://play.google.com/store/apps/details?id=com.MuslimLynk"
                                 class="homeHeroSecInnerMobileStoreActionBtn">
                                 <img src="{{ asset('assets/images/playStoreIcon.png') }}" class="img-fluid"
                                     alt="">
                             </a>
                             <a href="https://apps.apple.com/pk/app/muslimlynk/id6746872077"
                                 class="homeHeroSecInnerMobileStoreActionBtn">
                                 <img src="{{ asset('assets/images/appleStoreIcon.png') }}" class="img-fluid"
                                     alt="">
                             </a>
                         </div>
                     </div>
                     {{-- <div class="col-lg-5">
                         <div class="banner_right_image">
                             <img src="{{ asset('assets/images/home_banner_right.png') }}" alt="Apps">
                         </div>
                     </div> --}}
                 </div>
             </div>
         </div>

         <div id="footer">
             <p>© 2025 – Powered By AMCOB LLC. All Rights Reserved.</p>
         </div>
         <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"
             integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous">
         </script>
         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js"
             integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous">
         </script>
         <script>
             document.addEventListener("DOMContentLoaded", function() {
                 document.getElementById('toggleSwitch').addEventListener('change', function() {
                     if (this.checked) {
                         document.getElementById('monthlyTab').classList.remove('active');
                         document.getElementById('yearlyTab').classList.add('active');
                     } else {
                         document.getElementById('monthlyTab').classList.add('active');
                         document.getElementById('yearlyTab').classList.remove('active');
                     }
                 });
             });

             // Mobile Menu Toggle
             document.addEventListener('DOMContentLoaded', function() {
                 const menuToggle = document.querySelector('.mobileMenuToggle');
                 const navbarMenu = document.querySelector('.mainNavbarMenu');
                 const body = document.body;

                 // Create overlay
                 const overlay = document.createElement('div');
                 overlay.className = 'menuOverlay';
                 body.appendChild(overlay);

                 // Toggle menu
                 menuToggle.addEventListener('click', function() {
                     this.classList.toggle('active');
                     navbarMenu.classList.toggle('active');
                     overlay.classList.toggle('active');
                     body.style.overflow = navbarMenu.classList.contains('active') ? 'hidden' : '';
                 });

                 // Close menu when clicking overlay
                 overlay.addEventListener('click', function() {
                     menuToggle.classList.remove('active');
                     navbarMenu.classList.remove('active');
                     overlay.classList.remove('active');
                     body.style.overflow = '';
                 });

                 // Close menu when clicking menu links
                 const menuLinks = document.querySelectorAll('.mainNavbarLink');
                 menuLinks.forEach(link => {
                     link.addEventListener('click', function() {
                         menuToggle.classList.remove('active');
                         navbarMenu.classList.remove('active');
                         overlay.classList.remove('active');
                         body.style.overflow = '';
                     });
                 });
             });
         </script>
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const boxes = Array.from(document.querySelectorAll('.advantageSecInnerBoxes'));
                const mainImg = document.getElementById('advantageMainImg');

                if (!mainImg || boxes.length === 0) return; // safety guard

                // Helper to change image and active class
                function activateBox(box) {
                boxes.forEach(b => b.classList.remove('active'));
                box.classList.add('active');

                const newImg = box.dataset.img || box.getAttribute('data-img');
                if (newImg) {
                    // optional: fade effect
                    mainImg.style.transition = 'opacity 200ms ease';
                    mainImg.style.opacity = 0;
                    // small timeout to allow fade-out
                    setTimeout(() => {
                    mainImg.src = newImg;
                    mainImg.style.opacity = 1;
                    }, 180);
                }
                }

                // Set initial image from the active box (if any)
                const initial = boxes.find(b => b.classList.contains('active'));
                if (initial && (initial.dataset.img || initial.getAttribute('data-img'))) {
                mainImg.src = initial.dataset.img || initial.getAttribute('data-img');
                }

                // Preload images to avoid flicker
                boxes.forEach(b => {
                const url = b.dataset.img || b.getAttribute('data-img');
                if (url) {
                    const img = new Image();
                    img.src = url;
                }
                });

                // Add listeners for hover and touch/click
                boxes.forEach(box => {
                box.addEventListener('mouseenter', () => activateBox(box));
                box.addEventListener('focus', () => activateBox(box)); // keyboard accessibility
                // support taps on mobile
                box.addEventListener('click', (e) => {
                    e.preventDefault();
                    activateBox(box);
                });
                });
            });
        </script>
     </body>

     </html>
