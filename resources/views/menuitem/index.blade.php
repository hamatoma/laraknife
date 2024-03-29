@extends('layouts.backend')

@section('content')
    <form id="menuitem-index" action="/menuitem-index" method="POST">
        @csrf
        <x-laraknife.panels.index title="{{ __('Menu Items') }}">
            <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
                <x-laraknife.forms.string position="alone" name="text" label="Name" value="{{ $context->valueOf('text') }}"
                    width2="4" />
            </x-laraknife.panels.filter>
            <x-laraknife.forms.set-position position="first" />
            <x-laraknife.buttons.button name="btnNew" label="{{ __('New')}}" width1="0" width2="6"  />
            <x-laraknife.buttons.button name="btnAssign" label="{{ __('Assign Roles')}}" width1="0" width2="6"  />
            <x-laraknife.forms.set-position position="last" />
            <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
                <thead>
                    <tr>
                        <th></th>
                        <th sortId="name">{{ __('Name') }}</th>
                        <th sortId="label">{{ __('Label') }}</th>
                        <th sortId="icon">{{ __('Icon') }}</th>
                        <th sortId="section">{{ __('Section') }}</th>
                        <th sortId="link">{{ __('Link') }}</th>
                        <th sortId="Id">{{ __('Id') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $menuitem)
                        <tr>
                            <td><x-laraknife.icons.change-record module="menuitem" no="{{ $menuitem->id }}" /></td>
                            <td>{{ $menuitem->name }}</td>
                            <td>{{ $menuitem->label }}</td>
                            <td><i class="{{ $menuitem->icon }}"></i> {{ $menuitem->icon }}</td>
                            <td>{{ $menuitem->section }}</td>
                            <td>{{ $menuitem->link }}</td>
                            <td>{{ $menuitem->id }}</td>
                            <td><x-laraknife.icons.delete-record module="menuitem" no="{{ $menuitem->id }}" /></td>
                        </tr>
                    @endforeach
                </tbody>
                </x-laraknife.tables.sortable>
        </x-laraknife.panels.index>
    </form>
@endsection
