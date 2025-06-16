@props(['position' => 'alone', 'name' => 'checkbox', 'label' => '', 'value' => '0', 'labelBelow' => 'false', 'width1' => 2, 'width2' => 4, 'placeholder' => '', 'attribute' => ''])
@if($position === 'alone' || $position === 'first')
<div class="row">
@endif
@if ($labelBelow === 'true')
<div class="col-md-{{ $width2 }}">
<input type="checkbox" id="fld_{{$name}}" name="{{ $name }}" value="1"{{ $value === '1' ? ' checked' : ''}} {{$attribute}}>
    <label for="fld_{{$name}}"> {{ __($label) }} </label>
    <x-laraknife.forms.field-error name="{{$name}}" />
</div>
@else
  @if ($width1 > 0)
<div class="col-md-{{$width1}}"><label for="fld_{{$name}}"> {{ __($label) }} </label>
</div>
  @endif
<div class="col-md-{{ $width2 }}">
    <input type="checkbox" id="fld_{{$name}}" name="{{ $name }}" value="1"{{ $value === '1' ? ' checked' : ''}} {{$attribute}}>
    <x-laraknife.forms.field-error name="{{$name}}" />
</div>
@endif
@if($position === 'alone' || $position === 'last')
</div>
@endif
