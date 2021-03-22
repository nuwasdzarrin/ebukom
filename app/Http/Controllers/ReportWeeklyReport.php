<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use DB;
use MITBooster;

class ReportWeeklyReport extends \mixtra\controllers\MITController
{
    public function init() {
        
        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->table               = 'sdi_weekly_report';
        $this->orderby             = "created_at,DESC";
        $this->button_action_style = 'button_icon';
        $this->button_action_width = '50px';
        $this->show_numbering      = true;
        $this->button_import       = false;
        $this->button_export       = false;
        $this->button_bulk_action  = false;
        $this->button_add          = false;
        $this->button_edit         = false;
        $this->button_show         = true;
        $this->button_delete       = false;
        $this->button_detail       = true;
        $this->button_table_action = true;
        # END CONFIGURATION DO NOT REMOVE THIS LINE
        $this->student_id          = json_decode(DB::table('mit_users')->where('id', MITBooster::myId())->first()->student_id, true);
        $this->class_id            = DB::table('sdi_class')->where('class_wali_id', MITBooster::myId())->first()->id;
        $this->class_grade         = DB::table('sdi_class')->where('class_wali_id', MITBooster::myId())->first()->class_grade;
    
        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = [];
        $this->col[] = array("label"=>"Student","name"=>"student_id", 'join' => 'sdi_student,student_name');
        $this->col[] = ["label"=>"Hari, Tanggal","name"=>"created_at", "callback"=> function($row){
            $date = \Carbon\Carbon::parse($row->created_at);
            return 
            "Pekan Ke " . "<span class='ml-md-1 badge badge-info'>{$date->weekOfMonth}</span> "
            . "Bulan  <span class='ml-md-1 badge badge-success'>{$date->format('F')}</span>";
        }];
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
        $this->col[] = array("label"=>"Teacher Status","name"=>"status", "callback"=> function($row){
            if ($row->status == '1'){
                return "<span class='badge badge-success'><i class='fa fa-check-square-o'></i></span>";
            } else {
                return "<span class='badge badge-warning'><i class='fa fa-clock-o'></i></span>";
            }
        });
        $this->col[] = array("label"=>"Parents Status","name"=>"parents_status", "callback"=> function($row){
            if ($row->parents_status == '1'){
                return "<span class='badge badge-success'><i class='fa fa-check-square-o'></i></span>";
            } else {
                return "<span class='badge badge-warning'><i class='fa fa-clock-o'></i></span>";
            }
        });
        
        $this->col[] = ["label"=>"Kebersihan","visible"=>false,"name"=>"parents_bangun_pagi","callback_php"=>'!empty([parents_bangun_pagi]) ? count(explode(";",[parents_bangun_pagi])) : ""'];
        $this->col[] = ["label"=>"Mandiri","visible"=>false,"name"=>"parents_mandiri","callback_php"=>'!empty([parents_mandiri]) ? count(explode(";",[parents_mandiri])) : ""'];
        $this->col[] = ["label"=>"Subuh","visible"=>false,"name"=>"parents_subuh","callback_php"=>'!empty([parents_subuh]) ? count(explode(";",[parents_subuh])) : ""'];
        $this->col[] = ["label"=>"Dhuhur","visible"=>false,"name"=>"parents_dhuhur","callback_php"=>'!empty([parents_dhuhur]) ? count(explode(";",[parents_dhuhur])) : ""'];
        $this->col[] = ["label"=>"Ashar","visible"=>false,"name"=>"parents_ashar","callback_php"=>'!empty([parents_ashar]) ? count(explode(";",[parents_ashar])) : ""'];
        $this->col[] = ["label"=>"Magrib","visible"=>false,"name"=>"parents_magrib","callback_php"=>'!empty([parents_magrib]) ? count(explode(";",[parents_magrib])) : ""'];
        $this->col[] = ["label"=>"Isya","visible"=>false,"name"=>"parents_isya","callback_php"=>'!empty([parents_isya]) ? count(explode(";",[parents_isya])) : ""'];
        $this->col[] = ["label"=>"Mendoakan Ortu","visible"=>false,"name"=>"parents_mendoakan","callback_php"=>'!empty([parents_mendoakan]) ? count(explode(";",[parents_mendoakan])) : ""'];
        $this->col[] = ["label"=>"Patuh & Santun Ke Ortu","visible"=>false,"name"=>"parents_patuh","callback_php"=>'!empty([parents_patuh]) ? count(explode(";",[parents_patuh])) : ""'];
        $this->col[] = array("label"=>"Total","name"=>"id","callback"=> function($row){
            $total = DB::table($this->table)->selectRaw(
                'CONCAT_WS(";",
                    COALESCE(wudhu),
                    COALESCE(sholat),
                    COALESCE(on_time),
                    COALESCE(bel),
                    COALESCE(respect),
                    COALESCE(footwear),
                    COALESCE(trash),
                    COALESCE(learn),
                    COALESCE(dhuhur),
                    COALESCE(ashar),
                    COALESCE(dhuha),
                    COALESCE(parents_bangun_pagi),
                    COALESCE(parents_mandiri),
                    COALESCE(parents_subuh),
                    COALESCE(parents_dhuhur),
                    COALESCE(parents_ashar),
                    COALESCE(parents_magrib),
                    COALESCE(parents_isya),
                    COALESCE(parents_mendoakan),
                    COALESCE(parents_patuh)
                ) AS total')
            ->where('id', $row->id)->first();
            $total = count(explode(";",$total->total));
            return $total;
        });
        $this->col[] = ["label"=>"Informasi Dari Guru/Orangtua","visible"=>false,"name"=>"information"];
        $this->col[] = ["label"=>"Tanggapan Dari Guru/Orang Tua","visible"=>false,"name"=>"response"];

        # END COLUMNS DO NOT REMOVE THIS LINE
        

        // Form Disable
        $this->form = [];
        $this->form[] = [
                "label"             => "Nama Siswa",
                "name"              => "student_id",
                "type"              => "select2Parents",
                "required"          => true,
                "datatable_ajax"    => false,
                "datatable"         => "sdi_student,student_name",
                "datatable_format"  => "nis,' - ',student_name",
                "help"              => "Student Name",
            ];
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'Laporan Guru','name'=>'teacherReport', 'icon' => 'fa fa-user', 'type'=>'label','class'=>'title'];
        $this->form[] = ['name'=>'hr','type'=>'hr'];
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

