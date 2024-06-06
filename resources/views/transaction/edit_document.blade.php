@extends('layouts.backend')

@section('content')
    <form id="transaction-update_document" action="/transaction-update_document/{{ $context->model->id }}/{{ $context->valueOf('transaction_id') }}" method="POST">
        @csrf
        <x-laraknife.panels.standard title="{{ __('Change of a Document') }}" fieldset="false">
            <x-laraknife.layout.nav-tabs :info="$navTabsInfo" fieldset="true">
                <x-laraknife.forms.string position="first" name="account" label="Account"
                    value="{{ $context->valueOf('account') }}" width2="3" attribute="readonly" />
                <x-laraknife.forms.string position="middle" name="accountAmount" label=""
                    value="{{ $context->valueOf('accountAmount') }}" width1="0" width2="1" attribute="readonly" />
                <x-laraknife.forms.string position="last" name="mandator" label="Mandator"
                    value="{{ $context->valueOf('mandator') }}" width2="4" attribute="readonly" />
                <hr>
                <x-laraknife.forms.string position="alone" name="title" label="Title"
                    value="{{ $context->valueOf('title') }}" width2="10" />
                <x-laraknife.forms.text position="alone" name="description" label="Description"
                    value="{{ $context->valueOf('description') }}" width2="10" rows="4" />
                <x-laraknife.forms.combobox position="first" name="visibility_scope" label="Visibility" :options="$optionsVisibility"
                    width2="4" />
                <x-laraknife.forms.combobox position="last" name="filegroup_scope" label="Filegroup" :options="$optionsFilegroup"
                    width2="4" />
                <x-laraknife.forms.string position="alone" name="filename" label="Filename"
                    value="{{ $context->valueOf('filename') }}" width2="10" attribute="readonly" />
                <x-laraknife.forms.string position="first" name="size" label="Size"
                    value="{{ $context->valueOf('size') }}" width2="4" attribute="readonly" />
                <x-laraknife.forms.string position="last" name="created_at" label="Date"
                    value="{{ $context->valueOf('created_at') }}" width2="4" attribute="readonly" />
            </x-laraknife.layout.nav-tabs>
        </x-laraknife.panels.standard>
    </form>
@endsection
