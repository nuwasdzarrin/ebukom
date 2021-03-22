@if($form['begin_group'] == '' || $form['begin_group'] == 'true')
<div class="form-group {{$header_group_class}} row {{ ($errors->first($name))?'has-error': '' }}" id="form-group-{{$name}}" style="{{@$form['style']}}">
@endif
    <label class="col-form-label font-weight-bold {{$label_width?:'col-sm-2'}}">{{$form['label']}}
        @if($required)
            <span class='text-danger' title='{!! trans('crudbooster.this_field_is_required') !!}'>*</span>
        @endif
    </label>

    <div class="{{$col_width?:'col-sm-10'}}">
        @if($form['sufix'] != '')
        <div class="input-group">
        @endif
            <input type="text" title="{{$form['label']}}" {{$required}} {{$readonly}} {!!$placeholder!!} {{$disabled}} class="form-control inputMoney text-right" name="{{$name}}" id="{{$name}}" value="{{$value}}">
        @if($form['sufix'] != '')
            <div class="input-group-append">
                <span class="input-group-text" id="basic-{{$name}}">{{$form['sufix']}}</span>
            </div>
        </div>
        @endif
    </div>
@if($form['end_group'] == '' || $form['end_group'] == 'true')    
</div>
@endif

