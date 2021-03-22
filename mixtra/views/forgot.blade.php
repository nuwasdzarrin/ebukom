@extends('mitbooster::layouts.app')

@section('body-class', 'skin-default fixed-layout')

@section('title')
<title>Forgot Password</title>
@endsection

@section('admin_css')
<link href="{{ asset('assets/css/pages/login-register-lock.css') }}" rel="stylesheet">
@endsection

@section('admin_js')
<script type="text/javascript">
    $(function() {
        $(".preloader").fadeOut();
    });
</script>
@endsection

@section('wrapper')
<section id="wrapper">
    <div class="login-register" style="background-image:url({{ MITBooster::getSetting('login_background_image')?asset(MITBooster::getSetting('login_background_image')):asset('assets/images/background/login-register.jpg') }});">
        <div class="login-box card">
            <div class="card-body">
                <form class="form-horizontal form-material" id="loginform" method='post' action="{{ route('postForgot') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <div class="form-group m-b-10" style="min-height: 38px;">
                        <div class="col-xs-12 text-center">
                            <div class="user-thumb text-center">
                                <h3>Reset Password</h3>
                            </div>
                        </div>
                        @if ( Session::get('message') != '' )
                        <div class='alert alert-warning'>
                            {{ Session::get('message') }}
                        </div>
                        @endif
                    </div>
                    <div class="form-group m-b-10" style="min-height: 38px;">
                        <div class="col-xs-12">
                            <input class="form-control" type="text" required="" placeholder="email" name="username">
                        </div>
                    </div>
                    <div class="form-group m-b-10 text-center" style="min-height: 38px;">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Send Link Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection