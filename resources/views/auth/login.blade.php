@extends('layouts.admin_app')

{{-- @section('content')
<div class="container">
    <div class="row justify-content-center">
        @if(Session::get('error'))
        <div class="col-md-8">
            <div class="alert alert-danger">{{ Session::get('error') }}</div>    
        </div>        
        @endif
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">ADMIN {{ __('Login') }}</div>
                
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.auth') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection --}}
@section('content')
<html>
    <head>
        <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">

        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.bundle.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
        <link rel="stylesheet" type="text/css" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css">
    </head>
    <style type="text/css">
    body 
    {
        margin: 0;
        padding: 0;
        font-family: sans-serif;
        background: linear-gradient(to right, #b92b27, #1565c0)
    }
    .card
    {
        margin-bottom:15px;
        border:none;
    }

    .box 
    {
        width: 400px;
        padding: 50px;
        position: absolute;
        top: 50%;
        left: 50%;
        background: rgb(20, 19, 19);
        text-align: center;
        transition: 0.25s;
        margin-top: 60px
    }

    .box input[type="email"],
    .box input[type="password"] 
    {
        border: 0;
        background: none;
        display: block;
        margin: 20px auto;
        text-align: center;
        border: 2px solid #3498db;
        padding: 10px 10px;
        width: 250px;
        outline: none;
        color: white;
        border-radius: 24px;
        transition: 0.25s
    }

    .box h1 {
        color: white;
        text-transform: uppercase;
        font-weight: 400
    }

    .box input[type="email"]:focus,
    .box input[type="password"]:focus {
        width: 300px;
        border-color: #2ecc71
    }

    .box input[type="submit"] {
        border: 0;
        background: none;
        display: block;
        margin: 20px auto;
        text-align: center;
        border: 2px solid #2ecc71;
        padding: 14px 40px;
        outline: none;
        color: white;
        border-radius: 24px;
        transition: 0.25s;
        cursor: pointer
    }

    .box input[type="submit"]:hover {
        background: #2ecc71
    }

    .forgot {
        text-decoration: underline
    }

    ul.social-network {
        list-style: none;
        display: inline;
        margin-left: 0 !important;
        padding: 0
    }

    ul.social-network li {
        display: inline;
        margin: 0 5px
    }

    .social-network a.icoFacebook:hover {
        background-color: #3B5998
    }

    .social-network a.icoTwitter:hover {
        background-color: #33ccff
    }

    .social-network a.icoGoogle:hover {
        background-color: #BD3518
    }

    .social-network a.icoFacebook:hover i,
    .social-network a.icoTwitter:hover i,
    .social-network a.icoGoogle:hover i {
        color: #fff
    }

    a.socialIcon:hover,
    .socialHoverClass {
        color: #44BCDD
    }

    .social-circle li a {
        display: inline-block;
        position: relative;
        margin: 0 auto 0 auto;
        border-radius: 50%;
        text-align: center;
        width: 50px;
        height: 50px;
        font-size: 20px
    }

    .social-circle li i {
        margin: 0;
        line-height: 50px;
        text-align: center
    }

    .social-circle li a:hover i,
    .triggeredHover {
        transform: rotate(360deg);
        transition: all 0.2s
    }

    .social-circle i {
        color: rgb(218, 214, 229);
        transition: all 0.8s;
        transition: all 0.8s
    }
    </style>
<body>
    <div class="container-fluid"> 
        <div class="row"> 
            <div class="col-md-2"></div>
            @if(Session::get('error'))
            <div class="col-md-8">
                <div class="alert alert-danger alert-dismissible fade show" role="alert">{{Session::get('error')}}<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>    
            </div>    
            @endif
            <div class="col-md-2"></div>    
            <div class="col-md-9"> 
                <div class="card"> 
                    <form class="box" method="POST" action="{{ route('admin.auth') }}"> 
                        @csrf
                        <h2 class="text-white">Admin Login</h2> 
                        <p class="text-muted"> Please enter your login and password!</p> 
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter a Email" autofocus> 
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required placeholder="Enter a Password" autocomplete="current-password">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}/>
                        
                        <label class="text-muted">Remember me</label>
                        <input type="submit" value="login">
                         @if (Route::has('password.request'))
                        <a class="btn btn-link" href="{{ route('password.request') }}">
                            {{ __('Forgot Your Password?') }}
                        </a>
                        @endif
                        <div class="col-md-12"> 
                            <ul class="social-network social-circle"> 
                                <li><a href="#" class="icoFacebook" title="Facebook"><i class="fab fa-facebook-f">
                                    </i></a>
                                </li> 
                                <li>
                                    <a href="#" class="icoTwitter" title="Twitter"><i class="fab fa-twitter"></i></a>
                                </li> 
                                <li><a href="#" class="icoGoogle" title="Google +"><i class="fab fa-google-plus"></i></a>
                                </li> 
                            </ul> 
                        </div> 
                    </form> 
                </div> 
            </div> 
        </div>
    </div>
</body>
</html>
@endsection

