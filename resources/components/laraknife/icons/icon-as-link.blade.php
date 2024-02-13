@props(['position' => '', 'header' => '', 'footer' => '', 'link' => '#', 'icon' => '',  'width1' => 2, 'width2' => 10])
@if($position === 'alone' || $position === 'first')
<div class="row">
@endif
@if($width1 > 0)
<div class="col-md-{{$width1}}">
</div>
@endif
  <div class="col-md-{{ $width2 }} lkn-icon-as-link">
    <a href="{{ $link }}">
      @if($header !== '') 
      <h2>{{__($header)}}</h2>
      @endif()
      <div><i class="{{$icon}}"></i></div>
      @if($footer !== '') 
      <h2>{{__($footer)}}</h2>
      @endif()
    </a>
  </div>
@if($position === 'alone' || $position === 'last')
</div>
@endif
