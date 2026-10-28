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


<body class="inner-pages hd-white about">


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
        @include('site.header2')
        <div class="clearfix"></div>
        <!-- Header Container / End -->

        <section class="headings">
            <div class="text-heading text-center">
                <div class="container">
                <h1>About Our Company</h1>
                    <h2><a href="index.html">Home </a> &nbsp;/&nbsp; About Us</h2>
                </div>
            </div>
        </section>
        <!-- END SECTION HEADINGS -->

         <!-- START SECTION ABOUT -->
         <section class="about-us fh">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-6">
                        <div>
                            <h2 class="text-left mb-4">About <span>Our nest</span></h2>
                        </div>
                        <div class="pftext">
                            <p>Welcome to Our Nest Real Estate LLC – Elevating Your Real Estate Experience!
                                At Our Nest Real Estate LLC, we take pride in being more than just a real estate 
                                agency; we are your dedicated partners in turning your property dreams into reality.
                             With a commitment to excellence, personalized service, and a passion for exceeding 
                             expectations, Our Nest Real Estate LLC is here to guide you through every step of your 
                             real estate journey.</p>
                        </div>
                        <div class="box bg-2">
                            <a href="about.html" class="text-center button button--moema button--text-thick button--text-upper button--size-s">read More</a>
                            <img src="" class="ml-5" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6 col-xs-12">
                        <div class="wprt-image-video w50">
                            <img alt="image" src="{{ asset('assets/site/images/logo-b.png')}}">
                            <a class="icon-wrap popup-video popup-youtube" href="https://www.youtube.com/watch?v=2xHQqYRcrx4">
                                <i class="fa fa-play"></i>
                            </a>
                            <div class="iq-waves">
                                <div class="waves wave-1"></div>
                                <div class="waves wave-2"></div>
                                <div class="waves wave-3"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END SECTION ABOUT -->

        <!-- START SECTION WHY CHOOSE US -->
        <section class="how-it-works bg-white-2">
            <div class="container">
                <div class="sec-title">
                    <h2>Why Choose Our Nest Real Estate LLC:</h2>
                    <p>We provide full service at every step.</p>
                </div>
                <div class="row service-1">
                    <article class="col-lg-4 col-md-6 col-xs-12 serv mb-4" data-aos="fade-up">
                        <div class="serv-flex">
                            <div class="art-1 img-13">
                                <img src="{{ asset('assets/site/images/icons/client-centric.png')}}" alt="">
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
                    <article class="col-lg-4 col-md-6 col-xs-12 serv mb-4" data-aos="fade-up">
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
                    <article class="col-lg-4 col-md-6 col-xs-12 serv mb-4 pt" data-aos="fade-up">
                        <div class="serv-flex arrow">
                            <div class="art-1 img-15">
                                <img src="{{ asset('assets/site/images/icons/innovation-tech.png')}}" alt="">
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

                    <article class="col-lg-4 col-md-6 col-xs-12 serv mb-4 pt" data-aos="fade-up">
                        <div class="serv-flex arrow">
                            <div class="art-1 img-15">
                                <img src="{{ asset('assets/site/images/icons/honest-transation.png')}}" alt="">
                                <h3>Transparent and Honest Transactions:</h3>
                            </div>
                            <div class="service-text-p">
                                <p class="text-center">Trust is the cornerstone of any successful real estate 
                                    relationship. Our Nest Real Estate LLC is committed to transparency and honesty 
                                    in all our dealings. You can rely on us for straightforward communication, 
                                    ethical practices, and a commitment to your best interests.</p>
                            </div>
                        </div>
                    </article>

                    <article class="col-lg-4 col-md-6 col-xs-12 serv mb-4  pt" data-aos="fade-up">
                        <div class="serv-flex arrow">
                            <div class="art-1 img-15">
                                <img src="{{ asset('assets/site/images/icons/global-reach.png')}}" alt="">
                                <h3>Local Expertise, Global Reach:</h3>
                            </div>
                            <div class="service-text-p">
                                <p class="text-center">While Our Nest Real Estate LLC is deeply rooted in the local 
                                    community, our reach extends globally. Our agents combine a deep understanding
                                     of local markets with a broader perspective, ensuring that you receive insights
                                      that align with your unique preferences and goals.</p>
                            </div>
                        </div>
                    </article>

                    <article class="col-lg-4 col-md-6 col-xs-12 serv mb-4 pt" data-aos="fade-up">
                        <div class="serv-flex arrow">
                            <div class="art-1 img-15">
                                <img src="{{ asset('assets/site/images/icons/real-estate.png')}}" alt="">
                                <h3>Comprehensive Real Estate Solutions:</h3>
                            </div>
                            <div class="service-text-p">
                                <p class="text-center">Whether you're buying, selling, or investing, Our Nest Real 
                                    Estate LLC provides comprehensive solutions tailored to your specific needs. 
                                    We guide you through the intricacies of the real estate process, offering 
                                    support and expertise at every turn.</p>
                            </div>
                        </div>
                    </article>
                </div>
            </div>
        </section>
        <!-- END SECTION WHY CHOOSE US -->

        <section class="team">
            <div class="container">
                <div class="sec-title">
                    <h2><span>Unlocking Your Real Estate Journey:</span> Our Nest Real Estate LLC</h2>
                    <p>Discover the Our Nest Real Estate LLC difference – where your vision meets our dedication. Whether you're searching for your dream home or seeking the perfect buyer for your property, trust Our Nest Real Estate LLC to be your unwavering partner in achieving your real estate aspirations.
                        Unlock the door to a new chapter in your real estate journey with Our Nest Real Estate LLC – Your Home, Your Nest!</p>
                </div>
                
            </div>
        </section>

        <!-- START SECTION COUNTER UP -->
        <section class="counterup">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="countr">
                            <i class="fa fa-home" aria-hidden="true"></i>
                            <div class="count-me">
                                <p class="counter text-left">300</p>
                                <h3>Sold Houses</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="countr">
                            <i class="fa fa-list" aria-hidden="true"></i>
                            <div class="count-me">
                                <p class="counter text-left">400</p>
                                <h3>Daily Listings</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="countr mb-0">
                            <i class="fa fa-users" aria-hidden="true"></i>
                            <div class="count-me">
                                <p class="counter text-left">250</p>
                                <h3>Expert Agents</h3>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 col-xs-12">
                        <div class="countr mb-0 last">
                            <i class="fa fa-trophy" aria-hidden="true"></i>
                            <div class="count-me">
                                <p class="counter text-left">200</p>
                                <h3>Won Awars</h3>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END SECTION COUNTER UP -->

        <!-- START SECTION AGENTS -->
        <section class="team">
            <div class="container">
                <div class="sec-title">
                    <h2><span>Our </span>Team</h2>
                    <p>We provide full service at every step.</p>
                </div>
                <div class="row team-all">
                    <div class="col-lg-3 col-md-6 team-pro">
                        <div class="team-wrap">
                            <div class="team-img">
                                <img src="{{ asset('assets/site/images/team/t-5.jpg')}}" alt="" />
                            </div>
                            <div class="team-content">
                                <div class="team-info">
                                    <h3>Carls Jhons</h3>
                                    <p>Financial Advisor</p>
                                    <div class="team-socials">
                                        <ul>
                                            <li>
                                                <a href="#" title="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                                <a href="#" title="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                                <a href="#" title="instagran"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <span><a href="#">View Profile</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 team-pro">
                        <div class="team-wrap">
                            <div class="team-img">
                                <img src="{{ asset('assets/site/images/team/t-6.jpg')}}" alt="" />
                            </div>
                            <div class="team-content">
                                <div class="team-info">
                                    <h3>Arling Tracy</h3>
                                    <p>Acountant</p>
                                    <div class="team-socials">
                                        <ul>
                                            <li>
                                                <a href="#" title="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                                <a href="#" title="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                                <a href="#" title="instagran"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <span><a href="#">View Profile</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 team-pro pb-none">
                        <div class="team-wrap">
                            <div class="team-img">
                                <img src="{{ asset('assets/site/images/team/t-7.jpg')}}" alt="" />
                            </div>
                            <div class="team-content">
                                <div class="team-info">
                                    <h3>Mark Web</h3>
                                    <p>Founder &amp; CEO</p>
                                    <div class="team-socials">
                                        <ul>
                                            <li>
                                                <a href="#" title="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                                <a href="#" title="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                                <a href="#" title="instagran"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <span><a href="#">View Profile</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 team-pro kat">
                        <div class="team-wrap">
                            <div class="team-img">
                                <img src="{{ asset('assets/site/images/team/t-8.jpg')}}" alt="" />
                            </div>
                            <div class="team-content">
                                <div class="team-info">
                                    <h3>Katy Grace</h3>
                                    <p>Team Leader</p>
                                    <div class="team-socials">
                                        <ul>
                                            <li>
                                                <a href="#" title="facebook"><i class="fa fa-facebook" aria-hidden="true"></i></a>
                                                <a href="#" title="twitter"><i class="fa fa-twitter" aria-hidden="true"></i></a>
                                                <a href="#" title="instagran"><i class="fa fa-instagram" aria-hidden="true"></i></a>
                                            </li>
                                        </ul>
                                    </div>
                                    <span><a href="#">View Profile</a></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- END SECTION AGENTS -->

        <!-- START SECTION TESTIMONIALS -->
        <section class="testimonials home18 bg-white">
            <div class="container">
               <div class="sec-title">
                    <h2><span>Clients </span>Testimonials</h2>
                    <p>We collect reviews from our customers.</p>
                </div>
                <div class="owl-carousel style1">
                    <div class="test-1 pb-0 pt-0">
                        <img src="{{ asset('assets/site/images/testimonials/ts-1.jpg')}}" alt="">
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
                        <img src="{{ asset('assets/site/images/testimonials/ts-2.jpg')}}" alt="">
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
                        <img src="{{ asset('assets/site/images/testimonials/ts-4.jpg')}}" alt="">
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
                        <img src="{{ asset('assets/site/images/testimonials/ts-5.jpg')}}" alt="">
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
                        <img src="{{ asset('assets/site/images/testimonials/ts-6.jpg')}}" alt="">
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
        <div class="partners bg-white-2">
            <div class="container">
                <div class="sec-title">
                    <h2><span>Our </span>Partners</h2>
                    <p>The Companies That Represent Us.</p>
                </div>
                <div class="owl-carousel style2">
                    <div class="owl-item"><img src="{{ asset('assets/site/images/partners/11.jpg')}}" alt=""></div>
                    <div class="owl-item"><img src="{{ asset('assets/site/images/partners/12.jpg')}}" alt=""></div>
                    <div class="owl-item"><img src="{{ asset('assets/site/images/partners/13.jpg')}}" alt=""></div>
                    <div class="owl-item"><img src="{{ asset('assets/site/images/partners/14.jpg')}}" alt=""></div>
                    <div class="owl-item"><img src="{{ asset('assets/site/images/partners/15.jpg')}}" alt=""></div>
                    <div class="owl-item"><img src="{{ asset('assets/site/images/partners/16.jpg')}}" alt=""></div>
                    <div class="owl-item"><img src="{{ asset('assets/site/images/partners/17.jpg')}}" alt=""></div>
                    <div class="owl-item"><img src="{{ asset('assets/site/images/partners/11.jpg')}}" alt=""></div>
                    <div class="owl-item"><img src="{{ asset('assets/site/images/partners/12.jpg')}}" alt=""></div>
                    <div class="owl-item"><img src="{{ asset('assets/site/images/partners/13.jpg')}}" alt=""></div>
                </div>
            </div>
        </div>
       
        @include('site.footer')

        <a data-scroll href="#wrapper" class="go-up"><i class="fa fa-angle-double-up" aria-hidden="true"></i></a>
        <!-- END FOOTER -->

        
        <!--register form end -->

        @include('site.bottomcdn')
        <script>
            $(".accordion li").click(function() {
                $(this).closest(".accordion").hasClass("one-open") ? ($(this).closest(".accordion").find("li").removeClass("active"), $(this).addClass("active")) : $(this).toggleClass("active"), "undefined" != typeof window.mr_parallax && setTimeout(mr_parallax.windowLoad, 500)
            });
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