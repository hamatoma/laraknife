@extends('layouts.backend')

@section('content')
    <form id="person-show" action="/person-show/{{ $context->model->id }}/{{ $mode }}" method="POST">
        @csrf
        @if ($mode === 'delete')
            @method('DELETE')
        @endif
        <x-laraknife.panels.show title="{{ __($mode !== 'delete' ? 'A Person' : 'Deletion of a Person') }}"
            mode="{{ $mode }}">
            <x-laraknife.forms.combobox position="first" name="gender_scope" label="Gender" :options="$optionsGender" width2="4"
                attribute="readonly" />
            <x-laraknife.forms.combobox position="last" name="persongroup_scope" label="Persongroup" :options="$optionsPersongroup"
                width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="first" name="lastname" label="Lastname"
                value="{{ $context->valueOf('lastname') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="last" name="firstname" label="Firstname"
                value="{{ $context->valueOf('firstname') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="first" name="nickname" label="Nickname"
                value="{{ $context->valueOf('nickname') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="last" name="middlename" label="Middlename"
                value="{{ $context->valueOf('middlename') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.string position="alone" name="titles" label="Titles"
                value="{{ $context->valueOf('titles') }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}"
                width2="10" attribute="readonly" rows="2" />
        </x-laraknife.panels.show>
    </form>
@endsection
