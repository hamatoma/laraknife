@props(['title' => 'Edit', 'error' => '', 'with_storage' => true])
<div id="main-content" class="container mt-5">
    <x-laraknife.panels.common title="{{ $title }}" />
    <fieldset class="lkn-panel">
        {{ $slot }}
        <div class="row lkn-empty-line-above">
            @if( $with_storage !== 'false' ) <x-laraknife.buttons.store width1="2" width2="4" /> @endif()
            <x-laraknife.buttons.cancel width1="2" width2="4" />
        </div>
        <x-laraknife.forms.form-error/>
    </fieldset>
</div>