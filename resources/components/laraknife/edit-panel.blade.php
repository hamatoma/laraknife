@props(['title' => 'Edit', 'error' => ''])
<div id="main-content" class="container mt-5">
    <x-laraknife.main-header title="{{ $title }}" />
    <fieldset class="lkn-edit-panel">
        {{ $slot }}
        <div class="row lkn-empty-line-above">
            <x-laraknife.btn-store width1="2" width2="4" />
            <x-laraknife.btn-cancel width1="2" width2="4" />
        </div>
        <x-laraknife.form-error error="{{$error}}"/>
    </fieldset>
</div>