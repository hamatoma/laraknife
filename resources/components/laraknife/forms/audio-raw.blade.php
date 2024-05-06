@props(['fileLink' => '', 'type' => 'audio/mpeg', 'attribute' => '', 'class' => ''])
<audio controls @if ($class !== '') class="{{ $class }}" @endif> <source src="{{ $fileLink }}" type="{{ $type }}">{{ __("Sorry, audio is not supported.")}}. </audio> 
