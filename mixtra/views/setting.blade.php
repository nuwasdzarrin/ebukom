@extends('mitbooster::layouts.admin')

@push('head')
<link rel="stylesheet" href="{{ asset('assets/vendor/dropify/dist/css/dropify.min.css') }}"/>
@endpush

@section('content')
    @push('bottom')
        <!-- <script src="{{asset('vendor/laravel-filemanager/js/lfm.js')}}"></script> -->
		<script src="{{ asset('assets/vendor/dropify/dist/js/dropify.min.js') }}"></script>
    
        <script src="//cdn.tinymce.com/4/tinymce.min.js"></script>
        <script>
            $(function () {
                $('.label-setting').hover(function () {
                    $(this).find('a').css("visibility", "visible");
                }, function () {
                    $(this).find('a').css("visibility", "hidden");
                })
            })
            var editor_config = {
                path_absolute: "{{asset('/')}}",
                selector: ".wysiwyg",
                height: 250,
                {{ ($disabled)?"readonly:1,":"" }}
                plugins: [
                    "advlist autolink lists link image charmap print preview hr anchor pagebreak",
                    "searchreplace wordcount visualblocks visualchars code fullscreen",
                    "insertdatetime media nonbreaking save table contextmenu directionality",
                    "emoticons template paste textcolor colorpicker textpattern"
                ],
                toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image media",
                relative_urls: false,
                file_browser_callback: function (field_name, url, type, win) {
                    var x = window.innerWidth || document.documentElement.clientWidth || document.getElementsByTagName('body')[0].clientWidth;
                    var y = window.innerHeight || document.documentElement.clientHeight || document.getElementsByTagName('body')[0].clientHeight;

                    var cmsURL = editor_config.path_absolute + 'laravel-filemanager?field_name=' + field_name;
                    if (type == 'image') {
                        cmsURL = cmsURL + "&type=Images";
                    } else {
                        cmsURL = cmsURL + "&type=Files";
                    }

                    tinyMCE.activeEditor.windowManager.open({
                        file: cmsURL,
                        title: 'Filemanager',
                        width: x * 0.8,
                        height: y * 0.8,
                        resizable: "yes",
                        close_previous: "no"
                    });
                }
            };

            tinymce.init(editor_config);

        </script>
    @endpush
<p align="right">
	<a title='Add Field Setting' 
		class='btn btn-sm btn-primary' 
		href='{{route("SettingsControllerGetAdd")."?group_setting=".$page_title}}'>
		<i class='fa fa-plus'></i> Add Field Setting
	</a>
</p>

