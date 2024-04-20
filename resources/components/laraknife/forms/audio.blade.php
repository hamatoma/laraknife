@props(['position' => 'alone', 'label' => '', 'fileLink' => '', 'type' => 'audio/mpeg', 'width1' => 2, 'width2' => 10, 'attribute' => '', 'class' => ''])
@if($position === 'alone' || $position === 'first')
<div class="row">
@endif
@if ($width1 > 0)
<div class="col-md-{{$width1}}"><label>{{ __($label) }}</label>
</div>
@endif
<div class="col-md-{{ $width2 }}">
    <audio controls @if ($class !== '') class="{{ $class }}" @endif> <source src="{{ $fileLink }}" type="{{ $type }}">{{ __("Sorry, audio is not supported.")}}. </audio> 
</div>
@if($position === 'alone' || $position === 'last')
</div>
@endif
