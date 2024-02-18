@props(['position' => 'alone', 'name' => 'text', 'label' => '', 'value' => '', 'width1' => 2, 'width2' => 10, 'placeholder' => '', 'attribute' => ''])
@if($position === 'alone' || $position === 'first')
<div class="row">
@endif
@if ($width1 > 0)
<div class="col-md-{{$width1}}"><label for="fld_{{$name}}"> {{ __($label) }} </label>
</div>
@endif
<div class="col-md-{{ $width2 }}">
    <input type="file" class="lkn-expand100" id="fld_{{$name}}" name="{{ $name }}" value="{{ $value }}" @if($placeholder !== '')) placeholder="{{ $placeholder }}" @endif {{$attribute}}>
    <x-laraknife.forms.field-error name="{{$name}}" />
</div>
@if($position === 'alone' || $position === 'last')
</div>
@endif
