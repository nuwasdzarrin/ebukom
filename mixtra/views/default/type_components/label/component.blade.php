@if($form['begin_group'] == '' || $form['begin_group'] == 'true')
<div class='form-group {{$header_group_class}} row {{ ($errors->first($name))?"has-error":"" }}' id='form-group-{{$name}}' style="{{@$form['style']}}">
@endif

<div class="{{$form['class']}} {{$col_width?:'col-sm-12'}}">
    @if($form['icon'])
        <i class="{{$form['icon']}}"> </i> 
    @endif
    {{$form['label']}}
</div>

@if($form['end_group'] == '' || $form['end_group'] == 'true')    
</div>
@endif
