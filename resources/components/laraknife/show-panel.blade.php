@props(['title' => 'Show', 'mode' => 'delete'])
<div id="main-content" class="container mt-5">
    <x-laraknife.main-header title="{{ $title }}" />
    <fieldset class="lkn-show-panel">
        {{ $slot }}
        <div class="row lkn-empty-line-above">
            @if ($mode === 'delete')
            <x-laraknife.btn-delete width1="2" width2="4" />
            @endif
            <x-laraknife.btn-cancel width1="2" width2="4" />
        </div>
        <x-laraknife.form-error />
    </fieldset>
</div>