@extends('layouts.backend')

@section('content')
    <form id="user-edit" action="/user-update/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.standard title="{{ __('Change of an User') }}" fieldset="false">
            <x-laraknife.layout.nav-tabs :info="$navTabsInfo" fieldset="true">
                <x-laraknife.forms.string position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}"
                    width2="4" attribute="{!! $context->readonlyUnlessOwner() !!}"/>
                <x-laraknife.forms.string position="last" name="email" label="Email"
                    value="{{ $context->valueOf('email') }}" width2="4" attribute="{!! $context->readonlyUnlessOwner() !!}"/>
                <x-laraknife.forms.combobox position="first" name="role_id" label="Role" :options="$roleOptions"
                    width2="4" attribute="{!! $context->isAdmin() ? '' : 'readonly' !!}"/>
                <x-laraknife.forms.combobox position="last" name="localization" label="Localization" :options="$localizationOptions"
                    width2="4" attribute="{!! $context->readonlyUnlessOwner() !!}"/>
            </x-laraknife.layout.nav-tabs>
        </x-laraknife.panels.edit>
    </form>
@endsection
