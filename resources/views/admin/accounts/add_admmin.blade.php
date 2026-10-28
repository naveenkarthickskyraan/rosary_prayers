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
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card" style="background-color: #fff;">
                  <div class="card-body">
                    <h4 class="card-title text-dark">Admin Add Form</h4>
                    @if ($errors->any())
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                      @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                      @endforeach
                    </div>
                    @endif
                    <hr class="text-dark">
                    <form class="forms-sample" action="{{url('admin/admin_insert')}}" method="POST" role="form" enctype="multipart/form-data">
                      @csrf
                      <div class="form-group row mb-3">
                          <label for="parent" class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">Admin Type <span style="color:red;">&nbsp;*</span></label>
                          <div class="col-sm-9">
                          <select class="js-example-basic-single form-select form-select-white text-dark bg-outline-dark border-3" id="parent" name="parent" style="width:100%" >
                              <option class="text-dark" value="Administrator">Administrator</option>
                              <option class="text-dark" value="SubAdmin">SubAdmin</option>
                          </select>
                          </div>
                      </div>
                      <div class="form-group row mb-3">
                        <label for="name" class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">Admin Name <span style="color:red;">&nbsp;*</span></label>
                        <div class="col-sm-9">
                          <input type="text" multiple class="form-select bg-outline-dark border-3" name="name" id="name" placeholder="Enter a name">
                        </div>
                      </div>
                      <div class="form-group row mb-3">
                        <label for="email" class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">Admin Email <span style="color:red;">&nbsp;*</span></label>
                        <div class="col-sm-9">
                          <input type="email" multiple class="form-select bg-outline-dark border-3" name="email" id="email" placeholder="Enter a Email">
                        </div>
                      </div>
                      <div class="form-group row mb-3">
                          <label for="password" class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">Admin Password <span style="color:red;">&nbsp;*</span></label>
                          <div class="col-sm-9">
                            <input type="text" multiple class="form-select bg-outline-dark border-3" name="password" id="password" placeholder="Enter a Password">
                          </div>
                      </div>
                      <div class="form-group row mb-3">
                          <label for="password_confirmation" class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">Confirm Password<span style="color:red;">&nbsp;*</span></label>
                          <div class="col-sm-9">
                              <input type="text" multiple class="form-select bg-outline-dark border-3" name="password_confirmation" id="password_confirmation" placeholder="Enter a Confirm Password">
                          </div>
                      </div>
                      <style>
                          .mm{
                              width:700px;
                              border: 3px solid lightgrey;
                              padding: 10px;
                              margin: 10px;
                          }
                      </style>
                      <div class="form-group row mb-3">
                          <label class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">Accounts Modules <span style="color:red;">&nbsp;*</span></label>
                          <div class="col-sm-9 mm">
                              @foreach($module as $module)
                              <div class="form-check-inline h5 text-dark">
                                <input class="form-check-input bg-outline-dark border-3" type="checkbox" id="image_1{{$module->module_name}}" name="model[]" value="{{$module->module_name}}" style="font-size:1.00rem;align-items:center;">
                                <label class="form-check-label" for="image_1{{$module->module_name}}" style="font-size: 1.00rem;">
                                  {{$module->module_name}}
                                </label>
                              </div>
                              @endforeach
                          </div>
                      </div>
                      <div class="form-group row mb-3">
                          <label for="type" class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">Account Previleges <span style="color:red;">&nbsp;*</span></label>
                          <div class="col-sm-9 mm">
                              @php $array=array('view','edit','delete','add') @endphp
                              @foreach($array as $array)
                              <div class="form-check-inline h5 text-dark">
                                  <input class="form-check-input bg-outline-dark border-3" type="checkbox" id="image_1{{$array}}" name="privileges[]" value="{{$array}}" style="font-size:1.00rem;align-items:center;">
                                  <label class="form-check-label" for="image_1{{$array}}" style="font-size: 1.00rem;">
                                  {{$array}}
                                  </label>
                              </div>
                              @endforeach
                          </div>
                      </div>
                      <div class="form-group row  mb-3">
                        <label for="radio" class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">Image Type </label>
                        <div class="col-sm-9">
                          <div class="form-check-inline h5 text-dark">
                            <input class="form-check-input" type="radio" name="file_type" id="image_1" value='1' checked style="font-size:1.00rem;align-items:center;">
                            <label class="form-check-label" for="image_1" style="font-size: 1.00rem;">
                              Image Type
                            </label>
                          </div>
                          <div class="form-check-inline h5 text-dark">
                            <input class="form-check-input" type="radio" name="file_type" id="image_2" value='2' style="font-size:1.00rem;align-items:center;">
                            <label class="form-check-label" for="image_2" style="font-size: 1.00rem;"> 
                              URL Type
                            </label>
                          </div> 
                        </div>
                      </div>
                      <div id ='Normal_image_type' class="form-group row  mb-3">
                        <label for="file" class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">User Image <span style="color:red;">&nbsp;*</span></label>
                        <div class="col-sm-9">
                          <input type="File" multiple class="form-select bg-outline-dark border-3" id="file" name="file" placeholder="Choose File">
                        </div>
                      </div>
                      <div id="url_image_type" class="form-group row mb-3">
                        <label for="file_1" class="col-sm-3 col-form-label h2 text-dark" style="padding-left: 10px">User URL Image <span style="color:red;">&nbsp;*</span></label>
                        <div class="col-sm-9">
                          <input type="text" multiple class="form-select bg-outline-dark border-3" id="file_1" name="url_image" placeholder="Copy and past a URL">
                        </div>
                      </div>                     
                      <button type="submit" name="submit" class="btn btn-primary" >Submit</button>
                      <a href="{{url('admin/superadmin')}}" class="btn btn-dark" type="button">cancel</a>
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
      if($('#image_1').is(':checked'))
      {
        $("#Normal_image_type").show()
        $('#url_image_type').hide();
      }
    
      $('#image_1').bind('change', function () {
        if ($(this).is(':checked'))
          $("#Normal_image_type").show().removeAttr('checked',true)
          $('#url_image_type').hide();
      });
      
      $("#image_2").bind('change', function(){
        if ($(this).is(':checked'))
          $("#Normal_image_type").hide();
          $('#url_image_type').show().attr('checked',true);
      });
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


