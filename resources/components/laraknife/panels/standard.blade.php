@props(['title' => 'Edit', 'error' => '', 'class' => '', 'fieldset' => 'true'])
<div id="main-content" class="container mt-5">
    <x-laraknife.panels.common title="{{ $title }}" />
    @if ($fieldset === 'true')
    <fieldset class="lkn-standard-panel {{$class}}">
        {{ $slot }}
    </fieldset>
    @else
    {{ $slot }}
    @endif
</div>
