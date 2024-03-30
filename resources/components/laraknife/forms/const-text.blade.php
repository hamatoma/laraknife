@props(['position' => 'alone', 'text' => '', 'label' => '', 'width1' => 2, 'width2' => 10, 'attribute' => ''])
@if($position === 'alone' || $position === 'first')
<div class="row">
@endif
@if ($width1 > 0)
<div class="col-md-{{$width1}}">
  @if ($label === '')
  &nbsp;
  @else
  {{ __($label) }}
  @endif
</div>
@endif
@if ($width2 > 0)
<div class="col-md-{{ $width2 }}">
     {!! $text !!}
</div>
@endif
@if($position === 'alone' || $position === 'last')
</div>
@endif
