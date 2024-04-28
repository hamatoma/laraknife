@props(['fileLink' => '', 'type' => 'audio/mpeg', 'attribute' => '', 'class' => ''])
    <div class="lkn-expand100">
    <audio controls class="lkn-center-block lkn-audio-control {{ $class }}" <source src="{{ $fileLink }}" type="{{ $type }}">{{ __("Sorry, audio is not supported.")}}. </audio> 
    </div>