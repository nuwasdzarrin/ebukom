@extends('mitbooster::layouts.admin')
@push('head')
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/daterangepicker/daterangepicker.css')}}">
    <link rel="stylesheet" type="text/css" href="{{asset('assets/vendor/datatables/datatables.min.css')}}">
@endpush

@section('content')
    <p><a title='Return' href='{{MITBooster::mainpath()}}'><i class='fa fa-chevron-circle-left '></i>&nbsp; {{trans("mixtra.form_back_to_list",['module'=>MITBooster::getCurrentModule()->name])}}</a></p>

    <div class="card">
    	<div class="card-header">
    		<h4><i class="fa fa-filter"></i> Filter Data</h4>
    	</div>
	    <div class="card-body">
	        {{ csrf_field() }}
            <div class="form-group row">
            	<label for="filter" class="col-sm-2 col-form-label">{{trans('mixtra.filter_data_report')}}</label>
            	<div class="col-sm-5">
            		<input class="form-control" type="text" name="date_rage" id="filter">
            	</div>
            </div>
	    </div>
	</div>
    <div class="card">
        <div class="card-header">
            <h4><i class="fa fa-sticky-note-o"></i> Data Result</h4>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover no-wrap" id="dataResult">
                    <thead>
                        <tr>
                            <th class="text-center">#</th>
                            <th>Name</th>
                            <th>Wudhu</th>
                            <th>Sholat</th>
                            <th>Sholat Dhuha</th>
                            <th>Sholat Duhur</th>
                            <th>Sholat Ashar</th>
                            <th>On Time</th>
                            <th>Bell Sign</th>
                            <th>Respect The Teacher</th>
                            <th>Use Footwear</th>
                            <th>Garbage In Its Place</th>
                            <th>Serious In Learning</th>
                            <th>Mandiri</th>
                            <th>Bangun Pagi</th>
                            <th>Subuh At Home</th>
                            <th>Duhur At Home</th>
                            <th>Ashar At Home</th>
                            <th>Magrib At Home</th>
                            <th>Isya At Home</th>
                            <th>Pray for Parents</th>
                            <th>Patuh</th>
                        </tr>
                    </thead>
                    <tbody id="result">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@push('bottom')
    <script type="text/javascript" src="{{asset('assets/vendor/daterangepicker/daterangepicker.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendor/datatables/datatables.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendor/datatables/buttons/jszip.min.js')}}"></script>
    <script type="text/javascript" src="{{asset('assets/vendor/datatables/dataTables.rowGroup.min.js')}}"></script>


    <script type="text/javascript">
        $(document).ready(function() {
            $('input[name="date_rage"]').daterangepicker({
                'showWeekNumbers': true,
                'showISOWeekNumbers': true,
                'autoApply': true,
                'locale': {
                    'format': 'DD-MMM-YYYY',
                    'separator': ' - '
                },
            },function(start, end) {
                $('table[id="dataResult"]').dataTable().fnDestroy();
                $("#result").empty();
                var idx = 0;
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name=\'csrf-token\']').attr('content')
                    },
                    url: '{{MITBooster::mainpath("data-filter")}}',
                    method: 'POST',
                    data: {from: start.format('YYYY-MM-DD'), to: end.format('YYYY-MM-DD')},
                    success:function(data) {
                        $.each(data, function(index, item) {
                            idx  += 1;
                            var row = ""+
                                "<tr>"+
                                "    <td class='text-center'>"+(idx)+"</td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.name+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_wudhu+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_sholat+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_dhuha+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_dhuhur+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_ashar+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_on_time+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_bel+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_respect+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_footwear+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_trash+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_learn+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_parents_mandiri+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_parents_bangun_pagi+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_parents_subuh+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_parents_dhuhur+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_parents_ashar+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_parents_magrib+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_parents_isya+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_parents_mendoakan+"</div></td>"+
                                "    <td class='txt-oflo'><div class='text-left'>"+item.total_parents_patuh+"</div></td>"+
                                "</tr>";
                            $("#result").append(row);
                        });
                        $('table[id="dataResult"]').DataTable( {
                            dom: 'lBftip',
                            buttons: [
                                {
                                    extend:'excel',
                                    title: 'Weekly Report Period: ' + start.format('YYYY-MM-DD') + ' To ' + end.format('YYYY-MM-DD'),
                                    message: 'Class :' + {{$class->class_grade}}
                                },
                            ]
                        });
                    }
                })
            });
        });
    </script>
@endpush