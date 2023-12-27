@props(['position' => 'alone', 'name' => 'combo', 'label' => '', 'options', 'width1' => 2, 'width2' => 10, 'class' => ''])
@if ($position === 'alone' || $position === 'first')
    <div class="row">
@endif
@if ($width1 > 0)
<div class="col-md-{{ $width1 }}"><label for="{{ $name }}">{{ __($label) }}</label>
</div>
@endif
<div class="col-md-{{ $width2 }}">
    <select class="lkn-input expand100 {{$class}}" name="{{$name}}">
        @foreach($options as $option)
        @php 
        $v = $option['value'];
        $a = $option['active'] ? ' selected' : '';
        $t = $option['text'];
        @endphp
        <option value="{{$v}}"{{$a}}>{{$t}}</option>
        @endforeach
    </select>
</div>
@if ($position === 'alone' || $position === 'last')
    </div>
@endif