        // Report Orang Tua
        // Kemandirian
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'Laporan Orang Tua','name'=>'parentsreport', 'icon' => 'fa fa-user', 'type'=>'label','class'=>'title'];
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
         $this->alert[]      = ['message'=>'Perhatian untuk lebih detail silahkan klik action','type'=>'info', 'title' => 'Perhatian !'];
                

        
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
        if ( MITBooster::myPrivilegeId() == 2 ) {
            $this->index_button[] = ['label'=>'Export Data','url'=>MITBooster::mainpath("export-report"),'color'=> 'success', "icon"=>"fa fa-file-excel-o"];
        }



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
            $this->student_id = json_decode(DB::table('mit_users')->where('id', MITBooster::myId())->first()->student_id, true);
            $query->whereIn('student_id', $this->student_id);
        } elseif( MITBooster::myPrivilegeId() == 2 ){
            $class      = DB::table('sdi_class')->where('class_wali_id', MITBooster::myId())->first();
            $student    = DB::table('sdi_student')->where('class_id', $class->id)->get();
            foreach ($student as $key) {
                $student_id[] = $key->id; 
            }
            $query->whereIn('sdi_weekly_report.student_id', $student_id);
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

    // Custom Code Start
    public function getExportReport(){
        $this->teacher_id       = MITBooster::myId();
        $this->data['class']    = DB::table('sdi_class')->where('class_wali_id', $this->teacher_id)->first();
        $this->data['page_title'] = 'Export Data Weekly Report';

        if ( MITBooster::myPrivilegeId() == 2 ){
            return view('report.weekly_report', $this->data);
        } else {
            return redirect(MITBooster::mainpath())->with('success', trans('mixtra.denied_access'));
        }
    }

    public function postDataFilter(Request $request){
        $this->teacher_id   = MITBooster::myId();
        $this->from         = \Carbon\Carbon::parse($request->input('from'));
        $this->to           = \Carbon\Carbon::parse($request->input('to'));
        $this->class        = DB::table('sdi_class')->where('class_wali_id', $this->teacher_id)->first();
        $this->student_id = DB::table('sdi_class AS class')
                            ->select('student.id AS student_id')
                            ->join('sdi_student AS student', 'student.class_id', '=', 'class.id')
                            ->where('class_wali_id', $this->teacher_id)
                            ->orderBy('student_id')
                            ->pluck('student_id')
                            ->toArray();
        $this->report = DB::table('sdi_weekly_report AS report')
                ->whereIn('student_id', $this->student_id)
                ->selectRaw('
                    student.student_name AS name,
                    report.student_id,
                    LENGTH(report.wudhu) - LENGTH(REPLACE(report.wudhu, ";", "")) + 1 AS total_wudhu,
                    LENGTH(report.sholat) - LENGTH(REPLACE(report.sholat, ";", "")) + 1 AS total_sholat,
                    LENGTH(report.on_time) - LENGTH(REPLACE(report.on_time, ";", "")) + 1 AS total_on_time,
                    LENGTH(report.bel) - LENGTH(REPLACE(report.bel, ";", "")) + 1 AS total_bel,
                    LENGTH(report.respect) - LENGTH(REPLACE(report.respect, ";", "")) + 1 AS total_respect,
                    LENGTH(report.footwear) - LENGTH(REPLACE(report.footwear, ";", "")) + 1 AS total_footwear,
                    LENGTH(report.trash) - LENGTH(REPLACE(report.trash, ";", "")) + 1 AS total_trash,
                    LENGTH(report.learn) - LENGTH(REPLACE(report.learn, ";", "")) + 1 AS total_learn,
                    LENGTH(report.dhuhur) - LENGTH(REPLACE(report.dhuhur, ";", "")) + 1 AS total_dhuhur,
                    LENGTH(report.ashar) - LENGTH(REPLACE(report.ashar, ";", "")) + 1 AS total_ashar,
                    LENGTH(report.dhuha) - LENGTH(REPLACE(report.dhuha, ";", "")) + 1 AS total_dhuha,
                    LENGTH(report.parents_bangun_pagi) - LENGTH(REPLACE(report.parents_bangun_pagi, ";", "")) + 1 AS total_parents_bangun_pagi,
                    LENGTH(report.parents_mandiri) - LENGTH(REPLACE(report.parents_mandiri, ";", "")) + 1 AS total_parents_mandiri,
                    LENGTH(report.parents_subuh) - LENGTH(REPLACE(report.parents_subuh, ";", "")) + 1 AS total_parents_subuh,
                    LENGTH(report.parents_dhuhur) - LENGTH(REPLACE(report.parents_dhuhur, ";", "")) + 1 AS total_parents_dhuhur,
                    LENGTH(report.parents_ashar) - LENGTH(REPLACE(report.parents_ashar, ";", "")) + 1 AS total_parents_ashar,
                    LENGTH(report.parents_magrib) - LENGTH(REPLACE(report.parents_magrib, ";", "")) + 1 AS total_parents_magrib,
                    LENGTH(report.parents_isya) - LENGTH(REPLACE(report.parents_isya, ";", "")) + 1 AS total_parents_isya,
                    LENGTH(report.parents_mendoakan) - LENGTH(REPLACE(report.parents_mendoakan, ";", "")) + 1 AS total_parents_mendoakan,
                    LENGTH(report.parents_patuh) - LENGTH(REPLACE(report.parents_patuh, ";", "")) + 1 AS total_parents_patuh
                ')
                ->join('sdi_student AS student', 'student.id', '=', 'report.student_id')
                ->whereBetween('report.created_at', [$this->from, $this->to])
                ->orderBy('report.created_at')
                ->get();

        foreach ($this->student_id as $k => $v):
            $total_wudhu = NULL;
            $total_sholat = NULL;
            $total_on_time = NULL;
            $total_bel = NULL;
            $total_respect = NULL;
            $total_footwear = NULL;
            $total_trash = NULL;
            $total_learn = NULL;
            $total_dhuhur = NULL;
            $total_ashar = NULL;
            $total_dhuha = NULL;
            $total_parents_bangun_pagi = NULL;
            $total_parents_mandiri = NULL;
            $total_parents_subuh = NULL;
            $total_parents_dhuhur = NULL;
            $total_parents_ashar = NULL;
            $total_parents_magrib = NULL;
            $total_parents_isya = NULL;
            $total_parents_mendoakan = NULL;
            $total_parents_patuh = NULL;
            foreach ($this->report as $key) {
                if($key->student_id === $v ){
                    $total_wudhu                += (int)$key->total_wudhu;
                    $total_sholat               += (int)$key->total_sholat;
                    $total_on_time              += (int)$key->total_on_time;
                    $total_bel                  += (int)$key->total_bel;
                    $total_respect              += (int)$key->total_respect;
                    $total_footwear             += (int)$key->total_footwear;
                    $total_trash                += (int)$key->total_trash;
                    $total_learn                += (int)$key->total_learn;
                    $total_dhuhur               += (int)$key->total_dhuhur;
                    $total_ashar                += (int)$key->total_ashar;
                    $total_dhuha                += (int)$key->total_dhuha;
                    $total_parents_bangun_pagi  += (int)$key->total_parents_bangun_pagi;
                    $total_parents_mandiri      += (int)$key->total_parents_mandiri;
                    $total_parents_subuh        += (int)$key->total_parents_subuh;
                    $total_parents_dhuhur       += (int)$key->total_parents_dhuhur;
                    $total_parents_ashar        += (int)$key->total_parents_ashar;
                    $total_parents_magrib       += (int)$key->total_parents_magrib;
                    $total_parents_isya         += (int)$key->total_parents_isya;
                    $total_parents_mendoakan    += (int)$key->total_parents_mendoakan;
                    $total_parents_patuh        += (int)$key->total_parents_patuh;
                    $hasil[$v] = [
                        'name'                      => $key->name,
                        'class'                     => $this->class,
                        'total_wudhu'               => $total_wudhu,
                        'total_sholat'              => $total_sholat,
                        'total_on_time'             => $total_on_time,
                        'total_bel'                 => $total_bel,
                        'total_respect'             => $total_respect,
                        'total_footwear'            => $total_footwear,
                        'total_trash'               => $total_trash,
                        'total_learn'               => $total_learn,
                        'total_dhuhur'              => $total_dhuhur,
                        'total_ashar'               => $total_ashar,
                        'total_dhuha'               => $total_dhuha,
                        'total_parents_bangun_pagi' => $total_parents_bangun_pagi,
                        'total_parents_mandiri'     => $total_parents_mandiri,
                        'total_parents_subuh'       => $total_parents_subuh,
                        'total_parents_dhuhur'      => $total_parents_dhuhur,
                        'total_parents_ashar'       => $total_parents_ashar,
                        'total_parents_magrib'      => $total_parents_magrib,
                        'total_parents_isya'        => $total_parents_isya,
                        'total_parents_mendoakan'   => $total_parents_mendoakan,
                        'total_parents_patuh'       => $total_parents_patuh
                    ];
                }
            }
        endforeach;

        return $hasil;
    }
}
