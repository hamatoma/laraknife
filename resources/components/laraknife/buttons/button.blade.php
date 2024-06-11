@props(['name' => 'btnOK', 'label' => 'OK', 'width1' => 2, 'width2' => 4, 'class' => ""])
@if ($width1 > 0)
<div class="col-md-{{$width1}}"><span class="pseudo-label">&nbsp;</span>
</div>
@endif
<div class="col-md-{{ $width2 }} {{ $class }}">
    <button class="lkn-button expand100" name="btnSubmit" value="{{$name}}">{{$label}}</button>
</div>