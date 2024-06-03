@extends('layouts.backend')

@section('content')
    <form id="transaction-show" action="/transaction-show/{{ $context->model->id }}/{{ $mode }}" method="POST">
        @csrf
        @if($mode === 'delete')
        @method('DELETE')
        @endif
        <x-laraknife.panels.show title="{{ __($mode !== 'delete' ? 'A Transaction' : 'Deletion of a Transaction') }}" mode="{{$mode}}">
            <x-laraknife.forms.string position="first" name="id" label="Id" value="{{ $context->model->id }}" width2="4" attribute="readonly" />
            <x-laraknife.forms.combobox position="alone" name="transactiontype_scope" label="Transactiontype" :options="$optionsTransactiontype" width2="4" attribute="readonly"/>
            <x-laraknife.forms.combobox position="alone" name="transactionstate_scope" label="Transactionstate" :options="$optionsTransactionstate" width2="4" attribute="readonly"/>
            <x-laraknife.forms.combobox position="alone" name="account_id" label="Account" :options="$optionsAccount" width2="4" attribute="readonly"/>
            <x-laraknife.forms.combobox position="alone" name="twin_id" label="Twin" :options="$optionsTwin" width2="4" attribute="readonly"/>
            <x-laraknife.forms.combobox position="alone" name="owner_id" label="Owner" :options="$optionsOwner" width2="4" attribute="readonly"/>
            <x-laraknife.forms.text position="alone" name="info" label="Info" value="{{ $context->valueOf('info') }}" width2="4" attribute="readonly" rows="2" />
            <x-laraknife.forms.string position="alone" name="name" label="Name" value="{{ $context->valueOf('name') }}" width2="4" attribute="readonly" />
            <x-laraknife.string position="alone" name="amount" label="Amount" value="{{ $context->valueOf('amount') }}" width2="4" attribute="readonly" />
            <x-laraknife.string type="date" position="alone" name="date" label="Date" value="{{ $context->valueOf('date') }}" width2="4" attribute="readonly" />
        </x-laraknife.panels.show>
    </form>
@endsection
