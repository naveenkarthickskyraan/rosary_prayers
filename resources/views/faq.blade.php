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


<body class="inner-pages ui-elements hd-white faqs">


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
                    <h1>FAQ’S</h1>
                    <h2><a href="index.html">Home </a> &nbsp;/&nbsp; FAQ’S</h2>
                </div>
            </div>
        </section>
        <!-- END SECTION HEADINGS -->

        <!-- START SECTION FAQ -->
        <section class="faq service-details bg-white">
            <div class="container">
                <h3 class="mb-5">FREQUENTLY ASKED QUESTIONS</h3>
                <div class="row">
                    <div class="col-lg-12 col-md-12">
                        <h5 class="uppercase mb40">Questions about Selling</h5>
                        <ul class="accordion accordion-1 one-open">
                            <li class="active">
                                <div class="title">
                                    <span>What payment methods are available?</span>
                                </div>
                                <div class="content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span>How can i get ournest aid to live off campus?</span>
                                </div>
                                <div class="content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span>Does ournest share my information with others?</span>
                                </div>
                                <div class="content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span>What kind of real estate advice do you give?</span>
                                </div>
                                <div class="content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span>How do i link multiple accounts with my profile?</span>
                                </div>
                                <div class="content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span>What kind of real estate advice do you give?</span>
                                </div>
                                <div class="content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span>Is your advice really be helf full?</span>
                                </div>
                                <div class="content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span>How can i get real estate aid to live off campus?</span>
                                </div>
                                <div class="content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                    </p>
                                </div>
                            </li>
                            <li>
                                <div class="title">
                                    <span>Does realhome share my information with others?</span>
                                </div>
                                <div class="content">
                                    <p>
                                        Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.
                                    </p>
                                </div>
                            </li>
                        </ul>
                        <!--end of accordion-->
                    </div>
                </div>
            </div>
        </section>
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