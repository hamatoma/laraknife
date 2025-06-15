@props([
    'position' => 'alone',
    'name' => '',
    'label' => '',
    'value' => '',
    'placeholder' => '',
    'width1' => 2,
    'width2' => 4,
    'class' => '',
    'attribute' => '',
])
@if ($position === 'alone' || $position === 'first')
    <div class="row">
@endif
@if ($width1 > 0)
    <div class="col-md-{{ $width1 }}"><label for="fld_{{ $name }}">{{ __($label) }}</label>
    </div>
@endif
<div class="col-md-{{ $width2 }}">
    <input class="lkn-expand100" type="text" id="fld_{{ $name }}" name="{{ $name }}"
        value="{{ $value }}" @if ($placeholder !== '') ) placeholder="{{ $placeholder }}" @endif
        {{ $attribute }}>
    <x-laraknife.forms.field-error name="{{ $name }}" />
</div>
@if ($position === 'alone' || $position === 'last')
    </div>
@endif
