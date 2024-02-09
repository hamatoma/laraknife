@props(['title' => 'Show', 'mode' => 'delete'])
<div id="main-content" class="container mt-5">
    <x-laraknife.panels.common title="{{ $title }}" />
    <fieldset class="lkn-show-panel">
        {{ $slot }}
        <div class="row lkn-empty-line-above">
            @if ($mode === 'delete')
            <x-laraknife.buttons.delete width1="2" width2="4" />
            @endif
            <x-laraknife.buttons.cancel width1="2" width2="4" />
        </div>
        <x-laraknife.forms.form-error />
    </fieldset>
</div>