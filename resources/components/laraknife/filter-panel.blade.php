@props(['legend' => ''])
<fieldset class="lkn-filter">
    @if ($legend !== '')
        <legend>{{ $legend }}</legend>
    @endif
    {{$slot}}
</fieldset>
