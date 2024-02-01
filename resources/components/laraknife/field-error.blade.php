@props(['name' => 'text'])
@if ($errors->has($name))
<div class="lkn-field-error">{{ $errors->first($name) }}</div>
@endif

