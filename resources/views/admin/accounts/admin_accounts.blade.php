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
        <div class="container-fluid">
          <div class="row">
            @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
              @foreach ($errors->all() as $error)
              <li>{{ $error }}</li> <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              @endforeach
            </div>
            @endif
          </div>
          <div class="row">
            <div class="card recent-sales overflow-auto">
              <div class="card-body">
                <div class="row">
                  <div class="col-md-6">
                    <h5 class="card-title">Grammer Sub category List <span>| Today</span></h5>
                  </div>        
                  <div class="col-md-3"></div>
                  <div class="col-md-3">
                    <div class="action-btn" align="right" style="padding-top:20px;padding-right:20px;"> 
                      <a href="{{url('admin/admin_add_sub_admin')}}" class="btn btn-sm btn-primary btn-add">
                        <i class="ri-add-line"></i> Add New Data </a>
                    </div>
                  </div>
                </div>
                <br>

                <div class="row" style="background: #f6f6fe">
                  <div class="col-xs-12">
                    <div class="searchbox-wrap">
                      <div class="page-header text-left">
                        <h3 class="card-title h2">Search </h3>
                      </div>
                      <form action="{{url('admin/superadmin')}}" accept-charset="utf-8" name="searchFrm" method="GET" enctype="multipart/form-data" id="filter_form">
                        <input type="hidden" name="action" id="action">      
                        <div class="row">
                          <div class="form-group col-md-4">
                            <label class="control-label" for="searchKey">Search Key</label>
                            <br>
                            <input type="text" name="searchKey" id="searchKey" value="@if(isset($search)){{$search}}@endif" placeholder="" class="form-control required" required>              
                          </div>
                          <div class="form-group col-md-4">
                            <label class="control-label" for="searchBy">Search By</label>
                            <br>
                            <select name="searchBy" class="form-control" id="searchBy">
                                <option value=""  selected="selected">Select</option>
                                <option value="date" @if(isset($searchBy)) selected @endif>Sub category Name</option>
                            </select>
                          </div>
                          <div class="form-group col-md-4">
                            <label class="control-label" for="searchStatus">Status</label>
                            <br>
                            <select name="searchStatus" class="form-control" id="searchStatus">
                              <option value=""  selected="selected">All</option>
                              <option value="1" @if(isset($searchStatus) && $searchStatus == "1") selected @endif >Disabled</option>
                              <option value="0" @if(isset($searchStatus) && $searchStatus == "0") selected @endif >Enabled</option>
                            </select>
                          </div>
                          <div class="row">
                            <div class="col-xs-12">
                              <br>
                              <button name="myAccountBtn" type="submit" value="true" id="myAccountBtn" class="btn btn-success">Search</button>&nbsp;
                              @if(!isset($search))
                                <button name="resetAgrmtBtn" type="reset" value="true" id="resetAgrmtBtn1" class="btn btn-warning">Reset</button>
                              @else
                              <button name="resetAgrmtBtn" type="reset" value="true" id="resetAgrmtBtn" class="btn btn-warning" onclick="window.location.href='{{url('admin/superadmin')}}'">Back To List</button>              
                            @endif</div>
                          </div>
                        </div>
                      </form>
                    </div>
                  </div>
                </div>
                <br>
                <div class="row">
                  <div class="col-md-6" style="padding-top:10px;">
                    <label>
                      <select class="dataTable-selector">
                        <option value="10" @if(isset($action) && $action == "10") selected @endif>10</option>
                        <option value="20" @if(isset($action) && $action == "20") selected @endif>20</option>
                        <option value="40" @if(isset($action) && $action == "40") selected @endif>40</option>
                        <option value="75" @if(isset($action) && $action == "75") selected @endif>75</option>
                        <option value="100" @if(isset($action) && $action == "100") selected @endif>100</option>
                        <option value="140" @if(isset($action) && $action == "140") selected @endif>140</option>
                      </select> entries per page
                    </label>
                  </div>
                  <div class="col-md-6">
                    {{-- <div class="float-end" style="padding:10px 10px 10px 10px">
                      <a class="btn btn-success btn-sm align-self-center" href="{{ url('admin/file-export_daily_raasi_palan_list') }}">Export</a>
                      <button type="button" class="btn btn-success btn-sm align-self-center" data-bs-toggle="modal" data-bs-target="#exampleModal">Import</button>
                    </div>
                    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLabel">Daily Raasi Palan List File</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                          </div>
                          <div class="modal-body">
                          <form action="{{url('admin/file-import-daily_raasi_palan_list')}}" method="POST" enctype="multipart/form-data">
                                @csrf                              
                                <input type="file" name="file" class="form-control">
                                <br>
                                <button class="btn btn-success">submit</button>
                            </form>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                          </div>
                        </div>
                      </div>
                    </div> --}}
                  </div>
                </div>
                <style>
                  .message-list .checkbox-wrapper-mail label:before{
                    content: "\f012c";
                    font-family: "Material Design Icons";
                    top: 0;
                    height: 20px;
                    font-size: 2.00em !important;
                    color: #465189;
                    width: 20px;
                    position: absolute; 
                    margin-top: -13px;
                    left: 2px;
                  }
                  .message-list .checkbox-wrapper-mail label:checked{
                    position: relative;
                    height: 23px;
                    top: -11px !important;
                    width: 66px !important;
                    left: 4px !important;
                    bottom: -42px !important;
                    cursor: pointer;
                    opacity: 1 !important;
                    margin-bottom: -9px;
                    -webkit-transition-duration: 50ms !important;
                    top: 0;
                  }
                </style>
                <form action="{{url('admin/superadmin')}}" method="POST" accept-charset="utf-8" name="listFrm" id="listFrm">
                  @csrf
                  <br>
                  <input type="hidden" name="doAction" value="" id="doAction">
                  
                  <table id="datatable-buttons" class="table table-striped table-bordered dt-responsive nowrap" style="border-collapse: collapse; border-spacing: 0; width: 100%;">
                    <div class="message-list">
                        <div class="checkbox-wrapper-mail">
                          <div class="checkator_holder checkbox">
                            <input type="checkbox" name="checkedAll" id="checkedAll" class="checkator_source">
                            <label for="checkedAll"></label>
                            <div id="checkator_checkedAll" class="checkator_element checkbox">
                            </div>
                          </div>
                        </div>
                    </div>
                    <br>
                    <tr>
                      <th scope="col">#</th>
                      <th scope="col">ID</th>
                      <th scope="col"> <strong>Name </strong></th>
                      <th scope="col d-flex justify-content-center"> <strong> Email </strong></th>
                      <th scope="col d-flex justify-content-center"> <strong> Role </strong></th>
                      <th scope="col d-flex justify-content-center"> <strong> Admin Image </strong></th>
                      <th scope="col">Create At</th>
                      <th scope="col text-dark"> <strong> Status </strong></th>
                      <th scope="col text-dark"> <strong> Actions </strong></th>
                      <th scope="col">Active</th>
                    </tr>
                    </thead>
                    <tbody>
                      @if(!empty($superadmin) && count($superadmin) > 0)
                        @foreach($superadmin as $row)
                        <tr>
                          <td>
                            <div class="message-list">
                              {{-- <div class="checkator_holder checkbox" style="width: 13px; height: 13px; margin: 0px; float: none; display: inline-block;"> --}}
                                <div class="checkbox-wrapper-mail">
                                  <input type="checkbox" class="checkedAll checkator_source" name="galleryIds[]" value="{{$row->id}}" id="checked_{{$row->id}}" >
                                  <label for="checked_{{$row->id}}" class="toggle"></label>
                                  {{-- <div id="checkator_checked_{{$row->id}}" class="checkator_element checkbox"></div> --}}
                                </div>
                              {{-- </div> --}}
                            </div>
                          </td>
                          <td scope="row"><a class="text-primary" title="Full Details View ({{$row->id}})">#{{$row->id}}</a></td>
                          </form>
                          <td class="text-dark focus">{{$row->name}}</td>
                          <td class="text-dark focus">{{$row->email ?? ''}}</td>
                          <td class="text-dark focus fw-bold">{{$row->role ?? ''}}</td>
                          <td>
                            @if($row->image == null)
                              <center><img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQdoCQ7-yS62tALBS9_FY5pExwg8Lvvsie6Iml51YO_JQ&s" style="height:50px; width:auto;border-radius:70%;"></center>
                            @elseif($row->image_type == 1)
                              <center><img  src="{{asset($row->image) }}"  style="height:50px; width:auto;border-radius:70%;"></center>
                            @else
                              <center><img  src="{{$row->image}}" style="height:60px; width:70px;border-radius:50%;"></center>
                            @endif
                          </td> 
                          <td>
                            <div class="userDatatable-content">
                              {{date('d-m-Y', strtotime($row->created_at))}}
                            </div>
                          </td>
                          <td class="dc" style="padding-top:15px;">
                            @if($row->is_active == '0')
                            <span class="badge bg-success"><i class="bi bi-check-circle me-1"></i> Active</span>
                            @else
                            <span class="badge bg-danger rounded-fill" ><i class="bi bi-exclamation-octagon me-1"></i> Deactivate</span>
                            @endif
                          </td>
                          <td>
                            <div class="btn-group">
                              <div>
                                <form action="{{url("admin/edit_admin/".$row->id)}}" method="POST"style="padding-left:5px;">
                                  {{csrf_field()}}
                                  <button  type="submit" class="btn btn-icon btn-circle btn-sm btn-outline-primary"  title="Edit List"><i class="ion ion-md-create font-size-18"></i></button>
                                </form>
                              </div>
                              <div>
                                <form action="{{url("admin/delete_admin/".$row->id)}}" method="POST" style="padding-left:5px;">
                                {{csrf_field()}}
                                <button  type="submit" class="btn btn-icon btn-circle btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this item?');" title="Delete List"><i class="ion ion-md-trash font-size-18"></i></button>
                                </form>
                              </div>
                            </div>
                          </td>
                          <style>
                            .btn_status 
                            {
                              font-size: .70rem;
                              font-weight: 500;
                              letter-spacing: 1px;
                              padding: 5px 10px;
                              border-radius: 0.25rem;
                              text-transform: uppercase;
                              box-shadow: 0 0.125rem 0.25rem rgb(0 0 0 / 8%);
                            }
                          </style>

                          <td>
                            <form action="{{url('admin/user_status_update')}}" method="POST">
                              @csrf
                              <input type="hidden" name="user_id" value="{{$row->id}}">
                              @if($row->is_active == '0')
                                  <input type="hidden" name="status" value="0">
                                  <button type="submit"  title="Enable" id="enable" name="enable" title="" class="btn_status btn btn-success btn-sm enable">
                                    <i class="bi bi-check-circle me-1"></i>
                                  </button>
                              @else
                                  <input type="hidden" name="status" value="1">
                                  <button type="submit" title="Disable" id="disable" title="" name="disable" class="btn_status btn btn-success btn-sm" style="background: #ffad46!important;border-color: #ffad46!important;color: #fff!important;">
                                    <i class="bi bi-exclamation-octagon me-1"></i>
                                  </button>
                              @endif
                            </form>
                          </td>
                        </tr>
                        @endforeach
                      @else
                      <tr>
                        <td colspan="45">
                          <center><label class="text-dark">No Data Found</label></center>
                        </td>
                      </tr>
                      @endif
                    </tbody>
                  </table>

                  {{$superadmin->appends(request()->input())->links()}}

                  <br>
                  <div class="form-group col-md-12">
                    <div class="new-act-buttons disp_inline_fl">
                      <p>
                        <span class="w-select">With Selected</span>
                        <button type="button" class="btn btn-success btn-xs btn-enable" title="Enable" onclick="doActionFn('enable','all');">Enable</button>
                        <button type="button" class="btn btn-warning btn-xs btn-disable" title="Disable" onclick="doActionFn('disable','all');">Disable</button>
                        <button type="button" class="btn btn-danger btn-xs  btn-delete " title="Delete" onclick="doActionFn('delete','all');">Delete</button>
                      </p>
                    </div>
                  </div>
                  <div id="showActionErr">
                  </div>
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
                          Crafted with <i class="mdi mdi-heart text-danger"></i> by Skyraan.</span>
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
            