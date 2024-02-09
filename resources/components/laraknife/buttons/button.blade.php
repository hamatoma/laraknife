@props(['name' => 'btnOK', 'label' => 'OK', 'width1' => 2, 'width2' => 4])
@if($width1 > 0)
<x-laraknife.layout.col-empty width="{{$width1}}"/>
@endif
<div class="col-md-{{ $width2 }}">
    <button class="bg-primary white expand100" name="btnSubmit" value="{{$name}}">{{$label}}</button>
</div>