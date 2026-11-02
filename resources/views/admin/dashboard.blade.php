<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>Rosary Prayers</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  {{-- <link href="{{ asset('assets/img/Proposed.jpg')}}" rel="icon">
  <link href="{{ asset('assets/img/apple-touch-icon.png') }}" rel="apple-touch-icon"> --}}

  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  {{-- <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css')}}" rel="stylesheet"> --}}
  <link rel="shortcut icon" href="{{ asset('assets/assets/images/favicon.ico')}}">

  <!-- DataTables -->
  <link href="{{ asset('assets/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet"
      type="text/css" />

  <!-- Responsive datatable examples -->
  <link href="{{ asset('assets/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet"
      type="text/css" />
    <!-- Bootstrap Css -->
  <link href="{{ asset('assets/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />

  <!-- Icons Css -->
  <link href="{{ asset('assets/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />
  <!-- App Css-->
  <link href="{{ asset('assets/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="{{ asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css')}}">

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.0.96/css/materialdesignicons.min.css">

  <link rel="stylesheet" href="https://code.highcharts.com/css/highcharts.css">
  

 
</head>


<body data-sidebar="dark">

 
  

  <div id="layout-wrapper">
    @include('admin.navbar')
    <!-- ========== Left Sidebar Start ========== -->
    @include('admin.sidebar')
    
        <div class="container">
            <h1>Welcome</h1>
        </div>
    
 

      
   

  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>

  <script src="{{ asset('assets/assets/libs/jquery/jquery.min.js')}}"></script>
  <script src="{{ asset('assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{ asset('assets/assets/libs/metismenu/metisMenu.min.js')}}"></script>
  <script src="{{ asset('assets/assets/libs/simplebar/simplebar.min.js')}}"></script>
  <script src="{{ asset('assets/assets/libs/node-waves/waves.min.js')}}"></script>

  <script src="{{ asset('assets/assets/js/app.js')}}"></script>

  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="https://olybible.com/BibleReplications/dev/v2/admin/assets/js/core/jquery.3.2.1.min.js"></script>

  <script src="{{ asset('assets/editor_ckediter/ckeditor.js')}}"></script>
  <script src="{{ asset('assets/editor_ckediter/adapters/jquery.js')}}"></script>
  <script src="{{ asset('assets/editor_ckediter/styles.js')}}"></script>
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
  {{-- <script src="https://code.highcharts.com/modules/exporting.js"></script>
  <!-- optional -->
  <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>
  <script src="https://code.highcharts.com/modules/export-data.js"></script> --}}
  <script src="https://code.highcharts.com/modules/histogram-bellcurve.js"></script>
  <script src="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.js"></script>



  </body>
</html>
