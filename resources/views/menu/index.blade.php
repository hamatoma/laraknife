@extends('layouts.backend')

@section('content')
    <form id="menu-index" action="/menu-index" method="POST">
        @csrf
        <x-laraknife.panels.index title="{{ __('Menus') }}">
            <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
                <x-laraknife.forms.text position="alone" name="text" label="Name" value="{{ $context->valueOf('text') }}"
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
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $menu)
                        <tr>
                            <td><x-laraknife.icons.change-record module="menu" no="{{ $menu->id }}" /></td>
                            <td>{{ $menu->name }}</td>
                            <td>{{ $menu->label }}</td>
                            <td>{{ $menu->icon }}</td>
                            <td>{{ $menu->section }}</td>
                            <td>{{ $menu->link }}</td>
                            <td><a href="/menu-show/{{ $menu->id }}/delete">{{ __('Delete') }}</a></td>
                            <td><x-laraknife.icons.delete-record module="menu" no="{{ $menu->id }}" /></td>
                        </tr>
                    @endforeach
                </tbody>
                </x-laraknife.tables.sortable>
        </x-laraknife.panels.index>
    </form>
@endsection
