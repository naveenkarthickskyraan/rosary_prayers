<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Rosary Prayers Dashboard</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">
    <link rel="shortcut icon" href="{{ asset('assets/assets/images/favicon.ico') }}">
    <link href="{{ asset('assets/datatable_assets/simple-datatables/style.css')}}" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="{{ asset('assets/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <link href="{{ asset('assets/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.0.96/css/materialdesignicons.min.css">
    <style>
        /* Customize page length control */
        .table>:not(caption)>*>* {
            border-bottom-width: 0px !important;
        }

        .card {
            cursor: pointer;
        }

        .graph {
            padding: 10px;
            min-height: 190px
        }

        .metrics {
            color: #1d3b4a;
            text-align: center;
            padding: 0;
            margin-top: 20px;
            height: 160px;
            border: 1px solid #ccc;
            border-width: 0 1px 4px;
            border-top: 1px solid #ccc overflow: hidden;
            white-space: nowrap;

        }

        .metrics::before {
            width: 0;
            height: 0;
            border-color: #62778c transparent transparent
        }

        .graph {
            position: absolute;
            bottom: 0;
            right: 5px;
            z-index: 1;
            padding: 0;
            width: 100%;
            height: 60px;
            min-height: 60px;
            opacity: 1
        }

        .metrics .over {
            position: absolute;
            z-index: 2;
            width: 100%;
            padding: 16px 6px 0
        }

        .metrics div span {
            display: block;
            font-size: 36px;
            font-weight: 400;
            line-height: 1em;
            margin: .5em 0
        }

        .metrics div h4,
        .metrics div h5 {
            font-size: 16px;
            line-height: 1em;
            color: #1d3b4a;
            text-transform: none;
            font-weight: 700;
            margin: .5em 0
        }

        .metrics div h5 {
            color: #5eb95e
        }

        .metrics div a:link,
        .metrics div a:visited {
            color: #1d3b4a;
            border-bottom: 2px dotted #08c
        }

        .metrics div a:hover {
            text-decoration: none;
            border-bottom: 2px solid #08c
        }
    </style>
    </head>
</head>

<body data-sidebar="dark">
    <style>
        .dsd {

            /*font: 700 4em/1 "Oswald", sans-serif;*/
            letter-spacing: 0;
            padding: .25em 0 .325em;
            display: block;
            margin: 0 auto;
            text-shadow: 0 0 80px rgba(255, 255, 255, .5);

            background: url(https://i.ibb.co/RDTnNrT/animated-text-fill.png) repeat-y;
            -webkit-background-clip: text;
            background-clip: text;

            /* Animate Background Image */

            -webkit-text-fill-color: transparent;
            -webkit-animation: aitf 80s linear infinite;

            /* Activate hardware acceleration for smoother animations */

            -webkit-transform: translate3d(0, 0, 0);
            -webkit-backface-visibility: hidden;

        }

        /* Animate Background Image */

        @-webkit-keyframes aitf {
            0% {
                background-position: 0% 50%;
            }

            100% {
                background-position: 100% 50%;
            }
        }
    </style>
    <div >
        <div >
            <div class="page-content">
                <div class="header-bg bg-primary-gradient" style="background:#bcd2e3 fixed !Important">
                    <div class="container">
                        <div class="row">
                            <div class="col-md-12 h2 text-center mb-4 pt-5">
                                Report of ({{ \Carbon\Carbon::today()->subDay()->format('d-M-Y') }})
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="container">
                    <div class="row">
                        <div class="container">
                            <div class="header-bg"
                                style="background:#d9f0ff fixed;margin:13px 2px !important;padding-bottom:10px !important">
                                <div class="row mb-3 pt-2">
                                    <div class="col-md-12" style="display: flex;align-items: baseline;justify-content: center;">
                                        <h2>Today Users</h2>
                                        <div class="pt-4 px-3 text-center"
                                            style="display: flex;align-items: baseline;justify-content: center;">
                                            <div class="metric1 text-center" style="display: inline-block;">
                                                <h1 class="px-4" id="metric1_an"
                                                    style="font-weight: normal; color: #08c; margin: 0; font-size: 30px; line-height: 1.1em">
                                                    <?php if (isset($TodayCount) && !empty($TodayCount)) {
                                                        echo $TodayCount['androidRowCount'];
                                                    } else {
                                                        echo '0';
                                                    } ?></h1>
                                                <h2 class="px-3"
                                                    style="font-weight: normal; color: #ababab; margin: 0; letter-spacing: .15em; text-transform: uppercase; line-height: 1.5em; font-size: 12px">
                                                    Android Users</h2>
                                            </div>
                                            <div class="metric2" style="display: inline-block; margin-left:10px;">
                                                <h2 class="px-1"
                                                    style="font-weight: normal; color: #ababab; margin: 0; letter-spacing: .15em; text-transform: uppercase; line-height: 1.5em; font-size: 22px">
                                                    +</h2>
                                            </div>
                                            <div class="metric2 text-center" style="display: inline-block; margin-left: 10px;">
                                                <h1 class="px-2" id="metric1_io"
                                                    style="font-weight: normal; color:red;margin: 0; font-size: 30px; line-height: 1.1em">
                                                    <?php if (isset($TodayCount) && !empty($TodayCount)) {
                                                        echo $TodayCount['iosRowCount'];
                                                    } else {
                                                        echo '0';
                                                    } ?></h1>
                                                <h2 class="px-1"
                                                    style="font-weight: normal; color: #ababab; margin: 0; letter-spacing: .15em; text-transform: uppercase; line-height: 1.5em; font-size: 12px">
                                                    Apple Users</h2>
                                            </div>
                                            <div class="metric2" style="display: inline-block; margin-left:10px;">
                                                <h2 class="px-1"
                                                    style="font-weight: normal; color: #ababab; margin: 0; letter-spacing: .15em; text-transform: uppercase; line-height: 1.5em; font-size: 22px">
                                                    =</h2>
                                            </div>
                                            <div class="metric2 text-center" style="display: inline-block; margin-left: 10px;">
                                                <h1 class="px-2" id="metric1_io_an"
                                                    style="font-weight: normal; color:#390;  margin: 0; font-size: 30px; line-height: 1.1em">
                                                    <?php if ((isset($TodayCount) && !empty($TodayCount)) || (isset($TodayCount) && !empty($TodayCount))) {
                                                        echo $TodayCount['totalRowCount'];
                                                    } else {
                                                        echo '0';
                                                    } ?></h1>
                                                <h2 class="px-1"
                                                    style="font-weight: normal; color: #ababab; margin: 0; letter-spacing: .15em; text-transform: uppercase; line-height: 1.5em; font-size: 12px">
                                                    Total Users</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="container-fluid">
                            <div class="header-bg"
                                style="background:#d9f0ff fixed;margin:13px 2px !important;padding-bottom:10px !important">
                                <div class="row mb-3 pt-2">
                                    <div class="col-md-12" style="display: flex;align-items: baseline;justify-content: center;">
                                        <h2>Active Users</h2>
                                        <div class="pt-4 px-3 text-center"
                                            style="display: flex;align-items: baseline;justify-content: center;">
                                            <div class="metric1 text-center" style="display: inline-block;">
                                                <h1 class="px-4" id="metric1_act_an"
                                                    style="font-weight: normal; color: #08c; margin: 0; font-size: 30px; line-height: 1.1em">
                                                    <?php if (isset($TodayCount) && !empty($TodayCount)) {
                                                        echo $TodayCount['androidActiveRowCount'];
                                                    } else {
                                                        echo '0';
                                                    } ?></h1>
                                                <h2 class="px-3"
                                                    style="font-weight: normal; color: #ababab; margin: 0; letter-spacing: .15em; text-transform: uppercase; line-height: 1.5em; font-size: 12px">
                                                    Android Users</h2>
                                            </div>
                                            <div class="metric2" style="display: inline-block; margin-left:10px;">
                                                <h2 class="px-1"
                                                    style="font-weight: normal; color: #ababab; margin: 0; letter-spacing: .15em; text-transform: uppercase; line-height: 1.5em; font-size: 22px">
                                                    +</h2>
                                            </div>
                                            <div class="metric2 text-center" style="display: inline-block; margin-left: 10px;">
                                                <h1 class="px-2" id="metric1_act_io"
                                                    style="font-weight: normal; color:red;margin: 0; font-size: 30px; line-height: 1.1em">
                                                    <?php if (isset($TodayCount) && !empty($TodayCount)) {
                                                        echo $TodayCount['iosActiveRowCount'];
                                                    } else {
                                                        echo '0';
                                                    } ?></h1>
                                                <h2 class="px-1"
                                                    style="font-weight: normal; color: #ababab; margin: 0; letter-spacing: .15em; text-transform: uppercase; line-height: 1.5em; font-size: 12px">
                                                    Apple Users</h2>
                                            </div>
                                            <div class="metric2" style="display: inline-block; margin-left:10px;">
                                                <h2 class="px-1"
                                                    style="font-weight: normal; color: #ababab; margin: 0; letter-spacing: .15em; text-transform: uppercase; line-height: 1.5em; font-size: 22px">
                                                    =</h2>
                                            </div>
                                            <div class="metric2 text-center" style="display: inline-block; margin-left: 10px;">
                                                <h1 class="px-2" id="metric1_act_io_an"
                                                    style="font-weight: normal; color:#390;  margin: 0; font-size: 30px; line-height: 1.1em">
                                                    <?php if ((isset($TodayCount) && !empty($TodayCount)) || (isset($TodayCount) && !empty($TodayCount))) {
                                                        echo $TodayCount['TotalActiveUsersTodayCount'];
                                                    } else {
                                                        echo '0';
                                                    } ?></h1>
                                                <h2 class="px-1"
                                                    style="font-weight: normal; color: #ababab; margin: 0; letter-spacing: .15em; text-transform: uppercase; line-height: 1.5em; font-size: 12px">
                                                    Total Active Users</h2>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="container mb-3">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="card-header">
                                                <div class="card-head-row">
                                                    <h3 class="card-header text-dark">
                                                        Most Downloaded Apps as of
                                                        <span class="text-success">{{ \Carbon\Carbon::today()->subDay()->format('d-M-Y') }}</span>
                                                    </h3>
                                                </div>
                                            </div>
                    
                                            <div class="table-responsive mt-3">
                                                <table id="apphighDownloadsTable" class="table table-bordered table-striped" style="width:100%">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>App Name</th>
                                                            <th>Today's Downloads</th>
                                                            <th>Downloads (Last 7 Days)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="text-center">
                                                        @foreach ($downloadStats as $app)
                                                            <tr>
                                                                <td>{{ $app->app_name }}</td>
                                                                <td>{{ $app->todays_downloads }}</td>
                                                                <td>{{ $app->average_downloads_last_7_days }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="container mb-3">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="card-header">
                                                <div class="card-head-row">
                                                    <h3 class="card-header text-dark">Most Downgraded Apps as of  <span class="text-danger">{{ \Carbon\Carbon::today()->subDay()->format('d-M-Y') }}</span></h3>
                                                </div>
                                            </div>
                                            
                                            <div class="table-responsive-xl mt-3 ">
                                                <table id="apphighDowngradeTable" class="table table-bordered table-striped" style="width:100%">
                                                    <thead>
                                                        <tr class="text-center">
                                                            <th>App Name</th>
                                                            <th>Today's Downloads</th>
                                                            <th>Average Downloads (Last 7 Days)</th>
                                                            <th>Daily Downloads (Last 7 Days)</th>
                                                            <th>Ratio</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="body text-center">
                                                        @foreach ($apphighDowngradeTable as $app)
                                                            <tr>
                                                                <td>{{ $app->app_name }}</td>
                                                                <td>{{ $app->todays_downloads }}</td>
                                                                <td>
                                                                    Total: {{ $app->total_downloads_last_7_days }}<br>
                                                                    Average: {{ round($app->average_downloads_last_7_days, 2) }}
                                                                </td>
                                                                <td>
                                                                    <div class="space-c">
                                                                        @foreach ($app->downloads as $date => $data)
                                                                            @php
                                                                                $arrow = $data['trend'] === 'up' ? "⬆️" : 
                                                                                        ($data['trend'] === 'down' ? "⬇️" : 
                                                                                        '➡️');
                                                                            @endphp
                                                                            <span style="color: {{ $data['trend'] == 'up' ? 'green' : ($data['trend'] == 'down' ? 'red' : 'black') }}">{{ $date }}: {{ $data['count'] }} {!! $arrow !!} 
                                                                                @if ($data['percentage_change'] != 0) 
                                                                                    ({{ $data['percentage_change'] }}%) 
                                                                                @endif
                                                                            </span><br>
                                                                        @endforeach
                                                                    </div>
                                                                </td>
                                                                <td>{{ $app->ratio }}%</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="container mb-3">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                        <div class="text-center">
                                            <a href="https://bibledev.olybible.com/admin/daily_reports" class="btn btn-sm btn-primary text-light fw-bold" >View More</a>
                                        </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" crossorigin="anonymous"
        referrerpolicy="no-referrer"></script>

    <!-- Vendor JS Files -->
    <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js') }}"></script>

    <script src="{{ asset('assets/assets/libs/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/assets/libs/metismenu/metisMenu.min.js') }}"></script>
    <script src="{{ asset('assets/assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/assets/libs/node-waves/waves.min.js') }}"></script>

    <script src="{{ asset('assets/assets/js/app.js') }}"></script>

    <script src="https://olybible.com/BibleReplications/dev/v2/admin/assets/js/core/jquery.3.2.1.min.js"></script>

</body>

</html>
