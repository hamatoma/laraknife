@props(['position' => 'alone', 'name' => 'radiogroup', 'label' => '', 'options', 'width1' => 2, 'width2' => 10, 'oneperline' => 'false', 'class' => '', 'attribute' => ''])
@if ($position === 'alone' || $position === 'first')
    <div class="row">
@endif
@if ($width1 > 0)
<div class="col-md-{{ $width1 }}"><label for="fld_{{ $name }}">{{ __($label) }}</label>
</div>
@endif
<div class="col-md-{{ $width2 }}">
        @foreach($options as $option)
        @php 
        $v = $option['value'];
        $a = $option['active'] ? ' checked' : '';
        $t = $option['text'];
        $id = "$name-$v"; 
        @endphp
        <input type="radio" id="{{ $id }}" name="{{ $name }}" value="{{ $t}}" {{ $a }}>&nbsp;<label for="{{ $id }}" {{ $attribute }} >{{ $t }}</label> @if( $oneperline !== 'false')<br>@endif
        @endforeach
    </select>
    <x-laraknife.forms.field-error name="{{$name}}" />
</div>
@if ($position === 'alone' || $position === 'last')
    </div>
@endif
