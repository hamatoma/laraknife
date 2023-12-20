@props(['position' => 'alone', 'name' => 'combo', 'label' => '', 'options' => null, 'width1' => 2, 'width2' => 10, 'class' => ''])
@if ($position === 'alone' || $position === 'first')
    <div class="row">
@endif
<div class="col-md-{{ $width1 }}"><label for="{{ $name }}"> {{ __($label) }} </label>
</div>
<div class="col-md-{{ $width2 }}">
    <select class="lkn-input expand100 {{$class}}" name="{{$name}}">{!! $options !!}
    </select>
</div>
@if ($position === 'alone' || $position === 'last')
    </div>
@endif
