@props(['position' => '', 'name' => 'btnOK', 'label' => 'OK', 'width1' => 2, 'width2' => 4, 'class' => ''])
@if($position === 'alone' || $position === 'first')
<div class="row">
@endif
@if($width1 > 0)
<x-laraknife.layout.col-empty width="{{$width1}}"/>
@endif
<div class="col-md-{{ $width2 }}">
    <button class="lkn-button expand100 {{ $class }}" name="btnSubmit" value="{{$name}}">{{ __($label) }}</button>
</div>
@if($position === 'alone' || $position === 'last')
</div>
@endif
