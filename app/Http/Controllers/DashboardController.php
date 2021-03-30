<?php

namespace App\Http\Controllers;

use Request;
use Illuminate\Support\Facades\Validator;
use DB;
use MITBooster;

class DashboardController extends \mixtra\controllers\MITController
{

    public function getIndex()
    {
        $this->data = [];
        $this->data['page_title'] = "Dashboard";
//        $this->table        = 'mit_shortcut';
//        $shortcut= [];
//        $this->result = DB::table($this->table)
//        						->join('mit_menus', 'mit_menus.id', $this->table . '.path')
//        						->where('privileges_id', MITBooster::myPrivilegeId())
//        						->get();
//
//       	foreach ( $this->result AS $key => $val ):
//       		$shortcut = [
//       			'path'			=> route($val->path),
//       			'shortcut_name'	=> $val->shortcut_name,
//       			'icon'			=> $val->icon,
//       			'bgcolor'		=> $val->bgcolor,
//       			'description'	=> $val->description,
//       		];
//       	endforeach;
//       	$this->data['shortcut'] = $shortcut;

       	$this->data['role'] = '';

        if (MITBooster::myPrivilegeId() == 4 || MITBooster::myPrivilegeId() == 2){
            if (MITBooster::myPrivilegeId() == 2) $this->data['role'] = 'teacher';
            else $this->data['role'] = 'parent';
        	return view("dashboard.summary", $this->data);
        }else{
        	return view("dashboard.parents", $this->data);
        }
    }
}
