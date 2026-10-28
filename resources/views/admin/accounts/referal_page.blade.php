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
  <link rel="stylesheet" href="https://olybible.com/BibleReplications/dev/v2/admin/assets/css/bootstrap.min.css">
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
        <div class="container">
            <form class="" method="post" action="{{route('referal_form_submit')}}">
                @csrf
                <div class="mb-3">
                    <label for="exampleInputPassword1" class="form-label">name</label>
                    <input type="text" name="name" class="form-control" id="name" required>
                </div>
                <div class="mb-3">
                    <label for="exampleInputEmail1" class="form-label">Email address</label>
                    <input type="email" name="email" class="form-control" id="exampleInputEmail1" required aria-describedby="emailHelp">
                    <div id="emailHelp" class="form-text">We'll never share your email with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label for="phonenumber" class="form-label">phone number</label>
                    <input type="text" name="phonenumber" class="form-control" required id="phonenumber">
                </div>
                <div class="mb-3">
                    <label for="address" class="form-label">Address</label>
                    <textarea type="text" name="address" class="form-control" required id="address"></textarea>
                </div>
                
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
          <footer class="footer">
            <div class="container-fluid">
                <div class="row">
                    <div class="col-12">
                        ©
                        <script>document.write(new Date().getFullYear())</script> Fonik<span class="d-none d-sm-inline-block"> -
                            Crafted with <i class="mdi mdi-heart text-danger"></i> by  .</span>
                    </div>
                </div>
            </div>
          </footer>
        
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
      <!-- Datatable init js -->
    
      <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    
      <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
      <script src="https://olybible.com/BibleReplications/dev/v2/admin/assets/js/core/jquery.3.2.1.min.js"></script>
      <script src="https://olybible.com/BibleReplications/dev/v2/admin/assets/js/core/popper.min.js"></script>
      <script src="https://olybible.com/BibleReplications/dev/v2/admin/assets/js/core/bootstrap.min.js"></script>
      <script src="https://olybible.com/BibleReplications/dev/v2/admin/assets/multi_select_search/js/bootstrap-multiselect.min.js"></script>
      <script src="https://olybible.com/BibleReplications/dev/v2/admin/assets/multi_select_search/js/bootstrap-multiselect.js" ></script>
      <script>
        $('#app_select').multiselect({
          buttonWidth: '100%',
          enableCaseInsensitiveFiltering: true,
          includeSelectAllOption: true,
          maxHeight: 250,
        });
        $('#country_select').multiselect({
          buttonWidth: '100%',
          enableCaseInsensitiveFiltering: true,
          includeSelectAllOption: true,
          maxHeight: 250,
        });
      </script>
      <script>
        $('.dataTable-selector').on('change',function()
        {
          $('#action').val($(this).val());
          $('#filter_form').submit();
        });
        function doActionFn(action,id)
        {
          $('#doAction').val(action);	
          if(id != 'all' && $('#checked_'+id).length)
          {
            $('.checkedAll').prop('checked',true); 	
            $('#checked_'+id).prop('checked',true); 
          }
          var checkBox = false;
          $.each($("input[name='galleryIds[]']:checked"), function() 
          {
            checkBox =  true;
          });
          
          if(checkBox == false){
            $('#showActionErr').html('<div class="alert alert-danger alert-dismissable"> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button> Please select at least one item </div>');	 
            return false;
          }
          if(action == 'delete')
          {
            if (confirm("Are you sure you want to delete?")) {
              $('#listFrm').submit();
            }
          } 
          else{
            $('#listFrm').submit();
          }
        }
    
        $('#checkedAll').change(function(){ 
          if($(this).is(':checked')){ 
            $('.checkedAll').prop('checked',true); 
          }else{ 
            $('.checkedAll').prop('checked',false);
          }
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
          
      </body>
    </html>
      