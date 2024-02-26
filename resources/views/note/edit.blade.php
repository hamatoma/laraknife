@extends('layouts.backend')

@section('content')
    <form id="note-edit" action="/note-update/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.edit title="{{ __('Change of a Note') }}">
            <x-laraknife.layout.nav-tabs :info="$navTabsInfo">
                <x-laraknife.forms.combobox position="first" name="category_scope" label="Category" :options="$optionsCategory"
                    value="{{ $context->valueOf('category_scope') }}" width2="4" />
                <x-laraknife.forms.combobox position="last" name="notestatus_scope" label="Status" :options="$optionsNotestatus"
                    width2="4" />
                <x-laraknife.forms.string position="alone" name="title" label="Title"
                    value="{{ $context->valueOf('title') }}" width2="10" />
                <x-laraknife.forms.text position="alone" name="body" label="Body"
                    value="{{ $context->valueOf('body') }}" width2="10" rows="10" />
                <div class="row lkn-empty-line-above">
                    <x-laraknife.buttons.store width1="2" width2="4" />
                    <x-laraknife.buttons.cancel width1="2" width2="4" />
                </div>
            </x-laraknife.layout.nav-tabs>
        </x-laraknife.panels.edit>
    </form>
@endsection
