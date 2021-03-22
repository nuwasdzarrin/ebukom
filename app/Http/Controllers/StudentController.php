<?php

namespace App\Http\Controllers;

use Request;
use DB;
use MITBooster;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class StudentController extends \mixtra\controllers\MITController
{
   	public function init() {
		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->table               = 'sdi_student';
		$this->primary_key         = 'id';
		$this->title_field         = "student_name";
		$this->button_action_style = 'button_icon';
		$this->button_export 	   = FALSE;
		$this->button_import 	   = FALSE;
		# END CONFIGURATION DO NOT REMOVE THIS LINE
	
		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = array();
		$this->col[] = array("label"=>"NIS","name"=>"nis");
		$this->col[] = array("label"=>"Name","name"=>"student_name");
		$this->col[] = ["label"=>"Class","name"=>"class_id",'join'=>'sdi_class,class_name',"callback"=> function($row){
            return $row->sdi_class_class_grade . " - " . $row->sdi_class_class_name;
        }];
		$this->col[] = array("label"=>"Gender","name"=>"gender_id", 'join' => 'sdi_gender,gender');
		$this->col[] = array("label"=>"Birth Date","name"=>"birth_date");
		$this->col[] = array("label"=>"Address","name"=>"address");
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = array(); 		
		$this->form[] = array("label"=>"NIS","name"=>"nis",'required'=>true,'validation'=>'required|min:3|unique:sdi_student,nis');
		$this->form[] = array("label"=>"Name","name"=>"student_name",'required'=>true,'type'=>'text');		
		$this->form[] = [
            "label"             =>"Class",
            "name"              =>"class_id",
            "type"              =>"select",
            "datatable"         =>"sdi_class,class_name",
            "required"          => true,
            "datatable_format"  => "class_grade,' - ',class_name",
            "help"              => "Select Class",
        ];
		$this->form[] = array("label"=>"Gender","name"=>"gender_id","type"=>"select", "datatable"=> "sdi_gender,gender","required" => true);
		$this->form[] = array("label"=>"Birth Date","name"=>"birth_date",'required'=>true,'type'=>'date');		
		$this->form[] = array("label"=>"Address","name"=>"address",'required'=>true,'type'=>'textarea');		
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
        $this->button_selected[] = ['label' => 'Naik/Pindah Kelas', 'type' => 'custom', 'title' => 'Move Class/Next Grade', 'icon' => 'line-chart', 'name' => 'move_class'];

                
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
        if ( MITBooster::myPrivilegeId() == 2 ) {
            $this->index_button[] = ['label'=>'Import Student','url'=>MITBooster::mainpath("import-student"),'color'=> 'success', "icon"=>"fa fa-file-excel-o"];
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
        $this->script_js = "
            $('.selected-action div a').click(function () {
                var name  = $(this).data('name');
                var type  = $(this).attr('type');
                
                if ( type == 'custom' ) {
                    $(/'#form-table input[name='button_name']'/).val(name);
                    var title = $(this).attr('title');
                    var options = '" . MITBooster::mainpath('get-student') . "';
                    var move_class = '" . MITBooster::mainpath('move-student') . "';
                    var stdn = null;

                    $.ajax({
                        url: options,
                        headers: {'X-CSRF-TOKEN': '" . csrf_token() . "'},
                        type: 'POST',
                        async: false,
                        dataType: 'json',
                        success: function(data){
                            stdn = data[0];
                        }
                    });

                    Swal({
                        title: 'Move Student Class',
                        text: 'Are you sure want to ' + title + ' ?',
                        type: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#479788',
                        confirmButtonText: 'Yes!',
                        closeOnConfirm: false,
                        showLoaderOnConfirm: true,
                        input: 'select',
                        inputOptions: stdn,
                        inputPlaceholder: 'Select Class',
                    }).then( function (result) {
                        $('<input />').attr('type', 'hidden')
                            .attr('name', 'id_class')
                            .attr('value', result.value)
                            .appendTo('#form-table');
                        $('#form-table').submit();
                        // Swal.fire('Done');
                    });
                }
            });   
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
        $this->load_js[] = asset('assets/js/page/student.js');
        
        
        
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
        if($button_name == 'move_class') {
            DB::table('sdi_student')->whereIn('id', $id_selected)->update(['class_id'=> Request::post('id_class')]);
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
            $class = DB::table('sdi_class')->where('class_wali_id', MITBooster::myId())->first();
            $query->where('class_id', $class->id);
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
        //Your code here
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

    // Custom Code
    public function postGetStudent() {
        $data = db::table('sdi_class')->whereNull('deleted_at')->get();
        foreach ($data as $key) {
            $class[$key->id] = $key->class_grade . ' - ' . $key->class_name;
        }
        return response()->json([
            $class
        ]);
    }

    public function getImportStudent() {
        $this->data['page_title'] = 'Import Data Siswa';
        return view('student.import_student', $this->data);
    }

    public function postImportStudent() {
        $validator = Validator::make(Input::all(), [
            'select_file'  => 'required'
        ]);

        if ($validator->fails()) {
            $this->data['errors'] = $validator->errors()->all();
            return back()->with($this->data);
        } else {
            $data    = Excel::load(Input::file('select_file'))->get();
            $class   = DB::table('sdi_class')->where('class_wali_id', MITBooster::myId())->first();
            if ($data->count() > 0) {
                foreach ($data->toArray() as $row) {
                    $student = DB::table('sdi_student')
                            ->where('class_id', $class->id)
                            ->where('nis', $row['nis'])
                            ->first();

                    if ($student == !null){
                        $insert_data = [
                            'updated_at'         => date('Y-m-d H:i:s'),
                            'nis'               => $row["nis"],
                            'student_name'      => $row["nama_lengkap"],
                            'class_id'          => $class->id,
                            'gender_id'         => $row["gender"],
                            'birth_date'        => $row["tanggal_lahir"],
                            'address'           => $row["alamat"],
                            'deleted_at'        => null
                        ];
                        if (!empty($insert_data)) {
                            DB::table('sdi_student')
                            ->where('nis', $row['nis'])
                            ->update($insert_data);
                        }
                    } else {
                        $insert_data = [
                            'created_at'        => date('Y-m-d H:i:s'),
                            'nis'               => $row["nis"],
                            'student_name'      => $row["nama_lengkap"],
                            'class_id'          => $class->id,
                            'gender_id'         => $row["gender"],
                            'birth_date'        => $row["tanggal_lahir"],
                            'address'           => $row["alamat"]
                        ];
                        if (!empty($insert_data)) {
                            DB::table('sdi_student')->insert($insert_data);
                        }
                    }
                }
            }
            return back()->with('success', 'Student Imported successfully.');
        }
    }


}
