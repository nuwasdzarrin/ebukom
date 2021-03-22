@if($form['begin_group'] == '' || $form['begin_group'] == 'true')
<div class='form-group {{$header_group_class}} row {{ ($errors->first($name))?"has-error":"" }}' id='form-group-{{$name}}' style="{{@$form['style']}}">
@endif
    <label class='col-form-label font-weight-bold col-sm-2'>{{$form['label']}}
        @if($required)
            <span class='text-danger' title='{!! trans('mixtra.this_field_is_required') !!}'>*</span>
        @endif
    </label>

    <div class="{{$col_width?:'col-sm-10'}}">
        @if($value)
            <?php
            if(Storage::exists($value) || file_exists($value)):
            $url = asset($value);
            $ext = pathinfo($url, PATHINFO_EXTENSION);
            $images_type = array('jpg', 'png', 'gif', 'jpeg', 'bmp', 'tiff');
            if(in_array(strtolower($ext), $images_type)):
            ?>
            <p>
                <!-- <a data-lightbox='roadtrip' href='{{$url}}'> -->
                <a data-lightbox='roadtrip' style='cursor: pointer;' onclick='showModal("{{asset($value)}}")'>
                <div class="modal fade" tabindex="-1" role="dialog" id="imageModal">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"><i class="fa fa-image"></i> Image</h4>
                                <button class="close" aria-label="Close" type="button" data-dismiss="modal">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>
                            <div class="modal-body" style="text-align: center;">
                                <img id="image" src="" style="max-width: 700px; max-height: 700px">
                            </div>
                        </div>
                    </div>
                </div>
                    <img style='max-width:160px' title="Image For {{$form['label']}}" src='{{$url}}'/>
                </a>
            </p>
            <?php else:?>
            <p><a href='{{$url}}'>{{trans("mixtra.button_download_file")}}</a></p>
            <?php endif;
            echo "<input type='hidden' name='_$name' value='$value'/>";
            else:
                echo "<p class='text-danger'><i class='fa fa-exclamation-triangle'></i> ".trans("mixtra.file_broken")."</p>";
            endif;
            ?>
            @if(!$readonly || !$disabled)
                <p><a class='btn btn-danger btn-delete btn-sm' onclick="if(!confirm('{{trans("mixtra.delete_title_confirm")}}')) return false"
                      href='{{url(MITBooster::mainpath("delete-image?image=".$value."&id=".$row->id."&column=".$name))}}'><i
                                class='fa fa-ban'></i> {{trans('mixtra.text_delete')}} </a></p>
            @endif
        @endif
        @if(!$value)
            <input type='file' id="{{$name}}" title="{{$form['label']}}" {{$required}} {{$readonly}} {{$disabled}} class='form-control upload-type' name="{{$name}}"/>
            <p class='help-block'>{{ @$form['help'] }}</p>
        @else
            <p class='text-muted'><em>{{trans("mixtra.notice_delete_file_upload")}}</em></p>
        @endif
        <div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>

    </div>

@if($form['end_group'] == '' || $form['end_group'] == 'true')    
</div>
@endif

