<!-- resources/views/dashboard.blade.php -->

@extends('layouts.dashboard-layout')

@section('dashboard-content') 
    <div class="row">
        <div class="col-lg-4 mb-3">
           <div class="custom_card">
                <div class="icon_name_flex">
                    <div class="icon">
                        <i class="fa-solid fa-laptop"></i>
                    </div>
                    <div class="card_data">
                        <div class="heading">
                        Total Products
                        </div>
                        <div class="card_num">
                            {{ \App\Helpers\GeneralHelper::getProductCountbyAuth() }}
                        </div>
                    </div>
                </div>
           </div>
        </div>
        <div class="col-lg-4 mb-3">
            <div class="custom_card">
                 <div class="icon_name_flex">
                     <div class="icon">
                        <i class="fa-solid fa-user-cog"></i>
                     </div>
                     <div class="card_data">
                         <div class="heading">
                         Total Services
                         </div>
                         <div class="card_num">
                            {{ \App\Helpers\GeneralHelper::getServiceCountbyAuth() }}
                         </div>
                     </div>
                 </div>
            </div>
         </div>
       
    </div>
@endsection
