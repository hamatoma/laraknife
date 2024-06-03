@extends('layouts.backend')

@section('content')
    <form id="transaction-index" action="/transaction-index/{{ $context->valueOf('account_id') }}" method="POST">
        @csrf
        <x-laraknife.panels.standard title="{{ __('Transactions') }}" fieldset="false">
            <x-laraknife.layout.nav-tabs :info="$navTabsInfo" fieldset="true" button1Name="" button2Width1="8">
                <x-laraknife.forms.string position="first" name="mandator" label="Mandator"
                    value="{{ $context->valueOf('mandator') }}" width2="4" attribute="readonly"/>
                <x-laraknife.forms.string position="last" name="account" label="Account"
                    value="{{ $context->valueOf('account') }}" width2="4" attribute="readonly"/>
                <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
                    <x-laraknife.forms.combobox position="first" name="transactiontype" label="Type"
                        :options="$optionsTransactiontype" class="lkn-autoupdate" width2="4" />
                    <x-laraknife.forms.combobox position="last" name="transactionstate" label="Status"
                        :options="$optionsTransactionstate" class="lkn-autoupdate" width2="4" />
                        <x-laraknife.forms.string position="first" name="name" label="Text"
                            value="{{ $context->valueOf('text') }}" width2="4" />
                    <x-laraknife.forms.combobox position="last" name="owner" label="Owner" :options="$optionsOwner"
                        class="lkn-autoupdate" width2="4" />
                    <x-laraknife.forms.string type="date" position="first" name="from" label="From"
                        value="{{ $context->valueOf('from') }}" width2="4" />
                    <x-laraknife.forms.string type="date" position="last" name="until" label="To"
                        value="{{ $context->valueOf('until') }}" width2="4" />
                </x-laraknife.panels.filter>
                <x-laraknife.panels.index-button buttonType="new" />
                <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
                    <thead>
                        <tr>
                            <th></th>
                            <th sortId="name">{{ __('Name') }}</th>
                            <th sortId="info">{{ __('Info') }}</th>
                            <th sortId="amount" class="lkn-align-right">{{ __('Amount') }}</th>
                            <th sortId="transactiontype_scope">{{ __('Type') }}</th>
                            <th sortId="transactionstate_scope">{{ __('Status') }}</th>
                            <th sortId="date">{{ __('Date') }}</th>
                            <th sortId="owner_id">{{ __('Owner') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $transaction)
                            <tr>
                                <td><x-laraknife.icons.change-record module="transaction" no="{{ $transaction->id }}" />
                                </td>
                                <td> {{ __($transaction->transactiontype_scope) }}</td>
                                <td> {{ __($transaction->transactionstate_scope) }}</td>
                                <td>{{ $transaction->name }}</td>
                                <td>{{ $transaction->info }}</td>
                                <td class="lkn-align-right">{{ $transaction->amount }}</td>
                                <td>{{ $transaction->date }}</td>
                                <td>{{ $transaction->owner }}</td>
                                <td><x-laraknife.icons.delete-record module="transaction" no="{{ $transaction->id }}" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-laraknife.panels.sortable-table>
            </x-laraknife.layout.nav-tabs>
        </x-laraknife.panels.standard>
    </form>
@endsection
