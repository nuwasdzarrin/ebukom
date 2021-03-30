<?php
//used by teacher
namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\Validator;
use DB;
use MITBooster;

class TeacherReportController extends \mixtra\controllers\MITController
{
   	public function init() {
		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->table               = 'sdi_weekly_report';
		$this->primary_key         = 'id';
        $this->title_field         = "wudhu";
		$this->button_action_style = 'button_icon';
		$this->button_import 	   = FALSE;
		$this->button_export 	   = FALSE;
        $this->class_id            = DB::table('sdi_class')->where('class_wali_id', MITBooster::myId())->first()->id;
        $this->class_grade         = DB::table('sdi_class')->where('class_wali_id', MITBooster::myId())->first()->class_grade;
        // print_r($this->class_id);
        // echo " grade  ";
        // print_r($this->class_grade);
		# END CONFIGURATION DO NOT REMOVE THIS LINE

		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = array();
		$this->col[] = array("label"=>"Student","name"=>"student_id", 'join' => 'sdi_student,student_name');
        $this->col[] = array("label"=>"Week Number","name"=>"created_at", "callback"=> function($row){
            $date = \Carbon\Carbon::parse($row->created_at);
            return
            "Pekan Ke " . "<span class='ml-md-1 badge badge-info'>{$date->weekOfMonth}</span> "
            . "Bulan  <span class='ml-md-1 badge badge-success'>{$date->format('F')}</span>";
        });
        // $this->col[] = array("label"=>"Status","name"=>"status", "visible"=> FALSE);
        if ($this->class_grade == '1' || $this->class_grade == '2' ){
            $this->col[] = array("label"=>"Wudhu","name"=>"wudhu","callback_php"=>'!empty([wudhu]) ? count(explode(";",[wudhu])) : ""');
            $this->col[] = array("label"=>"Sholat","name"=>"sholat","callback_php"=>'!empty([sholat]) ? count(explode(";",[sholat])) : ""');

        } elseif ($this->class_grade == '3' || $this->class_grade == '4' || $this->class_grade == '5' ){
            $this->col[] = array("label"=>"Dhuha","name"=>"dhuha","callback_php"=>'!empty([dhuha]) ? count(explode(";",[dhuha])) : ""');
        } elseif($this->class_grade == '6') {
            $this->col[] = array("label"=>"Dhuhur","name"=>"dhuhur","callback_php"=>'!empty([dhuhur]) ? count(explode(";",[dhuhur])) : ""');
            $this->col[] = array("label"=>"Ashar","name"=>"ashar","callback_php"=>'!empty([ashar]) ? count(explode(";",[ashar])) : ""');
            $this->col[] = array("label"=>"Dhuha","name"=>"dhuha","callback_php"=>'!empty([dhuha]) ? count(explode(";",[dhuha])) : ""');
        }
        $this->col[] = array("label"=>"Arrive On Time","name"=>"on_time","callback_php"=>'!empty([on_time]) ? count(explode(";",[on_time])) : ""');
        $this->col[] = array("label"=>"Bell Sign","name"=>"bel","callback_php"=>'!empty([bel]) ? count(explode(";",[bel])) : ""');
        $this->col[] = array("label"=>"Respect The Teacher","name"=>"respect","callback_php"=>'!empty([respect]) ? count(explode(";",[respect])) : ""');
        $this->col[] = array("label"=>"Use Footwear","name"=>"footwear","callback_php"=>'!empty([footwear]) ? count(explode(";",[footwear])) : ""');
        $this->col[] = array("label"=>"Garbage In Its Place","name"=>"trash","callback_php"=>'!empty([trash]) ? count(explode(";",[trash])) : ""');
        $this->col[] = array("label"=>"Serious In Learning","name"=>"learn","callback_php"=>'!empty([learn]) ? count(explode(";",[learn])) : ""');
        $this->col[] = array("label"=>"Status","name"=>"status", "callback"=> function($row){
            if ($row->status == '1'){
                return "<span class='badge badge-success'><i class='fa fa-check-square-o'></i></span>";
            } else {
                return "<span class='badge badge-warning'><i class='fa fa-clock-o'></i></span>";
            }
        });
        $this->col[] = array("label"=>"Total","name"=>"id","callback"=> function($row){
            $total = DB::table($this->table)->selectRaw('CONCAT_WS(";", COALESCE(wudhu), COALESCE(sholat),COALESCE(on_time), COALESCE(bel), COALESCE(respect), COALESCE(footwear), COALESCE(trash), COALESCE(learn), COALESCE(dhuhur), COALESCE(ashar), COALESCE(dhuha)) AS total')->where('id', $row->id)->first();
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
		$this->form     = array();

        if ( MITBooster::myPrivilegeId() == 2 ) {
            $this->form[] = [
                "label"             => "Nama Siswa",
                "name"              => "student_id",
                "type"              => "select2Parents",
                "datatable_where"   => "class_id = $this->class_id",
                "required"          => true,
                "datatable_ajax"    => false,
                "datatable"         => "sdi_student,student_name",
                "datatable_format"  => "nis,' - ',student_name",
                "help"              => "Select Student",
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

        // Shalat Dengan Kesadaran
        $this->form[] = ['label'=>'Shalat Dengan Kesadaran','name'=>'kesadaran', 'icon' => 'fa fa-users', 'type'=>'label','class'=>'title'];
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        if ($this->class_grade == '1' || $this->class_grade == '2' ){
    		$this->form[] = ['label'=>'Berwudhu Dengan Tertib & Sempurna','name'=>'wudhu','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];
            $this->form[] = ['label'=>'Tuntas Gerakan / Bacaan Sholat','name'=>'sholat','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];
        } elseif ($this->class_grade == '3' || $this->class_grade == '4' || $this->class_grade == '5' ){
            $this->form[] = ['label'=>'Sholat Dhuha','name'=>'dhuha','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];
        } elseif($this->class_grade == '6') {
            $this->form[] = ['label'=>'Sholat Dhuhur Dengan Tertib, Khusyu dan tumaninah','name'=>'dhuhur','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];
            $this->form[] = ['label'=>'Sholat Ashar Dengan Tertib, Khusyu dan tumaninah','name'=>'ashar','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];
            $this->form[] = ['label'=>'Sholat Dhuha','name'=>'dhuha','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];
        } else {
            $this->form[] = ['label'=>'Berwudhu Dengan Tertib & Sempurna','name'=>'wudhu','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];
            $this->form[] = ['label'=>'Tuntas Gerakan / Bacaan Sholat','name'=>'sholat','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];
            $this->form[] = ['label'=>'Sholat Dhuha','name'=>'dhuha','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];
            $this->form[] = ['label'=>'Sholat Dhuhur Dengan Tertib, Khusyu dan tumaninah','name'=>'dhuhur','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];
            $this->form[] = ['label'=>'Sholat Ashar Dengan Tertib, Khusyu dan tumaninah','name'=>'ashar','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];
            $this->form[] = ['label'=>'Sholat Dhuha','name'=>'dhuha','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];
        }

        // Tertib Dan Disiplin
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'Tertib Dan Disiplin','name'=>'tertib', 'icon' => 'fa fa-list', 'type'=>'label','class'=>'title'];
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'Datang Ke Sekolah Tepat Waktu','name'=>'on_time','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];
        $this->form[] = ['label'=>'Masuk Kelas Tepat Waktu / Memahami Arti Tanda Bel','name'=>'bel','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];

        // Akhlakul Karimah
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'Ahlakul Karimah','name'=>'akhlak', 'icon' => 'fa fa-check', 'type'=>'label','class'=>'title'];
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'Menghormati Guru, Karyawan Dan Teman','name'=>'respect','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];

        // Bersih Dan Peduli Lingkungan
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'Bersih Dan Peduli Lingkungan','name'=>'bersih', 'icon' => 'fa fa-tree', 'type'=>'label','class'=>'title'];
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'Memakai Alas Kaki Di Halaman','name'=>'footwear','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];
        $this->form[] = ['label'=>'Membuang Sampah Di Tempatnya','name'=>'trash','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];

        // Bersih Dan Peduli Lingkungan
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'Nilai Akademik Tuntas','name'=>'akademik', 'icon' => 'fa fa-list-ol', 'type'=>'label','class'=>'title'];
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'Bersungguh - sungguh Dalam Belajar','name'=>'learn','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week,day_name', 'orderby' => 'id'];

        // Response
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'Informasi / Tanggapan','name'=>'berbakti', 'icon' => 'fa fa-info', 'type'=>'label','class'=>'title'];
        $this->form[] = ['label'=>'Informasi Dari Guru/Orang Tua','name'=>'information','type'=>'wysiwyg'];
        $this->form[] = ['label'=>'Tanggapan Dari Orang Tua/Guru','name'=>'response','type'=>'wysiwyg'];

		# END FORM DO NOT REMOVE THIS LINE


		/*
        | ----------------------------------------------------------------------
        | Sub Module
        | ----------------------------------------------------------------------
        | @label          = Label of action
        | @path           = Path of sub module
        | @foreign_key 	  = foreign key of sub table/module
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
        | @color 	   = Default is primary. (primary, warning, succecss, info)
        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
        |
        */
        $this->addaction = array();
        if ( MITBooster::myPrivilegeId() == 2 ) {
            $this->addaction[] = ['label'=>'','url'=>MITBooster::mainpath('set-status/[id]'),'icon'=>'fa fa-check','color'=>'warning','showIf'=>"[status] == '2'", 'confirmation' => true];
        }


        /*
        | ----------------------------------------------------------------------
        | Add More Button Selected
        | ----------------------------------------------------------------------
        | @label       = Label of action
        | @icon 	   = Icon from fontawesome
        | @name 	   = Name of button
        | @type        = type custom if custom js, null or normal is default action js
        | Then about the action, you should code at actionButtonSelected method
        |
        */
        $this->button_selected = [];
        $this->button_selected[] = ['label' => 'Approve/Paraf', 'type' => 'normal', 'title' => 'Approve/Paraf', 'icon' => 'file-code-o', 'name' => 'approve'];

        /*
        | ----------------------------------------------------------------------
        | Add alert message to this module at overheader
        | ----------------------------------------------------------------------
        | @message = Text of message
        | @type    = warning,success,danger,info
        | @title   = Title of Alert
        |
        */
        $this->alert        = array();
        $this->alert[]      = ['message'=>'Perhatian Jika Tombol ACC Muncul Maka Guru Belum Memberikan ACC pada laporan Mingguan.<br/>Response diisikan 1 kali setiap Minggu, Warna Hijau Berarti sudah ada isinya, warnah biru masih kosong<br/>Data yang ditampilkan adalah data seminggu terakhir, Untuk melihat data semuanya masuk menu report<br/>Untuk menambahkan data harap melihat daftar jika data sudah ada tinggal edit saja','type'=>'warning', 'title' => 'Perhatian !'];




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
        $this->script_js = "";


        /*
        | ----------------------------------------------------------------------
        | Include HTML Code before index table
        | ----------------------------------------------------------------------
        | html code to display it before index table
        | $this->pre_index_html = "<p>test</p>";
        |
        */
        $this->pre_index_html = [];



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
        if($button_name == 'approve') {
            DB::table('sdi_weekly_report')->whereIn('id', $id_selected)->update(['status'=> '1']);
        }
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
        if ( MITBooster::myPrivilegeId() == 2 ) {
            $start      = \Carbon\Carbon::now()->startOfWeek();
            $end        = \Carbon\Carbon::now()->endOfWeek();
            $class      = DB::table('sdi_class')->where('class_wali_id', MITBooster::myId())->first();
            $student    = DB::table('sdi_student')->where('class_id', $class->id)->get();
            foreach ($student as $key) {
                $student_id[] = $key->id;
            }
            $query->whereIn('sdi_weekly_report.student_id', $student_id);
//            $query->whereBetween('sdi_weekly_report.created_at', [$start,$end]);
        } elseif ( MITBooster::myPrivilegeId() == 4 ) {
            $this->student_id = json_decode(DB::table('mit_users')->where('id', MITBooster::myId())->first()->student_id, true);
            $query->whereIn('student_id', $this->student_id);
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
        //Your code here
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
        DB::table('sdi_weekly_report')->where('id',$id)->update(['status'=> '1']);
        MITBooster::redirect($_SERVER['HTTP_REFERER'],"The Report Has Been Accepted !","info");
    }
}
