@extends('layouts.backend')

@section('content')
    <form id="transaction-editowner" action="/transaction-editowner/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.standard title="{{ __('Change of a Transaction') }}" fieldset="false">
            <x-laraknife.layout.nav-tabs :info="$navTabsInfo" fieldset="true" button1Name="" button2Width1="8">
                <x-laraknife.forms.combobox position="first" name="owner_id" label="Owner" :options="$optionsOwner"
                    value="{{ $context->valueOf('owner_id') }}" width2="4" />
                <x-laraknife.buttons.button-position position="last" name="btnStore" label="Store" width2="4" />
                <x-laraknife.forms.checkbox position="alone" name="withEmail" label="Send email notification" labelBelow="true" width1="2" />
             </x-laraknife.layout.nav-tabs>
            </x-laraknife.panels.edit>
    </form>
@endsection
