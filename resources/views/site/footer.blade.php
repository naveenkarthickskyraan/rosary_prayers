<!-- START FOOTER -->
<footer class="first-footer">
            <div class="top-footer">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-5 col-md-6">
                            <div class="netabout">
                                <a href="{{route('admin.home')}}" class="logo">
                                    <img src="{{asset('assets/site/images/logo-w.png')}}" alt="netcom">
                                </a>
                                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Cum incidunt architecto soluta laboriosam, perspiciatis, aspernatur officiis esse.</p>
                            </div>
                            <div class="contactus">
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
                                </ul>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="navigation">
                                <h3>Navigation</h3>
                                <div class="nav-footer">
                                    <ul>
                                        <li><a href="{{route('admin.home')}}">Home One</a></li>
                                        <li><a href="{{route('admin.faq')}}">Faq</a></li>
                                    </ul>
                                    <ul class="nav-right">
                                        
                                        <li><a href="{{route('admin.about')}}">About Us</a></li>
                                        <li class="no-mgb"><a href="{{route('admin.contactus')}}">Contact Us</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-3 col-md-6">
                            <div class="newsletters">
                                <h3>Newsletters</h3>
                                <p>Sign Up for Our Newsletter to get Latest Updates and Offers. Subscribe to receive news in your inbox.</p>
                            </div>
                            <form class="bloq-email mailchimp form-inline" method="post">
                                <label for="subscribeEmail" class="error"></label>
                                <div class="email">
                                    <input type="email" id="subscribeEmail" name="EMAIL" placeholder="Enter Your Email">
                                    <input type="submit" value="Subscribe">
                                    <p class="subscription-success"></p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="second-footer">
                <div class="container">
                    <p>2021 © Copyright - All Rights Reserved.</p>
                    <ul class="netsocials">
                        <li><a href="#"><i class="fa fa-facebook" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fa fa-twitter" aria-hidden="true"></i></a></li>
                        <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#"><i class="fa fa-youtube" aria-hidden="true"></i></a></li>
                    </ul>
                </div>
            </div>
        </footer>

        <!-- Modal -->
        <div class="modal fade" id="loginmodal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="card">
                        <div class="card-body">
                            <div class="text-center mt-4">
                                <div class="mb-3">
                                  <span class="text-dark fw-bold navbar-brand" style="font-size:20px;"> Rosary Prayers</span>
                                </div>
                            </div>
                            <div class="p-3">
                                <h4 class="font-size-18 text-muted mt-0 text-center">Welcome To Rosary Prayers</h4>
                                <p class="text-muted text-center mb-4"></p>

                                <form class="form-horizontal"   method="POST" action="{{ route('admin.auth') }}" novalidate>
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label text-muted" for="email">Email</label>
                                        <i class="mdi mdi-email"></i><input type="email"  name="email" class="form-control " id="email"
                                            placeholder="Enter email">
                                        @error('email')
                                            <span class="input-error text-danger fw-bold">{{ $errors->first('email') }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted" for="password">Password</label>
                                        <i class="mdi mdi-lock"></i><input type="password" name="password" class="form-control" id="password"
                                            placeholder="Enter password">
                                        @error('email')
                                            <span class="input-error text-danger fw-bold">{{ $errors->first('email') }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3 row mt-4">
                                        <div class="col-sm-6">
                                            <div class="form-checkbox">
                                                <input type="checkbox" class=""
                                                    id="customControlInline" required>
                                                <label class="form-label" class="form-check-label"
                                                    for="customControlInline">Remember me</label>
                                            </div>
                                        </div>
                                        <div class="col-sm-6 text-end">
                                            <button class="btn btn-primary w-md waves-effect waves-light"
                                                type="submit">Log In</button>
                                        </div>
                                    </div>
                                    
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <p class="text-muted">Don't have an account? <a href="" class="fw-bold text-black text-decoration-underline" data-bs-target="#registermodal" data-bs-toggle="modal" data-bs-dismiss="modal">sign up</a></p>
                </div>
                </div>
            </div>
            
        </div>

        <div class="modal fade" id="registermodal" aria-hidden="true" aria-labelledby="exampleModalToggleLabel2" tabindex="-1">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalToggleLabel2">Register</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <div class="card">
                        <div class="card-body">
                            <div class="text-center mt-4">
                                <div class="mb-3">
                                  <span class="text-dark fw-bold navbar-brand" style="font-size:20px;"> Rosary Prayers</span>
                                </div>
                            </div>
                            <div class="p-3">
                                <p class="text-muted text-center mb-4"></p>

                                <form class="form-horizontal"   method="POST" action="{{ route('admin.registersubmit') }}" novalidate>
                                    @csrf
                                    <div class="mb-3">
                                        <label class="form-label text-muted" for="email">Email</label>
                                        <i class="mdi mdi-email"></i><input type="email"  name="email" class="form-control " id="email"
                                            placeholder="Enter email">
                                        @error('email')
                                            <span class="input-error text-danger fw-bold">{{ $errors->first('email') }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted" for="password">Password</label>
                                        <i class="mdi mdi-lock"></i><input type="password" name="password" class="form-control" id="password"
                                            placeholder="Enter password">
                                        @error('password')
                                            <span class="input-error text-danger fw-bold">{{ $errors->first('password') }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted" for="phonenumber">phonenumber</label>
                                        <i class="mdi mdi-lock"></i><input type="password" name="phonenumber" class="form-control" id="phonenumber"
                                            placeholder="Enter phonenumber">
                                        @error('phonenumber')
                                            <span class="input-error text-danger fw-bold">{{ $errors->first('phonenumber') }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label text-muted" for="address">address</label>
                                        <i class="mdi mdi-lock"></i><input type="address" name="address" class="form-control" id="address"
                                            placeholder="Enter address">
                                        @error('address')
                                            <span class="input-error text-danger fw-bold">{{ $errors->first('address') }}</span>
                                        @enderror
                                    </div>

                                    <div class="mb-3 row mt-4">
                                        
                                        <div class=" text-center">
                                            <button class="btn btn-primary w-md waves-effect waves-light"
                                                type="submit">Sign In</button>
                                        </div>
                                    </div>
                                    
                                </form>

                            </div>

                        </div>
                    </div>
                </div>
                <div class="text-center">
                    <p class="text-muted">Already have an account? <a href="" class="fw-bold text-black text-decoration-underline" data-bs-target="#loginmodal" data-bs-toggle="modal" data-bs-dismiss="modal">Login</a></p>
                </div>
                </div>
            </div>
        </div>