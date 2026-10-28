<!DOCTYPE html>
<html lang="en">
  <head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="csrf-token" content="{{ csrf_token() }}" />
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<title>Admin Dashboard</title>
	<!-- plugins:css -->
	<link href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}" rel="stylesheet">
	<link rel="stylesheet" href="{{ asset('assets/vendors/mdi/css/materialdesignicons.min.css') }}">
	<link rel="stylesheet" href="{{ asset('assets/vendors/css/vendor.bundle.base.css') }}">
	<!-- endinject -->
	<!-- Plugin css for this page -->
	{{-- <link rel="stylesheet" href="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.css') }}"> --}}
	<link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
	<link rel="stylesheet" href="{{ asset('assets/vendors/flag-icon-css/css/flag-icon.min.css')}}">
	<link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel-2/owl.carousel.min.css')}}">
	<link rel="stylesheet" href="{{ asset('assets/vendors/owl-carousel-2/owl.theme.default.min.css')}}">
	<!-- End plugin css for this page -->
	<!-- inject:css -->
	<!-- endinject -->
	<!-- Layout styles -->
	<link rel="stylesheet" href="{{ asset('assets/css/style.css')}}">
	<link rel="stylesheet" href="{{ asset('assets/css/table_style.css')}}">
	<!-- End layout styles -->
	<link rel="shortcut icon" href="{{ asset('assets/images/Proposed.jpg')}}"/>
	<style>
	  a:focus, input:focus {
		  border-color: black;
		  outline: black;
	  }
	  #snackbar {
		visibility: hidden;
		min-width: 250px;
		margin-left: -125px;
		background-color: #313a46;
		color: #fff;
		text-align: center;
		border-radius: 2px;
		padding: 16px;
		position: fixed;
		z-index: 1;
		left: 80%;
		bottom: 82%;
		font-size: 17px;
	  }
	  
	  #snackbar.show {
		visibility: visible;
		-webkit-animation: fadein 0.5s, fadeout 0.5s 2.5s;
		
		animation: fadein 0.5s, fadeout 0.5s 2.5s;
	  }
	  
	  @keyframes fadein {
		from {top: 0; opacity: 0;}
		to {top: 50px; opacity: 1;}
	  }
	  
	  /* @keyframes fadeout {
		from {top: 0; opacity: 0;}
		to {top: 50px; opacity: 1;}
	  } */


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
	  .dataTables_wrapper .dataTables_length select{
		background: white !important;  
		border: 1.5px solid !important;   
		color: black !important; 
	  }
	</style>
  </head>
  <body>
	<div class="container-scroller" >
		@include('admin.sidebar')
  <div id="loader"></div>
		<div class="container-fluid page-body-wrapper">
			@include('admin.navbar')
			<!-- partial --><style>
				.cc{
					background-color: lightgrey;    
				}
			</style>
			<div class="main-panel">
			<div class="content-wrapper cc">  
				<nav aria-label="breadcrumb">
					<div class="btn-group">             
					  <ol class="breadcrumb">
						<li class="breadcrumb-item text-dark"><i class="mdi mdi-table-large mdi-15px"></i></li>
						<li class="breadcrumb-item"><a href="{{url('admin/logActivity')}}">Activity List</a></li>
						<li class="breadcrumb-item active" aria-current="page">List of Activity table</li>
					  </ol>
					</div>
					<div id="error">
					</div> 
					<div>
					  @if(session()->get('message'))
						<div id="snackbar"><b>Success:</b> {{session()->get('message')}}</div>
					  @endif
					  @if(session()->get('error'))
						<div id="snackbar" style="background-color: red;"><b>Warning:</b> {{session()->get('error')}}</div>
					  @endif
					</div>             
				</nav>     
			  <div class="row">
				<div class="col-lg-12 grid-margin stretch-card">
				  <div class="card" style="background-color: #fff;">
					<div class="card-body">
					  <div class="row">
						<div class="col-md-6">
						  <h4 class="card-title text-dark">App List</h4>
						</div>
						<div class="col-md-4"></div>
						<div class="col-md-2">
						  {{-- <a class="nav-link btn btn-success create-new-button" href="{{url('admin/LogActivity')}}">+ Create New App</a> --}}
						</div>
					  </div>
					  {{-- <p class="card-title h5 text-dark"> Disabled Apps : {{count($app_active)}} </p> --}}
					  <strong><p class="card-title h5 text-dark" style="padding-right:">Total Logs : {{count($logs)}}</p></strong>
					  <div class="table-responsive">
						<form action="{{url('admin/activitydelete')}}" method="POST" role="form">
						@csrf
						<button type="submit" name="delete" class="btn btn-primary" placeholder="">Delete</button>
							<table id="members_list" class="table table-bordered border-0 bg-light">
							<thead>
								<tr>
									<th>[]</th>
									<th>No</th>
									<th>URL</th>
									<th>Method</th>
									<th>Ip</th>
									<th>User Id</th>
									<th>Email</th>
									<th>Role</th>
									<th>Action</th>
								</tr>
							</thead>
							<tbody>
								@if($logs->count())
									@foreach($logs as $key => $log)
									<tr>
										<td><input name="checkbox[]" type="checkbox" id="checkbox[]" value="{{$log->id}}"></td>
										<td>{{ ++$key }}</td>
										<td class="text-success">{{ $log->url }}</td>
										<td><label class="label label-info">{{ $log->method }}</label></td>
										<td class="text-warning">{{ $log->ip }}</td>
										<td>{{ $log->user_id }}</td>
										<td>{{ $log->email }}</td>
										<td>{{$log->role}}</td>
										<td><button class="btn btn-danger btn-sm">Delete</button></td>
									</tr>
									@endforeach
								@else
									<p>No Data Found</p>
								@endif
							</tbody>
							</table>
						</form>
					  </div>
					</div>
				  </div>
				</div>
			  </div>  
			</div>
			<!-- content-wrapper ends -->
			<!-- partial:partials/_footer.html -->
			<!-- partial -->
			</div>
			<!-- main-panel ends -->
		</div>
		<!-- page-body-wrapper ends -->
		<style>
		  .footer{
			position: fixed;
			left: 0;
			bottom: 0;
			width: 100%;
			color: white;
			background-color: #313a46;
			text-align: center;
		  }
		  </style>
		<footer class="footer">
		  <div class="d-sm-flex justify-content-center justify-content-sm-between">
		  </div>
	  </footer>
	</div>
	
   
	<!-- container-scroller -->
	<!-- plugins:js -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.1/jquery.min.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
	<script>
	</script>
	<script src="{{ asset('assets/vendors/js/vendor.bundle.base.js')}}"></script>
	<!-- endinject -->
	<!-- Plugin js for this page -->
	<script src="{{ asset('assets/vendors/chart.js/Chart.min.js')}}"></script>
	<script src="{{ asset('assets/vendors/progressbar.js/progressbar.min.js')}}"></script>
	<script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap.min.js')}}"></script>
	<script src="{{ asset('assets/vendors/jvectormap/jquery-jvectormap-world-mill-en.js')}}"></script>
	<script src="{{ asset('assets/vendors/owl-carousel-2/owl.carousel.min.js')}}"></script>
	<script src="{{ asset('assets/js/jquery.cookie.js')}}"></script>
	<!-- End plugin js for this page -->
	<!-- inject:js -->
	<script src="{{ asset('assets/js/off-canvas.js')}}"></script>
	<script src="{{ asset('assets/js/hoverable-collapse.js')}}"></script>
	<script src="{{ asset('assets/js/misc.js')}}"></script>
	<script src="{{ asset('assets/js/settings.js')}}"></script>
	<script src="{{ asset('assets/js/todolist.js')}}"></script>
	<!-- endinject -->
	<!-- Custom js for this page -->
	<script src="{{ asset('assets/js/dashboard.js')}}"></script>
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<script src="https://cdn.datatables.net/r/dt/dt-1.10.9/datatables.min.js"></script>
	<!-- End custom js for this page -->
	<script>
	  $(document).ready(function (){
		  var table = $('#members_list').DataTable({
			 lengthMenu: [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
			 pageLength: 10
		  });
	  });
	</script>
	<script>
	  myFunction();
	  function myFunction() {
		var x = document.getElementById("snackbar");
		x.className = "show";
		setTimeout(function(){ x.className = x.className.replace("show", ""); }, 3000);
	  }
	</script>
  </body>
</html>