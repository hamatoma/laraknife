@props(['legend' => ''])
<fieldset class="lkn-filter lkn-panel">
    @if ($legend !== '')
        <legend>{{ $legend }}</legend>
    @endif
    {{$slot}}
    <div class="row lkn-empty-line-above">
        <x-laraknife.buttons.search width2="10" />
    </div>
</fieldset>
