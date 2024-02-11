@props(['position' => 'alone', 'label' => '', 'icon' => 'bi bi-question-octagon', 'width1' => 2, 'width2' => 10])
@if($position === 'alone' || $position === 'first')
<div class="row">
@endif
@if ($width1 > 0)
<div class="col-md-{{$width1}}">
    <label for="fld_{{$name}}">{{ __($label) }}</label>
</div>
@endif
<div class="col-md-{{ $width2 }} lkn-icon-as-link-frame">
     <a href="{{ $link }}"><i class="{{$icon}} lkn-icon-as-link"></i></a><br/>
     {{$label}}
</div>
@if($position === 'alone' || $position === 'last')
</div>
@endif
