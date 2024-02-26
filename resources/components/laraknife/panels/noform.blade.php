@props(['title' => 'Edit', 'error' => '', 'class' => ''])
<div id="main-content" class="container mt-5">
    <x-laraknife.panels.common title="{{ $title }}" />
    <fieldset class="lkn-noform-panel {{$class}}">
        {{ $slot }}
    </fieldset>
</div>