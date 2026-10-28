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
  <style>
        .form-steps {
            display: block;
            width: 100%;
            position: relative;
            margin: 40px 0;
        }

        .form-steps:after {
                content: "";
                display: table;
                clear: both;
        }

        .form-steps__item {
                padding: 0;
                position: relative;
                display: block;
                float: left;
                width: 25%;
                text-align: center;
        }

        .form-steps__item-content {
                display: inline-block;
        }

        .form-steps__item-icon {
                background: lightgray;
                color: lightslategray;
                display: block;
                border-radius: 100%;
                text-align: center;
                width: 40px;
                height: 40px;
                line-height: 40px;
                margin: 0 auto 10px auto;
                position: relative;
                font-size: 13px;
                font-weight: 700;
                z-index: 2;
                border: 5px solid;
        }

        .form-steps__item-text {
                font-size: 13px;
                color: lightslategray;
                font-weight: 500;
        }

        .form-steps__item-line {
                display: inline-block;
                height: 3px;
                width: 100%;
                background: lightgray;
                float: left;
                position: absolute;
                left: -50%;
                top: 20px;
                z-index: 1;
                border: 5px solid lightslategray;
        }

        .form-steps__item--active .form-steps__item-icon {
                background: green;
                color: white;
                border-color: mediumseagreen;
        }

        .form-steps__item--fail .form-steps__item-icon {
                background: red;
                color: white;
                border-color: red;
        }

        .form-steps__item--active .form-steps__item-text {
                color: green;
                font-weight: 600;
        }

        .form-steps__item--active .form-steps__item-line,
        .form-steps__item--completed .form-steps__item-line {
                background: green;
                border: 5px solid mediumseagreen;
        }

        .form-steps__item--completed .form-steps__item-text {
                color: #4f5e77;
        }

        .form-steps__item--completed .form-steps__item-icon {
            background: green;
        background-image: url(data:image/svg+xml;base64,PHN2ZyBkYXRhLW5hbWU9IkxheWVyIDEiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgdmlld0JveD0iMCAwIDkuMTIgNyI+PHBhdGggZmlsbD0iI2ZmZiIgZD0iTTkuMTIgMS4wNkw4LjA2IDAgMy4xOCA0Ljg4IDEuMDYgMi43NiAwIDMuODIgMy4xOCA3bDUuOTQtNS45NHoiLz48L3N2Zz4=);
        color: transparent;
        background-size: 10px;
        background-repeat: no-repeat;
            background-position: center center;
        width: 40px;
        height: 40px;
        line-height: 40px;
        border: 5px solid mediumseagreen;
        }

        @media only screen and (max-width: 767px) {

                .form-steps__item-icon,
                .form-steps__item--completed .form-steps__item-icon {
                        width: 30px;
                        height: 30px;
                        line-height: 30px;
                }

                .form-steps__item-line {
                        top: 15px;
                }
        }

        .form-steps__item-icon:after {
            content: '';
            position: absolute;
            top: 50%;
            right: -5px;
            width: 5px;
            height: 3px;
            background: lightgrey;
        }

        .form-steps__item-icon:before {
            content: '';
            position: absolute;
            top: 50%;
            left: -5px;
            width: 5px;
            height: 3px;
            background: lightgrey;
        }

        .form-steps__item--active .form-steps__item-icon:before,
        .form-steps__item--completed .form-steps__item-icon:after,
        .form-steps__item--completed .form-steps__item-icon:before {
            background: green;
        }

        .form-steps__item:last-child .form-steps__item-icon:after {
            background: lightslategray;
        }

        .form-steps__item--active .form-steps__item-icon:after,
        .form-steps__item:last-child.form-steps__item--active .form-steps__item-icon:after,
        .form-steps__item:first-child.form-steps__item--active .form-steps__item-icon:before,
        .form-steps__item:first-child.form-steps__item--completed .form-steps__item-icon:before{
            background: mediumseagreen;
        }
    </style>
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
        <div class="container p-3">
        @if(isset($userrefered) && $userrefered)

            @foreach($userrefered as $row)
            <div class="row align-items-center">
                <div class="col-3">{{$row->name}}</div>
                <div class="col-9">
                    <nav class="form-steps">
                        @php $status = $row->process_stage; @endphp

                        <div class="form-steps__item form-steps__item--completed">
                            <div class="form-steps__item-content">
                                <span class="form-steps__item-icon">1</span>
                                <span class="form-steps__item-text">Inprogress</span>
                            </div>
                        </div>

                        @switch($status)
                            @case(1)
                            <div class="form-steps__item form-steps__item--active">
                                <div class="form-steps__item-content">
                                    <span class="form-steps__item-icon">2</span>
                                    <span class="form-steps__item-line"></span>
                                    <span class="form-steps__item-text">viewed</span>
                                </div>
                            </div>
                            <div class="form-steps__item">
                                <div class="form-steps__item-content">
                                    <span class="form-steps__item-icon">3</span>
                                    <span class="form-steps__item-line"></span>
                                    <span class="form-steps__item-text">contacted</span>
                                </div>
                            </div>
                            <div class="form-steps__item">
                                <div class="form-steps__item-content">
                                    <span class="form-steps__item-icon">4</span>
                                    <span class="form-steps__item-line"></span>
                                    <span class="form-steps__item-text">completed</span>
                                </div>
                            </div>
                                @break

                            @case(2)
                                <div class="form-steps__item form-steps__item--completed">
                                    <div class="form-steps__item-content">
                                        <span class="form-steps__item-icon">2</span>
                                        <span class="form-steps__item-line"></span>
                                        <span class="form-steps__item-text">viewed</span>
                                    </div>
                                </div>
                                <div class="form-steps__item form-steps__item--active">
                                    <div class="form-steps__item-content">
                                        <span class="form-steps__item-icon">3</span>
                                        <span class="form-steps__item-line"></span>
                                        <span class="form-steps__item-text">contacted</span>
                                    </div>
                                </div>
                                <div class="form-steps__item ">
                                    <div class="form-steps__item-content">
                                        <span class="form-steps__item-icon">4</span>
                                        <span class="form-steps__item-line"></span>
                                        <span class="form-steps__item-text">completed</span>
                                    </div>
                                </div>
                                @break

                            @case(3)
                                <div class="form-steps__item form-steps__item--completed">
                                    <div class="form-steps__item-content">
                                        <span class="form-steps__item-icon">2</span>
                                        <span class="form-steps__item-line"></span>
                                        <span class="form-steps__item-text">viewed</span>
                                    </div>
                                </div>
                                <div class="form-steps__item form-steps__item--completed">
                                    <div class="form-steps__item-content">
                                        <span class="form-steps__item-icon">3</span>
                                        <span class="form-steps__item-line"></span>
                                        <span class="form-steps__item-text">contacted</span>
                                    </div>
                                </div>
                                <div class="form-steps__item form-steps__item--active">
                                    <div class="form-steps__item-content">
                                        <span class="form-steps__item-icon">4</span>
                                        <span class="form-steps__item-line"></span>
                                        <span class="form-steps__item-text">completed</span>
                                    </div>
                                </div>
                                @break

                            @case(4)
                                <div class="form-steps__item form-steps__item--completed">
                                    <div class="form-steps__item-content">
                                        <span class="form-steps__item-icon">2</span>
                                        <span class="form-steps__item-line"></span>
                                        <span class="form-steps__item-text">viewed</span>
                                    </div>
                                </div>
                                <div class="form-steps__item form-steps__item--completed">
                                    <div class="form-steps__item-content">
                                        <span class="form-steps__item-icon">3</span>
                                        <span class="form-steps__item-line"></span>
                                        <span class="form-steps__item-text">contacted</span>
                                    </div>
                                </div>
                                <div class="form-steps__item form-steps__item--fail">
                                    <div class="form-steps__item-content">
                                        <span class="form-steps__item-icon">4</span>
                                        <span class="form-steps__item-line"></span>
                                        <span class="form-steps__item-text">failed</span>
                                    </div>
                                </div>
                                @break

                            @default
                                <div class="form-steps__item">
                                    <div class="form-steps__item-content">
                                        <span class="form-steps__item-icon">2</span>
                                        <span class="form-steps__item-line"></span>
                                        <span class="form-steps__item-text">viewed</span>
                                    </div>
                                </div>
                                <div class="form-steps__item">
                                    <div class="form-steps__item-content">
                                        <span class="form-steps__item-icon">3</span>
                                        <span class="form-steps__item-line"></span>
                                        <span class="form-steps__item-text">contacted</span>
                                    </div>
                                </div>
                                <div class="form-steps__item">
                                    <div class="form-steps__item-content">
                                        <span class="form-steps__item-icon">4</span>
                                        <span class="form-steps__item-line"></span>
                                        <span class="form-steps__item-text">completed</span>
                                    </div>
                                </div>
                        @endswitch
                        
                    </nav>
                </div>
            </div>
            @endforeach

        @else
            <div>
                <p>No user<p>
            </div>
        @endif
    
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
      