<form method='post' 
	id="form" 
	class="form-horizontal" 
	enctype="multipart/form-data" 
	action='{{MITBooster::mainpath("save-setting?group_setting=$page_title")}}'>
	<div class="card" style="max-width: 750px;margin: 0 auto">
	    <div class="card-header">
	        <i class='fa fa-cog'></i> {{$page_title}}
	    </div>
	    <div class="card-body">
	        <input type="hidden" name="_token" value="{{ csrf_token() }}">

	        <?php
			$set = DB::table('mit_settings')->where('group_setting', $page_title)->orderby('id')->get();
	        foreach($set as $s):

	        	$value = $s->content;

	            if (! $s->label) {
	                $label = ucwords(str_replace('_', ' ', $s->name));
	                DB::table('mit_settings')->where('id', $s->id)->update(['label' => $label]);
	                $s->label = $label;
	            }

	            $dataenum = explode(',', $s->dataenum);
	            if ($dataenum) {
	                array_walk($dataenum, 'trim');
	            }

	        ?>

	        <div class="form-group">
	            <label class='label-setting' title="{{$s->name}}">{{$s->label}}
	            	<a style="visibility:hidden;padding:2px;" href='{{MITBooster::mainpath("edit/$s->id")}}' title='Edit This Meta Setting'
                       class='btn btn-box-tool'><i class='fas fa-pencil-alt'></i></a>
                    <a style="visibility:hidden;padding:2px;" href='javascript:;' title='Delete this Setting' class='btn btn-box-tool'
                       onClick='swal({   title: "Are you sure?",   text: "You will not be able to recover {{$s->label}} and may be can cause some errors on your system !",   type: "warning",   showCancelButton: true,   confirmButtonColor: "#DD6B55",   confirmButtonText: "Yes, delete it!",   closeOnConfirm: false }).then(function(){  location.href="{{MITBooster::mainpath("delete/$s->id")}}" });'><i class='fas fa-trash-alt'></i></a>
                </label>
	            <?php
	                switch ($s->content_input_type) {
	                    case 'text':
	                        echo "<input type='text' class='form-control form-control-sm' name='$s->name' value='$value'/>";
	                        break;
	                    case 'number':
	                        echo "<input type='number' class='form-control form-control-sm' name='$s->name' value='$value'/>";
	                        break;
	                    case 'email':
	                        echo "<input type='email' class='form-control form-control-sm' name='$s->name' value='$value'/>";
	                        break;
	                    case 'textarea':
	                        echo "<textarea name='$s->name' class='form-control form-control-sm'>$value</textarea>";
	                        break;
	                    case 'wysiwyg':
	                        echo "<textarea name='$s->name' class='form-control form-control-sm wysiwyg'>$value</textarea>";
	                        break;
	                    case 'upload':
	                    case 'upload_image':
	                    	?>
	                    	@push('bottom')
	                    		<script>$('.{{ $s->name }}').dropify();</script>
	                    	@endpush
	                    	<?php
	                        if ($value) {
	                            echo "<input type='hidden' name='$s->name' value='$value'/>";
	                        	echo "<input type='file' name='$s->name' id='$s->name' class='form-control $s->name' data-max-file-size='10M' data-allowed-file-extensions='jpg png gif' data-default-file='".asset($value)."'/>";
								echo "<p>
									<a href='javascript:;' onclick='if(confirm(\"Are you sure want to delete ?\")) location.href=\"".MITBooster::mainpath("delete-file-setting?id=$s->id")."\"' title='Click here to delete'><i class='fa fa-trash-alt'></i> Delete the File</a><br/>
									<a href='".asset($value)."' target='_blank' title='Download the file of $s->label'><i class='fa fa-download'></i> Download the File  of $s->label</a></p>";
	                        } else {
	                        	echo "<input type='file' name='$s->name' id='$s->name' class='form-control $s->name' data-max-file-size='10M' />";
	                        }
	                        echo "<footer><small class='text-muted'>File support only jpg,png,gif Max 10 MB</small></footer>";
	                        break;
	                    case 'upload_file':
	                    	?>
	                    	@push('bottom')
	                    		<script>$('.{{ $s->name }}').dropify();</script>
	                    	@endpush
	                    	<?php
	                        if ($value) {
	                            echo "<input type='hidden' name='$s->name' value='$value'/>";
	                        	echo "<input type='file' name='$s->name' id='$s->name' class='form-control $s->name' data-max-file-size='20M' data-default-file='".asset($value)."'/>";
								echo "<p>
									<a href='javascript:;' onclick='if(confirm(\"Are you sure want to delete ?\")) location.href=\"".MITBooster::mainpath("delete-file-setting?id=$s->id")."\"' title='Click here to delete'><i class='fa fa-trash-alt'></i> Delete the File</a><br/>
									<a href='".asset($value)."' target='_blank' title='Download the file of $s->label'><i class='fa fa-download'></i> Download the File  of $s->label</a></p>";

	                            // echo "<p><a href='".asset($value)."' target='_blank' title='Download the file of $s->label'><i class='fa fa-download'></i> Download the File  of $s->label</a></p>";
	                            // echo "<input type='hidden' name='$s->name' value='$value'/>";
	                            // echo "<div class='text-right'><a class='btn btn-danger btn-xs' onclick='if(confirm(\"Are you sure want to delete ?\")) location.href=\"".MITBooster::mainpath("delete-file-setting?id=$s->id")."\"' title='Click here to delete'><i class='fa fa-trash'></i></a></div>";
	                        } else {
	                        	echo "<input type='file' name='$s->name' id='$s->name' class='form-control $s->name' data-max-file-size='20M' />";
	                        }
	                        echo "<footer><small class='text-muted'>File support only doc,docx,xls,xlsx,ppt,pptx,pdf,zip,rar, Max 20 MB</small></footer>";
	                        break;
	                    case 'datepicker':
	                        echo "<input type='text' class='datepicker form-control form-control-sm' name='$s->name' value='$value'/>";
	                        break;
	                    case 'radio':
	                        if ($dataenum):
	                            echo "<br/>";
	                            foreach ($dataenum as $enum) {
	                                $checked = ($enum == $value) ? "checked" : "";
	                                echo "<label class='radio-inline'>";
	                                echo "<input type='radio' name='".$s->name."' value='$enum' $checked > $enum";
	                                echo "</label>";
	                            }
	                        endif;
	                        break;
	                    case 'select':
	                        echo "<select name='$s->name' class='form-control form-control-sm'><option value=''>** Please select $s->label</option>";
	                        if ($dataenum):
	                            foreach ($dataenum as $enum) {
	                                $selected = ($enum == $value) ? "selected" : "";
	                                echo "<option $selected value='$enum'>$enum</option>";
	                            }
	                        endif;
	                        echo "</select>";
	                        break;
	                }
	                ?>
	                <footer><small class="text-muted">{{$s->helper}}</small></footer>
	        </div>
	        <?php endforeach;?>

	    </div>
	    <div class="card-footer">
		    <div class='text-right'>
		        <input type='submit' name='submit' value='Save' class='btn btn-success'/>
		    </div>
		</div>

	</div>
</form>


@endsection