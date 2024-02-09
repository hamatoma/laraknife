@extends('layouts.backend')

@section('content')
    <form id="role-index" action="/role-index" method="POST">
        @csrf
        <x-laraknife.panels.index title="{{ __('Roles') }}">
            <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
                <x-laraknife.forms.text position="alone" name="name" label="Name" value="{{ $context->valueOf('name') }}"
                    width2="4" />
            </x-laraknife.panels.filter>

            <x-laraknife.panels.index-button buttonType="new" />
            <x-laraknife.forms.set-position position="first" />
            <x-laraknife.forms.icon-action name="action" no="3" icon="bi bi-trash" />
            <x-laraknife.forms.set-position position="last" />
            <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
                <thead>
                    <tr>
                        <th></th>
                        <th sortId="name">{{ __('Name') }}</th>
                        <th sortId="priority">{{ __('Priority') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $role)
                        <tr>
                            <td><x-laraknife.icons.change-record module="role" no="{{ $role->id }}" /></td>
                            <td>{{ $role->name }}</td>
                            <td>{{ $role->priority }}</td>
                        </tr>
                    @endforeach
                </tbody>
                </x-laraknife.tables.sortable>
        </x-laraknife.panels.index>
    </form>
@endsection
