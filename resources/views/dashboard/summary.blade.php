@extends('mitbooster::layouts.admin')

@push('head')
	<link rel="stylesheet" href="{{asset('assets/css/pages/dashboard.css')}}">
	<style>
		.card-border {
			border: 1px solid #dcdcdc;
			border-radius: 5px;
		}
		.card-img-icon {
			background-color: white;
			font-size: 50px;
			padding: 15px 10px;
			text-align: center;
			height: 100px;
			margin-bottom: 15px;
			border-radius: 5px;
		}
	</style>
@endpush

@push('bottom')
@endpush

@section('content')
	@if($role == 'parent')
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h4 class="font-weight-bolder"><i class="fa fa-home"></i> Laporan aktivitas siswa di rumah</h4>
					<div>*untuk diisi orang tua</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6 col-lg-4">
							<div class="card card-border bg-light">
								<div class="card-body">
									<div class="card-img-icon">
										<i class="fa fa-address-card"></i>
									</div>
									<h4 class="card-title" style="margin-bottom: 0;">Ibadah dan Akhlak</h4>
								</div>
								<ul class="list-group list-group-flush">
									<li class="list-group-item">Kemandirian</li>
									<li class="list-group-item">Ibadah Sholat</li>
									<li class="list-group-item">Berbakti Kepada Orang Tua</li>
								</ul>
								<div class="card-body text-right">
									<a href="{{ route('ParentReportControllerGetIndex') }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Lihat</a>
									<a href="{{ route('ParentReportControllerGetAdd') }}?return_url={{ route('ParentReportControllerGetIndex') }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Isi Laporan</a>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-4">
							<div class="card card-border bg-light">
								<div class="card-body">
									<div class="card-img-icon">
										<i class="fa fa-book"></i>
									</div>
									<h4 class="card-title" style="margin-bottom: 0;">Membaca</h4>
								</div>
								<ul class="list-group list-group-flush">
									<li class="list-group-item">Komik/Cerpen/Buku Cerita</li>
									<li class="list-group-item">Buku Pelajaran</li>
									<li class="list-group-item">Buku Lainnya</li>
								</ul>
								<div class="card-body text-right">
									<a href="{{ route('ParentReadControllerGetIndex') }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Lihat</a>
									<a href="{{ route('ParentReadControllerGetAdd') }}?return_url={{ route('ParentReadControllerGetIndex') }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Isi Laporan</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr style="border-top: 2px solid;"/>
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h4 class="font-weight-bolder"><i class="fa fa-university"></i> Laporan aktivitas siswa di sekolah</h4>
					<div>*orang tua hanya dapat melihat</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6 col-lg-4">
							<div class="card card-border bg-light">
								<div class="card-body">
									<div class="card-img-icon">
										<i class="fa fa-check-square-o"></i>
									</div>
									<h4 class="card-title" style="margin-bottom: 0;">Ibadah, Kedisiplinan, Akhlak</h4>
								</div>
								<ul class="list-group list-group-flush">
									<li class="list-group-item">Sholat Dengan Kesadaran</li>
									<li class="list-group-item">Tertib dan Disiplin</li>
									<li class="list-group-item">Ahlakul Karimah</li>
								</ul>
								<div class="card-body text-right">
									<a href="{{ route('TeacherReportControllerGetIndex') }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Lihat</a>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-4">
							<div class="card card-border bg-light">
								<div class="card-body">
									<div class="card-img-icon">
										<i class="fa fa-file-o"></i>
									</div>
									<h4 class="card-title" style="margin-bottom: 0;">Belajar Al-Qur'an</h4>
								</div>
								<ul class="list-group list-group-flush">
									<li class="list-group-item">Wafa</li>
									<li class="list-group-item">Tahfidz</li>
									<li class="list-group-item">&nbsp;</li>
								</ul>
								<div class="card-body text-right">
									<a href="{{ route('TeacherAlquranControllerGetIndex') }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Lihat</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@else
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h4 class="font-weight-bolder"><i class="fa fa-university"></i> Laporan aktivitas siswa di sekolah</h4>
					<div>*untuk diisi guru</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6 col-lg-4">
							<div class="card card-border bg-light">
								<div class="card-body">
									<div class="card-img-icon">
										<i class="fa fa-check-square-o"></i>
									</div>
									<h4 class="card-title" style="margin-bottom: 0;">Ibadah, Kedisiplinan, Akhlak</h4>
								</div>
								<ul class="list-group list-group-flush">
									<li class="list-group-item">Sholat Dengan Kesadaran</li>
									<li class="list-group-item">Tertib dan Disiplin</li>
									<li class="list-group-item">Ahlakul Karimah</li>
								</ul>
								<div class="card-body text-right">
									<a href="{{ route('TeacherReportControllerGetIndex') }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Lihat</a>
									<a href="{{ route('TeacherReportControllerGetAdd') }}?return_url={{ route('TeacherReportControllerGetIndex') }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Isi Laporan</a>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-4">
							<div class="card card-border bg-light">
								<div class="card-body">
									<div class="card-img-icon">
										<i class="fa fa-file-o"></i>
									</div>
									<h4 class="card-title" style="margin-bottom: 0;">Belajar Al-Qur'an</h4>
								</div>
								<ul class="list-group list-group-flush">
									<li class="list-group-item">Wafa</li>
									<li class="list-group-item">Tahfidz</li>
									<li class="list-group-item">&nbsp;</li>
								</ul>
								<div class="card-body text-right">
									<a href="{{ route('TeacherAlquranControllerGetIndex') }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Lihat</a>
									<a href="{{ route('TeacherAlquranControllerGetAdd') }}?return_url={{ route('TeacherAlquranControllerGetIndex') }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Isi Laporan</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr style="border-top: 2px solid;"/>
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h4 class="font-weight-bolder"><i class="fa fa-home"></i> Laporan aktivitas siswa di rumah</h4>
					<div>*guru hanya dapat melihat</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6 col-lg-4">
							<div class="card card-border bg-light">
								<div class="card-body">
									<div class="card-img-icon">
										<i class="fa fa-address-card"></i>
									</div>
									<h4 class="card-title" style="margin-bottom: 0;">Ibadah dan Akhlak</h4>
								</div>
								<ul class="list-group list-group-flush">
									<li class="list-group-item">Kemandirian</li>
									<li class="list-group-item">Ibadah Sholat</li>
									<li class="list-group-item">Berbakti Kepada Orang Tua</li>
								</ul>
								<div class="card-body text-right">
									<a href="{{ route('ParentReportControllerGetIndex') }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Lihat</a>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-4">
							<div class="card card-border bg-light">
								<div class="card-body">
									<div class="card-img-icon">
										<i class="fa fa-book"></i>
									</div>
									<h4 class="card-title" style="margin-bottom: 0;">Membaca</h4>
								</div>
								<ul class="list-group list-group-flush">
									<li class="list-group-item">Komik/Cerpen/Buku Cerita</li>
									<li class="list-group-item">Buku Pelajaran</li>
									<li class="list-group-item">Buku Lainnya</li>
								</ul>
								<div class="card-body text-right">
									<a href="{{ route('ParentReadControllerGetIndex') }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Lihat</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<hr style="border-top: 2px solid;"/>
	<div class="row">
		<div class="col-sm-12">
			<div class="card">
				<div class="card-header">
					<h4 class="font-weight-bolder"><i class="fa fa-database"></i> Data</h4>
					<div>*guru dapat menambahkan</div>
				</div>
				<div class="card-body">
					<div class="row">
						<div class="col-md-6 col-lg-4">
							<div class="card card-border bg-light">
								<div class="card-body">
									<div class="card-img-icon">
										<i class="fa fa-users"></i>
									</div>
									<h4 class="card-title text-center" style="margin-bottom: 0;">Siswa</h4>
								</div>
								<div class="card-body text-center">
									<a href="{{ route('StudentControllerGetIndex') }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Lihat</a>
									<a href="{{ route('StudentControllerGetAdd') }}?return_url={{ route('StudentControllerGetIndex') }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Tambah Data</a>
								</div>
							</div>
						</div>
						<div class="col-md-6 col-lg-4">
							<div class="card card-border bg-light">
								<div class="card-body">
									<div class="card-img-icon">
										<i class="fa fa-user-circle"></i>
									</div>
									<h4 class="card-title text-center" style="margin-bottom: 0;">Orang Tua</h4>
								</div>
								<div class="card-body text-center">
									<a href="{{ route('ParentControllerGetIndex') }}" class="btn btn-info btn-sm"><i class="fa fa-eye"></i> Lihat</a>
									<a href="{{ route('ParentControllerGetAdd') }}?return_url={{ route('ParentControllerGetIndex') }}" class="btn btn-success btn-sm"><i class="fa fa-edit"></i> Tambah Data</a>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	@endif

{{--		@if($shortcut)--}}
{{--			@foreach($shortcut AS $key)--}}
{{--				<div class="col-md-3 col-sm-6 col-xs-12">--}}
{{--					<a href="{{$key['path']}}">--}}
{{--						<div class="info-box {{$key['bgcolor']}}">--}}
{{--							<span class="info-box-icon"><i class="{{$key['icon']}}"></i></span>--}}
{{--							<div class="info-box-content">--}}
{{--								<span class="info-box-number">{{$key['shortcut_name']}}</span>--}}
{{--								<span class="info-box-text">{{$key['description']}}</span>--}}
{{--							</div>--}}
{{--						</div>--}}
{{--					</a>--}}
{{--				</div>--}}
{{--			@endforeach--}}
{{--		@endif--}}
@endsection

