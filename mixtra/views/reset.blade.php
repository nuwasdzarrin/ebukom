@extends('mitbooster::layouts.app')

@section('body-class', 'skin-default fixed-layout')

@section('title')
<title>Reset Password</title>
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
                <form class="form-horizontal form-material" id="loginform" method='post' action="{{ route('postReset') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                    <input type="hidden" name="email" value="{{ app('request')->input('email') }}" />
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
                        <label for="new-password">New Password</label>
                        <input class="form-control" type="password" required="" placeholder="new password" name="password" id="new-password">
                    </div>
                    <div class="form-group m-b-10" style="min-height: 38px;">
                        <label for="re-password">Repeat Password</label>
                        <input class="form-control" type="password" required="" placeholder="repeat password" name="re-password" id="re-password">
                    </div>
                    <div class="form-group m-b-10 text-center" style="min-height: 38px;">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light" type="submit">Reset Password</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection