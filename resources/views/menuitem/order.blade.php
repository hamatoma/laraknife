@extends('layouts.backend')

@section('content')
    <form id="menuitem-order" action="/menuitem-order" method="POST">
        @csrf
        <x-laraknife.panels.index title="{{ __('Assign Roles') }}">
            <div class="lkn-form-table">
                <input type="hidden" name="selectedMenuItems" value="{{ $context->valueOf('selectedMenuItems') }}">
                <input type="hidden" name="lastRole" value="{{ $context->valueOf('lastRole') }}">
                <x-laraknife.forms.combobox position="first" name="role" label="Role" :options="$roleOptions"
                    class="lkn-autoupdate" width2="4" />
                <x-laraknife.forms.combobox position="last" name="section" label="Section" :options="$optionsSection"
                    class="lkn-autoupdate" width2="4" />
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
                        @foreach ($records as $menuitem)
                            <tr><td>
                                <x-laraknife.actions.down no="{{ $menuitem->id }}" />&nbsp;
                                <x-laraknife.actions.up no="{{ $menuitem->id }}" />
                                </td>
                                <td>{{ $context->currentNo()}}</td>
                                <td>{{ __($menuitem->name) }}</td>
                                <td>{{ __($menuitem->label) }}</td>
                                <td>{{ __($menuitem->id) }}</td>
                                <td>
                                    <x-laraknife.actions.delete no="{{ $menuitem->id }}" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <x-laraknife.forms.string position="first" name="position" label="Insert Position"
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
                        @foreach ($records2 as $menuitem)
                            <tr>
                                <td>
                                    <x-laraknife.actions.action name="insert" no="{{ $menuitem->id }}"
                                        icon="bi bi-box-arrow-in-up" />
                                </td>
                                <td>{{ $menuitem->name }}</td>
                                <td>{{ $menuitem->label }}</td>
                                <td>{{ $menuitem->link }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
        </x-laraknife.panels.index>
    </form>
@endsection
