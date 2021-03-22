@extends('mitbooster::layouts.admin')
@push('head')
@endpush

@section('content')
    <p><a title='Return' href='{{MITBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i>&nbsp; {{trans("mixtra.form_back_to_list",['module'=>MITBooster::getCurrentModule()->name])}}</a></p>


    <div class="card">
	    <div class="card-body">
	        <div class="container">
	            @if(count($errors) > 0)
		            <div class="alert alert-danger">
		                <ul>
		                    @foreach($errors as $key)
		                    <li>{{ $key }}</li>
		                    @endforeach
		                </ul>
		            </div>
	            @endif

	            @if($message = Session::get('success'))
	            <div class="alert alert-success alert-block">
	                <button type="button" class="close" data-dismiss="alert">×</button>
	                <strong>{{ $message }}</strong>
	            </div>
	            @endif
	            <form method="POST" enctype="multipart/form-data" action="{{ MITBooster::mainpath('import-student') }}">
	                {{ csrf_field() }}
	                <div class="form-group row">
	                	<label for="fileInput" class="col-sm-2 col-form-label">{{trans('mixtra.input_excel')}}</label>
	                	<div class="col-sm-10">
	                        <input type="file" id="fileInput" name="select_file" />
	                	</div>
	                    <input type="submit" name="upload" class="btn btn-primary" value="Upload">
	                </div>
	            </form>
	        </div>
	    </div>
	</div>


@endsection
@push('bottom')
@endpush