<?php

namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\Validator;
use DB;
use MITBooster;

class ClassController extends \mixtra\controllers\MITController
{
   	public function init() {
		# START CONFIGURATION DO NOT REMOVE THIS LINE
		$this->table               = 'sdi_class';
		$this->primary_key         = 'id';
		$this->title_field         = "class_name";
		$this->button_action_style = 'button_icon';	
		$this->button_import 	   = FALSE;	
		$this->button_export 	   = FALSE;	
		# END CONFIGURATION DO NOT REMOVE THIS LINE
		
		# START COLUMNS DO NOT REMOVE THIS LINE
		$this->col = array();
		$this->col[] = array("label"=>"Class ID","name"=>"id");
		$this->col[] = array("label"=>"Class Grade","name"=>"class_grade");
		$this->col[] = array("label"=>"Class name","name"=>"class_name");
		$this->col[] = array("label"=>"Teacher","name"=>"class_wali_id", 'join' => 'mit_users,name');
		$this->col[] = array("label"=>"Class Info","name"=>"class_info");
		# END COLUMNS DO NOT REMOVE THIS LINE

		# START FORM DO NOT REMOVE THIS LINE
		$this->form = array(); 		
		$this->form[] = array("label"=>"Class Name","name"=>"class_name",'required'=>true,'validation'=>'required|alpha_spaces|min:3');
		$this->form[] = [
			"label"				=> "Teacher",
			"name"				=> "class_wali_id",
			'type'				=> 'select2',
			'required'			=> true,
			"datatable"			=> "mit_users,name",
			'datatable_where'	=> 'mit_privileges_id = 2',
			"datatable_format"  => "nik,' - ',name",
		];

		$this->form[] = [
			"label"				=> "Class Grade",
			"name"				=> "class_grade",
			'type'				=> 'select',
			'required'			=> true,
			'dataenum'			=> '1;2;3;4;5;6'
		];

		$this->form[] = array("label"=>"Class Info","name"=>"class_info",'required'=>true,'type'=>'textarea');		
		# END FORM DO NOT REMOVE THIS LINE
	}
}