@props(['position' => 'alone', 'name' => 'text', 'label' => '', 'value' => '', 'width1' => 2, 'width2' => 10, 'placeholder' => '', 'rows' => '2', 'attribute' => ''])
@if($position === 'alone' || $position === 'first')
<div class="row">
@endif
<div class="col-md-{{$width1}}">
    <label for="{{$name}}">{{ __($label) }}</label>
</div>
<div class="col-md-{{ $width2 }}">
     <textarea class="expand100" name="{{$name}}" rows="{{$rows}}" @if($placeholder !== '') placeholder="{{$placeholder}}" @endif>{{$value}} {{ $attribute }}</textarea>
</div>
@if($position === 'alone' || $position === 'last')
</div>
@endif
