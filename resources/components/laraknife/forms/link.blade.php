@props([
    'position' => 'alone',
    'reference' => '',
    'label' => '',
    'text' => '',
    'width1' => 2,
    'width2' => 10,
    'attribute' => '',
])
@if ($position === 'alone' || $position === 'first')
    <div class="row">
@endif
@if ($width1 > 0)
    <div class="col-md-{{ $width1 }}">
        @if ($label !== '')
            {{ __($label) }}
        @else
            <span class="pseudo-label"></span>
        @endif
    </div>
@endif
<div class="col-md-{{ $width2 }}">
    @if ($reference !== '')
        <a class="lkn-link" href="{{ $reference }}" {{ $attribute }}>{{ $text === '' ? $reference : $text }}</a>
    @endif
</div>
@if ($position === 'alone' || $position === 'last')
    </div>
@endif
