@extends('layouts.backend')

@section('content')
    <form id="user-edit" action="/user-address/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.standard title="{{ __('Addresses') }}" fieldset="false">
            <x-laraknife.layout.nav-tabs :info="$navTabsInfo" fieldset="true" button1Name="">
                <x-laraknife.forms.text position="alone" name="list" label="Current addresses"
                    value="{{ $context->valueOf('list') }}" width2="10" rows="8" attribute="readonly" />
                <x-laraknife.layout.cell position="first" content="{{ __('') }}" width="2" />
                <x-laraknife.layout.cell position="last" content="{{ __('phone;info;prio or email;info;prio or country-zip city;street;additional;info;prio or linenumber (for deletion)') }}" width="10" />
                <x-laraknife.forms.string position="first" name="address" label="Address"
                    value="{{ $context->valueOf('address') }}" width2="8" />
                <x-laraknife.buttons.button name="btnChange" label="{{ _('Change') }}" width1="0" width2="2" />
                <x-laraknife.forms.set-position position="last" />
            </x-laraknife.layout.nav-tabs>
            </x-laraknife.panels.edit>
    </form>
@endsection
