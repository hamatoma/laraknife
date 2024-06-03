@extends('layouts.backend')

@section('content')
    <form id="account-index" action="/account-index/{{ $context->valueOf('mandator_id')}}" method="POST">
        @csrf
        <x-laraknife.panels.standard title="{{ __('Accounts') }}" fieldset="false">
            <x-laraknife.layout.nav-tabs :info="$navTabsInfo" fieldset="true" button1Name="" button2Width1="8">
                <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
                    <x-laraknife.forms.string position="alone" name="text" label="Text"
                        value="{{ $context->valueOf('text') }}" width2="4" />
                </x-laraknife.panels.filter>
                <x-laraknife.panels.index-button buttonType="new" />
                <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
                    <thead>
                        <tr>
                            <th></th>
                            <th sortId="name">{{ __('Name') }}</th>
                            <th sortId="info">{{ __('Info') }}</th>
                            <th class="lkn-align-right" sortId="amount">{{ __('Amount') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $account)
                            <tr>
                                <td><x-laraknife.icons.change-record module="account" no="{{ $account->id }}" /></td>
                                <td>{{ $account->name }}</td>
                                <td>{{ $account->info }}</td>
                                <td class="lkn-align-right">{{ $account->amount }}</td>
                                <td><x-laraknife.icons.delete-record module="account" no="{{ $account->id }}" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-laraknife.panels.sortable-table>
            </x-laraknife.layout.nav-tabs>
        </x-laraknife.panels.standard>
    </form>
@endsection
