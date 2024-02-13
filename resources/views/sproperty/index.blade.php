@extends('layouts.backend')

@section('content')
<form id="sproperty-index" action="/sproperty-index" method="POST">
    @csrf
    <x-laraknife.panels.index title="{{ __('Scoped Properties') }}">
        <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
            <x-laraknife.forms.combobox position="first" name="scope" label="Scope" :options="$options"
                width2="4" class="lkn-autoupdate" />
            <x-laraknife.forms.string position="last" name="text" label="Text" value="{{ $context->valueOf('text') }}"
                width2="4" />
        </x-laraknife.panels.filter>
        <x-laraknife.panels.index-button buttonType="new"/>
        <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
            <thead>
                <tr>
                    <th></th>
                    <th sortId="id">{{ __('Id') }}</th>
                    <th sortId="scope">{{ __('Scope') }}</th>
                    <th sortId="name">{{ __('Name') }}</th>
                    <th sortId="order">{{ __('Order') }}</th>
                    <th sortId="shortname">{{ __('Shortname') }}</th>
                    <th sortId="value">{{ __('Value') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $sproperty)
                    <tr>
                        <td><x-laraknife.icons.change-record module="sproperty" no="{{ $sproperty->id }}" /></td>
                        <td>{{ $sproperty->id }}</td>
                        <td>{{ $sproperty->scope }}</td>
                        <td>{{ $sproperty->name }}</td>
                        <td>{{ $sproperty->order }}</td>
                        <td>{{ $sproperty->shortname }}</td>
                        <td>{{ $sproperty->value }}</td>
                        <td><x-laraknife.icons.delete-record module="sproperty" key="{{ $sproperty->id }}" /></td>
                    </tr>
                @endforeach
            </tbody>
        </x-laraknife.tables.sortable>
    </x-laraknife.panels.index>
</form>
@endsection
