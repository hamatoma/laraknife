@props(['position' => '', 'label' => '', 'link' => '#', 'icon' => 'bi bi-question-octagon',  'width1' => 2, 'width2' => 10])
@if($position === 'alone' || $position === 'first')
<div class="row">
@endif
@if ($width1 > 0)
<div class="col-md-{{$width1}}">
</div>
@endif
  <div class="col-md-{{ $width2 }}">
     <a href="{{ $link }}" class="lkn-overview-cell">
        <h2>{{__($label)}}</h2>
        <div><i class="{{$icon}} lkn-icon-as-link"></i></div></a>
  </div>
@if($position === 'alone' || $position === 'last')
       </div>
@endif
