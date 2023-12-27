@props(['legend' => ''])
<fieldset class="lkn-filter">
    @if ($legend !== '')
        <legend>{{ $legend }}</legend>
    @endif
    {{$slot}}
    <div class="row lkn-empty-line-above">
        <x-laraknife.btn-search width2="10" />
    </div>
</fieldset>
