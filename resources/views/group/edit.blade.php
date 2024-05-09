@extends('layouts.backend')

@section('content')
    <form id="group-edit" action="/group-edit/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.edit title="{{ __('Change of a Group') }}">
            <input type="hidden" name="members" value="{{ $context->valueOf('members', True) }}">
            <x-laraknife.forms.string position="alone" name="name" label="Name" value="{{ $context->valueOf('name') }}"
                width2="4" />
                <x-laraknife.forms.combobox position="first" name="member" label="Member" :options="$optionsMember"
                width2="4" />
                <x-laraknife.buttons.button-position position="last" name="btnChange" label="Change" />
            <x-laraknife.forms.text position="alone" name="names" label="Members"
                value="{{ $context->valueOf('names') }}" width2="10" rows="3" attribute="readonly" />
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}"
                width2="10" rows="2" />
        </x-laraknife.panels.edit>
    </form>
@endsection
