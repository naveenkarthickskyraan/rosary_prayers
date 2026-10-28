<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <title>@yield('title', 'Bible Character Explorer') - Admin</title>
  <meta content="" name="description">
  <meta content="" name="keywords">

  <!-- Favicons -->
  <link rel="shortcut icon" href="{{ asset('assets/assets/images/favicon.ico')}}">

  <link href="https://fonts.gstatic.com" rel="preconnect">
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Nunito:300,300i,400,400i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i" rel="stylesheet">

  <!-- DataTables -->
  <link href="{{ asset('assets/assets/libs/datatables.net-bs4/css/dataTables.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/assets/libs/datatables.net-buttons-bs4/css/buttons.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/assets/libs/datatables.net-responsive-bs4/css/responsive.bootstrap4.min.css')}}" rel="stylesheet" type="text/css" />

  <!-- Bootstrap Css -->
  <link href="{{ asset('assets/assets/css/bootstrap.min.css')}}" id="bootstrap-style" rel="stylesheet" type="text/css" />

  <!-- Icons Css -->
  <link href="{{ asset('assets/assets/css/icons.min.css')}}" rel="stylesheet" type="text/css" />

  <!-- App Css-->
  <link href="{{ asset('assets/assets/css/app.min.css')}}" id="app-style" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/vendor/bootstrap-icons/bootstrap-icons.css')}}" rel="stylesheet">

  <!-- Select2 -->
  <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="{{ asset('assets/vendors/select2-bootstrap-theme/select2-bootstrap.min.css')}}">

  <!-- Material Design Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.0.96/css/materialdesignicons.min.css">

  <!-- Toastr -->
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <!-- Date Range Picker -->
  <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />

  <!-- Highcharts -->
  <link rel="stylesheet" href="https://code.highcharts.com/css/highcharts.css">

  @stack('styles')
</head>

<body data-sidebar="dark">

  <div id="layout-wrapper">
    @include('admin.navbar')

    <!-- ========== Left Sidebar Start ========== -->
    @include('admin.sidebar')

    <!-- ========== Left Sidebar End ========== -->

    <!-- ========== Main Content Start ========== -->
    <div class="main-content">
      <div class="page-content">
        <div class="container-fluid">
          @yield('content')
        </div>
      </div>
    </div>
    <!-- ========== Main Content End ========== -->

    <!-- Footer -->
    <footer class="footer">
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-6">
            <script>document.write(new Date().getFullYear())</script> © Bible Character Explorer
          </div>
          <div class="col-sm-6">
            <div class="text-sm-end d-none d-sm-block">
              Design & Development by Bible Character Explorer Team
            </div>
          </div>
        </div>
      </div>
    </footer>

  </div>
  <!-- END layout-wrapper -->

  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <script src="{{ asset('assets/assets/libs/jquery/jquery.min.js')}}"></script>

  <!-- Bootstrap JS -->
  <script src="{{ asset('assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>

  <!-- MetisMenu -->
  <script src="{{ asset('assets/assets/libs/metismenu/metisMenu.min.js')}}"></script>

  <!-- SimpleBar -->
  <script src="{{ asset('assets/assets/libs/simplebar/simplebar.min.js')}}"></script>

  <!-- Waves -->
  <script src="{{ asset('assets/assets/libs/node-waves/waves.min.js')}}"></script>

  <!-- App JS -->
  <script src="{{ asset('assets/assets/js/app.js')}}"></script>

  <!-- Toastr -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>

  <!-- Select2 -->
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

  <!-- Chart.js -->
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-moment@^1"></script>
  <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-zoom"></script>

  <!-- Date Range Picker -->
  <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
  <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js" defer></script>

  <!-- Highcharts -->
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/accessibility.js"></script>
  <script src="https://code.highcharts.com/modules/pattern-fill.js"></script>
  <script src="https://code.highcharts.com/modules/histogram-bellcurve.js"></script>

  <!-- DataTables -->
  <script src="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.js"></script>

  <!-- CKEditor (if needed) -->
  <script src="{{ asset('assets/editor_ckediter/ckeditor.js')}}"></script>
  <script src="{{ asset('assets/editor_ckediter/adapters/jquery.js')}}"></script>

  @stack('scripts')

  @if(session('success'))
    <script>
      toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      };
      toastr.success("{{ session('success') }}");
    </script>
  @endif

  @if(session('error'))
    <script>
      toastr.options = {
        "closeButton": true,
        "debug": false,
        "newestOnTop": false,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "preventDuplicates": false,
        "onclick": null,
        "showDuration": "300",
        "hideDuration": "1000",
        "timeOut": "5000",
        "extendedTimeOut": "1000",
        "showEasing": "swing",
        "hideEasing": "linear",
        "showMethod": "fadeIn",
        "hideMethod": "fadeOut"
      };
      toastr.error("{{ session('error') }}");
    </script>
  @endif

</body>

</html>