@extends('layouts.backend')

@section('content')
    <form id="change-edit" action="/change-update/{{ $context->model->id  }}" method="POST">
        @csrf
        <x-laraknife.panels.edit title="{{ __('Change of a Change') }}">
        <x-laraknife.forms.text position="alone" name="id" label="Id" value="{{ $context->model->id }}" width2="4" attribute="readonly"/>
        <x-laraknife.forms.combobox position="alone" name="changetype_scope" label="Changetype" :options="$optionsChangetype" width2="4"/>
        <x-laraknife.forms.combobox position="alone" name="module_id" label="Module" :options="$optionsModule" width2="4"/>
        <x-laraknife.forms.combobox position="alone" name="reference_id" label="Reference" :options="$optionsReference" width2="4"/>
        <x-laraknife.forms.combobox position="alone" name="user_id" label="User" :options="$optionsUser" width2="4"/>
        <x-laraknife.forms.text position="alone" name="current" label="Current" value="{{ $context->valueOf('current') }}" width2="4" rows="2" />
        <x-laraknife.forms.string position="alone" name="description" label="Description" value="{{ $context->valueOf('description') }}" width2="4" />
        <x-laraknife.forms.string position="alone" name="link" label="Link" value="{{ $context->valueOf('link') }}" width2="4" />
        </x-laraknife.panels.edit>
    </form>
@endsection
