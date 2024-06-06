@extends('layouts.backend')

@section('content')
    <form id="transaction-edit-documents" action="/transaction-index_documents/{{ $transaction->id }}" method="POST">
        @csrf
        <x-laraknife.panels.standard title="{{ __('Documents of a Transaction') }}" fieldset="false">
            <x-laraknife.layout.nav-tabs :info="$navigationTabs" fieldset="false" button1Name="" button2Width1="4">
                <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
                    <x-laraknife.forms.string position="first" name="account" label="Account"
                        value="{{ $context->valueOf('account') }}" width2="3" attribute="readonly" />
                    <x-laraknife.forms.string position="middle" name="accountAmount" label=""
                        value="{{ $context->valueOf('accountAmount') }}" width1="0" width2="1"
                        attribute="readonly" />
                    <x-laraknife.forms.string position="last" name="mandator" label="Mandator"
                        value="{{ $context->valueOf('mandator') }}" width2="4" attribute="readonly" />
                    <hr>
                    <x-laraknife.forms.string position="alone" name="text" label="Text"
                        value="{{ $context->valueOf('text') }}" width2="10" />
                </x-laraknife.panels.filter>
                <x-laraknife.panels.index-button buttonType="new" />
                <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
                    <thead>
                        <tr>
                            <th></th>
                            <th sortId="title">{{ __('Title') }}</th>
                            <th sortId="description">{{ __('Description') }}</th>
                            <th sortId="filename">{{ __('Filename') }}</th>
                            <th sortId="filegroup_scope">{{ __('Filegroup') }}</th>
                            <th sortId="user_id">{{ __('User') }}</th>
                            <th sortid="size">{{ __('Size (MByte)') }}</th>
                            <th sortid="created_at">{{ __('Date') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $file)
                            <tr>
                                <td><x-laraknife.icons.change-record module="transaction" method="edit_document"
                                        no="{{ $file->id }}/{{ $context->valueOf('transaction_id') }}" /></td>
                                <td>{{ $file->title }}</td>
                                <td>{{ $file->description }}</td>
                                <td>{!! $context->callback('buildAnchor', $file) !!}</td>
                                <td> {{ __($file->filegroup_scope) }}</td>
                                <td>{{ $file->user_id }}</td>
                                <td>{{ $file->size }}</td>
                                <td>{{ $file->created_at }}</td>
                                <td><x-laraknife.icons.delete-record module="transaction" method="show_document"
                                        no="{{ $file->id }}" /></td>
                            </tr>
                        @endforeach
                    </tbody>
                </x-laraknife.panels.sortable-table>
            </x-laraknife.layout.nav-tabs>
        </x-laraknife.panels.standard>
    </form>
@endsection
