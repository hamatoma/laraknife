@props(['position' => 'alone', 'name' => 'file', 'fieldId' => '', 'label' => '', 'filename' => '', 'width1' => 2, 'width2' => 10, 'placeholder' => '', 'attribute' => ''])

@if(empty($fieldId))
  <x-laraknife.forms.file position="{{ $position }}" name="{{ $name }}" label="{{ $label }}" 
    width1="{{ $width1 }}" width2="{{ $width2 }}" />
@else
  <x-laraknife.forms.string position="{{ $position }}" name="{{ $name }" label="{{ $label }}" value="{{ $filename }}"
    width1="{{ $width1 }}" width2="{{ $width2 }}" placeholder="{{ $placeholder }}" attribute="readonly" />
@endif
