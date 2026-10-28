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
      @include('admin.navbar')
      <!-- ========== Left Sidebar Start ========== -->
      @include('admin.sidebar')
      
      <div class="main-content">
        <div class="page-content">
          <div class="container-fluid">
            <div class="row">
              <div class="col-md-12">
                @if ($errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                  @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                  @endforeach
                </div>
                @endif
              </div>
            </div>
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card" style="background-color: #fff;">
                  <div class="card-body">
                    <h4 class="card-title text-dark">Update Profile</h4>
                    <hr class="text-dark">

                    <form class="forms-sample" role="form" action="{{route('profileupdate')}}" method="POST" enctype='multipart/form-data'>
                      @csrf
                      <div class="form-group row">
                          <label for="name" class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">User Name <span style="color:red;">&nbsp;*</span></label>
                          <div class="col-sm-9">
                              <input type="text" multiple class="form-select bg-outline-dark border-3"  id="name" name="name" value="{{Auth::guard('admin')->user()->name}}">
                          </div>
                      </div>
                      <br>
                      <div class="form-group row">
                          <label for="email" class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">User Email <span style="color:red;">&nbsp;*</span></label>
                          <div class="col-sm-9">
                          <input type="text" multiple class="form-select bg-outline-dark border-3"  id ="email" value="{{Auth::guard('admin')->user()->email}}" name="email">
                          </div>
                      </div>
                      <br>
                      <div class="form-group row">
                          <label for="password_confirmation" class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">Password Changed <span style="color:red;">&nbsp;*</span></label>
                          <div class="col-sm-9">
                              <input id="check" class="toggle" type="checkbox" name="checkbox" checked>
                          </div>
                      </div>
                      <br>
                      <div id="Normal">
                          <div class="form-group row">
                              <label for="password" class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">password <span style="color:red;">&nbsp;*</span></label>
                              <div class="col-sm-9">
                              <input type="password" multiple class="form-select bg-outline-dark border-3"  id ="password" name="password" placeholder="Enter a Password">
                              </div>
                          </div>
                          <br>
                          <div class="form-group row">
                              <label for="password_confirmation" class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">Password Change<span style="color:red;">&nbsp;*</span></label>
                              <div class="col-sm-9">
                              <input type="password" multiple class="form-select bg-outline-dark border-3"  id ="password_confirmation" name="password_confirmation" placeholder="Enter a confirm Password">
                              </div>
                          </div>
                          <br>
                      </div>
                          <div class="form-group row">
                              <label for="file" class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">Profile Image <span style="color:red;">&nbsp;*</span></label>
                              <div class="col-sm-9">
                                <input type="File" multiple class="form-select bg-outline-dark border-3" id="file" name="file" placeholder="Choose File">
                              </div>
                          </div>
                          <br>
                      <button type="submit" name="submit" class="btn btn-primary" >Update Profile</button>
                      <a href="{{'app_list'}}" class="btn btn-dark" type="button">cancel</a>
                  </form>
                  </div>
              </div>
            </div><!-- End Recent Sales -->
          </div>
        </div>
        <footer class="footer">
          <div class="container-fluid">
              <div class="row">
                  <div class="col-12">
                      ©
                      <script>document.write(new Date().getFullYear())</script> Fonik<span class="d-none d-sm-inline-block"> -
                          Crafted with <i class="mdi mdi-heart text-danger"></i> by Themesbrand.</span>
                  </div>
              </div>
          </div>
        </footer>
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
      $(function() 
      { 
        $('.toggle').change(function() 
        { 
          var status = $(this).prop('checked') == true ? 1 : 0;        
          var name = status;
          // alert(name);
          test_check(name);
          function test_check(name)
          {
              if(name == "1")
              {
                $('#check').attr("checked",true);
                $("#Normal").show();
                $('#check').attr("checked",false);
                $("#Normal").show().attr('checked',true);
              }
              else if(name == "0") 
              { 
                $('#check').attr("checked",false);
                $("#Normal").hide();
                $('#check').attr("checked",true);
                $("#Normal").hide().attr('checked',false);
              }

              if($('#check').is(':checked'))
              {
                  $('#check').attr("checked",false);
                  $("#Normal").hide();
              }
              else
              {
                  $('#check').bind('change', function () {
                      if ($(this).is(':checked'))
                      {
                          $('#check').attr("checked",true);
                          $("#Normal").show();
                      }
                  });
              }
          }
        }) 
      }) 
    </script>

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



                      