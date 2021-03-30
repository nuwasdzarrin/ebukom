<?php

namespace App\Http\Controllers;
//used by prev parent
use Request;
use Illuminate\Support\Facades\Validator;
use DB;
use MITBooster;

class ParentReadController extends \mixtra\controllers\MITController
{
    public function init() {

        # START CONFIGURATION DO NOT REMOVE THIS LINE
        $this->table               = 'sdi_reading_parents_weekly_report';
        $this->primary_key         = 'id';
        $this->title_field         = "id";
        $this->button_action_style = 'button_icon';
        $this->button_action_width = '150px';
        $this->button_import       = FALSE;
        $this->button_export       = FALSE;
        # END CONFIGURATION DO NOT REMOVE THIS LINE
        $this->student_id          = json_decode(DB::table('mit_users')->where('id', MITBooster::myId())->first()->student_id, true);

        # START COLUMNS DO NOT REMOVE THIS LINE
        $this->col = [];
        $this->col[] = ["label"=>"Hari, Tanggal","name"=>"created_at", "callback"=> function($row){
            $date = \Carbon\Carbon::parse($row->created_at);
            return
            "Pekan Ke " . "<span class='ml-md-1 badge badge-info'>{$date->weekOfMonth}</span> "
            . "Bulan  <span class='ml-md-1 badge badge-success'>{$date->format('F')}</span>";
        }];
        $this->col[] = ["label"=>"Siswa","name"=>"student_id", 'join'=>'sdi_student,student_name'];
        $this->col[] = ["label"=>"Komik/Cerpen/Buku Cerita","name"=>"komik"];
        $this->col[] = ["label"=>"Buku Pelajaran","name"=>"b_pelajaran"];
        $this->col[] = ["label"=>"Buku Lainya","name"=>"b_lainya"];
        $this->col[] = array("label"=>"Value","name"=>"total_perday", "callback"=> function($row){
            $day = \Carbon\Carbon::getDays();
            $data = explode(',', $row->total_perday);
            foreach ($data as $key => $val) {
                $hasil .= $day[$key] . ": <strong>" . $val . "</strong><br/>" ;
            }
            return $hasil;
        });
        # END COLUMNS DO NOT REMOVE THIS LINE

        # START FORM DO NOT REMOVE THIS LINE
        $this->form = [];
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
        $this->form[] = ['name'=>'hr','type'=>'hr'];

        // Komik/Cerpen/Buku Cerita
        $pane = [];
        $groups = [];
        $pane[] = ['label'=>'Komik/Cerpen/Buku Cerita','name'=>'komik', 'icon' => 'fa fa-newspaper-o', 'type'=>'label','class'=>'title'];
        $pane[] = ['name'=>'hr','type'=>'hr'];
        $pane[] = ['label'=>'Senin','name'=>'senin_komik','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Selasa','name'=>'selasa_komik','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Rabu','name'=>'rabu_komik','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Kamis','name'=>'kamis_komik','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Jumat','name'=>'jumat_komik','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Sabtu','name'=>'sabtu_komik','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Ahad','name'=>'ahad_komik','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $groups[] = ['name'=>'kncb', 'width'=>'col-sm-2','pane'=>$pane];

        $pane = [];
        $pane[] = ['label'=>'Buku Pelajaran','name'=>'pelajaran', 'icon' => 'fa fa-book', 'type'=>'label','class'=>'title'];
        $pane[] = ['name'=>'hr','type'=>'hr'];
        $pane[] = ['label'=>'Senin','name'=>'senin_pelajaran','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Selasa','name'=>'selasa_pelajaran','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Rabu','name'=>'rabu_pelajaran','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Kamis','name'=>'kamis_pelajaran','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Jumat','name'=>'jumat_pelajaran','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Sabtu','name'=>'sabtu_pelajaran','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Ahad','name'=>'ahad_pelajaran','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $groups[] = ['name'=>'bp', 'width'=>'col-sm-2','pane'=>$pane];

        $pane = [];
        $pane[] = ['label'=>'Buku Lainya Jika Ada','name'=>'wafa', 'icon' => 'fa fa-book', 'type'=>'label','class'=>'title'];
        $pane[] = ['name'=>'hr','type'=>'hr'];
        $pane[] = ['label'=>'Senin','name'=>'senin_other','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Selasa','name'=>'selasa_other','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Rabu','name'=>'rabu_other','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Kamis','name'=>'kamis_other','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Jumat','name'=>'jumat_other','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Sabtu','name'=>'sabtu_other','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $pane[] = ['label'=>'Ahad','name'=>'ahad_other','type'=>'number','label_width'=>'col-md-4','width'=>'col-md-8'];
        $groups[] = ['name'=>'bl', 'width'=>'col-sm-2','pane'=>$pane];

        $pane = [];
        $pane[] = ['label'=>'Menulis Diary','name'=>'wafa', 'icon' => 'fa fa-pencil', 'type'=>'label','class'=>'title'];
        $pane[] = ['name'=>'hr','type'=>'hr'];
        $pane[] = ['label'=>'Menulis Diary','name'=>'diary','required'=>false,'type'=>'checkbox','datatable'=>'sdi_day_of_week_parents,day_name', 'orderby' => 'id'];
        $groups[] = ['name'=>'md', 'width'=>'col-sm-2','pane'=>$pane];

        $pane = [];
        $pane[] = ['label'=>'Total Halaman Buku Dibaca / Hari','name'=>'wafa', 'icon' => 'fa fa-calculator', 'type'=>'label','class'=>'title'];
        $pane[] = ['name'=>'hr','type'=>'hr'];
        $pane[] = ['label'=>'Senin','name'=>'total_senin','type'=>'number', 'readonly' => true,'width'=>'col-md-8','label_width'=>'col-md-4'];
        $pane[] = ['label'=>'Selasa','name'=>'total_selasa','type'=>'number', 'readonly' => true,'width'=>'col-md-8','label_width'=>'col-md-4'];
        $pane[] = ['label'=>'Rabu','name'=>'total_rabu','type'=>'number', 'readonly' => true,'width'=>'col-md-8','label_width'=>'col-md-4'];
        $pane[] = ['label'=>'Kamis','name'=>'total_kamis','type'=>'number', 'readonly' => true,'width'=>'col-md-8','label_width'=>'col-md-4'];
        $pane[] = ['label'=>'Jumat','name'=>'total_jumat','type'=>'number', 'readonly' => true,'width'=>'col-md-8','label_width'=>'col-md-4'];
        $pane[] = ['label'=>'Sabtu','name'=>'total_sabtu','type'=>'number', 'readonly' => true,'width'=>'col-md-8','label_width'=>'col-md-4'];
        $pane[] = ['label'=>'ahad','name'=>'total_ahad','type'=>'number', 'readonly' => true,'width'=>'col-md-8','label_width'=>'col-md-4'];
        $groups[] = ['name'=>'md', 'width'=>'col-sm-2','pane'=>$pane];

        $this->form[] = ['name'=>'membaca_buku','type'=>'group','groups'=>$groups];

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
        $this->script_js = "
            var curentMethod = '" . MITBooster::getCurrentMethod() . "';
            var student_id = '" . request()->segment(count(request()->segments())) . "';
            var path = '" . MITBooster::mainpath('get-detail') . "';
            if ( curentMethod == 'getEdit' || curentMethod == 'getDetail' ){
                $.ajax({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name=\'csrf-token\']').attr('content')
                    },
                    url: path,
                    type: 'POST',
                    dataType: 'JSON',
                    data: {'id' : student_id},
                    success:function(dt){

                    var komik = dt.komik.split(',');
                        $('#senin_komik').val(komik[0]);
                        $('#selasa_komik').val(komik[1]);
                        $('#rabu_komik').val(komik[2]);
                        $('#kamis_komik').val(komik[3]);
                        $('#jumat_komik').val(komik[4]);
                        $('#sabtu_komik').val(komik[5]);
                        $('#ahad_komik').val(komik[6]);

                    var pelajaran = dt.b_pelajaran.split(',');
                        $('#senin_pelajaran').val(pelajaran[0]);
                        $('#selasa_pelajaran').val(pelajaran[1]);
                        $('#rabu_pelajaran').val(pelajaran[2]);
                        $('#kamis_pelajaran').val(pelajaran[3]);
                        $('#jumat_pelajaran').val(pelajaran[4]);
                        $('#sabtu_pelajaran').val(pelajaran[5]);
                        $('#ahad_pelajaran').val(pelajaran[6]);

                    var other = dt.b_lainya.split(',');
                        $('#senin_other').val(other[0]);
                        $('#selasa_other').val(other[1]);
                        $('#rabu_other').val(other[2]);
                        $('#kamis_other').val(other[3]);
                        $('#jumat_other').val(other[4]);
                        $('#sabtu_other').val(other[5]);
                        $('#ahad_other').val(other[6]);


                    var total = dt.total_perday.split(',');
                        $('#total_senin').val(total[0]);
                        $('#total_selasa').val(total[1]);
                        $('#total_rabu').val(total[2]);
                        $('#total_kamis').val(total[3]);
                        $('#total_jumat').val(total[4]);
                        $('#total_sabtu').val(total[5]);
                        $('#total_ahad').val(total[6]);
                    }
                });
            }
            
            $(document).ready(function() {
                $('input[name^=\'senin_\']').on('input', function(e){
                    var senin = 0;
                    $('input[name^=\'senin_\']').each(function() {
                        senin += Number($(this).val());
                    });
                    $('input[name=\'diary[]\']:checked').each(function() {
                        if ( $(this).val() == 1){
                            senin += parseInt(1);
                        }
                    });
                    $('input[name=\'total_senin\']').val(senin);
                });
                $('input[name^=\'selasa_\']').on('input', function(e){
                    var selasa = 0;
                    $('input[name^=\'selasa_\']').each(function() {
                        selasa += Number($(this).val());
                    });
                    $('input[name=\'diary[]\']:checked').each(function() {
                        if ( $(this).val() == 2){
                            selasa += parseInt(1);
                        }
                    });
                    $('input[name=\'total_selasa\']').val(selasa);
                });
                $('input[name^=\'rabu_\']').on('input', function(e){
                    var rabu = 0;
                    $('input[name^=\'rabu_\']').each(function() {
                        rabu += Number($(this).val());
                    });
                    $('input[name=\'diary[]\']:checked').each(function() {
                        if ( $(this).val() == 3){
                            rabu += parseInt(1);
                        }
                    });
                    $('input[name=\'total_rabu\']').val(rabu);
                });
                $('input[name^=\'kamis_\']').on('input', function(e){
                    var kamis = 0;
                    $('input[name^=\'kamis_\']').each(function() {
                        kamis += Number($(this).val());
                    });
                    $('input[name=\'diary[]\']:checked').each(function() {
                        if ( $(this).val() == 4){
                            kamis += parseInt(1);
                        }
                    });
                    $('input[name=\'total_kamis\']').val(kamis);
                });
                $('input[name^=\'jumat_\']').on('input', function(e){
                    var jumat = 0;
                    $('input[name^=\'jumat_\']').each(function() {
                        jumat += Number($(this).val());
                    });
                    $('input[name=\'diary[]\']:checked').each(function() {
                        if ( $(this).val() == 5){
                            jumat += parseInt(1);
                        }
                    });
                    $('input[name=\'total_jumat\']').val(jumat);
                });
                $('input[name^=\'sabtu_\']').on('input', function(e){
                    var sabtu = 0;
                    $('input[name^=\'sabtu_\']').each(function() {
                        sabtu += Number($(this).val());
                    });
                    $('input[name=\'diary[]\']:checked').each(function() {
                        if ( $(this).val() == 6){
                            sabtu += parseInt(1);
                        }
                    });
                    $('input[name=\'total_sabtu\']').val(sabtu);
                });
                $('input[name^=\'ahad_\']').on('input', function(e){
                    var ahad = 0;
                    $('input[name^=\'ahad_\']').each(function() {
                        ahad += Number($(this).val());
                    });
                    $('input[name=\'diary[]\']:checked').each(function() {
                        if ( $(this).val() == 7){
                            ahad += parseInt(1);
                        }
                    });
                    $('input[name=\'total_ahad\']').val(ahad);
                });
                $('input[name^=\'diary\']').on('change', function(e){
                    var hasil = 0;
                    var selector    = $(this).val();
                    var element     = ['','total_senin','total_selasa','total_rabu','total_kamis','total_jumat','total_sabtu','total_ahad'];
                    var val         = document.getElementsByName(element[selector])[0];
                    var is_checked  = $(this).is(':checked');

                    if (is_checked){
                        hasil = parseInt(val.value.trim()) + parseInt(1);
                        val.value = hasil || 1;
                    } else if(!is_checked){
                        hasil = parseInt(val.value.trim()) - parseInt(1);
                        val.value = hasil || 0;
                    }
                });

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



        /*
        | ----------------------------------------------------------------------
        | Add css style at body
        | ----------------------------------------------------------------------
        | css code in the variable
        | $this->style_css = ".style[....}];
        |
        */
        $this->style_css = "
        .title {
            font-size: 12px !important;
            font-weight: bold;
            height: 50px;
        }
        #form-group-diary .col-form-label{
            display: none;
        }
        .form-check-inline{
            display: block !important;
            height: 46px;
            padding: 5px 0px 10px 0px;
        }
        .form-check-inline input{
            float: right; 
            width: 20%; 
            text-align: left;
        }
        .form-check-inline label{
            font-weight: 700 !important;
        }
        .col-sm-2{
            margin-left: 27px !important;
        }

        ";



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
            $this->student_id = explode(',',$this->student_id);
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
        $postdata['komik'] =
            $postdata['senin_komik'] . ',' .
            $postdata['selasa_komik'] . ',' .
            $postdata['rabu_komik'] . ',' .
            $postdata['kamis_komik'] . ',' .
            $postdata['jumat_komik'] . ',' .
            $postdata['sabtu_komik'] . ',' .
            $postdata['ahad_komik'];

        $postdata['b_pelajaran'] =
            $postdata['senin_pelajaran'] . ',' .
            $postdata['selasa_pelajaran'] . ',' .
            $postdata['rabu_pelajaran'] . ',' .
            $postdata['kamis_pelajaran'] . ',' .
            $postdata['jumat_pelajaran'] . ',' .
            $postdata['sabtu_pelajaran'] . ',' .
            $postdata['ahad_pelajaran'];

        $postdata['b_lainya'] =
            $postdata['senin_other'] . ',' .
            $postdata['selasa_other'] . ',' .
            $postdata['rabu_other'] . ',' .
            $postdata['kamis_other'] . ',' .
            $postdata['jumat_other'] . ',' .
            $postdata['sabtu_other'] . ',' .
            $postdata['ahad_other'];

        $postdata['total_perday'] =
            $postdata['total_senin'] . ',' .
            $postdata['total_selasa'] . ',' .
            $postdata['total_rabu'] . ',' .
            $postdata['total_kamis'] . ',' .
            $postdata['total_jumat'] . ',' .
            $postdata['total_sabtu'] . ',' .
            $postdata['total_ahad'];

            // Komik
            unset($postdata['senin_komik']);
            unset($postdata['selasa_komik']);
            unset($postdata['rabu_komik']);
            unset($postdata['kamis_komik']);
            unset($postdata['jumat_komik']);
            unset($postdata['sabtu_komik']);
            unset($postdata['ahad_komik']);

            // Buku Pelajaran
            unset($postdata['senin_pelajaran']);
            unset($postdata['selasa_pelajaran']);
            unset($postdata['rabu_pelajaran']);
            unset($postdata['kamis_pelajaran']);
            unset($postdata['jumat_pelajaran']);
            unset($postdata['sabtu_pelajaran']);
            unset($postdata['ahad_pelajaran']);

            // Buku Lainya
            unset($postdata['total_senin']);
            unset($postdata['total_selasa']);
            unset($postdata['total_rabu']);
            unset($postdata['total_kamis']);
            unset($postdata['total_jumat']);
            unset($postdata['total_sabtu']);
            unset($postdata['total_ahad']);

            // Total
            unset($postdata['senin_other']);
            unset($postdata['selasa_other']);
            unset($postdata['rabu_other']);
            unset($postdata['kamis_other']);
            unset($postdata['jumat_other']);
            unset($postdata['sabtu_other']);
            unset($postdata['ahad_other']);
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
        $postdata['komik'] =
            $postdata['senin_komik'] . ',' .
            $postdata['selasa_komik'] . ',' .
            $postdata['rabu_komik'] . ',' .
            $postdata['kamis_komik'] . ',' .
            $postdata['jumat_komik'] . ',' .
            $postdata['sabtu_komik'] . ',' .
            $postdata['ahad_komik'];

        $postdata['b_pelajaran'] =
            $postdata['senin_pelajaran'] . ',' .
            $postdata['selasa_pelajaran'] . ',' .
            $postdata['rabu_pelajaran'] . ',' .
            $postdata['kamis_pelajaran'] . ',' .
            $postdata['jumat_pelajaran'] . ',' .
            $postdata['sabtu_pelajaran'] . ',' .
            $postdata['ahad_pelajaran'];

        $postdata['b_lainya'] =
            $postdata['senin_other'] . ',' .
            $postdata['selasa_other'] . ',' .
            $postdata['rabu_other'] . ',' .
            $postdata['kamis_other'] . ',' .
            $postdata['jumat_other'] . ',' .
            $postdata['sabtu_other'] . ',' .
            $postdata['ahad_other'];

        $postdata['total_perday'] =
            $postdata['total_senin'] . ',' .
            $postdata['total_selasa'] . ',' .
            $postdata['total_rabu'] . ',' .
            $postdata['total_kamis'] . ',' .
            $postdata['total_jumat'] . ',' .
            $postdata['total_sabtu'] . ',' .
            $postdata['total_ahad'];

            // Komik
            unset($postdata['senin_komik']);
            unset($postdata['selasa_komik']);
            unset($postdata['rabu_komik']);
            unset($postdata['kamis_komik']);
            unset($postdata['jumat_komik']);
            unset($postdata['sabtu_komik']);
            unset($postdata['ahad_komik']);

            // Buku Pelajaran
            unset($postdata['senin_pelajaran']);
            unset($postdata['selasa_pelajaran']);
            unset($postdata['rabu_pelajaran']);
            unset($postdata['kamis_pelajaran']);
            unset($postdata['jumat_pelajaran']);
            unset($postdata['sabtu_pelajaran']);
            unset($postdata['ahad_pelajaran']);

            // Buku Lainya
            unset($postdata['total_senin']);
            unset($postdata['total_selasa']);
            unset($postdata['total_rabu']);
            unset($postdata['total_kamis']);
            unset($postdata['total_jumat']);
            unset($postdata['total_sabtu']);
            unset($postdata['total_ahad']);

            // Total
            unset($postdata['senin_other']);
            unset($postdata['selasa_other']);
            unset($postdata['rabu_other']);
            unset($postdata['kamis_other']);
            unset($postdata['jumat_other']);
            unset($postdata['sabtu_other']);
            unset($postdata['ahad_other']);
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

    public function postGetDetail(){
        $id = Request::get('id');
        $result = DB::table('sdi_reading_parents_weekly_report')->where('id', $id)->first();
        return response()->json($result);
    }
}
