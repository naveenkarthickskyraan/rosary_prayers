{{-- <!-- ======= Header ======= -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/7.0.96/css/materialdesignicons.min.css">
<style>
  * {
    box-sizing: border-box;
  }
  
  body {
    font: 16px Arial;  
  }
  
  /*the container must be positioned relative:*/
  .autocomplete {
    position: relative;
    display: inline-block;
  }
  
  .autocomplete-items {
    position: absolute;
    border: 1px solid #d4d4d4;
    border-bottom: none;
    border-top: none;
    z-index: 99;
    top: 80%;
    left:362px;
    right: 830px;
  }

  @media only screen and (max-width: 600px) {
    .autocomplete-items {
      position: absolute;
      border: 1px solid #d4d4d4;
      border-bottom: none;
      border-top: none;
      left:20px !important;
      width: auto;
    }
    .autocomplete-items div {
      cursor: pointer;
      padding-left:10px !important; 
      background-color: #fff; 
      border-bottom: 1px solid #000; 
      background: white;
      position: absolute;
    }
    
    .autocomplete-items div:hover {
      background-color: #e9e9e9; 
      color: #000;
    }
    .autocomplete-active {
      background-color: DodgerBlue !important; 
      color: #ffffff; 
    }
  }
  
  .autocomplete-items div {
    padding: 10px;
    cursor: pointer;
    background-color: #fff; 
    border-bottom: 1px solid #d4d4d4; 
  }
  
  /*when hovering an item:*/
  .autocomplete-items div:hover {
    background-color: #e9e9e9; 
  }
  
  /*when navigating through the items using the arrow keys:*/
  .autocomplete-active {
    background-color: DodgerBlue !important; 
    color: #ffffff; 
  }
  </style>

<header id="header" class="header fixed-top d-flex align-items-center">

  <div class="d-flex align-items-center justify-content-between">
    <a href="{{url('admin/dashboard')}}" class="logo d-flex align-items-center">
      <span><i class="mdi mdi-calendar-today"></i></span>
      <span class="d-none d-lg-block" style="padding-left:7px;">Calendar App</span>
    </a>
    <i class="bi bi-list toggle-sidebar-btn"></i>
    <div class="search-bar">
      <form autocomplete="off" class="search-form d-flex align-items-center">
          <input id="myInput" type="search" name="myCountry" placeholder="Search Something here!...... ">
        <button type="button" title="Search" id="Search"><i class="bi bi-search"></i></button>
      </form>
    </div>
  </div><!-- End Logo -->

  <nav class="header-nav ms-auto">
    <ul class="d-flex align-items-center">

      <li class="nav-item d-block d-lg-none">
        <a class="nav-link nav-icon search-bar-toggle " href="#">
          <i class="bi bi-search"></i>
        </a>

      <li class="nav-item dropdown pe-3">
        <a class="nav-link nav-profile d-flex align-items-center pe-0" href="#" data-bs-toggle="dropdown">
          @if(Auth::guard('admin')->user()->image_type == 1)
            <img src="{{asset(Auth::guard('admin')->user()->image) }}"alt="Profile" class="rounded-circle">
          @elseif(Auth::guard('admin')->user()->image_type == 2)
            <img src="{{Auth::guard('admin')->user()->image}}" alt="Profile" class="rounded-circle">
          @endif
          <br>
          <br>
          <span class="d-none d-md-block dropdown-toggle ps-2">{{ Auth::guard('admin')->user()->name}}</span>
        </a>
          

        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow profile">
          <li class="dropdown-header"> 
            @if(Auth::guard('admin')->user()->image_type == 1)   
            <img src="{{asset(Auth::guard('admin')->user()->image) }}"alt="Profile" class="rounded-circle" style="height:30% !important;width:30% !important;">
            @elseif(Auth::guard('admin')->user()->image_type == 2)
              <img src="{{Auth::guard('admin')->user()->image}}" alt="Profile" class="rounded-circle" style="min-height:40% !important;min-width:40% !important; ">
            @endif
            <h6>{{ Auth::guard('admin')->user()->name}}</h6>
            <span></span>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{url('admin/profile_update')}}">
              <i class="bi bi-person"></i>
              <span>My Profile</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>

          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{url('/page-not-found')}}">
              <i class="bi bi-gear"></i>
              <span>Account Settings</span>
            </a>
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          <li>
            <hr class="dropdown-divider">
          </li>
          @if(Auth::guard('admin')->check())
          <li>
            <a class="dropdown-item d-flex align-items-center" href="{{route('admin.logout')}}">
              <i class="bi bi-box-arrow-right"></i>
              <span>Sign Out</span>
            </a>
          </li>
          @endif
        </ul><!-- End Profile Dropdown Items -->
      </li><!-- End Profile Nav -->

    </ul>
  </nav><!-- End Icons Navigation -->

</header> --}}


<header id="page-topbar">
  <div class="navbar-header">
      <div class="d-flex">
          <!-- LOGO -->
          <div class="navbar-brand-box">
              <a href="{{url('admin/dashboard')}}" class="logo logo-dark">
                  <span class="logo-sm">
                      <img src="{{ asset('assets/assets/images/logo-sm.png')}}" alt="" height="22">
                  </span>
                  <span class="logo-lg">
                      <img src="{{ asset('assets/assets/images/logo.png')}}" alt="" height="20">
                  </span>
              </a>

              <a href="{{url('admin/dashboard')}}" class="logo logo-light">
                  <span class="logo-sm">
                      <img src="{{ asset('assets/assets/images/400.png')}}" alt="" height="22">
                  </span>
                  <span class="logo-lg">
                      {{-- <img src="{{ asset('assets/assets/images/400.png')}}" alt="" height="20" style="max-width:20%;height: auto;">  --}}
                      <span class="text-white fw-bold navbar-brand" style="font-size:20px;"> Rosary Prayers</span>
                  </span>
              </a>
          </div>

          <button type="button" class="btn btn-sm px-3 font-size-24 header-item waves-effect" id="vertical-menu-btn">
              <i class="mdi mdi-menu"></i>
          </button>

          <div class="d-none d-sm-block ms-2">
              <h4 class="page-title font-size-18">Rosary Prayers </h4>
          </div>

      </div>

      <!-- Search input -->
      <div class="search-wrap" id="search-wrap">
          <div class="search-bar">
              <input class="search-input form-control" placeholder="Search" />
              <a href="#" class="close-search toggle-search" data-bs-target="#search-wrap">
                  <i class="mdi mdi-close-circle"></i>
              </a>
          </div>
      </div>

      <div class="d-flex">

          <div class="dropdown d-none d-lg-inline-block">
              <button type="button" class="btn header-item toggle-search noti-icon waves-effect"
                  data-bs-target="#search-wrap">
                  <i class="mdi mdi-magnify"></i>
              </button>
          </div>

          <div class="dropdown d-inline-block ms-2">
              <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                  data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  @if(Auth::guard('admin')->user()->image_type == 1)
                    <img class="rounded-circle header-profile-user" src="{{asset(Auth::guard('admin')->user()->image) }}"alt="Header Avatar">
                  @elseif(Auth::guard('admin')->user()->image_type == 2)
                    <img class="rounded-circle header-profile-user" src="{{asset(Auth::guard('admin')->user()->image) }}"alt="Header Avatar">
                  @endif
              </button>
              <div class="dropdown-menu dropdown-menu-end">
                  <!-- item-->
                  <center><h6>{{ Auth::guard('admin')->user()->name}}</h6></center>
                  <a class="dropdown-item" href="{{url('admin/profile_update')}}"><i class="dripicons-user font-size-16 align-middle me-2"></i>
                      Profile</a>
                  {{-- <a class="dropdown-item" href="#"><i class="dripicons-wallet font-size-16 align-middle me-2"></i> My
                      Wallet</a>
                  <a class="dropdown-item d-block" href="#"><span class="badge bg-success float-end">5</span><i
                          class="dripicons-gear font-size-16 align-middle me-2"></i> Settings</a>
                  <a class="dropdown-item" href="#"><i class="dripicons-lock font-size-16 align-middle me-2"></i> Lock
                      screen</a> --}}
                  <div class="dropdown-divider"></div>
                  <a class="dropdown-item" href="{{route('admin.logout')}}"><i class="dripicons-exit font-size-16 align-middle me-2"></i>
                      Logout</a>
              </div>
          </div>

          {{-- <div class="dropdown d-inline-block">
              <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                  <i class="mdi mdi-spin mdi-cog"></i>
              </button>
          </div> --}}

      </div>
  </div>
</header>