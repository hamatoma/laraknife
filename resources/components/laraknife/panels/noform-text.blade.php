@props(['title' => 'Edit', 'error' => '', 'class' => ''])
<div id="main-content" class="container mt-5">
    <x-laraknife.panels.common title="{{ $title }}" />
    <div class="lkn-noform-frame">
    <fieldset class="lkn-noform-text {{$class}}">
        {{ $slot }}
    </fieldset>
    </div>
</div>