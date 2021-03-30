<?php
//used by prev teacher
namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\Validator;
use DB;
use MITBooster;

class TeacherAlquranController extends \mixtra\controllers\MITController
{
    public function init() {

        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->table               = 'sdi_quran_report';
        $this->orderby             = "created_at,DESC";
        $this->primary_key         = 'id';
        $this->title_field         = "id";
        $this->button_action_style = 'button_icon';
        $this->button_import       = FALSE;
        $this->button_export       = FALSE;
        # END CONFIGURATION DO NOT REMOVE THIS LINE
        $this->class_id            = DB::table('sdi_class')->where('class_wali_id', MITBooster::myId())->first()->id;
        $this->student_id          = json_decode(DB::table('mit_users')->where('id', MITBooster::myId())->first()->student_id, true);

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = array();
        $this->col[] = array("label"=>"Hari, Tanggal","name"=>"created_at", "callback"=> function($row){
            $date = \Carbon\Carbon::parse($row->created_at);
            return $date->format('l, d-m-Y');
        });
        $this->col[] = array("label"=>"Siswa","name"=>"student_id", 'join'=>'sdi_student,student_name');
        $this->col[] = array("label"=>"Jilid/Surah","name"=>"w_jilid");
        $this->col[] = array("label"=>"Hal/Ayat","name"=>"w_hal");
        $this->col[] = array("label"=>"Nilai","name"=>"w_nilai");
        $this->col[] = array("label"=>"Lanjut/Ulang","name"=>"w_lu");
        $this->col[] = array("label"=>"Al-Qur'an Surah/Hadits/Do'a","name"=>"t_doa");
        $this->col[] = array("label"=>"Hal/Ayat","name"=>"t_hal");
        $this->col[] = array("label"=>"Nilai","name"=>"t_nilai");
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = array();
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

        // WAFA
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'WAFA','name'=>'wafa', 'icon' => '', 'type'=>'label','class'=>'title'];
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = array("label"=>"Jilid/Surah","name"=>"w_jilid");
        $this->form[] = array("label"=>"Hal/Ayat","name"=>"w_hal");
        $this->form[] = array("label"=>"Nilai","name"=>"w_nilai");
        $this->form[] = array("label"=>"Lanjut/Ulang","name"=>"w_lu");

        // Tahfids
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = ['label'=>'TAHFIDZ','name'=>'wafa', 'icon' => '', 'type'=>'label','class'=>'title'];
        $this->form[] = ['name'=>'hr','type'=>'hr'];
        $this->form[] = array("label"=>"Al-qur'an Surah/Hadits/Do'a","name"=>"t_doa");
        $this->form[] = array("label"=>"Hal/Ayat","name"=>"t_hal");
        $this->form[] = array("label"=>"Nilai","name"=>"t_nilai");


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
            $query->whereIn('student_id', $this->student_id);
        } elseif( MITBooster::myPrivilegeId() == 2 ){
            $class      = DB::table('sdi_class')->where('class_wali_id', MITBooster::myId())->first();
            $student    = DB::table('sdi_student')->where('class_id', $class->id)->get();
            foreach ($student as $key) {
                $student_id[] = $key->id;
            }
            $query->whereIn('student_id', $student_id);
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
}
