@extends('layouts.backend')

@section('content')
    <form id="export-index" action="/export-index" method="POST">
        @csrf
        <x-laraknife.panels.index title="{{ __('Exported Files') }}">
            <x-laraknife.panels.filter legend="">
                <x-laraknife.forms.string position="alone" name="patterns" label="Patterns"
                    value="{{ $context->valueOf('patterns') }}" width2="4" />
            </x-laraknife.panels.filter>
        <x-laraknife.buttons.button-position position="alone" name="btnImport" label="Import" width1="8" width2="4" />
            <x-laraknife.panels.simple>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>{{ __('Name') }}</th>
                            <th>{{ __('Date') }}</th>
                            <th class="lkn-align-right">{{ __('Size') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($records as $file)
                            <tr>
                                <td>{!! $context->buildFileLink($file->node, "export") !!}</td>
                                <td>{{ $file->date->format('Y.m.d H:i') }}</td>
                                <td class="lkn-align-right">{{ sprintf('%.6f MByte', $file->sizeMByte) }}</td>
                                <td><x-laraknife.icons.delete-file url="/export-rm" file="{{ $file->urlEncoded() }}" /></td>
                                </tr>
                        @endforeach
                    </tbody>
                </table>
            </x-laraknife.panels.simple>
        </x-laraknife.panels.index>
    </form>
@endsection
