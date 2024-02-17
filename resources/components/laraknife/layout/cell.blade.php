@props(['position' => '', 'content' => '', 'width' => '1'])
@if ($position === 'alone' || $position === 'first')
    <div class="row">
@endif
<div class="col-md-{{$width}}">
    {!! $content !!}
</div>
@if ($position === 'alone' || $position === 'last')
    </div>
@endif

