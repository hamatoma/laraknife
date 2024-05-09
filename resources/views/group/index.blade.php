@extends('layouts.backend')

@section('content')
    <form id="group-index" action="/group-index" method="POST">
        @csrf
        <x-laraknife.panels.index title="{{ __('Groups') }}">
            <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
                <x-laraknife.forms.string position="first" name="text" label="Text" value="{{ $context->valueOf('text') }}"
                    width2="4" />
                <x-laraknife.forms.combobox position="last" name="member" label="Member" :options="$optionsMember"
                    class="lkn-autoupdate" width2="4" />
            </x-laraknife.panels.filter>
            <x-laraknife.panels.index-button buttonType="new" />
            <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
                <thead>
                    <tr>
                        <th></th>
                        <th sortId="name">{{ __('Name') }}</th>
                        <th>{{ __('Members') }}</th>
                         <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $group)
                        <tr>
                            <td><x-laraknife.icons.change-record module="group" no="{{ $group->id }}" /></td>
                            <td>{{ $group->name }}</td>
                            <td>{{ $context->callback('members', $group->members) }}</td>
                            <td><x-laraknife.icons.delete-record module="group" no="{{ $group->id }}" /></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-laraknife.panels.sortable-table>
        </x-laraknife.panels.index>
    </form>
@endsection
