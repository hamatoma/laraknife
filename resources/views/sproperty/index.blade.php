@extends('layouts.backend')

@section('content')
    <form id="sproperty-index" action="/sproperty-index" method="POST">
        @csrf
        <div id="main-content" class="container mt-5">
            <x-laraknife.main-header title="{{ __('Scoped Properties') }}" />

            <!-- panel.filter -->
            <fieldset class="lkn-filter">
                <legend>{{ $legend }}</legend>
                <x-laraknife.combobox position="first" name="scope" label="Scope" options="{!! $options !!}"
                    width2="4" class="lkn-autoupdate" />
                <x-laraknife.text position="last" name="text" label="Text" value="{{ $fields['text'] }}"
                    width2="4" />
                <div class="row">
                    <x-laraknife.btn-search width2="10" />
                </div>
            </fieldset>
            <div class="lkn-behind-filter">
                <div class="row">
                    <x-laraknife.btn-new width1="0" width2="12" />
                </div>
            </div>
            <div class="lkn-form-table">
                <x-laraknife.hidden-button />
                <x-laraknife.sortable-table value="{{ $fields['_sortParams'] }}" />
                {!! $pagination->pagingHtml() !!}
                <table class="table table-striped table-db lkn-sortable">
                    <thead>
                        <tr>
                            <th></th>
                            <th sortId="id">{{ __('Id') }}</th>
                            <th sortId="scope">{{ __('Scope') }}</th>
                            <th sortId="name">{{ __('Name') }}</th>
                            <th sortId="order">{{ __('Order') }}</th>
                            <th sortId="shortname">{{ __('Shortname') }}</th>
                            <th sortId="value">{{ __('Value') }}</th>
                            <th></th>
                        </tr>
                    </thead>
                    @foreach ($records as $sproperty)
                        <tr>
                            <td><x-laraknife.change-record module="sproperty" key="{{ $sproperty->id }}" /></td>
                            <td>{{ $sproperty->id }}</td>
                            <td>{{ $sproperty->scope }}</td>
                            <td>{{ $sproperty->name }}</td>
                            <td>{{ $sproperty->order }}</td>
                            <td>{{ $sproperty->shortname }}</td>
                            <td>{{ $sproperty->value }}</td>
                            <td><x-laraknife.delete-record module="sproperty" key="{{ $sproperty->id }}" /></td>
                        </tr>
                    @endforeach
                    <tbody>
                    </tbody>
                </table>

            </div>
        </div>
    </form>
@endsection
