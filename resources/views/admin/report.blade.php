<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>Bible Character Dashboard</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <link href="https://fonts.gstatic.com" rel="preconnect">
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
        rel="stylesheet">

    <link rel="shortcut icon" href="{{ asset('assets/assets/images/favicon.ico') }}">
    
    <link href="{{ asset('assets/datatable_assets/simple-datatables/style.css')}}" rel="stylesheet">

    <link href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css" rel="stylesheet">
    
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet"
        type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.0.96/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="https://code.highcharts.com/css/highcharts.css">
    <style>
        /* Customize page length control */
        .table>:not(caption)>*>* {
            border-bottom-width: 0px !important;
        }
        
        .highcharts-credits {
            display: none !important;
        }

        .highcharts-yaxis text {
            display: none !important;
        }

        .highcharts-legend-item {
            display: none !important;
        }

        .highcharts-axis-line {
            display: none !important;
        }

        .highcharts-grid-line {
            display: none !important;
        }

        .highcharts-background {
            display: none !important;
        }

        .highcharts-title {
            display: none !important;
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
    <style>
    
    .loader, .is-loading:after {
        display: block;
        width: 1.5rem;
        height: 1.5rem;
        background: transparent;
        border: 3px solid #6861CE;
        border-bottom-color: transparent;
        border-radius: 50%;
        animation: 1s spin linear infinite; }
        
    .loader-lg, .is-loading-lg:after {
        width: 2rem;
        height: 2rem;
        border-width: 5px; }
        
    .loader-sm, .is-loading-sm:after {
        width: 1rem;
        height: 1rem;
        border-width: 2px; }
    
    .is-loading {
        position: relative;
        color: transparent !important; }
        .is-loading > * {
        opacity: 0.2 !important; }
    .is-loading:after {
        position: absolute;
        top: calc(50% - 1.5rem/2);
        left: calc(50% - 1.5rem/2);
        content: ''; }
    
    .is-loading-lg:after {
        top: calc(50% - 2rem/2);
        left: calc(50% - 2rem/2); }
        
    .is-loading-sm:after {
        top: calc(50% - 1rem/2);
        left: calc(50% - 1rem/2); }
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
    
    <!-- Loader -->
    <div id="preloader">
        <div id="status">
            <div class="spinner"></div>
        </div>
    </div>

    <div id="layout-wrapper">
        @include('admin.navbar')
        @include('admin.sidebar')
        <div class="main-content is-loading">
            <div class="page-content">
                <div class="header-bg bg-primary-gradient" style="background:#bcd2e3 fixed !Important">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-12 h2 text-center mb-4 pt-5">
                                Report of ({{ \Carbon\Carbon::today()->subDays(1)->format('d-M-Y') }})
                            </div>
                        </div>
                    </div>
                </div>

                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="card-body text-muted">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5><b>New Apps </b></h5>
                                            <p class="text-muted">Today Added Apps</p>
                                        </div>
                                        <h3 class="text-info mt-2 fw-bold" id="app_list">
                                            {{ $todayAppCreated == 0 ? 'Loading..' : $todayAppCreated }}</h3>
                                    </div>
                                    <div class="progress progress-sm">
                                    </div>

                                    <div class="d-flex justify-content-between mt-2">
                                        <div class="d-flex justify-content-between mt-2">
                                            <p class="text-muted mb-0"><a
                                                    href="{{ url('admin/apps_analytics') }}">More
                                                    Info</a></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="mb-2 card-body text-muted">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5><b>Apps </b></h5>
                                            <p class="text-muted">Apps Total</p>
                                        </div>
                                        <h3 class="text-purple mt-2 fw-bold" id="all">
                                            {{ $totalApps == 0 ? 'Loading..' : formatIndianNumberWithDecimal($totalApps) }}</h3>
                                    </div>
                                    <div class="progress progress-sm">
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <p class="text-muted mb-0"><a href="{{ url('admin/word') }}">More Info</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            function formatIndianNumberWithDecimal($number)
                            {
                                // Convert the number to a string
                                $number_str = strval($number);

                                // Separate the integer and decimal parts
                                [$integer_part, $decimal_part] = explode('.', $number_str . '.0');

                                // Get the length of the integer part
                                $length = strlen($integer_part);

                                // Initialize an empty string to store the formatted number
                                $formatted_number = '';

                                // Iterate through each digit in reverse order
                                for ($i = $length - 1, $j = 1; $i >= 0; $i--, $j++) {
                                    // Add a comma after every 2 digits except for the last two digits
                                    $formatted_number =
                                        $integer_part[$i] .
                                        ($j % 2 == 0 && $i != $length - 2 ? ',' : '') .
                                        $formatted_number;
                                }

                                // Combine the formatted integer part with the decimal part
                                $formatted_number = rtrim($formatted_number, ',');

                                return $formatted_number;
                            }
                        @endphp
                        <div class="col-md-6 col-xl-3">
                            <div class="card">
                                <div class="mb-2 card-body text-muted">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5><b>Total Users </b></h5>
                                            <p class="text-muted">Users of all apps</p>
                                        </div>
                                        <h3 class="text-primary mt-2 fw-bold" id="totalRowCount">
                                            {{ $TotalRowCount == 0 ? 'Loading..' : formatIndianNumberWithDecimal($TotalRowCount) }}
                                        </h3>
                                    </div>
                                    <div class="progress progress-sm">
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <p class="text-muted mb-0"><a href="{{ url('admin/superadmin') }}">More
                                                Info</a></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 col-xl-3">
                            <div class="card"
                                style="color: #ffffff !important;background: #113a65;border-radius: 5px;">
                                <div class="mb-2 card-body text-muted"
                                    style="background: #113a65;color: #ffffff !important;">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h5><b>New Users</b></h5>
                                            <p class="text-muted" style="color: #ffffff !important;">All apps as a new
                                                user today.</p>
                                        </div>
                                        <h3 class="dsd text-primary mt-2 fw-bold" id="admin">
                                            {{ $TotalUsersTodayCount == 0 ? 'Loading..' : $TotalUsersTodayCount }}</h3>
                                    </div>
                                    <div class="progress progress-sm">
                                    </div>
                                    <div class="d-flex justify-content-between mt-2">
                                        <p class="text-muted mb-0"><a href="{{ url('admin/superadmin') }}">More
                                                Info</a></p>
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
                                        <h2>New Users</h2>
                                        <div class="pt-4 px-3 text-center"
                                            style="display: flex;align-items: baseline;justify-content: center;">
                                            <div class="metric1" style="display: inline-block;">
                                                <h1 class="px-4" id="metric1_an"
                                                    style="font-weight: normal; color: #08c; margin: 0; font-size: 30px; line-height: 1.1em">
                                                    <?php if (isset($androidcount) && !empty($androidcount)) {
                                                        echo array_sum($androidcount);
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
                                            <div class="metric2" style="display: inline-block; margin-left: 10px;">
                                                <h1 class="px-2" id="metric1_io"
                                                    style="font-weight: normal; color:red;margin: 0; font-size: 30px; line-height: 1.1em">
                                                    <?php if (isset($ioscount) && !empty($ioscount)) {
                                                        echo array_sum($ioscount);
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
                                            <div class="metric2" style="display: inline-block; margin-left: 10px;">
                                                <h1 class="px-2" id="metric1_io_an"
                                                    style="font-weight: normal; color:#390;  margin: 0; font-size: 30px; line-height: 1.1em">
                                                    <?php if ((isset($ioscount) && !empty($ioscount)) || (isset($androidcount) && !empty($androidcount))) {
                                                        echo array_sum($androidcount) + array_sum($ioscount);
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
                        <!--<div class="container-fluid mb-3">-->
                        <!--    <div class="row mb-3">-->
                        <!--        <div class="col-md-12">-->
                        <!--            <div class="card">-->
                        <!--                <div class="card-body">-->

                        <!--                    <div class="card-header">-->
                        <!--                        <div class="card-head-row">-->
                        <!--                            <h4 class="card-title text-dark">Charts Statistics</h4>-->
                        <!--                        </div>-->
                        <!--                    </div>-->
                        <!--                    <br>-->
                        <!--                    <div id="loader_id">-->
                        <!--                    </div>-->
                        <!--                    <div id="chart_container" class="chart-container mb-3">-->
                        <!--                        <canvas class="ct-chart ct-golden-section" id="gained-chart"-->
                        <!--                            height="400"></canvas>-->
                        <!--                        <section class="range-slider" style="display:none">-->
                        <!--                            <span class="rangeValues"></span>-->
                        <!--                            <input class="form-control" value="0" min="0"-->
                        <!--                                max="" type="range" />-->
                        <!--                            <input class="form-control" value="" min="0"-->
                        <!--                                max="" type="range" />-->
                        <!--                        </section>-->
                        <!--                    </div>-->

                        <!--                    <div id="loading_container"-->
                        <!--                        style="text-align: center;margin-top: 50px;margin-bottom: 50px;">-->
                        <!--                        <h1-->
                        <!--                            style="font-weight: normal; color: #08c; margin: 0; font-size: 30px; line-height: 1.1em">-->
                        <!--                            <i class="fa fa-spinner fa-spin"></i>-->
                        <!--                        </h1>-->
                        <!--                    </div>-->

                        <!--                </div>-->
                        <!--            </div>-->
                        <!--        </div>-->
                        <!--    </div>-->
                        <!--</div>-->
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
                                            <div class="metric1" style="display: inline-block;">
                                                <h1 class="px-4" id="metric1_act_an"
                                                    style="font-weight: normal; color: #08c; margin: 0; font-size: 30px; line-height: 1.1em">
                                                    <?php if (isset($androidActiveRowCount) && !empty($androidActiveRowCount)) {
                                                        echo array_sum($androidActiveRowCount);
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
                                            <div class="metric2" style="display: inline-block; margin-left: 10px;">
                                                <h1 class="px-2" id="metric1_act_io"
                                                    style="font-weight: normal; color:red;margin: 0; font-size: 30px; line-height: 1.1em">
                                                    <?php if (isset($iosActiveRowCount) && !empty($iosActiveRowCount)) {
                                                        echo array_sum($iosActiveRowCount);
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
                                            <div class="metric2" style="display: inline-block; margin-left: 10px;">
                                                <h1 class="px-2" id="metric1_act_io_an"
                                                    style="font-weight: normal; color:#390;  margin: 0; font-size: 30px; line-height: 1.1em">
                                                    <?php if ((isset($iosActiveRowCount) && !empty($iosActiveRowCount)) || (isset($androidActiveRowCount) && !empty($androidActiveRowCount))) {
                                                        echo array_sum($androidActiveRowCount) + array_sum($iosActiveRowCount);
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
                        <div class="container-fluid mb-3">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="card-header">
                                                <div class="card-head-row">
                                                    <h3 class="card-header text-dark">Most Downloaded Apps as of  <span class="text-success">{{ \Carbon\Carbon::today()->subDay()->format('d-M-Y') }}</span></h3>
                                                </div>
                                            </div>
                                            
                                            <div class="table-responsive mt-3">
                                                <table id="apphighDownloadsTable" class="display" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>App Name</th>
                                                            <th>Package Name or Bundle ID</th>
                                                            <th>Today's Downloads</th>
                                                            <th>Downloads (Last 7 Days)</th>
                                                            <th>Created Date</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="container-fluid mb-3">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="card-header">
                                                <div class="card-head-row">
                                                    <h3 class="card-header text-dark">Performance Comparison: Yesterday vs. Today - <span class="text-success">{{ \Carbon\Carbon::today()->subDay()->format('d-M-Y') }}</span></h3>
                                                </div>
                                            </div>
                                            
                                            <div class="table-responsive mt-3">
                                                <table id="appYesTodTable" class="display" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>App Name</th>
                                                            <th>Package Name or Bundle ID</th>
                                                            <th>Today's Downloads</th>
                                                            <th>Average Downloads (Last 7 Days)</th>
                                                            <th>Daily Downloads (Last 7 Days)</th>
                                                            <th>Ratio</th>
                                                            <th>Created Date</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="container-fluid mb-3">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="card-header">
                                                <div class="card-head-row">
                                                    <h3 class="card-header text-dark">Most Downgraded Apps as of  <span class="text-danger">{{ \Carbon\Carbon::today()->subDay()->format('d-M-Y') }}</span></h3>
                                                </div>
                                            </div>
                                            
                                            <div class="table-responsive mt-3">
                                                <table id="apphighDowngradeTable" class="display" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>App Name</th>
                                                            <th>Package Name or Bundle ID</th>
                                                            <th>Today's Downloads</th>
                                                            <th>Average Downloads (Last 7 Days)</th>
                                                            <th>Daily Downloads (Last 7 Days)</th>
                                                            <th>Ratio</th>
                                                            <th>Created Date</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="container-fluid mb-3">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">

                                            <div class="card-header">
                                                <div class="card-head-row">
                                                    <h3 class="card-header text-dark">Most Upgrade Apps as of  <span class="text-success">{{ \Carbon\Carbon::today()->subDay()->format('d-M-Y') }}</span></h3>
                                                </div>
                                            </div>
                                            
                                            <div class="table-responsive mt-3">
                                                <table id="apphigUpgradeTable" class="display" style="width:100%">
                                                    <thead>
                                                        <tr>
                                                            <th>ID</th>
                                                            <th>App Name</th>
                                                            <th>Package Name or Bundle ID</th>
                                                            <th>Today's Downloads</th>
                                                            <th>Average Downloads (Last 7 Days)</th>
                                                            <th>Daily Downloads (Last 7 Days)</th>
                                                            <th>Ratio</th>
                                                            <th>Created Date</th>
                                                        </tr>
                                                    </thead>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- end row -->
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

        </div>
        <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        ©
                        <script>
                            document.write(new Date().getFullYear())
                        </script> Fonik<span class="d-none d-sm-inline-block"> -
                            Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand.</span>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <div class="rightbar-overlay"></div>

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

    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script src="https://olybible.com/BibleReplications/dev/v2/admin/assets/js/core/jquery.3.2.1.min.js"></script>

    <script src="{{ asset('assets/editor_ckediter/ckeditor.js') }}"></script>
    <script src="{{ asset('assets/editor_ckediter/adapters/jquery.js') }}"></script>
    <script src="{{ asset('assets/editor_ckediter/styles.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>


    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@^1"></script>

    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>

    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

    <script src="https://code.highcharts.com/highcharts.js"></script>

    <!-- jQuery library -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.highcharts.com/modules/accessibility.js"></script>
    <script src="https://code.highcharts.com/modules/pattern-fill.js"></script>

    <script src="https://code.highcharts.com/modules/histogram-bellcurve.js"></script>
    <script src="{{ asset('assets/datatable_assets/simple-datatables/simple-datatables.js')}}"></script>

    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function() {
            toastr.options = {
                "closeButton": true,
                "progressBar": true
            }
            @if (Session::has('error'))
                toastr.error("{{ Session::get('error') }}");
            @elseif (Session::has('message'))
                toastr.success("{{ Session::get('message') }}");
            @endif

            if("{{$todayAppCreated}}" == 0){
                $.ajax({
                    type: "get",
                    dataType: "json",
                    url: "{{route('today_count.index')}}",
                    data: {'_token': $('meta[name="_token"]').attr('content')},
                    success: function(data){
                        var app_list = data.app_list;
                        var totalRowCount = data.totalRowCount;
                        var admin = data.admin;
                        var all = data.all;
                        var androidRowCount = data.androidRowCount;
                        var androidActiveRowCount = data.androidActiveRowCount;
                        var iosRowCount = data.iosRowCount;
                        var iosActiveRowCount = data.iosActiveRowCount;
                        var TotalActiveUsersTodayCount = data.TotalActiveUsersTodayCount;

                        let result_app_list = formatIndianNumber(app_list);
                        let result_totalRowCount = formatIndianNumber(totalRowCount);
                        let result_admin = formatIndianNumber(admin);
                        let result_all = formatIndianNumber(all);
                        let metric1_an = formatIndianNumber(androidRowCount);
                        let metric1_io = formatIndianNumber(iosRowCount);
                        let metric1_io_an = formatIndianNumber(all);
                        
                        let metric1_act_an = formatIndianNumber(androidActiveRowCount);
                        let metric1_act_io = formatIndianNumber(iosActiveRowCount);
                        let metric1_act_io_an = formatIndianNumber(TotalActiveUsersTodayCount);
                        
                        $('#app_list').text(result_app_list);
                        $('#totalRowCount').text(result_totalRowCount);
                        $('#admin').text(result_admin);
                        $('#all').text(result_all);
                        $('#metric1_an').text(metric1_an);
                        $('#metric1_io').text(metric1_io);
                        $('#metric1_io_an').text(result_admin);
                        $('#metric1_act_an').text(metric1_act_an);
                        $('#metric1_act_io').text(metric1_act_io);
                        $('#metric1_act_io_an').text(metric1_act_io_an);
                        $('.main-content').removeClass('is-loading');
                    }
                });
            }
        });
        
    
        // setInterval(function() {
        //     $.ajax({
        //         type: "get",
        //         dataType: "json",
        //         url: "{{route('today_count.index')}}",
        //         data: {'_token': $('meta[name="_token"]').attr('content')},
        //         success: function(data){
        //             var app_list = data.app_list;
        //             var totalRowCount = data.totalRowCount;
        //             var admin = data.admin;
        //             var all = data.all;
        //             var androidRowCount = data.androidRowCount;
        //             var iosRowCount = data.iosRowCount;

        //             let result_app_list = formatIndianNumber(app_list);
        //             let result_totalRowCount = formatIndianNumber(totalRowCount);
        //             let result_admin = formatIndianNumber(admin);
        //             let result_all = formatIndianNumber(all);
        //             let metric1_an = formatIndianNumber(androidRowCount);
        //             let metric1_io = formatIndianNumber(iosRowCount);
        //             let metric1_io_an = formatIndianNumber(all);
                    
        //             $('#app_list').text(result_app_list);
        //             $('#totalRowCount').text(result_totalRowCount);
        //             $('#admin').text(result_admin);
        //             $('#all').text(result_all);
        //             $('#metric1_an').text(metric1_an);
        //             $('#metric1_io').text(metric1_io);
        //             $('#metric1_io_an').text(result_admin);
        //         }
        //     });
        // }, 10000);
        
        function formatIndianNumber(number) {
            // Convert the number to a string
            let numberStr = number.toString();

            // Separate the integer and decimal parts
            let [integerPart] = numberStr.split('.');

            // Get the length of the integer part
            let length = integerPart.length;

            // Initialize an empty string to store the formatted number
            let formattedNumber = '';

            // Iterate through each digit in reverse order
            for (let i = length - 1, j = 1; i >= 0; i--, j++) {
                // Add a comma after every 2 digits except for the last two digits
                formattedNumber = integerPart[i] + ((j % 2 === 0 && i !== length - 2) ? ',' : '') + formattedNumber;
            }

            // Combine the formatted integer part
            formattedNumber = formattedNumber.replace(/,$/, '');

            return formattedNumber;
        }
        
    </script>
    
    <script>
        $(document).ready(function () {
            $('#apphighDownloadsTable').DataTable({
                processing: true,
                serverSide: true,
                stateSave:true,
                paging: false,
                ordering: true,
                autoWidth: true,
                info: false,
                order: [
                    [3, 'desc'] // Order by the 'ratio' column in descending order
                ],
                ajax: '{{ route("datatable_index.index") }}', // Replace with your route
                columns: [
                    { data: 'app_id', name: 'app_id' },
                    { data: 'app_name', name: 'app_name' },
                    { data: 'app_type_android', name: 'app_type_android' },
                    { data: 'todays_downloads', name: 'todays_downloads' },
                    { data: 'average_downloads_last_7_days', name: 'average_downloads_last_7_days' },
                    { data: 'created_at', name: 'created_at' },
                ],
                language: {
                    'paginate': {
                        'previous': '<<',
                        'next': '>>'
                    }
                },
            });
            
            $('#apphighDowngradeTable').DataTable({
                processing: true,
                serverSide: true,
                // stateSave:true,
                scrollX:true,
                paging: false,
                ordering: true,
                autoWidth: true,
                info: false,
                order: [
                    [6, 'desc'] // Order by the 'ratio' column in descending order
                ],
                ajax: '{{ route("high_downgrade.index") }}', // Replace with your route
                columns: [
                    { data: 'app_id', name: 'app_id' },
                    { data: 'app_name', name: 'app_name' ,width: '50px', },
                    { data: 'app_type_android', name: 'app_type_android',width: '50px', },
                    { data: 'todays_downloads', name: 'todays_downloads',width: '50px', },
                    { data: 'average_downloads_last_7_days', name: 'average_downloads_last_7_days', orderable: false, searchable: false},
                    { data: 'daily_downloads', name: 'daily_downloads', orderable: false, searchable: false  },
                    { data: 'ratio', name: 'ratio' },
                    { data: 'created_at', name: 'created_at',width: '50px', },
                ],
                language: {
                    'paginate': {
                        'previous': '<<',
                        'next': '>>'
                    }
                },
            });
            
            $('#apphigUpgradeTable').DataTable({
                processing: true,
                serverSide: true,
                // stateSave:true,
                scrollX:true,
                paging: false,
                ordering: true,
                autoWidth: true,
                info: false,
                order: [
                    [6, 'desc'] // Order by the 'ratio' column in descending order
                ],
                ajax: '{{ route("apphigUpgradeTable.index") }}', // Replace with your route
                columns: [
                    { data: 'app_id', name: 'app_id' },
                    { data: 'app_name', name: 'app_name' ,width: '50px', },
                    { data: 'app_type_android', name: 'app_type_android',width: '50px', },
                    { data: 'todays_downloads', name: 'todays_downloads',width: '50px', },
                    { data: 'average_downloads_last_7_days', name: 'average_downloads_last_7_days', orderable: false, searchable: false},
                    { data: 'daily_downloads', name: 'daily_downloads', orderable: false, searchable: false  },
                    { data: 'ratio', name: 'ratio' },
                    { data: 'created_at', name: 'created_at',width: '50px', },
                ],
                language: {
                    'paginate': {
                        'previous': '<<',
                        'next': '>>'
                    }
                },
            });
            
            $('#appYesTodTable').DataTable({
                processing: true,
                serverSide: true,
                // stateSave:true,
                paging: false,
                ordering: true,
                autoWidth: true,
                info: false,
                order: [
                    [6, 'desc'] // Order by the 'ratio' column in descending order
                ],
                ajax: '{{ route("appYesTodTable.index") }}', // Replace with your route
                columns: [
                    { data: 'app_id', name: 'app_id' },
                    { data: 'app_name', name: 'app_name' ,width: '50px', },
                    { data: 'app_type_android', name: 'app_type_android',width: '50px', },
                    { data: 'todays_downloads', name: 'todays_downloads',width: '50px', },
                    { data: 'average_downloads_last_7_days', name: 'average_downloads_last_7_days', orderable: false, searchable: false},
                    { data: 'daily_downloads', name: 'daily_downloads', orderable: false, searchable: false  },
                    { data: 'ratio', name: 'ratio' },
                    { data: 'created_at', name: 'created_at',width: '50px', },
                ],
                language: {
                    'paginate': {
                        'previous': '<<',
                        'next': '>>'
                    }
                },
            });
        });
    </script>
</body>

</html>
