@extends('mitbooster::layouts.admin')

@push('head')
	<link rel="stylesheet" href="{{asset('assets/css/pages/dashboard.css')}}">
@endpush

@push('bottom')
@endpush

@section('content')
	<div class="row">
		@if($shortcut)
			@foreach($shortcut AS $key)
				<div class="col-md-3 col-sm-6 col-xs-12">
					<a href="{{$key['path']}}">
						<div class="info-box {{$key['bgcolor']}}">
							<span class="info-box-icon"><i class="{{$key['icon']}}"></i></span>
							<div class="info-box-content">
								<span class="info-box-number">{{$key['shortcut_name']}}</span>
								<span class="info-box-text">{{$key['description']}}</span>
							</div>
						</div>
					</a>
				</div>
			@endforeach
		@endif
	</div>	
@endsection

