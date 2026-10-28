<!DOCTYPE html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
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
    <!-- End layout styles -->
    <link rel="shortcut icon" href="{{ asset('assets/images/Proposed.jpg')}}"/>
  </head>
  <body>
    <div class="container-scroller" >
        @include('sidebar')
        <div class="container-fluid page-body-wrapper">
            @include('navbar')
            <!-- partial -->
            <div class="main-panel">
            <div class="content-wrapper">     
              <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                  <li class="breadcrumb-item"><i class="mdi mdi-home-map-marker"></i></li>
                  <li class="breadcrumb-item"><a href="#">Dashboard</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Admin</li>
                </ol>           
              </nav>    
              <div class="row">
                <div class="col-md-6 col-xl-4 grid-margin stretch-card">
                  <div class="card">
                    @if(session()->get('message'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <strong>Success:</strong> {{session()->get('message')}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif
                    <div class="card-body">
                      <div class="d-flex flex-row justify-content-between">
                        <h4 class="card-title">Messages</h4>
                        <p class="text-muted mb-1 small">View all</p>
                      </div>
                      <div class="preview-list">
                        <div class="preview-item border-bottom">
                          <div class="preview-thumbnail">
                            <img src="assets/images/faces/face6.jpg" alt="image" class="rounded-circle" />
                          </div>
                          <div class="preview-item-content d-flex flex-grow">
                            <div class="flex-grow">
                              <div class="d-flex d-md-block d-xl-flex justify-content-between">
                                <h6 class="preview-subject">Leonard</h6>
                                <p class="text-muted text-small">5 minutes ago</p>
                              </div>
                              <p class="text-muted">Well, it seems to be working now.</p>
                            </div>
                          </div>
                        </div>
                        <div class="preview-item border-bottom">
                          <div class="preview-thumbnail">
                            <img src="assets/images/faces/face8.jpg" alt="image" class="rounded-circle" />
                          </div>
                          <div class="preview-item-content d-flex flex-grow">
                            <div class="flex-grow">
                              <div class="d-flex d-md-block d-xl-flex justify-content-between">
                                <h6 class="preview-subject">Luella Mills</h6>
                                <p class="text-muted text-small">10 Minutes Ago</p>
                              </div>
                              <p class="text-muted">Well, it seems to be working now.</p>
                            </div>
                          </div>
                        </div>
                        <div class="preview-item border-bottom">
                          <div class="preview-thumbnail">
                            <img src="assets/images/faces/face9.jpg" alt="image" class="rounded-circle" />
                          </div>
                          <div class="preview-item-content d-flex flex-grow">
                            <div class="flex-grow">
                              <div class="d-flex d-md-block d-xl-flex justify-content-between">
                                <h6 class="preview-subject">Ethel Kelly</h6>
                                <p class="text-muted text-small">2 Hours Ago</p>
                              </div>
                              <p class="text-muted">Please review the tickets</p>
                            </div>
                          </div>
                        </div>
                        <div class="preview-item border-bottom">
                          <div class="preview-thumbnail">
                            <img src="assets/images/faces/face11.jpg" alt="image" class="rounded-circle" />
                          </div>
                          <div class="preview-item-content d-flex flex-grow">
                            <div class="flex-grow">
                              <div class="d-flex d-md-block d-xl-flex justify-content-between">
                                <h6 class="preview-subject">Herman May</h6>
                                <p class="text-muted text-small">4 Hours Ago</p>
                              </div>
                              <p class="text-muted">Thanks a lot. It was easy to fix it .</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-xl-4 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">Portfolio Slide</h4>
                      <div class="owl-carousel owl-theme full-width owl-carousel-dash portfolio-carousel" id="owl-carousel-basic">
                        <div class="item">
                          <img src="assets/images/dashboard/Rectangle.jpg" alt="">
                        </div>
                        <div class="item">
                          <img src="assets/images/dashboard/Img_5.jpg" alt="">
                        </div>
                        <div class="item">
                          <img src="assets/images/dashboard/img_6.jpg" alt="">
                        </div>
                      </div>
                      <div class="d-flex py-4">
                        <div class="preview-list w-100">
                          <div class="preview-item p-0">
                            <div class="preview-thumbnail">
                              <img src="assets/images/faces/face12.jpg" class="rounded-circle" alt="">
                            </div>
                            <div class="preview-item-content d-flex flex-grow">
                              <div class="flex-grow">
                                <div class="d-flex d-md-block d-xl-flex justify-content-between">
                                  <h6 class="preview-subject">CeeCee Bass</h6>
                                  <p class="text-muted text-small">4 Hours Ago</p>
                                </div>
                                <p class="text-muted">Well, it seems to be working now.</p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <p class="text-muted">Well, it seems to be working now. </p>
                      <div class="progress progress-md portfolio-progress">
                        <div class="progress-bar bg-success" role="progressbar" style="width: 50%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12 col-xl-4 grid-margin stretch-card">
                  <div class="card">
                    <div class="card-body">
                      <h4 class="card-title">To do list</h4>
                      <div class="add-items d-flex">
                        <input type="text" class="form-control todo-list-input" placeholder="enter task..">
                        <button class="add btn btn-primary todo-list-add-btn">Add</button>
                      </div>
                      <div class="list-wrapper">
                        <ul class="d-flex flex-column-reverse text-white todo-list todo-list-custom">
                          <li>
                            <div class="form-check form-check-primary">
                              <label class="form-check-label">
                                <input class="checkbox" type="checkbox"> Create invoice </label>
                            </div>
                            <i class="remove mdi mdi-close-box"></i>
                          </li>
                          <li>
                            <div class="form-check form-check-primary">
                              <label class="form-check-label">
                                <input class="checkbox" type="checkbox"> Meeting with Alita </label>
                            </div>
                            <i class="remove mdi mdi-close-box"></i>
                          </li>
                          <li class="completed">
                            <div class="form-check form-check-primary">
                              <label class="form-check-label">
                                <input class="checkbox" type="checkbox" checked> Prepare for presentation </label>
                            </div>
                            <i class="remove mdi mdi-close-box"></i>
                          </li>
                          <li>
                            <div class="form-check form-check-primary">
                              <label class="form-check-label">
                                <input class="checkbox" type="checkbox"> Plan weekend outing </label>
                            </div>
                            <i class="remove mdi mdi-close-box"></i>
                          </li>
                          <li>
                            <div class="form-check form-check-primary">
                              <label class="form-check-label">
                                <input class="checkbox" type="checkbox"> Pick up kids from school </label>
                            </div>
                            <i class="remove mdi mdi-close-box"></i>
                          </li>
                        </ul>
                      </div>
                    </div>
                  </div>
                </div>
              </div>  
            </div>
            <!-- content-wrapper ends -->
            
            <!-- partial:partials/_footer.html -->
            <footer class="footer">
                <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted d-block text-center text-sm-left d-sm-inline-block">Copyright © bootstrapdash.com 2021</span>
                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center"> Free <a href="https://www.bootstrapdash.com/bootstrap-admin-template/" target="_blank">Bootstrap admin template</a> from Bootstrapdash.com</span>
                </div>
            </footer>
            <!-- partial -->
            </div>
            <!-- main-panel ends -->
        </div>
        <!-- page-body-wrapper ends -->
    </div>
   
    <!-- container-scroller -->
    <!-- plugins:js -->
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
    <!-- End custom js for this page -->
  </body>
</html>