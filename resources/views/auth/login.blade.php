<!DOCTYPE html>
<html dir="ltr">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="robots" content="noindex, nofollow" />
    <!-- Favicon icon -->
    <link rel="icon" href="{{ URL::asset('assets/images/logoTab.PNG') }}">
    <title>{{ env('APP_NAME') }}</title>
    <!-- Custom CSS -->
    <link href="{{ URL::asset('assets/extra-libs/c3/c3.min.css') }}" rel="stylesheet">


    <!-- Custom CSS -->
    <link href="{{ URL::asset('assets/dist/css/style.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('assets/custom/css/custom.css') }}" rel="stylesheet">



    <link rel="stylesheet" href="{{ URL::asset('assets/dist/css/animate.min.css') }}">
    <script src="{{ URL::asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ URL::asset('assets/dist/js/bootstrap-notify.js') }}"></script>
</head>

<body>
<div class="main-wrapper">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="lds-ripple">
            <div class="lds-pos"></div>
            <div class="lds-pos"></div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- Login box.scss -->
    <!-- ============================================================== -->
    <div class="auth-wrapper d-flex no-block justify-content-center align-items-center position-relative"
         style="background:url({{url('assets/images/big/auth-bg.jpg')}}) no-repeat center center;">
        <div class="auth-box row">
            <div class="col-lg-7 col-md-5 modal-bg-img" style="background-image: url({{url('assets/images/big/3.jpg')}});">
            </div>
            <div class="col-lg-5 col-md-7 bg-white">
                <div class="p-3">
                    <div class="text-center">
                        <img src="{{url('assets/images/big/icon.png')}}" alt="wrapkit">
                    </div>
                    <h2 class="mt-3 text-center">Sign In</h2>
                    <p class="text-center">Enter your email address and password to access admin panel.</p>
                    <form action="{{ url('/login') }}" method="POST" class="mt-4">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="text-dark" for="uname">Username</label>
                                    <input class="form-control" id="email" type="text"
                                           name="email" value="{{ old('email') }}" placeholder="Company email">
                                    @if ($errors->has('email'))
                                        <span class="text-danger" style="font-size: 13px;">
                    	                <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="text-dark" for="pwd">Password</label>
                                    <input class="form-control" id="pwd" type="password" name="password"
                                           placeholder="enter your password">
                                    @if ($errors->has('password'))
                                        <span class="text-danger" style="font-size: 13px;">
						                <strong>{{ $errors->first('password') }}</strong>
					                </span>
                                    @endif
                                </div>

                            </div>
                            <div class="col-lg-12" id="remember" class="checkbox">
                                <label>
                                    <input type="checkbox" name="remember"> Remember Me
                                </label>
                            </div>
                            <div class="col-lg-12 text-center">
                                <button type="submit" class="btn btn-block btn-dark">Sign In</button>
                            </div>

                        </div>
                        <div style="padding-top: 20px;"></div>

                        <div>
                           @if(Session::has('msg'))
                                <script>
                                    $.notify({
                                        icon: "fa fa-check-circle",
                                        message: "<b> {!! Session::get('msg')  !!}</b>.",
                                    }, {
                                        type: 'success',
                                        timer: 4500
                                    });
                                </script>
                            @endif
                        </div>

                        <div style="padding-top: 65px;">

                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Login box.scss -->
    <!-- ============================================================== -->
</div>
<!-- ============================================================== -->
<!-- All Required js -->
<!-- ============================================================== -->

<!-- Bootstrap tether Core JavaScript -->
<script src="{{ URL::asset('assets/libs/jquery/dist/jquery.min.js') }}"></script>
<script src="{{ URL::asset('assets/dist/js/bootstrap-notify.js') }}"></script>
<script src="{{ URL::asset('assets/libs/popper.js/dist/umd/popper.min.js') }}"></script>
<script src="{{ URL::asset('assets/libs/bootstrap/dist/js/bootstrap.min.js') }} "></script>
<!-- ============================================================== -->
<!-- This page plugin js -->
<!-- ============================================================== -->
<script>
    $(".preloader ").fadeOut();
</script>
</body>

</html>