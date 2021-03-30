<?php
//used by prev parent
namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\Validator;
use DB;
use MITBooster;

class ParentReportController extends \mixtra\controllers\MITController
{
    public function init() {

        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->table               = 'sdi_weekly_report';
        $this->primary_key         = 'id';
        $this->title_field         = "student_id";
        $this->button_action_style = 'button_icon';
        $this->button_action_width = '150px';
        $this->button_import       = FALSE;
        $this->button_export       = FALSE;
        # END CONFIGURATION DO NOT REMOVE THIS LINE
        $this->student_id          = json_decode(DB::table('mit_users')->where('id', MITBooster::myId())->first()->student_id, true);

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = array();
        $this->col[] = array("label"=>"Hari, Tanggal","name"=>"created_at", "callback"=> function($row){
            $date = \Carbon\Carbon::parse($row->created_at);
            return
            "Pekan Ke " . "<span class='ml-md-1 badge badge-info'>{$date->weekOfMonth}</span> "
            . "Bulan  <span class='ml-md-1 badge badge-success'>{$date->format('F')}</span>";
        });
        $this->col[] = array("label"=>"Student Name","name"=>"student_id", 'join'=>'sdi_student,student_name');
        $this->col[] = array("label"=>"Kebersihan","name"=>"parents_bangun_pagi","callback_php"=>'!empty([parents_bangun_pagi]) ? count(explode(";",[parents_bangun_pagi])) : ""');
        $this->col[] = array("label"=>"Mandiri","name"=>"parents_mandiri","callback_php"=>'!empty([parents_mandiri]) ? count(explode(";",[parents_mandiri])) : ""');
        $this->col[] = array("label"=>"Subuh","name"=>"parents_subuh","callback_php"=>'!empty([parents_subuh]) ? count(explode(";",[parents_subuh])) : ""');
        $this->col[] = array("label"=>"Dhuhur","name"=>"parents_dhuhur","callback_php"=>'!empty([parents_dhuhur]) ? count(explode(";",[parents_dhuhur])) : ""');
        $this->col[] = array("label"=>"Ashar","name"=>"parents_ashar","callback_php"=>'!empty([parents_ashar]) ? count(explode(";",[parents_ashar])) : ""');
        $this->col[] = array("label"=>"Magrib","name"=>"parents_magrib","callback_php"=>'!empty([parents_magrib]) ? count(explode(";",[parents_magrib])) : ""');
        $this->col[] = array("label"=>"Isya","name"=>"parents_isya","callback_php"=>'!empty([parents_isya]) ? count(explode(";",[parents_isya])) : ""');
        $this->col[] = array("label"=>"Mendoakan Ortu","name"=>"parents_mendoakan","callback_php"=>'!empty([parents_mendoakan]) ? count(explode(";",[parents_mendoakan])) : ""');
        $this->col[] = array("label"=>"Patuh & Santun Ke Ortu","name"=>"parents_patuh","callback_php"=>'!empty([parents_patuh]) ? count(explode(";",[parents_patuh])) : ""');
        $this->col[] = array("label"=>"Status","name"=>"parents_status", "callback"=> function($row){
            if ($row->parents_status == '1'){
                return "<span class='badge badge-success'><i class='fa fa-check-square-o'></i></span>";
            } else {
                return "<span class='badge badge-warning'><i class='fa fa-clock-o'></i></span>";
            }
        });
        $this->col[] = array("label"=>"Total","name"=>"id","callback"=> function($row){
            $total = DB::table($this->table)->selectRaw('CONCAT_WS(";", COALESCE(parents_bangun_pagi), COALESCE(parents_mandiri),COALESCE(parents_subuh), COALESCE(parents_dhuhur), COALESCE(parents_ashar), COALESCE(parents_magrib), COALESCE(parents_isya), COALESCE(parents_mendoakan), COALESCE(parents_patuh)) AS total')->where('id', $row->id)->first();
            $total = count(explode(";",$total->total));
            return $total;
        });

        // $this->col[] = array("label"=>"Response","name"=>"id", "visible" => TRUE, "callback"=> function($row){
        //     $id = DB::table('sdi_response')->where('weekly_report_id', $row->id)->first();
        //     if (!empty($id)) {
        //         return '<a class="btn btn-xs btn-success" href="' . MITBooster::adminpath("response/edit/{$id->id}") . '" target="_self"><i class="fa fa-comments"></i></a>';
        //     } else {
        //         return '<a class="btn btn-xs btn-info" href="' . MITBooster::adminpath("response/add/{$row->id}") . '" target="_self"><i class="fa fa-comments"></i></a>';
        //     }
        // });
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = array();
        if ( MITBooster::myPrivilegeId() == 4 ) {
            $this->student_id = implode(',',$this->student_id);
            $this->form[] = [
                "label"             => "Nama Siswa",
                "name"              => "student_id",
                "type"              => "select2Parents",
                "datatable_where"   => "id in($this->student_id)",
                "required"          => true,
                "datatable_ajax"    => false,
                "datatable"         => "sdi_student,student_name",
                "datatable_format"  => "nis,' - ',student_name",
                "help"              => "Select Students",
            ];
        } else {
            $this->form[] = [
                "label"             => "Nama Siswa",
                "name"              => "student_id",
                "type"              => "select2",
                "datatable_ajax"    => true,
                "required"          => true,
                "datatable"         => "sdi_student,student_name",
                "datatable_format"  => "nis,' - ',student_name",
                "help"              => "Select Student",
            ];
        }

        // Kemandirian
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'Kemandirian','name'=>'mandiri', 'icon' => 'fa fa-check-square', 'type'=>'label','class'=>'title'];
        $this->form[] = ['label'=>'Bangun Pagi Mandi Dan Mengosok Gigi','name'=>'parents_bangun_pagi','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week_parents,day_name', 'orderby' => 'id'];
        $this->form[] = ['label'=>'Melayani Diri Sendiri Saat Makan, Berpakaian dan Menyiapkan Perlengkapan Sekolah','name'=>'parents_mandiri','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week_parents,day_name', 'orderby' => 'id'];

