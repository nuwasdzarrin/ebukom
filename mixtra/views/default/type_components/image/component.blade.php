<?php
$width = $form['width'] == '' ? '' : 'width='.$form['width'];
$height = $form['height'] == '' ? '' : 'height='.$form['height'];

if ($form['url'] != null) {
    $value = $form['url'];
}else{
    $value = asset($value);
}
?>
<div class='text-center'>
    <img src="{{$value}}" alt="{{$label}}" name="img-{{$name}}" id="img-{{$name}}" {{$width}} {{$height}} />
</div>