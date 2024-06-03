@extends('layouts.backend')

@section('content')
    <form id="transaction-create" action="/transaction-store/{{ $context->valueOf('account_id') }}" method="POST">
        @csrf
        @method('PUT')
        <x-laraknife.panels.standard title="{{ __('Creation of a Transaction') }}" fieldset="false">
            <x-laraknife.layout.nav-tabs :info="$navTabsInfo" fieldset="true">
                <x-laraknife.forms.string position="first" name="account" label="Account" value="{{ $context->valueOf('account') }}"
                    width2="4" attribute="readonly"/>
                <x-laraknife.forms.string position="last" name="mandator" label="Mandator" value="{{ $context->valueOf('mandator') }}"
                    width2="4" attribute="readonly"/>
                <x-laraknife.forms.string position="first" name="name" label="Name" value="{{ $context->valueOf('name') }}"
                    width2="4" />
                <x-laraknife.forms.string type="date" position="last" name="date" label="Date"
                    value="{{ $context->valueOf('date') }}" width2="4" />
                <x-laraknife.forms.combobox position="first" name="transactiontype_scope" label="Type" :options="$optionsTransactiontype"
                    width2="4" />
                <x-laraknife.forms.combobox position="last" name="transactionstate_scope" label="Status" :options="$optionsTransactionstate"
                    width2="4" />
                <x-laraknife.forms.string position="first" name="amount" label="Amount"
                    value="{{ $context->valueOf('amount') }}" width2="4" />
                <x-laraknife.forms.combobox position="last" name="owner_id" label="Owner" :options="$optionsOwner"
                    width2="4" />
                <x-laraknife.forms.text position="alone" name="info" label="Info"
                    value="{{ $context->valueOf('info') }}" width2="10" rows="3" />
            </x-laraknife.layout.nav-tabs>
        </x-laraknife.panels.standard>
    </form>
@endsection
