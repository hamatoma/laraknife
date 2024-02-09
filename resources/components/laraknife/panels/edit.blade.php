@props(['title' => 'Edit', 'error' => ''])
<div id="main-content" class="container mt-5">
    <x-laraknife.panels.common title="{{ $title }}" />
    <fieldset class="lkn-edit-panel">
        {{ $slot }}
        <div class="row lkn-empty-line-above">
            <x-laraknife.buttons.store width1="2" width2="4" />
            <x-laraknife.buttons.cancel width1="2" width2="4" />
        </div>
        <x-laraknife.forms.form-error/>
    </fieldset>
</div>