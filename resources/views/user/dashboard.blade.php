<!-- resources/views/dashboard.blade.php -->

@extends('layouts.dashboard-layout')

<style>
@import url('https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap');

    .custom_card .icon_name_flex {
        border: 2px solid #F2F2F2;
        background: #FFFFFF;
        justify-content: space-between;
        padding: 0 30px;
        transition: 0.2s ease-in-out;
    }

    .custom_card.active .icon_name_flex{
        background: #273572;
        border: 2px solid #273572;
    }

    .custom_card .icon_name_flex:hover{
        background: #273572;
        border: 2px solid #273572;
    }

    .custom_card .icon_name_flex:hover .heading,
    .custom_card .icon_name_flex:hover .card_num{
        color: #FFF !important;
    }

    .custom_card .icon_name_flex:hover .icon {
        background: #fff !important;
    }

    .custom_card .icon_name_flex:hover .icon img{
      filter: invert(87%) sepia(75%) saturate(2400%) hue-rotate(215deg) brightness(90%) contrast(95%);
    }

    .custom_card .icon_name_flex .card_data .heading{
        font-size: 20px;
        font-weight: 500;
        font-family: "Inter";
        margin-top: 13px;
        color: #333;
        transition: color 0.2s ease;
    }

    .custom_card.active .icon_name_flex .card_data .heading{
        color: #FFF;
    }

    .custom_card .icon_name_flex .card_data .card_num{
        font-size: 30px;
        font-weight: 600;
        font-family: "Inter";
        margin-top: 13px;
        color: #333;
        transition: color 0.2s ease;
    }

    .custom_card.active .icon_name_flex .card_data .card_num{
        color: #FFF;
    }

    .custom_card .icon {
        background: #273572;
        padding: 16px;
        border-radius: 50%;
        transition: background 0.2s ease;
    }

    .custom_card.active .icon {
        background: #fff;
    }

    .custom_card .icon img{
        filter: none;
    }
        
    .custom_card.active .icon img{
        
      filter: invert(87%) sepia(75%) saturate(2400%) hue-rotate(215deg) brightness(90%) contrast(95%);
    }

    .dashboardBtns button img{
        margin-right: 8px;
        margin-top: -2px;
    } 

    button.filterButton {
        border: 1px solid #E9EBF0;
        background: #fff;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 18px;
        font-weight: 300;
        font-family: "Inter";
        color: #02253A;
        text-transform: capitalize;
    }

    button.exportButton {
        border: 1px solid #E9EBF0;
        background: #273572;
        padding: 10px 20px;
        border-radius: 8px;
        font-size: 18px;
        font-family: "Inter";
        color: #fff;
        font-weight: 300;
        text-transform: capitalize;
    }

</style>


@section('dashboard-content') 
    <div class="row mb-3">
        <div class="col-lg-12">
             <h5 class="filter_heading top_filter_heading">Dashboard</h5>
        </div>
        <!-- <div class="col-lg-6 dashboardBtns">
            <button class="filterButton"> <img src="assets/images/dashboard/filterIcon.svg" alt=""> filter</button>
            <button class="exportButton"> <img src="assets/images/dashboard/exportDownloadIcon.svg" alt=""> Export</button>
        </div> -->
    </div>
    <div class="row">
        <div class="col-lg-3 mb-3">
           <div class="custom_card active">
                <div class="icon_name_flex">
                     <div class="card_data">
                         <div class="card_num">
                            {{ \App\Helpers\GeneralHelper::getServiceCountbyAuth() }}
                         </div>
                         <div class="heading">
                         Total Services
                         </div>
                     </div>
                     
                     <div class="icon">
                         <img class="iconTotalServices" src="assets/images/dashboard/TotalServiceIcon.svg" alt="">
                     </div>
                 </div>
           </div>
        </div>
        <div class="col-lg-3 mb-3">
            <div class="custom_card">
                <div class="icon_name_flex">
                    <div class="card_data">
                        <div class="card_num">
                            {{ \App\Helpers\GeneralHelper::getProductCountbyAuth() }}
                        </div>
                        <div class="heading">
                        Total Products
                        </div>
                    </div>
                    <div class="icon">
                        <img class="iconTotalProducts" src="assets/images/dashboard/TotalProductIcon.svg" alt="">
                    </div>
                </div>
            </div>
         </div>
         <!-- <div class="col-lg-3 mb-3">
           <div class="custom_card">
                <div class="icon_name_flex">
                    <div class="card_data">
                        <div class="card_num">
                            {{ \App\Helpers\GeneralHelper::getProductCountbyAuth() }}
                        </div>
                        <div class="heading">
                        Total Members
                        </div>
                    </div>
                    <div class="icon">
                         <img class="iconTotalMembers" src="assets/images/dashboard/TotalMembersIcon.svg" alt="">
                    </div>
                </div>
           </div>
        </div>
        <div class="col-lg-3 mb-3">
            <div class="custom_card">
                 <div class="icon_name_flex">
                     <div class="card_data">
                         <div class="card_num">
                            {{ \App\Helpers\GeneralHelper::getServiceCountbyAuth() }}
                         </div>
                         <div class="heading">
                         Total Industries
                         </div>
                     </div>
                     <div class="icon">
                         <img class="iconTotalIndustries" src="assets/images/dashboard/TotalIndustriesIcon.svg" alt="">
                     </div>
                 </div>
            </div>
         </div> -->
       
    </div>
@endsection
