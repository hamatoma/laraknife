@props(['position' => 'alone', 'name' => 'password', 'label' => '', 'value' => '', 'width1' => 2, 'width2' => 10, 'placeholder' => '', 'attribute' => ''])
@if($position === 'alone' || $position === 'first')
<div class="row">
@endif
<div class="col-md-{{$width1}}"><label for="fld_{{$name}}"> {{ __($label) }} </label>
</div>
<div class="col-md-{{ $width2 }}">
    <input type="password" id="fld_{{$name}}" class="expand100" name="{{ $name }}" value="{{ $value }}" @if(!empty($placeholder)) placeholder="{{ $placeholder }}" @endif {{$attribute}}>
</div>
@if($position === 'alone' || $position === 'last')
</div>
@endif
