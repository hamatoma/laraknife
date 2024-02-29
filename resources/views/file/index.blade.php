@extends('layouts.backend')

@section('content')
    <form id="file-index" action="/file-index" method="POST">
        @csrf
        <x-laraknife.panels.index title="{{ __('Files') }}">
            <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
                <x-laraknife.forms.combobox position="first" name="filegroup" label="Filegroup" :options="$optionsFilegroup"
                    class="lkn-autoupdate" width2="4" />
                <x-laraknife.forms.combobox position="last" name="user" label="User" :options="$optionsUser"
                    class="lkn-autoupdate" width2="4" />
                <x-laraknife.forms.string position="first" name="size" label="Size"
                    value="{{ $context->valueOf('size') }}" placeholder="Examples: '<10M' '>=45k' '=5001'" width2="4" />
                <x-laraknife.forms.string position="last" name="created_at" label="Date"
                    value="{{ $context->valueOf('created_at') }}"
                    placeholder="Examples: '<1.1.2024' '>=20.3.2023' '=4.4.2024" width2="4" />
                      <x-laraknife.forms.string position="first" name="text" label="Text"
                      value="{{ $context->valueOf('text') }}" width2="4" />
                      <x-laraknife.forms.combobox position="last" name="visibility" label="Visibility" :options="$optionsVisibility"
                          class="lkn-autoupdate" width2="4" />
            </x-laraknife.panels.filter>
            <x-laraknife.panels.index-button buttonType="new" />
            <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
                <thead>
                    <tr>
                        <th></th>
                        <th sortId="title">{{ __('Title') }}</th>
                        <th sortId="description">{{ __('Description') }}</th>
                        <th sortId="filename">{{ __('Filename') }}</th>
                        <th sortId="filegroup_scope">{{ __('Filegroup') }}</th>
                        <th sortId="user">{{ __('User') }}</th>
                        <th sortid="size">{{ __('Size (MByte)') }}</th>
                        <th sortid="visibility">{{ __('Visibility') }}</th>
                        <th sortid="created_at">{{ __('Date') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($records as $file)
                        <tr>
                            <td><x-laraknife.icons.change-record module="file" no="{{ $file->id }}" /></td>
                            <td>{{ $file->title }}</td>
                            <td>{{ $file->description }}</td>
                            <td>{!! $context->callback('buildAnchor', $file) !!}</td>
                            <td> {{ __($file->filegroup_scope) }}</td>
                            <td>{{ $file->user_id }}</td>
                            <td>{{ $file->size }}</td>
                            <td>{{ $file->created_at }}</td>
                            <td><x-laraknife.icons.delete-record module="file" no="{{ $file->id }}" /></td>
                        </tr>
                    @endforeach
                </tbody>
            </x-laraknife.panels.sortable-table>
        </x-laraknife.panels.index>
    </form>
@endsection
