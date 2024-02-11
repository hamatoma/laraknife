@extends('layouts.backend')

@section('content')
    <form id="menu-order" action="/menu-order" method="POST">
        @csrf
        <x-laraknife.panels.index title="{{ __('Assign Roles') }}">
            <div class="lkn-form-table">
                <input type="hidden" name="selectedMenus" value="{{ $context->valueOf('selectedMenus') }}">
                <input type="hidden" name="lastRole" value="{{ $context->valueOf('lastRole') }}">
                <x-laraknife.forms.combobox position="alone" name="role" label="Role" :options="$roleOptions"
                    class="lkn-autoupdate" width2="10" />
                <x-laraknife.layout.row-empty />
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th>{{ __('Action') }}</th>
                            <th>{{ __('Position') }}</th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Label') }}</th>
                            <th>{{ __('Id') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $menu)
                            <tr><td>
                                <x-laraknife.actions.down no="{{ $menu->id }}" />&nbsp;
                                <x-laraknife.actions.up no="{{ $menu->id }}" />
                                </td>
                                <td>{{ $context->currentNo()}}</td>
                                <td>{{ __($menu->name) }}</td>
                                <td>{{ __($menu->label) }}</td>
                                <td>{{ __($menu->id) }}</td>
                                <td>
                                    <x-laraknife.actions.delete no="{{ $menu->id }}" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <x-laraknife.forms.text position="first" name="position" label="Insert Position"
                    value="{{ $context->valueOf('position') }}" width1="3" width2="1" />
                <x-laraknife.buttons.button name="btnStore" label="Store Menu" width1="0" width2="4" />
                <x-laraknife.buttons.button name="btnCancel" label="Cancel" width1="0" width2="4" />
                <x-laraknife.forms.set-position position="last" />
                <h2>{{ __('Not Assigned Menu Items') }}</h2>
                <table class="table table-responsive">
                    <thead>
                        <tr>
                            <th></th>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Label') }}</th>
                            <th>{{ __('Link') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records2 as $menu)
                            <tr>
                                <td>
                                    <x-laraknife.actions.up name="insert" no="{{ $menu->id }}"
                                        icon="bi bi-box-arrow-in-up" />
                                </td>
                                <td>{{ $menu->name }}</td>
                                <td>{{ $menu->label }}</td>
                                <td>{{ $menu->link }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </x-laraknife.panels.index>
    </form>
@endsection
