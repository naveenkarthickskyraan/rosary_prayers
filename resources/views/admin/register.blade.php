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


<body class="account-bg">


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
                            <div class="text-center mt-4">
                                <div class="mb-3">
                                  <span class="text-dark fw-bold navbar-brand" style="font-size:20px;"> Rosary Prayers</span>
                                </div>
                            </div>
                            <div class="p-3">
                                <p class="text-muted text-center mb-4"></p>

                                <form class="form-horizontal"   method="POST" action="{{ route('admin.registersubmit') }}" novalidate>
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label text-muted" for="email">Email</label>
                                        <i class="mdi mdi-email"></i><input type="email"  name="email" class="form-control " id="email"
                                            placeholder="Enter email">
                                        @error('email')
                                            <span class="input-error text-danger fw-bold">{{ $errors->first('email') }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted" for="password">Password</label>
                                        <i class="mdi mdi-lock"></i><input type="password" name="password" class="form-control" id="password"
                                            placeholder="Enter password">
                                        @error('password')
                                            <span class="input-error text-danger fw-bold">{{ $errors->first('password') }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted" for="phonenumber">phonenumber</label>
                                        <i class="mdi mdi-lock"></i><input type="password" name="phonenumber" class="form-control" id="phonenumber"
                                            placeholder="Enter phonenumber">
                                        @error('phonenumber')
                                            <span class="input-error text-danger fw-bold">{{ $errors->first('phonenumber') }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted" for="address">address</label>
                                        <i class="mdi mdi-lock"></i><input type="address" name="address" class="form-control" id="address"
                                            placeholder="Enter address">
                                        @error('address')
                                            <span class="input-error text-danger fw-bold">{{ $errors->first('address') }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3 row mt-4">
                                        
                                        <div class=" text-center">
                                            <button class="btn btn-primary w-md waves-effect waves-light"
                                                type="submit">Sign In</button>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mt-4">
                                                    <p class="text-muted">Already have an account? <a href="{{ route('admin.login') }}" class="fw-bold text-black text-decoration-underline" >Login</a></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-12 mt-4">
                                            <a href="pages-recoverpw.html" class="text-muted"><i
                                                    class="mdi mdi-lock"></i> Forgot your password?</a>
                                        </div>
                                    </div>
                                </form>

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
        @elseif(Session::has('address'))
            toastr.success("{{Session::get('address')}}");
        @endif
    });
  </script>
  </body>
</html>
