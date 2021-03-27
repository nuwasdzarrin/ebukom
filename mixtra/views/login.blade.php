@extends('mitbooster::layouts.app')

@section('title')
<title>Login Panel : {{MITBooster::getSetting('appname')}}</title>
@endsection

@section('admin_css')
<link href="{{ asset('assets/css/pages/login-register-lock.css') }}" rel="stylesheet">
    <style>
        @media screen and (max-width: 426px) {
            .login-img-behind {
                display: none;
            }
        }
    </style>
@endsection

@section('admin_js')
<script type="text/javascript">
	$(function() {
	    $(".preloader").fadeOut();
	});
</script>
@endsection

@section('wrapper')
<section id="wrapper" class="login-register login-sidebar">
    <div class="row" style="height: 100%">
        <div class="col-md-8 login-img-behind">
            <div class="d-flex justify-content-center align-items-center" style="height: 100%">
                <img src="{{ MITBooster::getSetting('login_background_image')?asset(MITBooster::getSetting('login_background_image')):asset('assets/images/background/login-register.jpg') }}" style="width: 500px; height: auto;">
            </div>
        </div>
        <div class="col-md-4 col-sm-12">
            <div class="login-box card">
                <div class="card-body" style="padding-top: 25%;">
                    @if ( Session::get('message') != '' )
                        <div class='alert alert-warning'>
                            {{ Session::get('message') }}
                        </div>
                    @endif
                    <h3><b>Selamat Datang di Ebukom</b></h3>
                    <p>Silahkan login untuk mengakses aplikasi.</p>
                    <form class="form-horizontal form-material text-center" id="loginform"  autocomplete='off' action="{{ route('postLogin') }}" method="post">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                        <div class="form-group m-t-40 m-b-10" style="min-height: 38px;">
                            <div class="col-sm-12">
                                <input class="form-control" type="text" required="" placeholder="Username" name="username">
                            </div>
                        </div>
                        <div class="form-group m-b-10" style="min-height: 38px;">
                            <div class="col-sm-12">
                                <input class="form-control" type="password" required="" placeholder="Password" name="password">
                            </div>
                        </div>
{{--                        <div class="form-group row m-b-10" style="min-height: 38px;">--}}
{{--                            <div class="col-sm-12">--}}
{{--                                <div class="d-flex no-block align-items-center">--}}
{{--                                    <div class="custom-control custom-checkbox">--}}
{{--                                        <input type="checkbox" class="custom-control-input" id="customCheck1">--}}
{{--                                        <label class="custom-control-label" for="customCheck1">Remember me</label>--}}
{{--                                    </div>--}}
{{--                                    <div class="ml-auto">--}}
{{--                                        <a href="{{ route('getForgot') }}" id="to-recover" class="text-muted"><i class="fas fa-lock m-r-5"></i> Forgot pwd?</a>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}
                        <div class="form-group text-center m-t-20 m-b-10" style="min-height: 38px;">
                            <div class="col-sm-12">
                                <button class="btn btn-primary btn-md btn-block" type="submit">Log In</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
