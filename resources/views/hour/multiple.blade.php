@extends('layouts.backend')

@section('content')
    <form id="hour-multiple" action="/hour-multiple" method="POST">
        @csrf
        <x-laraknife.panels.index title="{{ __('Modify hour entries') }}">
            <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
                <x-laraknife.forms.combobox position="first" name="hourtype" label="Hourtype" :options="$optionsHourtype"
                    class="lkn-autoupdate" width2="4" />
                <x-laraknife.forms.combobox position="last" name="hourstate" label="Hourstate" :options="$optionsHourstate"
                    class="lkn-autoupdate" width2="4" />
                <x-laraknife.forms.string type="date" position="first" name="from" label="From"
                    value="{{ $context->valueOf('from') }}" width2="4" />
                <x-laraknife.forms.string type="date" position="last" name="until" label="To"
                    value="{{ $context->valueOf('until') }}" width2="4" />
                <x-laraknife.forms.string position="first" name="text" label="Text"
                    value="{{ $context->valueOf('description') }}" width2="4" />
                <x-laraknife.forms.combobox position="last" name="owner" label="Owner" :options="$optionsOwner"
                    class="lkn-autoupdate" width2="4" />
            </x-laraknife.panels.filter>
            <x-laraknife.panels.simple>
                <x-laraknife.forms.combobox position="first" name="hourstate2" label="Hourstate" :options="$optionsHourstate2"
                    width2="4" />
                <x-laraknife.buttons.button-position position="last" name="btnSet" label="Set feature to all visible"
                    width2="4" class="lkn-button-primary" />
                <x-laraknife.buttons.button-position position="alone" name="btnExport" label="Export"
                    width2="4" class="lkn-button-primary" />
            </x-laraknife.panels.simple>
            <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
                <thead>
                    <tr>
                        <th></th>
                        <th sortId="time">{{ __('Time') }}</th>
                        <th sortId="duration" class="lkn-align-right">{{ __('Duration') }}</th>
                        <th sortId="factor" class="lkn-align-right">{{ __('Factor') }}</th>
                        <th sortId="interested" class="lkn-align-right">{{ __('Interested') }}</th>
                        <th sortId="hourtype">{{ __('Hourtype') }}</th>
                        <th sortId="hourstate">{{ __('Hourstate') }}</th>
                        <th sortId="description">{{ __('Description') }}</th>
                        <th sortId="owner">{{ __('Owner') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $hour)
                        <tr>
                            <td><x-laraknife.icons.change-record module="hour" no="{{ $hour->id }}" /></td>
                            <td>{{ $context->asDateString($hour->time) }}</td>
                            <td class="lkn-align-right">{{ $context->asDuration($hour->duration) }}</td>
                            <td class="lkn-align-right">{{ $hour->factor }}</td>
                            <td class="lkn-align-right">{{ $hour->interested }}</td>
                            <td> {{ __($hour->hourtype) }}</td>
                            <td> {{ __($hour->hourstate) }}</td>
                            <td>{{ $hour->description }}</td>
                            <td>{{ $hour->owner }}</td>
                            <td><x-laraknife.icons.delete-record module="hour" no="{{ $hour->id }}" /></td>
                        </tr>
                    @endforeach
                    <tr>
                        <td></td>
                        <td><span class="lkn-text-tagged">{{ __('Sum') }}</span></td>
                        <td class="lkn-align-right"><span class="lkn-text-tagged">{{ $context->valueOf('sum') }}</span>
                        </td>
                    </tr>
                </tbody>
            </x-laraknife.panels.sortable-table>
        </x-laraknife.panels.index>
    </form>
@endsection
