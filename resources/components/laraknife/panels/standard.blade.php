@props(['title' => 'Edit', 'error' => ''])
<div id="main-content" class="container mt-5">
    <x-laraknife.panels.common title="{{ $title }}" />
    <fieldset class="lkn-standard-panel">
        {{ $slot }}
    </fieldset>
</div>