@extends('admin.layouts.main')

<style>
    body{
        background: #fafbff !important;
        font-family: "Inter" !important;
    }

    /* div#\#modernCard {
        border: 2px solid #F2F2F2 !important;
        background: #FFFFFF !important;
        justify-content: space-between !important;
        padding: 0 30px !important;
        transition: 0.2s ease-in-out !important;
        border-radius: 20px !important;
        box-shadow: none !important;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
    } */

    .col-lg-6 .card-header:first-child,
    .col-lg-6 .card-body {
        background: #ffffff !important;
    }

    .card-modern:hover {
        border-color: #37488E !important;
        box-shadow: 0 4px 12px rgba(55, 72, 142, 0.15) !important;
        transform: translateY(-2px);
    }

    .card-modern:hover .card-numbers,
    .card-modern:hover .card-title {
        color: #37488E !important;
    }

    .col-lg-6 .card {
         border: 2px solid #F2F2F2 !important;
        background: #FFFFFF !important;
        justify-content: space-between !important;
        padding: 0 30px !important;
        transition: 0.2s ease-in-out !important;
        border-radius: 20px !important;
    }

    .card-modern a {
        text-decoration: none;
        color: inherit;
        display: block;
        width: 100%;
       
    }

    a.card.card-modern{
        border: 2px solid #F2F2F2 !important;
        background: #FFFFFF !important;
        justify-content: space-between !important;
        padding: 0 30px !important;
        transition: 0.2s ease-in-out !important;
        border-radius: 20px !important;
        box-shadow: none !important;
        cursor: pointer;
        text-decoration: none;
        color: inherit;
    }

    .card-modern .card-body {
        display: flex;
        text-align: center;
        padding: 10px 0 !important;
        justify-content: space-between;
        align-items: center;
        background: #ffffff !important;
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

    span#account-filter-badge,
    span#platforms-filter-badge {
        background: #e4e7f1 !important;
        font-size: 16px !important;
        font-weight: 500;
        color: #333;
    }

    .d-flex.gap-2.mt-2.align-items-center {
        flex-wrap: wrap;
    }

    button.btn.btn-primary.btn-sm,
    button.btn.btn-secondary.btn-sm {
        font-size: 14px !important;
    }

    @media (min-width: 768px) and (max-width: 1502px) {
        .card-modern .card-body {
           flex-direction: column-reverse;
        }

        .cardCounting {
            text-align: center !important;
        }

        .cardSvg {
            margin-top: 20px;
        }
    }

    
    @media (min-width: 558px) and (max-width: 1190px) {
        .container .row.g-4 .col-lg-3 {
            width: 50%;
        }
    }
