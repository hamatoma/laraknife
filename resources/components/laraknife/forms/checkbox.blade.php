@props(['position' => 'alone', 'name' => 'checkbox', 'label' => '', 'checked' => '', 'labelBelow' => 'false', 'width1' => 2, 'width2' => 4, 'placeholder' => '', 'attribute' => ''])
@if($position === 'alone' || $position === 'first')
<div class="row">
@endif
@if ($width1 > 0 and $labelBelow === 'false')
<div class="col-md-{{$width1}}"><label for="fld_{{$name}}"> {{ __($label) }} </label>
</div>
@endif
<div class="col-md-{{ $width2 }}">
    <input class="lkn-expand100" type="checkbox" id="fld_{{$name}}" name="{{ $name }}" value="1"{{ $checked ? ' checked' : ''}}" @if($placeholder !== '')) placeholder="{{ $placeholder }}" @endif {{$attribute}}>
    <x-laraknife.forms.field-error name="{{$name}}" />
    @if ($labelBelow === 'true')
    <label for="fld_{{$name}}"> {{ __($label) }} </label>
    @endif
</div>
@if($position === 'alone' || $position === 'last')
</div>
@endif
