@extends('layouts.backend')

@section('content')
    <form id="location-index" action="/location-index" method="POST">
        @csrf
        <x-laraknife.panels.index title="{{ __('Locations') }}">
            <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
                <x-laraknife.forms.string position="first" name="text" label="Text" value="{{ $context->valueOf('text') }}"
                    width2="4" />
                <x-laraknife.forms.string position="last" name="country" label="Country"
                    value="{{ $context->valueOf('country') }}" width2="4" />
                <x-laraknife.forms.string position="first" name="zip" label="Zip"
                    value="{{ $context->valueOf('zip') }}" width2="4" />
                <x-laraknife.forms.string position="last" name="city" label="City"
                    value="{{ $context->valueOf('city') }}" width2="4" />
            </x-laraknife.panels.filter>
            <x-laraknife.panels.index-button buttonType="new" />
            <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
                <thead>
                    <tr>
                        <th></th>
                        <th sortId="country">{{ __('Country') }}</th>
                        <th sortId="zip">{{ __('Zip') }}</th>
                        <th sortId="city">{{ __('City') }}</th>
                        <th sortId="street">{{ __('Street') }}</th>
                        <th sortId="additional">{{ __('Additional') }}</th>
                        <th sortId="owner">{{ __('Owner') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $location)
                        <tr>
                            <td><x-laraknife.icons.change-record module="location" no="{{ $location->id }}" /></td>
                            <td>{{ $location->country }}</td>
                            <td>{{ $location->zip }}</td>
                            <td>{{ $location->city }}</td>
                            <td>{{ $location->street }}</td>
                            <td>{{ $location->additional }}</td>
                            <td>{{ $location->owner }}</td>
                            <td><x-laraknife.icons.delete-record module="location" no="{{ $location->id }}" /></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-laraknife.panels.sortable-table>
        </x-laraknife.panels.index>
    </form>
@endsection