</style>
@section('content')
    <main class="main-content">

        <div class="container">
            <div class="row g-4">
                <div class="col-lg-3">
                    <a href="{{ route('admin.users') }}" class="card card-modern" style="text-decoration: none; color: inherit;">
                        <div class="card-body">
                            <div class="cardCounting">
                                <h2 class="card-numbers">
                                    {{ \App\Helpers\GeneralHelper::getUserCount() }}
                                </h2>
                                <h1 class="card-title">Users</h1>
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
                    </a>
                </div>
                <!-- Subscriptions card removed - not part of newsfeed boilerplate -->
                                </h2>
                                <h1 class="card-title">Subscribers</h1>
                            </div>

                            <div class="cardSvg">
                                <div class="cardSvgCon">
                                    <!-- <svg width="30px" height="30px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <title>Subscribers</title>
                                        <path d="M20 4H4C2.89 4 2 4.89 2 6V18C2 19.11 2.89 20 4 20H20C21.11 20 22 19.11 22 18V6C22 4.89 21.11 4 20 4ZM20 18H4V6H20V18ZM5 10H7V8H5V10ZM5 16H7V14H5V16ZM9 10H11V8H9V10ZM9 16H11V14H9V16ZM13 10H15V8H13V10ZM13 16H15V14H13V16ZM17 10H19V8H17V10ZM17 16H19V14H17V16Z" fill="#ffffff"/>
                                    </svg> -->
                                    <svg width="30px" height="30px" viewBox="0 0 24 24" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <g id="Media" transform="translate(-720.000000, -48.000000)">
                                                <g id="notification_fill" transform="translate(720.000000, 48.000000)">
                                                    <path style="fill: transparent;" d="M24,0 L24,24 L0,24 L0,0 L24,0 Z M12.5934901,23.257841 L12.5819402,23.2595131 L12.5108777,23.2950439 L12.4918791,23.2987469 L12.4918791,23.2987469 L12.4767152,23.2950439 L12.4056548,23.2595131 C12.3958229,23.2563662 12.3870493,23.2590235 12.3821421,23.2649074 L12.3780323,23.275831 L12.360941,23.7031097 L12.3658947,23.7234994 L12.3769048,23.7357139 L12.4804777,23.8096931 L12.4953491,23.8136134 L12.4953491,23.8136134 L12.5071152,23.8096931 L12.6106902,23.7357139 L12.6232938,23.7196733 L12.6232938,23.7196733 L12.6266527,23.7031097 L12.609561,23.275831 C12.6075724,23.2657013 12.6010112,23.2592993 12.5934901,23.257841 L12.5934901,23.257841 Z M12.8583906,23.1452862 L12.8445485,23.1473072 L12.6598443,23.2396597 L12.6498822,23.2499052 L12.6498822,23.2499052 L12.6471943,23.2611114 L12.6650943,23.6906389 L12.6699349,23.7034178 L12.6699349,23.7034178 L12.678386,23.7104931 L12.8793402,23.8032389 C12.8914285,23.8068999 12.9022333,23.8029875 12.9078286,23.7952264 L12.9118235,23.7811639 L12.8776777,23.1665331 C12.8752882,23.1545897 12.8674102,23.1470016 12.8583906,23.1452862 L12.8583906,23.1452862 Z M12.1430473,23.1473072 C12.1332178,23.1423925 12.1221763,23.1452606 12.1156365,23.1525954 L12.1099173,23.1665331 L12.0757714,23.7811639 C12.0751323,23.7926639 12.0828099,23.8018602 12.0926481,23.8045676 L12.108256,23.8032389 L12.3092106,23.7104931 L12.3186497,23.7024347 L12.3186497,23.7024347 L12.3225043,23.6906389 L12.340401,23.2611114 L12.337245,23.2485176 L12.337245,23.2485176 L12.3277531,23.2396597 L12.1430473,23.1473072 Z" id="MingCute" fill-rule="nonzero">
                                                    </path>
                                                    <path d="M15,19 C15,20.0543909 14.18415,20.9181678 13.1492661,20.9945144 L13,21 L11,21 C9.94563773,21 9.08183483,20.18415 9.00548573,19.1492661 L9,19 L15,19 Z M12.0002,2 C15.7856583,2 18.869299,5.0047865 18.996141,8.75935044 L19.0002,9 L19.0002,12.7639 L20.8222,16.4081 C21.1704857,17.1046714 20.7047125,17.9183404 19.9532033,17.9942531 L19.8384,18 L4.16197,18 C3.38318905,18 2.86370061,17.2195011 3.13189688,16.5133571 L3.1781,16.4081 L5.00016,12.7639 L5.00016,9 C5.00016,5.13401 8.13417,2 12.0002,2 Z" fill="#333">
                                                    </path>
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-lg-3">
                    <a href="{{ route('admin.blogs') }}" class="card card-modern" style="text-decoration: none; color: inherit;">
                        <div class="card-body">
                            <div class="cardCounting">
                                <h2 class="card-numbers">
                                    {{ \App\Helpers\GeneralHelper::getBlogCount() }}
                                </h2>
                                <h1 class="card-title">Blogs</h1>
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
                    </a>
                </div>
                <div class="col-lg-3">
                    <a href="{{ route('admin.events') }}" class="card card-modern" style="text-decoration: none; color: inherit;">
                        <div class="card-body">
                            <div class="cardCounting">
                                <h2 class="card-numbers">
                                    {{ \App\Helpers\GeneralHelper::getEventCount() }}
                                </h2>
                                <h1 class="card-title">Events</h1>
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
                    </a>
                </div>
            </div>

            <!-- Charts Section -->
            <div class="row g-4 mt-2">
                <!-- Chart 1: User Registrations Line Chart -->
                <div class="col-lg-6">
                    <div class="card" style="border: 2px solid #F2F2F2; border-radius: 20px; box-shadow: none;">
                        <div class="card-header" style="background: #fafbff; border: none;">
                            <h4 class="card-title" style="font-family: 'Inter'; font-weight: 500; font-size: 20px; color: #333;">User Registrations</h4>
                            <div class="d-flex gap-2 mt-2 align-items-center">
                                <label class="mb-0" style="font-size: 14px; color: #666;">From:</label>
                                <input type="date" id="signups-start-date" class="form-control form-control-sm" style="max-width: 150px;">
                                <label class="mb-0" style="font-size: 14px; color: #666;">To:</label>
                                <input type="date" id="signups-end-date" class="form-control form-control-sm" style="max-width: 150px;">
                                <button onclick="loadSignupsChart()" style="padding: 4px 30px;" class="btn btn-primary btn-sm">Apply</button>
                            </div>
                        </div>
                        <div class="card-body" style="height: 400px;">
                            <canvas id="signupsChart"></canvas>
                        </div>
                    </div>
                </div>

                <!-- Chart 2: Users by Platform Pie Chart -->
                <div class="col-lg-6">
                    <div class="card" style="border: 2px solid #F2F2F2; border-radius: 20px; box-shadow: none;">
                        <div class="card-header" style="background: #fafbff; border: none;">
                            <h4 class="card-title" style="font-family: 'Inter'; font-weight: 500; font-size: 20px; color: #333;">Users by Platform <span class="badge bg-info" style="font-size: 12px;" id="platforms-filter-badge">All Time</span></h4>
                            <div class="d-flex gap-2 mt-2 align-items-center">
                                <label class="mb-0" style="font-size: 14px; color: #666;">From:</label>
                                <input type="date" id="platforms-start-date" class="form-control form-control-sm" style="max-width: 150px;">
                                <label class="mb-0" style="font-size: 14px; color: #666;">To:</label>
                                <input type="date" id="platforms-end-date" class="form-control form-control-sm" style="max-width: 150px;">
                                <button onclick="loadPlatformsChart()" style="padding: 4px 30px;" class="btn btn-primary btn-sm">Apply</button>
                                <button onclick="resetPlatformsFilter()" style="padding: 4px 30px;" class="btn btn-secondary btn-sm">Reset</button>
                            </div>
                        </div>
                        <div class="card-body" style="height: 400px; position: relative;">
                            <div class="row h-100">
                                <div class="col-8">
                                    <canvas id="platformsChart"></canvas>
                                </div>
                                <div class="col-4 d-flex flex-column justify-content-center" id="platformsLegend">
                                    <!-- Labels will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Chart 4: User Registration Sources Pie Chart -->
                <div class="col-lg-6">
                    <div class="card" style="border: 2px solid #F2F2F2; border-radius: 20px; box-shadow: none;">
                        <div class="card-header" style="background: #fafbff; border: none;">
                            <h4 class="card-title" style="font-family: 'Inter'; font-weight: 500; font-size: 20px; color: #333;">User Registration Sources <span class="badge" style="font-size: 12px;" id="account-filter-badge">All Time</span></h4>
                            <div class="d-flex gap-2 mt-2 align-items-center">
                                <label class="mb-0" style="font-size: 14px; color: #666;">From:</label>
                                <input type="date" id="account-start-date" class="form-control form-control-sm" style="max-width: 150px;">
                                <label class="mb-0" style="font-size: 14px; color: #666;">To:</label>
                                <input type="date" id="account-end-date" class="form-control form-control-sm" style="max-width: 150px;">
                                <button onclick="loadAccountCreationChart()" style="padding: 4px 30px;" class="btn btn-primary btn-sm">Apply</button>
                                <button onclick="resetAccountFilter()" style="padding: 4px 30px;" class="btn btn-secondary btn-sm">Reset</button>
                            </div>
                        </div>
                        <div class="card-body" style="height: 400px; position: relative;">
                            <div class="row h-100">
                                <div class="col-8">
                                    <canvas id="accountCreationChart"></canvas>
                                </div>
                                <div class="col-4 d-flex flex-column justify-content-center" id="accountLegend">
                                    <!-- Labels will be inserted here -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </main>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
    // Initialize date inputs with default values (last 7 days for first 2 charts)
    const defaultEndDate = new Date().toISOString().split('T')[0];
    const defaultStartDate7Days = new Date(Date.now() - 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];

    // Set default dates for first 2 charts (7 days)
    document.getElementById('signups-start-date').value = defaultStartDate7Days;
    document.getElementById('signups-end-date').value = defaultEndDate;
    document.getElementById('subscribers-start-date').value = defaultStartDate7Days;
    document.getElementById('subscribers-end-date').value = defaultEndDate;

    // Pie charts start empty (all-time data by default)
    document.getElementById('platforms-start-date').value = '';
    document.getElementById('platforms-end-date').value = '';
    document.getElementById('account-start-date').value = '';
    document.getElementById('account-end-date').value = '';

    let signupsChart, subscribersChart, platformsChart, accountCreationChart;

    // Chart 1: Sign-ups Line Chart
    function loadSignupsChart() {
        const startDate = document.getElementById('signups-start-date').value;
        const endDate = document.getElementById('signups-end-date').value;

        fetch(`{{ route('admin.dashboard.chart-data') }}?chart_type=signups&start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('signupsChart').getContext('2d');

                if (signupsChart) {
                    signupsChart.destroy();
                }

                signupsChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'New Users',
                            data: data.data,
                            borderColor: '#37488E',
                            backgroundColor: 'rgba(55, 72, 142, 0.1)',
                            tension: 0.4,
                            fill: true,
                            pointRadius: 4,
                            pointHoverRadius: 6
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                title: {
                                    display: true,
                                    text: 'Number of Users'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            }
                        }
                    }
                });
            });
    }

    // Chart 2: Subscribers Bar Chart
    function loadSubscribersChart() {
        const startDate = document.getElementById('subscribers-start-date').value;
        const endDate = document.getElementById('subscribers-end-date').value;

        fetch(`{{ route('admin.dashboard.chart-data') }}?chart_type=subscribers&start_date=${startDate}&end_date=${endDate}`)
            .then(response => response.json())
            .then(data => {
                const ctx = document.getElementById('subscribersChart').getContext('2d');

                if (subscribersChart) {
                    subscribersChart.destroy();
                }

                subscribersChart = new Chart(ctx, {
                    type: 'bar',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            label: 'Active',
                            data: data.active,
                            backgroundColor: '#28a745'
                        }, {
                            label: 'Renewed',
                            data: data.renewed,
                            backgroundColor: '#ffc107'
                        }, {
                            label: 'Cancelled',
                            data: data.cancelled,
                            backgroundColor: '#dc3545'
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: true,
                                position: 'top'
                            },
                            tooltip: {
                                mode: 'index',
                                intersect: false
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                },
                                title: {
                                    display: true,
                                    text: 'Number of Subscriptions'
                                }
                            },
                            x: {
                                title: {
                                    display: true,
                                    text: 'Date'
                                }
                            }
                        }
                    }
                });
            });
    }

    // Chart 3: Users by Platform Pie Chart
    function loadPlatformsChart() {
        const startDate = document.getElementById('platforms-start-date').value;
        const endDate = document.getElementById('platforms-end-date').value;
        const filterBadge = document.getElementById('platforms-filter-badge');

        // Update badge
        if (startDate && endDate) {
            filterBadge.textContent = 'Filtered';
            filterBadge.className = 'badge bg-warning';
        } else {
            filterBadge.textContent = 'All Time';
            filterBadge.className = 'badge bg-info';
        }

        const params = new URLSearchParams({
            chart_type: 'platforms'
        });
        if (startDate) params.append('start_date', startDate);
        if (endDate) params.append('end_date', endDate);

        fetch(`{{ route('admin.dashboard.chart-data') }}?${params.toString()}`)
            .then(response => response.json())
            .then(data => {
                // Update legend container with labels and counts
                const legendContainer = document.getElementById('platformsLegend');
                legendContainer.innerHTML = '';
                // Ensure labels are in correct order: Android, iOS, Web
                const orderedLabels = ['Android', 'iOS', 'Web'];
                const colors = ['#28a745', '#007bff', '#ffc107'];

                // Find index in data for each ordered label
                orderedLabels.forEach((orderedLabel, index) => {
                    const dataIndex = data.labels.indexOf(orderedLabel);
                    const value = dataIndex >= 0 ? (data.data[dataIndex] || 0) : 0;
                    const colorIndex = dataIndex >= 0 ? dataIndex : index;

                    const legendItem = document.createElement('div');
                    legendItem.style.cssText = 'display: flex; align-items: center; margin-bottom: 5px; font-family: Inter;';
                    legendItem.innerHTML = `
                        <span style="width: 12px; height: 12px; background-color: ${colors[colorIndex]}; border-radius: 30px; margin-right: 10px; display: inline-block;"></span>
                        <span style="font-size: 14px; font-weight: 400; color: #333;">${orderedLabel} ${value}</span>
                    `;
                    legendContainer.appendChild(legendItem);
                });

                const ctx = document.getElementById('platformsChart').getContext('2d');

                if (platformsChart) {
                    platformsChart.destroy();
                }

                platformsChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: data.labels,
                        datasets: [{
                            data: data.data,
                            backgroundColor: ['#28a745', '#007bff', '#ffc107']
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                        label += context.parsed + ' (' + percentage + '%)';
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            });
    }

    // Chart 4: Account Creation Pie Chart
    function loadAccountCreationChart() {
        const startDate = document.getElementById('account-start-date').value;
        const endDate = document.getElementById('account-end-date').value;
        const filterBadge = document.getElementById('account-filter-badge');

        // Update badge
        if (startDate && endDate) {
            filterBadge.textContent = 'Filtered';
            filterBadge.className = 'badge bg-warning';
        } else {
            filterBadge.textContent = 'All Time';
            filterBadge.className = 'badge bg-info';
        }

        const params = new URLSearchParams({
            chart_type: 'account_creation'
        });
        if (startDate) params.append('start_date', startDate);
        if (endDate) params.append('end_date', endDate);

        fetch(`{{ route('admin.dashboard.chart-data') }}?${params.toString()}`)
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                // Update legend container with labels and counts
                const legendContainer = document.getElementById('accountLegend');
                legendContainer.innerHTML = '';
                // Ensure labels are in correct order: Web, iOS, Android, Amcob API, Admin
                const orderedLabels = ['Web', 'iOS', 'Android', 'Amcob API', 'Admin'];
                // Color mapping: Web=Yellow, iOS=Blue, Android=Green, Amcob API=Orange, Admin=Gray
                const colorMap = {
                    'Web': '#ffc107',      // Yellow
                    'iOS': '#007bff',      // Blue
                    'Apple': '#007bff',    // Blue (for backward compatibility)
                    'Android': '#28a745',  // Green
                    'Amcob API': '#fd7e14', // Orange
                    'Admin': '#6c757d'     // Gray
                };

                const labels = data.labels || [];
                const dataValues = data.data || [];

                // Find index in data for each ordered label (check both iOS and Apple for compatibility)
                orderedLabels.forEach((orderedLabel, index) => {
                    let dataIndex = labels.indexOf(orderedLabel);
                    // Fallback: if iOS not found, try Apple
                    if (orderedLabel === 'iOS' && dataIndex === -1) {
                        dataIndex = labels.indexOf('Apple');
                    }
                    const value = dataIndex >= 0 ? (dataValues[dataIndex] || 0) : 0;
                    const color = colorMap[orderedLabel] || '#6c757d';

                    const legendItem = document.createElement('div');
                    legendItem.style.cssText = 'display: flex; align-items: center; margin-bottom: 5px; font-family: Inter;';
                    // Always display "iOS" instead of "Apple"
                    const displayLabel = orderedLabel === 'iOS' || orderedLabel === 'Apple' ? 'iOS' : orderedLabel;
                    legendItem.innerHTML = `
                        <span style="width: 12px; height: 12px; background-color: ${color};  border-radius: 30px; margin-right: 10px; display: inline-block;"></span>
                        <span style="font-size: 14px; font-weight: 400; color: #333;">${displayLabel} ${value}</span>
                    `;
                    legendContainer.appendChild(legendItem);
                });

                const ctx = document.getElementById('accountCreationChart').getContext('2d');

                if (accountCreationChart) {
                    accountCreationChart.destroy();
                }

                // Map colors based on label order: Web=Yellow, iOS/Apple=Blue, Android=Green, Amcob API=Orange, Admin=Gray
                const chartColors = labels.map(label => {
                    if (label === 'Web') return '#ffc107';      // Yellow
                    if (label === 'iOS' || label === 'Apple') return '#007bff';    // Blue
                    if (label === 'Android') return '#28a745';  // Green
                    if (label === 'Amcob API') return '#fd7e14'; // Orange
                    if (label === 'Admin') return '#6c757d';     // Gray
                    return '#6c757d'; // Default gray
                });

                accountCreationChart = new Chart(ctx, {
                    type: 'pie',
                    data: {
                        labels: labels,
                        datasets: [{
                            data: dataValues,
                            backgroundColor: chartColors
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                callbacks: {
                                    label: function(context) {
                                        let label = context.label || '';
                                        if (label) {
                                            label += ': ';
                                        }
                                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                        const percentage = total > 0 ? ((context.parsed / total) * 100).toFixed(1) : 0;
                                        label += context.parsed + ' (' + percentage + '%)';
                                        return label;
                                    }
                                }
                            }
                        }
                    }
                });
            })
            .catch(error => {
                console.error('Error loading account creation chart:', error);
            });
    }

    // Reset filters for platforms chart
    function resetPlatformsFilter() {
        document.getElementById('platforms-start-date').value = '';
        document.getElementById('platforms-end-date').value = '';
        loadPlatformsChart();
    }

    // Reset filters for account creation chart
    function resetAccountFilter() {
        document.getElementById('account-start-date').value = '';
        document.getElementById('account-end-date').value = '';
        loadAccountCreationChart();
    }

    // Load all charts on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadSignupsChart();
        loadSubscribersChart();
        loadPlatformsChart();
        loadAccountCreationChart();
    });
</script>
@endsection
