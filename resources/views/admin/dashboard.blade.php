@extends('admin.layouts.main')

<style>
    body{
        background: #fafbff !important;
    }

    .card-modern {
        border: 2px solid #F2F2F2 !important;
        background: #FFFFFF !important;
        justify-content: space-between !important;
        padding: 0 30px !important;
        transition: 0.2s ease-in-out !important;
        border-radius: 20px !important;
        box-shadow: none !important;
    }

    .card-modern .card-body {
        display: flex;
        text-align: center;
        padding: 10px 0 !important;
        justify-content: space-between;
        align-items: center;
    }
    
    .cardCounting {
        text-align: left !important;
    }

    .card-modern .card-numbers {
        font-size: 30px !important;
        font-weight: 600 !important;
        font-family: "Inter";
        margin-top: 20px;
        color: #333 !important;
        transition: color 0.2s ease;
    }

    .card-modern .card-title {
        font-size: 20px !important;
        font-weight: 500 !important;
        font-family: "Inter";
        margin-top: 10px;
        color: #333 !important;
        margin-bottom: 20px !important;
        transition: color 0.2s ease;
    }

    .cardSvgCon {
        border: 6px solid #e4e7f1;
        padding: 17px;
        background: linear-gradient(180deg, #0e1948, #213bae);
        border-radius: 50px;
        
    }

    .cardSvgCon svg,
    .cardSvgCon svg path{
        fill: #fff;
    }
</style>
@section('content')
    <main class="main-content">

        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3">
                    <div class="card card-modern">
                        <div class="card-body">
                            <div class="cardCounting">
                                <h2 class="card-numbers">
                                    {{ \App\Helpers\GeneralHelper::getUserCount() }}
                                </h2>
                                <h1 class="card-title">No. Of Users</h1>
                            </div>

                            <div class="cardSvg">
                                <div class="cardSvgCon">
                                    <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M1.5 6.5C1.5 3.46243 3.96243 1 7 1C10.0376 1 12.5 3.46243 12.5 6.5C12.5 9.53757 10.0376 12 7 12C3.96243 12 1.5 9.53757 1.5 6.5Z" fill="#000000"/>
                                    <path d="M14.4999 6.5C14.4999 8.00034 14.0593 9.39779 13.3005 10.57C14.2774 11.4585 15.5754 12 16.9999 12C20.0375 12 22.4999 9.53757 22.4999 6.5C22.4999 3.46243 20.0375 1 16.9999 1C15.5754 1 14.2774 1.54153 13.3005 2.42996C14.0593 3.60221 14.4999 4.99966 14.4999 6.5Z" fill="#000000"/>
                                    <path d="M0 18C0 15.7909 1.79086 14 4 14H10C12.2091 14 14 15.7909 14 18V22C14 22.5523 13.5523 23 13 23H1C0.447716 23 0 22.5523 0 22V18Z" fill="#000000"/>
                                    <path d="M16 18V23H23C23.5522 23 24 22.5523 24 22V18C24 15.7909 22.2091 14 20 14H14.4722C15.4222 15.0615 16 16.4633 16 18Z" fill="#000000"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card card-modern">
                        <!-- <div class="card-body">
                            <h1 class="card-title">No. Of Subscribers</h1>
                            <h2 class="card-numbers">
                                {{ \App\Helpers\GeneralHelper::getSubscriberCount() }}
                            </h2>
                        </div> -->
                        <div class="card-body">
                            <div class="cardCounting">
                                <h2 class="card-numbers">
                                    {{ \App\Helpers\GeneralHelper::getSubscriberCount() }}
                                </h2>
                                <h1 class="card-title">No. Of Subscribers</h1>
                            </div>

                            <div class="cardSvg">
                                <div class="cardSvgCon">
                                    <svg width="30px" height="30px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <title>Subscriptions</title>
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Media" transform="translate(-720.000000, -48.000000)">
                                                <g id="notification_fill" transform="translate(720.000000, 48.000000)">
                                                    <path style="fill: transparent;" d="M24,0 L24,24 L0,24 L0,0 L24,0 Z M12.5934901,23.257841 L12.5819402,23.2595131 L12.5108777,23.2950439 L12.4918791,23.2987469 L12.4918791,23.2987469 L12.4767152,23.2950439 L12.4056548,23.2595131 C12.3958229,23.2563662 12.3870493,23.2590235 12.3821421,23.2649074 L12.3780323,23.275831 L12.360941,23.7031097 L12.3658947,23.7234994 L12.3769048,23.7357139 L12.4804777,23.8096931 L12.4953491,23.8136134 L12.4953491,23.8136134 L12.5071152,23.8096931 L12.6106902,23.7357139 L12.6232938,23.7196733 L12.6232938,23.7196733 L12.6266527,23.7031097 L12.609561,23.275831 C12.6075724,23.2657013 12.6010112,23.2592993 12.5934901,23.257841 L12.5934901,23.257841 Z M12.8583906,23.1452862 L12.8445485,23.1473072 L12.6598443,23.2396597 L12.6498822,23.2499052 L12.6498822,23.2499052 L12.6471943,23.2611114 L12.6650943,23.6906389 L12.6699349,23.7034178 L12.6699349,23.7034178 L12.678386,23.7104931 L12.8793402,23.8032389 C12.8914285,23.8068999 12.9022333,23.8029875 12.9078286,23.7952264 L12.9118235,23.7811639 L12.8776777,23.1665331 C12.8752882,23.1545897 12.8674102,23.1470016 12.8583906,23.1452862 L12.8583906,23.1452862 Z M12.1430473,23.1473072 C12.1332178,23.1423925 12.1221763,23.1452606 12.1156365,23.1525954 L12.1099173,23.1665331 L12.0757714,23.7811639 C12.0751323,23.7926639 12.0828099,23.8018602 12.0926481,23.8045676 L12.108256,23.8032389 L12.3092106,23.7104931 L12.3186497,23.7024347 L12.3186497,23.7024347 L12.3225043,23.6906389 L12.340401,23.2611114 L12.337245,23.2485176 L12.337245,23.2485176 L12.3277531,23.2396597 L12.1430473,23.1473072 Z" id="MingCute" fill-rule="nonzero">
                                                    </path>
                                                    <path d="M15,19 C15,20.0543909 14.18415,20.9181678 13.1492661,20.9945144 L13,21 L11,21 C9.94563773,21 9.08183483,20.18415 9.00548573,19.1492661 L9,19 L15,19 Z M12.0002,2 C15.7856583,2 18.869299,5.0047865 18.996141,8.75935044 L19.0002,9 L19.0002,12.7639 L20.8222,16.4081 C21.1704857,17.1046714 20.7047125,17.9183404 19.9532033,17.9942531 L19.8384,18 L4.16197,18 C3.38318905,18 2.86370061,17.2195011 3.13189688,16.5133571 L3.1781,16.4081 L5.00016,12.7639 L5.00016,9 C5.00016,5.13401 8.13417,2 12.0002,2 Z"  fill="#333">
                                                    </path>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card card-modern">
                        <!-- <div class="card-body">
                            <h1 class="card-title">No. Of Blogs</h1>
                            <h2 class="card-numbers">
                                {{ \App\Helpers\GeneralHelper::getBlogCount() }}
                            </h2>
                        </div> -->
                        <div class="card-body">
                            <div class="cardCounting">
                                <h2 class="card-numbers">
                                    {{ \App\Helpers\GeneralHelper::getBlogCount() }}
                                </h2>
                                <h1 class="card-title">No. Of Blogs</h1>
                            </div>

                            <div class="cardSvg">
                                <div class="cardSvgCon">
                                    <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <title>Blogs</title>
                                    <g id="style=fill">
                                    <g id="document">
                                    <path id="Subtract" fill-rule="evenodd" clip-rule="evenodd" d="M8 1.25C4.82436 1.25 2.25 3.82436 2.25 7V17C2.25 20.1756 4.82436 22.75 8 22.75H16C19.1756 22.75 21.75 20.1756 21.75 17V7C21.75 3.82436 19.1756 1.25 16 1.25H8ZM8 7.44995C7.58579 7.44995 7.25 7.78574 7.25 8.19995C7.25 8.61416 7.58579 8.94995 8 8.94995H16C16.4142 8.94995 16.75 8.61416 16.75 8.19995C16.75 7.78574 16.4142 7.44995 16 7.44995H8ZM7.25 12.2C7.25 11.7857 7.58579 11.45 8 11.45H16C16.4142 11.45 16.75 11.7857 16.75 12.2C16.75 12.6142 16.4142 12.95 16 12.95H8C7.58579 12.95 7.25 12.6142 7.25 12.2ZM9 15.45C8.58579 15.45 8.25 15.7857 8.25 16.2C8.25 16.6142 8.58579 16.95 9 16.95H15C15.4142 16.95 15.75 16.6142 15.75 16.2C15.75 15.7857 15.4142 15.45 15 15.45H9Z" fill="#333"/>
                                    </g>
                                    </g>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="card card-modern">
                        <!-- <div class="card-body">
                            <h1 class="card-title">No. Of Events</h1>
                            <h2 class="card-numbers">
                                {{ \App\Helpers\GeneralHelper::getEventCount() }}
                            </h2>
                        </div> -->
                        <div class="card-body">
                            <div class="cardCounting">
                                <h2 class="card-numbers">
                                    {{ \App\Helpers\GeneralHelper::getEventCount() }}
                                </h2>
                                <h1 class="card-title">No. Of Events</h1>
                            </div>

                            <div class="cardSvg">
                                <div class="cardSvgCon">
                                    <svg  fill="#333" xmlns="http://www.w3.org/2000/svg"  width="40px" height="40px"
                                        viewBox="0 0 100 100" xml:space="preserve">
                                        <title>Events</title>

                                    <g>
                                        <g>
                                            <path d="M76,42H24c-1.1,0-2,0.9-2,2v30c0,3.3,2.7,6,6,6h44c3.3,0,6-2.7,6-6V44C78,42.9,77.1,42,76,42z M40,70
                                                c0,1.1-0.9,2-2,2h-4c-1.1,0-2-0.9-2-2v-4c0-1.1,0.9-2,2-2h4c1.1,0,2,0.9,2,2V70z M40,56c0,1.1-0.9,2-2,2h-4c-1.1,0-2-0.9-2-2v-4
                                                c0-1.1,0.9-2,2-2h4c1.1,0,2,0.9,2,2V56z M54,70c0,1.1-0.9,2-2,2h-4c-1.1,0-2-0.9-2-2v-4c0-1.1,0.9-2,2-2h4c1.1,0,2,0.9,2,2V70z
                                                M54,56c0,1.1-0.9,2-2,2h-4c-1.1,0-2-0.9-2-2v-4c0-1.1,0.9-2,2-2h4c1.1,0,2,0.9,2,2V56z M68,70c0,1.1-0.9,2-2,2h-4
                                                c-1.1,0-2-0.9-2-2v-4c0-1.1,0.9-2,2-2h4c1.1,0,2,0.9,2,2V70z M68,56c0,1.1-0.9,2-2,2h-4c-1.1,0-2-0.9-2-2v-4c0-1.1,0.9-2,2-2h4
                                                c1.1,0,2,0.9,2,2V56z"/>
                                        </g>
                                        <g>
                                            <path d="M72,26h-5v-2c0-2.2-1.8-4-4-4s-4,1.8-4,4v2H41v-2c0-2.2-1.8-4-4-4s-4,1.8-4,4v2h-5c-3.3,0-6,2.7-6,6v2
                                                c0,1.1,0.9,2,2,2h52c1.1,0,2-0.9,2-2v-2C78,28.7,75.3,26,72,26z"/>
                                        </g>
                                    </g>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

           
        </div>

    </main>
@endsection
