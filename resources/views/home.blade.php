<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta content="width=device-width, initial-scale=1.0" name="viewport">

  <title>Our nest  Dashboard</title>
  <meta content="" name="description">
  <meta content="" name="keywords">
  @include('site.topcdn')
</head>


<body data-sidebar="dark">


  <!-- Loader -->
  <div id="preloader">
      <div id="status">
          <div class="spinner"></div>
      </div>
  </div>
  
  <div id="layout-wrapper">
    <body class="homepage-4">
    <!-- Wrapper -->
    <div id="wrapper">
        <!-- START SECTION HEADINGS -->
        <!-- Header Container
        ================================================== -->
        @include('site.header')
        <div class="clearfix"></div>
        <!-- Header Container / End -->

        <!-- START SECTION HEADINGS -->
        <section id="home" class="section welcome-area bg-overlay d-flex align-items-center overflow-hidden">
            <div class="container">
                <div class="row align-items-center">
                    <!-- Welcome Intro Start -->
                    <div class="header-image left h1 col-12 col-md-7 col-lg-6">
                        <div class="welcome-intro" data-aos="fade-right">
                            <div class="left wow fadeInLeft">
                                <h1>The best way to find your dream home</h1>
                                <p>With over 400,000 active listings, ournest has the largest inventory of apartments in the United States..</p>
                                <a href="submit-property.html" class="btn-white">Get Started</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-5 col-lg-6">
                        <!-- Welcome Thumb -->
                        <div class="welcome-thumb text-center mx-auto" data-aos="fade-left">
                            <!-- Play Button -->
                            <a class="play-btn popup-video popup-youtube" data-fancybox href="https://www.youtube.com/watch?v=14semTlwyUY">
                                <div class="btn-circle play-animation"></div>
                                <div class="btn-circle play-animation animation-short"></div>
                                <!-- Play Icon -->
                                <div class="play-icon">
                                    <i class="fas fa-play"></i>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Shape Bottom -->
            <div class="shape-bottom">
                <svg fill="#fff" id="Layer_1" x="0px" y="0px" viewBox="0 0 1921 819.8" style="enable-background:new 0 0 1921 819.8;" xml:space="preserve">
                    <path class="st0" d="M1921,413.1v406.7H0V0.5h0.4l228.1,598.3c30,74.4,80.8,130.6,152.5,168.6c107.6,57,212.1,40.7,245.7,34.4  c22.4-4.2,54.9-13.1,97.5-26.6L1921,400.5V413.1z"></path>
                    <path class="st1" d="M1921,387.9v431.9H0V0h13l226.6,594.4c14.4,35.7,34,67.4,58.3,94.3c24.4,26.9,54.2,49.8,88.6,68.1  c50.2,26.6,105.8,40.1,165.2,40.1c36.1,0,63.6-5.2,72.6-6.9c21.6-4,53.9-12.9,96.1-26.2L1921,387.9z"></path>
                </svg>
            </div>
        </section>
        <!-- END SECTION HEADINGS -->

        <!-- START SECTION WHY CHOOSE US -->
        <section class="how-it-works bg-white-1 home18">
            <div class="container">
                <div class="sec-title">
                    <h2><span>Why </span>Choose Us</h2>
                    <p>We provide full service at every step.</p>
                </div>
                <div class="row service-1">
                    <article class="col-lg-4 col-md-6 col-xs-12 serv" data-aos="fade-up">
                        <div class="serv-flex">
                            <div class="art-1 img-13">
                                <img src="{{ asset('assets/site/images/icons/client-centric.png') }}" alt="">
                                <h3>Client-Centric Approach:</h3>
                            </div>
                            <div class="service-text-p">
                                <p class="text-center">At Our Nest Real Estate LLC, you are not just a client;
                                     you are a valued member of our community. We prioritize your unique needs,
                                      preferences, and goals, ensuring a personalized approach that goes beyond the transaction. 
                                      Your satisfaction is our ultimate measure of success.</p>
                            </div>
                        </div>
                    </article>
                    <article class="col-lg-4 col-md-6 col-xs-12 serv" data-aos="fade-up">
                        <div class="serv-flex">
                            <div class="art-1 img-14">
                                <img src="{{ asset('assets/site/images/icons/expert-team.png')}}" alt="">
                                <h3>Expert Team of Professionals:</h3>
                            </div>
                            <div class="service-text-p">
                                <p class="text-center">Our Nest Real Estate LLC boasts a team of seasoned professionals with diverse expertise in the real estate industry. Whether you're a first-time homebuyer, a seasoned investor, or selling your property,
                                     our team is equipped to provide you with comprehensive guidance and support.</p>
                            </div>
                        </div>
                    </article>
                    <article class="col-lg-4 col-md-6 col-xs-12 serv mb-0 pt" data-aos="fade-up">
                        <div class="serv-flex arrow">
                            <div class="art-1 img-15">
                                <img src="{{ asset('assets/site/images/icons/innovation-tech.png') }}" alt="">
                                <h3>Innovative Technology Solutions:</h3>
                            </div>
                            <div class="service-text-p">
                                <p class="text-center">Embracing cutting-edge technology, 
                                    Our Nest Real Estate LLC brings you the latest tools to enhance 
                                    your real estate experience. From virtual property tours to online marketing 
                                    strategies, we leverage technology to streamline processes, making your journey 
                                    efficient and enjoyable.</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>
        <!-- END SECTION WHY CHOOSE US -->

        <!-- START SECTION POPULAR PLACES -->
        <section class="popular-places home18 bg-white-1">
            <div class="container">
                <div class="sec-title">
                    <h2><span>Popular </span>Places</h2>
                    <p>Properties In Most Popular Places.</p>
                </div>
                <div class="row">
                    <div class="col-md-12">
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="fade-right">
                        <!-- Image Box -->
                        <a href="#" class="img-box hover-effect">
                            <img src="{{ asset('assets/site/images/popular-places/12.jpg')}}" class="img-responsive" alt="">
                            <!-- Badge -->
                            <div class="listing-badges">
                                <span class="featured">Featured</span>
                            </div>
                            <div class="img-box-content visible">
                                <h4>New York City </h4>
                                <span>203 Properties</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-8 col-md-6" data-aos="fade-left">
                        <!-- Image Box -->
                        <a href="#" class="img-box hover-effect">
                            <img src="{{ asset('assets/site/images/popular-places/13.jpg')}}" class="img-responsive" alt="">
                            <div class="img-box-content visible">
                                <h4>Los Angeles</h4>
                                <span>307 Properties</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-8 col-md-6" data-aos="fade-right">
                        <!-- Image Box -->
                        <a href="#" class="img-box hover-effect no-mb">
                            <img src="{{ asset('assets/site/images/popular-places/14.jpg') }}" class="img-responsive" alt="">
                            <div class="img-box-content visible">
                                <h4>San Francisco </h4>
                                <span>409 Properties</span>
                            </div>
                        </a>
                    </div>
                    <div class="col-lg-4 col-md-6" data-aos="fade-left">
                        <!-- Image Box -->
                        <a href="#" class="img-box hover-effect no-mb x3">
                            <img src="{{ asset('assets/site/images/popular-places/15.jpg')}}" class="img-responsive" alt="">
                            <!-- Badge -->
                            <div class="listing-badges">
                                <span class="featured">Featured</span>
                            </div>
                            <div class="img-box-content visible">
                                <h4>Miami</h4>
                                <span>507 Properties</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <!-- END SECTION POPULAR PLACES -->

        <!-- START SECTION INFO-HELP -->
        <section class="info-help">
            <div class="container">
                <div class="row info-head">
                    <div class="col-lg-4 col-md-6" data-aos="fade-right">
                        <div class="info-text">
                            <h3>Popular Properties</h3>
                            <p>We Help you find the best places and offer near you. Bring to the table win-win survival strategies to ensure proactive domination going forward.</p>
                            <div class="inf-btn pro">
                                <a href="contact-us.php" class="btn btn-pro btn-secondary btn-lg">Get Started</a>
                            </div>
                        </div>
                    </div>
                    <div class="featured portfolio home18 item col-lg-4 col-md-6 landscapes sale">
                        <div class="project-single specials" data-aos="fade-left">
                            <div class="project-inner project-head">
                                <div class="homes">
                                    <!-- homes img -->
                                    <a href="#" class="homes-img">
                                        <div class="homes-tag button alt featured">Featured</div>
                                        <div class="homes-tag button alt sale">For Sale</div>
                                        <div class="homes-price">$9,000/mo</div>
                                        <img src="{{asset('assets/site/images/blog/b-11.jpg')}}" alt="home-1" class="img-responsive">
                                    </a>
                                </div>
                                <div class="button-effect">
                                    <a href="#" class="btn"><i class="fa fa-link"></i></a>
                                    <a href="https://www.youtube.com/watch?v=14semTlwyUY" class="btn popup-video popup-youtube"><i class="fas fa-video"></i></a>
                                    <a href="#" class="img-poppu btn"><i class="fa fa-photo"></i></a>
                                </div>
                            </div>
                            <!-- homes content -->
                            <div class="homes-content">
                                <!-- homes address -->
                                <h3><a href="#">Real House Luxury Villa</a></h3>
                                <p class="homes-address mb-3">
                                    <a href="#">
                                        <i class="fa fa-map-marker"></i><span>Est St, 77 - Central Park South, NYC</span>
                                    </a>
                                </p>
                                <!-- homes List -->
                                <ul class="homes-list clearfix pb-0">
                                    <li class="the-icons">
                                        <i class="flaticon-bed mr-2" aria-hidden="true"></i>
                                        <span>6 Bedrooms</span>
                                    </li>
                                    <li class="the-icons">
                                        <i class="flaticon-bathtub mr-2" aria-hidden="true"></i>
                                        <span>3 Bathrooms</span>
                                    </li>
                                    <li class="the-icons">
                                        <i class="flaticon-square mr-2" aria-hidden="true"></i>
                                        <span>720 sq ft</span>
                                    </li>
                                    <li class="the-icons">
                                        <i class="flaticon-car mr-2" aria-hidden="true"></i>
                                        <span>2 Garages</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="featured portfolio home18 item col-lg-4 col-md-6 people landscapes rent no-pb">
                        <div class="project-single no-mb" data-aos="fade-left">
                            <div class="project-inner project-head">
                                <div class="homes">
                                    <!-- homes img -->
                                    <a href="#" class="homes-img">
                                        <div class="homes-tag button alt featured">Featured</div>
                                        <div class="homes-tag button sale rent">For Rent</div>
                                        <div class="homes-price">$3,000/mo</div>
                                        <img src="images/feature-properties/fp-10.jpg" alt="home-1" class="img-responsive">
                                    </a>
                                </div>
                                <div class="button-effect">
                                    <a href="#" class="btn"><i class="fa fa-link"></i></a>
                                    <a href="https://www.youtube.com/watch?v=14semTlwyUY" class="btn popup-video popup-youtube"><i class="fas fa-video"></i></a>
                                    <a href="#" class="img-poppu btn"><i class="fa fa-photo"></i></a>
                                </div>
                            </div>
                            <!-- homes content -->
                            <div class="homes-content">
                                <!-- homes address -->
                                <h3><a href="#">Real House Luxury Villa</a></h3>
                                <p class="homes-address mb-3">
                                    <a href="#">
                                        <i class="fa fa-map-marker"></i><span>Est St, 77 - Central Park South, NYC</span>
                                    </a>
                                </p>
                                <!-- homes List -->
                                <ul class="homes-list clearfix pb-0">
                                    <li class="the-icons">
                                        <i class="flaticon-bed mr-2" aria-hidden="true"></i>
                                        <span>6 Bedrooms</span>
                                    </li>
                                    <li class="the-icons">
                                        <i class="flaticon-bathtub mr-2" aria-hidden="true"></i>
                                        <span>3 Bathrooms</span>
                                    </li>
                                    <li class="the-icons">
                                        <i class="flaticon-square mr-2" aria-hidden="true"></i>
                                        <span>720 sq ft</span>
                                    </li>
                                    <li class="the-icons">
                                        <i class="flaticon-car mr-2" aria-hidden="true"></i>
                                        <span>2 Garages</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END SECTION INFO-HELP -->

        <!-- START SECTION TESTIMONIALS -->
        <section class="testimonials home18 bg-white-1">
            <div class="container">
                <div class="sec-title">
                    <h2><span>Clients </span>Testimonials</h2>
                    <p>We collect reviews from our customers.</p>
                </div>
                <div class="owl-carousel style1">
                    <div class="test-1 pb-0 pt-0" data-aos="zoom-in">
                        <img src="{{asset('assets/site/images/testimonials/ts-1.jpg')}}" alt="">
                        <h3 class="mt-3 mb-0">Lisa Smith</h3>
                        <h6 class="mt-1">New York</h6>
                        <ul class="starts text-center mb-2">
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                        </ul>
                        <p>Lorem ipsum dolor sit amet, ligula magna at etiam aliquip venenatis. Vitae sit felis donec, suscipit tortor et sapien donec.</p>
                    </div>
                    <div class="test-1 pb-0 pt-0">
                        <img src="{{asset('assets/site/images/testimonials/ts-2.jpg')}}" alt="">
                        <h3 class="mt-3 mb-0">Jhon Morris</h3>
                        <h6 class="mt-1">Los Angeles</h6>
                        <ul class="starts text-center mb-2">
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star-o"></i>
                            </li>
                        </ul>
                        <p>Lorem ipsum dolor sit amet, ligula magna at etiam aliquip venenatis. Vitae sit felis donec, suscipit tortor et sapien donec.</p>
                    </div>
                    <div class="test-1 pt-0">
                        <img src="{{ asset('assets/site/images/testimonials/ts-3.jpg')}}" alt="">
                        <h3 class="mt-3 mb-0">Mary Deshaw</h3>
                        <h6 class="mt-1">Chicago</h6>
                        <ul class="starts text-center mb-2">
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                        </ul>
                        <p>Lorem ipsum dolor sit amet, ligula magna at etiam aliquip venenatis. Vitae sit felis donec, suscipit tortor et sapien donec.</p>
                    </div>
                    <div class="test-1 pt-0">
                        <img src="{{asset('assets/site/images/testimonials/ts-4.jpg')}}" alt="">
                        <h3 class="mt-3 mb-0">Gary Steven</h3>
                        <h6 class="mt-1">Philadelphia</h6>
                        <ul class="starts text-center mb-2">
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star-o"></i>
                            </li>
                        </ul>
                        <p>Lorem ipsum dolor sit amet, ligula magna at etiam aliquip venenatis. Vitae sit felis donec, suscipit tortor et sapien donec.</p>
                    </div>
                    <div class="test-1 pt-0">
                        <img src="{{asset('assets/site/images/testimonials/ts-5.jpg')}}" alt="">
                        <h3 class="mt-3 mb-0">Cristy Mayer</h3>
                        <h6 class="mt-1">San Francisco</h6>
                        <ul class="starts text-center mb-2">
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                        </ul>
                        <p>Lorem ipsum dolor sit amet, ligula magna at etiam aliquip venenatis. Vitae sit felis donec, suscipit tortor et sapien donec.</p>
                    </div>
                    <div class="test-1 pt-0">
                        <img src="{{asset('assets/site/images/testimonials/ts-6.jpg')}}" alt="">
                        <h3 class="mt-3 mb-0">Ichiro Tasaka</h3>
                        <h6 class="mt-1">Houston</h6>
                        <ul class="starts text-center mb-2">
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star"></i>
                            </li>
                            <li><i class="fa fa-star-o"></i>
                            </li>
                        </ul>
                        <p>Lorem ipsum dolor sit amet, ligula magna at etiam aliquip venenatis. Vitae sit felis donec, suscipit tortor et sapien donec.</p>
                    </div>
                </div>
            </div>
        </section>
        <!-- END SECTION TESTIMONIALS -->


        <!-- STAR SECTION PARTNERS -->
        <div class="partners bg-white-1 home18">
            <div class="container">
                <div class="sec-title">
                    <h2><span>Our </span>Partners</h2>
                    <p>The Companies That Represent Us.</p>
                </div>
                <div class="owl-carousel style2">
                    <div class="owl-item" data-aos="fade-up"><img src="{{asset('assets/site/images/partners/11.jpg')}}" alt=""></div>
                    <div class="owl-item" data-aos="fade-up"><img src="{{asset('assets/site/images/partners/12.jpg')}}" alt=""></div>
                    <div class="owl-item" data-aos="fade-up"><img src="{{asset('assets/site/images/partners/13.jpg')}}" alt=""></div>
                    <div class="owl-item" data-aos="fade-up"><img src="{{asset('assets/site/images/partners/14.jpg')}}" alt=""></div>
                    <div class="owl-item" data-aos="fade-up"><img src="{{asset('assets/site/images/partners/15.jpg')}}" alt=""></div>
                    <div class="owl-item" data-aos="fade-up"><img src="{{asset('assets/site/images/partners/16.jpg')}}" alt=""></div>
                    <div class="owl-item" data-aos="fade-up"><img src="{{asset('assets/site/images/partners/17.jpg')}}" alt=""></div>
                    <div class="owl-item" data-aos="fade-up"><img src="{{asset('assets/site/images/partners/11.jpg')}}" alt=""></div>
                    <div class="owl-item" data-aos="fade-up"><img src="{{asset('assets/site/images/partners/12.jpg')}}" alt=""></div>
                    <div class="owl-item" data-aos="fade-up"><img src="{{asset('assets/site/images/partners/13.jpg')}}" alt=""></div>
                </div>
            </div>
        </div>
        <!-- END SECTION PARTNERS -->

        @include('site.footer')

        <a data-scroll href="#wrapper" class="go-up"><i class="fa fa-angle-double-up" aria-hidden="true"></i></a>
        <!-- END FOOTER -->

        <!--register form end -->

        @include('site.bottomcdn')
        <script>
            $('.style1').owlCarousel({
                loop: true,
                margin: 10,
                autoplay: false,
                autoplayTimeout: 5000,
                responsive: {
                    0: {
                        items: 1
                    },
                    400: {
                        items: 1,
                        margin: 20
                    },
                    500: {
                        items: 1,
                        margin: 20
                    },
                    768: {
                        items: 1,
                        margin: 20
                    },
                    991: {
                        items: 1,
                        margin: 20
                    },
                    1000: {
                        items: 1,
                        margin: 20
                    }
                }
            });

        </script>


    </div>
    <!-- Wrapper / End -->
</body>