@extends('layouts.backend')

@section('content')
    <form id="person-edit" action="/person-address/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.standard title="{{ __('Addresses') }}" fieldset="false">
            <x-laraknife.layout.nav-tabs :info="$navTabsInfo" fieldset="true" button1Name="">
                <x-laraknife.forms.string position="first" name="lastname" label="Lastname"
                    value="{{ $context->valueOf('lastname') }}" width2="4" attribute="readonly" />
                <x-laraknife.forms.string position="last" name="firstname" label="Firstname"
                    value="{{ $context->valueOf('firstname') }}" width2="4"  attribute="readonly"/>
                <x-laraknife.forms.text position="alone" name="list" label="Current addresses"
                    value="{{ $context->valueOf('list') }}" width2="10" rows="8" attribute="readonly" />
                <x-laraknife.layout.cell position="first" content="{{ __('') }}" width="2" />
                <x-laraknife.layout.cell content="{!! __('phone or phone;info or email or email;info<br>or street NEWLINE zip city') !!}" width="8" />
                <x-laraknife.forms.link position="last" reference="/page-showhelp/help_person_address" text="{!! __('Help') !!}" width1="0" width2="2" />
                <x-laraknife.forms.text position="first" name="address" label="Address"
                    value="{{ $context->valueOf('address') }}" width2="8" rows="3" />
                <x-laraknife.buttons.button name="btnAdd" label="{{ __('Add') }}" width1="0" width2="2" /><br>
                <x-laraknife.forms.set-position position="last" />
            </x-laraknife.layout.nav-tabs>
            </x-laraknife.panels.edit>
    </form>
@endsection
