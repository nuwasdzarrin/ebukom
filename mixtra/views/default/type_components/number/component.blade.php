<?php
$decimals = $form["decimals"] == '' ? 0 : $form["decimals"];
if($value == null || $value == '')
    $value = 0;
?>
@if($form['begin_group'] == '' || $form['begin_group'] == 'true')
<div class='form-group {{$header_group_class}} row {{ ($errors->first($name))?"has-error":"" }}' id='form-group-{{$name}}' style="{{@$form['style']}}">
@endif
    <label class='col-form-label font-weight-bold {{$label_width?:"col-sm-2"}}' style="{{@$form['style2']}}" style="{{@$form['style2']}}">{{$form['label']}}
        @if($required)
            <span class='text-danger' title='{!! trans('mixtra.this_field_is_required') !!}'>*</span>
        @endif
    </label>
    <div class="{{$col_width?:'col-sm-10'}}" style="{{@$form['style2']}}">
        @if($form['sufix'] != '' || $form['button_javascript'] != '')
        <div class="input-group">
        @endif
            <input type='text' title="{{$form['label']}}" aria-label="{{$form['label']}}" aria-describedby="basic-{{$name}}"
                   {{$required}} {{$readonly}} {!!$placeholder!!} {{$disabled}} {{$validation['max']?"maxlength=".$validation['max']:""}} 
                   class='form-control decimal text-right {{@$form["class"]}}' name="{{$name}}" id="{{$name}}" value='{{number_format($value,$decimals)}}'/>
            @if($form['button_javascript'] != '')
            <div class="input-group-append">
                <button class="btn btn-info btn-sm" onclick="{{$form['button_javascript']}}" type="button">
                    <i class='{{$form["button_icon"]}}'></i> {{$form['datamodal_button_caption']}}
                </button>
            </div>
            @endif
            @if($form['sufix'] != '')
            <div class="input-group-append">
                <span class="input-group-text {{@$form['class']}}" id="basic-{{$name}}">{{$form['sufix']}}</span>
            </div>
            @endif
        @if($form['sufix'] != '' || $form['button_javascript'] != '')
        </div>
        @endif

        <div class="text-danger">{!! $errors->first($name)?"<i class='fa fa-info-circle'></i> ".$errors->first($name):"" !!}</div>
        <p class='help-block'>{{ @$form['help'] }}</p>

    </div>
@if($form['end_group'] == '' || $form['end_group'] == 'true')    
</div>
@endif

@push('bottom')
<script type="text/javascript">
    @if($readonly == '')
        $('#{{$name}}').blur(function()
        {
            var value = $(this).val();
            $(this).val(numberToString(value, {{$decimals}}));
        });
    @endif
    
    // hanya number, no minus value
    $('#{{$name}}').keypress(function onlyNumberKey(evt) { 
        var ASCIICode = (evt.which) ? evt.which : evt.keyCode 
        if (ASCIICode > 31 && (ASCIICode < 48 || ASCIICode > 57)) 
        return false; 
        return true; 
    });
</script>
@endpush
