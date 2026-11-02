<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

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

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.0.96/css/materialdesignicons.min.css">
</head>


<body data-sidebar="dark">


  <!-- Loader -->
  <div id="preloader">
      <div id="status">
          <div class="spinner"></div>
      </div>
  </div>
  
  <div id="layout-wrapper">
    <div class="account-pages my-5 pt-5">
      <div class="container">
        <div class="row justify-content-center">
          <div class="col-md-8 col-lg-6 col-xl-5">
            <div class="card">
              <div class="card-body">
                <div class="ex-page-content text-center">
                  <h1 class="">404!</h1>
                  <h4 class="">Sorry, page not found</h4>
                  <br>
                  <a class="btn btn-info mb-4 waves-effect waves-light" href="{{url('admin/dashboard')}}"><i
                          class="mdi mdi-home"></i> Back to Dashboard</a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="rightbar-overlay"></div>
      
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

  <!-- Vendor JS Files -->
  <script src="{{ asset('assets/vendor/apexcharts/apexcharts.min.js')}}"></script>
  
  <script src="{{ asset('assets/assets/libs/jquery/jquery.min.js')}}"></script>
  <script src="{{ asset('assets/assets/libs/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
  <script src="{{ asset('assets/assets/libs/metismenu/metisMenu.min.js')}}"></script>
  <script src="{{ asset('assets/assets/libs/simplebar/simplebar.min.js')}}"></script>
  <script src="{{ asset('assets/assets/libs/node-waves/waves.min.js')}}"></script>

  <!--Morris Chart-->
  {{-- <script src="{{ asset('assets/assets/libs/morris.js/morris.min.js')}}"></script>
  <script src="{{ asset('assets/assets/libs/raphael/raphael.min.js')}}"></script>
  
  <script src="{{ asset('assets/assets/js/pages/dashboard.init.js')}}"></script> --}}

  <script src="{{ asset('assets/assets/js/app.js')}}"></script>

  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
  <script src="https://olybible.com/BibleReplications/dev/v2/admin/assets/js/core/jquery.3.2.1.min.js"></script>

  <script src="{{ asset('assets/editor_ckediter/ckeditor.js')}}"></script>
  <script src="{{ asset('assets/editor_ckediter/adapters/jquery.js')}}"></script>
  <script src="{{ asset('assets/editor_ckediter/styles.js')}}"></script>
  <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
  <script>
    $(document).ready(function() {
        toastr.options =
    {
      "closeButton" : true,
      "progressBar" : true
    }
        @if (Session::has('error'))
            toastr.error("{{ Session::get('error') }}");
        @elseif(Session::has('message'))
            toastr.success("{{Session::get('message')}}");
        @endif
    });

  </script>

  <script>
    $('#loading').hide();
    $('#susubmitbmit').on('click',function(){
      $('#loading').show();
      $('#susubmitbmit').hide();
    });
  </script>
      
  </body>
</html>
      