@extends('layouts.backend')

@section('content')
    <form id="user-index" action="/user-index" method="POST">
        @csrf
        @php
            $t = __('Users');
            $l = $pagination->legendText();
        @endphp
        <x-laraknife.panels.index title="{{ $t }}">
            <x-laraknife.panels.filter legend="{{ $l }}">
                <x-laraknife.forms.combobox position="first" name="role" label="Role" :options="$roleOptions"
                    class="lkn-autoupdate" width2="4" />
                <x-laraknife.forms.string position="last" name="text" label="Text"
                    value="{{ $context->valueOf('text') }}" width2="4" />
            </x-laraknife.panels.filter>
            <x-laraknife.panels.index-button buttonType="new" />
            <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
                <thead>
                    <tr>
                        <th></th>
                        <th sortId="id">{{ __('Id') }}</th>
                        <th sortId="name">{{ __('Name') }}</th>
                        <th sortId="email">{{ __('Email') }}</th>
                        <th sortId="role">{{ __('Role') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $user)
                        <tr>
                            <td><x-laraknife.icons.change-record module="user" no="{{ $user->id }}" /></td>
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->role }}</td>
                            <td><x-laraknife.icons.delete-record module="user" no="{{ $user->id }}" /></td>
                        </tr>
                    @endforeach
                </tbody>
                </x-laraknife.tables.sortable>
        </x-laraknife.panels.index>
    </form>
@endsection
