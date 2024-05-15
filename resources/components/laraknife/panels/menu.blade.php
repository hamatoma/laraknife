@props(['title'])
<div id="main-content" class="container mt-5">
    <x-laraknife.panels.common title="{{ $title }}" />
    <fieldset class="lkn-panel">
        {{ $slot }}
    </fieldset>
</div>