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


<body class="inner-pages hd-white contactus">


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
                    <h1>Contact Us</h1>
                    <h2><a href="index.html">Home </a> &nbsp;/&nbsp; Contact Us</h2>
                </div>
            </div>
        </section>
        <!-- END SECTION HEADINGS -->

        <section class="contact-us">
            <div class="container">
                <div class="property-location mb-5">
                    <h3>Our Location</h3>
                    <div class="divider-fade">
                        
                    </div>
                    <div id="map-contact" class="contact-map"></div>
                </div>
                <div class="row">
                    <div class="col-lg-8 col-md-12"> 
                        <h3 class="mb-4">Contact Us</h3>
                        <form id="contactform" action="" class="contact-form" name="contactform" method="post" novalidate>
                            @csrf
                            <!-- <div id="success" class="successform">
                                <p class="alert alert-success font-weight-bold" role="alert">Your message was sent successfully!</p>
                            </div>
                            <div id="error" class="errorform">
                                <p>Something went wrong, try refreshing and submitting the form again.</p>
                            </div>  -->
                            <div class="form-group">
                                <input type="text" required class="form-control input-custom input-full" name="name" placeholder="First Name">
                            </div>
                            <div class="form-group">
                                <input type="text" required class="form-control input-custom input-full" name="lastname" placeholder="Last Name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control input-custom input-full" name="email" placeholder="Email">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control textarea-custom input-full" id="ccomment" name="message" required rows="8" placeholder="Message"></textarea>
                            </div>
                            <button type="submit" id="submit-contact" class="btn btn-primary btn-lg">Submit</button>
                        </form>
                        <div id="response" class="text-success fw-bold"></div>
                    </div>
                    <div class="col-lg-4 col-md-12 bgc">
                        <div class="call-info">
                            <h3>Contact Details</h3>
                            <p class="mb-5">Please find below contact details and contact us today!</p>
                            <ul>
                                <li>
                                    <div class="info">
                                        <i class="fa fa-map-marker" aria-hidden="true"></i>
                                        <p class="in-p">
                                                Mabroor business center Phase - 1,
                                                Block - J,<br>
                                                Unit # 7 - 8,<br>
                                                Dubai Industrial City,<br>
                                                Dubai. U.A.E
                                            </p>
                                    </div>
                                </li>
                                <li>
                                    <div class="info">
                                        <i class="fa fa-phone" aria-hidden="true"></i>
                                        <p class="in-p">+456 875 369 208</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="info">
                                        <i class="fa fa-envelope" aria-hidden="true"></i>
                                        <p class="in-p ti">support@ournest.com</p>
                                    </div>
                                </li>
                                <li>
                                    <div class="info cll">
                                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                                        <p class="in-p ti">8:00 a.m - 9:00 p.m</p>
                                    </div>
                                </li>
                            </ul>
                        </div>
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
           $(document).ready(function(){
            $('#contactform').submit(function(event){
                // Prevent default form submission
                event.preventDefault();

                // Serialize form data
                var formData = $(this).serialize();

                // Send AJAX request
                $.ajax({
                    type: 'POST',
                    url: '{{url("/")}}/contactform', // Change this to the PHP file handling the form submission
                    data: formData,
                    success: function(response){
                        // if(response.send){
                        //     $('#response').html(response.message);
                        // } else{
                        //     $('#response').html(response.message);
                        // }
                        $('#contactform').css('opacity', '1');
                        $('#response').html(response.message);
                    }
                });
            });
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