        // Cek Ibadah
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'Cek Ibadah Sholat','name'=>'ibadah', 'icon' => 'fa fa-graduation-cap', 'type'=>'label','class'=>'title'];
        $this->form[] = ['label'=>'Subuh','name'=>'parents_subuh','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week_parents,day_name', 'orderby' => 'id'];
        $this->form[] = ['label'=>'Dhuhur','name'=>'parents_dhuhur','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week_parents,day_name', 'orderby' => 'id'];
        $this->form[] = ['label'=>'Ashar','name'=>'parents_ashar','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week_parents,day_name', 'orderby' => 'id'];
        $this->form[] = ['label'=>'Magrib','name'=>'parents_magrib','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week_parents,day_name', 'orderby' => 'id'];
        $this->form[] = ['label'=>'Isya','name'=>'parents_isya','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week_parents,day_name', 'orderby' => 'id'];

//        $this->form[] = ['label'=>'Tahajud','name'=>'parents_tahajud','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week_parents,day_name', 'orderby' => 'id'];
//        $this->form[] = ['label'=>'Tilawah','name'=>'parents_tilawah','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week_parents,day_name', 'orderby' => 'id'];

        // Berbakti Kepada Orangtua
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'Berbakti Kepada Orangtua','name'=>'berbakti', 'icon' => 'fa fa-child', 'type'=>'label','class'=>'title'];
        $this->form[] = ['label'=>'Mendoakan Kedua Orang Tua','name'=>'parents_mendoakan','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week_parents,day_name', 'orderby' => 'id'];
        $this->form[] = ['label'=>'Patuh Dan Berkata Dantun Pada Orang Tua','name'=>'parents_patuh','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week_parents,day_name', 'orderby' => 'id'];

        // Info atau Response
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'Informasi / Tanggapan','name'=>'berbakti', 'icon' => 'fa fa-info', 'type'=>'label','class'=>'title'];
        $this->form[] = ['label'=>'Informasi Dari Guru/Orang Tua','name'=>'information','type'=>'wysiwyg'];
        $this->form[] = ['label'=>'Tanggapan Dari Orang Tua/Guru','name'=>'response','type'=>'wysiwyg'];


        /*
        | ----------------------------------------------------------------------
        | Sub Module
        | ----------------------------------------------------------------------
        | @label          = Label of action
        | @path           = Path of sub module
        | @foreign_key    = foreign key of sub table/module
        | @button_color   = Bootstrap Class (primary,success,warning,danger)
        | @button_icon    = Font Awesome Class
        | @parent_columns = Sparate with comma, e.g : name,created_at
        |
        */
        $this->sub_module = array();


        /*
        | ----------------------------------------------------------------------
        | Add More Action Button / Menu
        | ----------------------------------------------------------------------
        | @label       = Label of action
        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
        | @icon        = Font awesome class icon. e.g : fa fa-bars
        | @color       = Default is primary. (primary, warning, succecss, info)
        | @showIf      = If condition when action show. Use field alias. e.g : [id] == 1
        |
        */
        $this->addaction = array();
        if ( MITBooster::myPrivilegeId() == 4 ) {
            $this->addaction[] = ['label'=>'ACC','url'=>MITBooster::mainpath('set-status/[id]'),'icon'=>'fa fa-check','color'=>'warning','showIf'=>"[parents_status] == '2'", 'confirmation' => true];
        }


        /*
        | ----------------------------------------------------------------------
        | Add More Button Selected
        | ----------------------------------------------------------------------
        | @label       = Label of action
        | @icon        = Icon from fontawesome
        | @name        = Name of button
        | Then about the action, you should code at actionButtonSelected method
        |
        */
        $this->button_selected = array();


        /*
        | ----------------------------------------------------------------------
        | Add alert message to this module at overheader
        | ----------------------------------------------------------------------
        | @message = Text of message
        | @type    = warning,success,danger,info
        |
        */
        $this->alert        = array();
        $this->alert[]      = ['message'=>'Perhatian Jika Tombol ACC Muncul Maka Orangtua Belum Memberikan ACC pada laporan Mingguan.<br/>Response diisikan 1 kali setiap Minggu, Warna Hijau Berarti sudah ada isinya, warnah biru masih kosong<br/>Data yang ditampilkan adalah data seminggu terakhir, Untuk melihat data semuanya masuk menu report<br/>Untuk menambahkan data harap melihat daftar jika data sudah ada tinggal edit saja','type'=>'warning', 'title' => 'Perhatian !'];



        /*
        | ----------------------------------------------------------------------
        | Add more button to header button
        | ----------------------------------------------------------------------
        | @label = Name of button
        | @url   = URL Target
        | @icon  = Icon from Awesome.
        |
        */
        $this->index_button = array();



        /*
        | ----------------------------------------------------------------------
        | Customize Table Row Color
        | ----------------------------------------------------------------------
        | @condition = If condition. You may use field alias. E.g : [id] == 1
        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.
        |
        */
        $this->table_row_color = array();


        /*
        | ----------------------------------------------------------------------
        | You may use this bellow array to add statistic at dashboard
        | ----------------------------------------------------------------------
        | @label, @count, @icon, @color
        |
        */
        $this->index_statistic = array();



        /*
        | ----------------------------------------------------------------------
        | Add javascript at body
        | ----------------------------------------------------------------------
        | javascript code in the variable
        | $this->script_js = "function() { ... }";
        |
        */
        // $this->script_js = null;
        $this->script_js = "
        $('#form-group-parents_tahajud').attr('style','display:none');
        $('#form-group-parents_tilawah').attr('style','display:none');
        cekClass();

        $('#student_id').change(function(){
            cekClass();    
        });

        function cekClass(){
            var student_id = $('#student_id').val();
            $.ajax({
                headers: {'X-CSRF-TOKEN': $('meta[name=\'csrf-token\']').attr('content')},
                url: '" . MITBooster::mainpath('student-class') . "',
                type: 'POST',
                dataType: 'JSON',
                data: {'id' : student_id},
                success:function(data){
                    console.log(data.class_grade)
                    kelas = data.class_grade;
                    if (kelas !== undefined) {
                        if (kelas == 5 || kelas == 6){
                            $('#form-group-parents_tahajud').attr('style','display:flex');
                            $('#form-group-parents_tilawah').attr('style','display:flex');
                        }
                    }
                }
            });
        }
        ";


        /*
        | ----------------------------------------------------------------------
        | Include HTML Code before index table
        | ----------------------------------------------------------------------
        | html code to display it before index table
        | $this->pre_index_html = "<p>test</p>";
        |
        */
        $this->pre_index_html = null;



        /*
        | ----------------------------------------------------------------------
        | Include HTML Code after index table
        | ----------------------------------------------------------------------
        | html code to display it after index table
        | $this->post_index_html = "<p>test</p>";
        |
        */
        $this->post_index_html = null;



        /*
        | ----------------------------------------------------------------------
        | Include Javascript File
        | ----------------------------------------------------------------------
        | URL of your javascript each array
        | $this->load_js[] = asset("myfile.js");
        |
        */
        $this->load_js = array();



        /*
        | ----------------------------------------------------------------------
        | Add css style at body
        | ----------------------------------------------------------------------
        | css code in the variable
        | $this->style_css = ".style{....}";
        |
        */
        $this->style_css = null;



        /*
        | ----------------------------------------------------------------------
        | Include css File
        | ----------------------------------------------------------------------
        | URL of your css each array
        | $this->load_css[] = asset("myfile.css");
        |
        */
        $this->load_css = array();
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for button selected
    | ----------------------------------------------------------------------
    | @id_selected = the id selected
    | @button_name = the name of button
    |
    */
    public function actionButtonSelected($id_selected, $button_name)
    {
        //Your code here
    }


    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate query of index result
    | ----------------------------------------------------------------------
    | @query = current sql query
    |
    */
    public function hook_query_index(&$query)
    {
        if ( MITBooster::myPrivilegeId() == 4 ) {
            $start      = \Carbon\Carbon::now()->startOfWeek();
            $end        = \Carbon\Carbon::now()->endOfWeek();
            $this->student_id = explode(',',$this->student_id);
            $query->whereIn('sdi_weekly_report.student_id', $this->student_id);
            $query->whereBetween('sdi_weekly_report.created_at', [$start,$end]);
        }
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate row of index table html
    | ----------------------------------------------------------------------
    |
    */
    public function hook_row_index($column_index, &$column_value)
    {
        //Your code here
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before add data is execute
    | ----------------------------------------------------------------------
    | @arr
    |
    */
    public function hook_before_add(&$postdata)
    {
        $start  = \Carbon\Carbon::now()->startOfWeek();
        $end    = \Carbon\Carbon::now()->endOfWeek();
        $data   = DB::table('sdi_weekly_report')
                  ->whereBetween('created_at', [$start, $end])
                  ->where('student_id', $postdata['student_id'])
                  ->first();
        if(!empty($data)){
            MITBooster::redirect(MITBooster::mainpath("edit/{$data->id}"), trans('mixtra.data_available'), 'info');
        }
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after add public static function called
    | ----------------------------------------------------------------------
    | @id = last insert id
    |
    */
    public function hook_after_add($id)
    {
        //Your code here
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for manipulate data input before update data is execute
    | ----------------------------------------------------------------------
    | @postdata = input post data
    | @id       = current id
    |
    */
    public function hook_before_edit(&$postdata, $id)
    {
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after edit public static function called
    | ----------------------------------------------------------------------
    | @id       = current id
    |
    */
    public function hook_after_edit($id)
    {
        //Your code here
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command before delete public static function called
    | ----------------------------------------------------------------------
    | @id       = current id
    |
    */
    public function hook_before_delete($id)
    {
        //Your code here
    }

    /*
    | ----------------------------------------------------------------------
    | Hook for execute command after delete public static function called
    | ----------------------------------------------------------------------
    | @id       = current id
    |
    */
    public function hook_after_delete($id)
    {
        //Your code here
    }
    public function getSetStatus($id) {
        DB::table('sdi_weekly_report')->where('id',$id)->update(['parents_status'=> '1']);
        MITBooster::redirect($_SERVER['HTTP_REFERER'], trans("mixtra.report_accepted"),"info");
    }

    public function postStudentClass(){
        $id = Request::get('id');
        $data = DB::table('sdi_student as A')
        ->select('B.class_grade')
        ->join('sdi_class as B', 'B.id', 'A.class_id')
        ->where('A.id', $id)
        ->first();
        return response()->json($data);
    }
}
