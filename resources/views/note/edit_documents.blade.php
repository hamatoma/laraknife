@extends('layouts.backend')

@section('content')
    <form id="note-edit-documents" action="/note-edit_documents/{{ $context->model->id }}" method="POST">
        @csrf
        <x-laraknife.panels.noform title="{{ __('Change of a Note') }}">
            <x-laraknife.layout.nav-tabs :info="$navTabsInfo">
                <table class="table table-striped">
                    <tr>
                        <th>{{__('Filename')}}</th>
                        <th>{{__('Size')}}</th>
                        <th>{{__('Date')}}</th>
                    </tr>
                    @foreach($records as $record)
                    <tr>
                        <td>{{$record['name']}}</th>
                        <td>{{$record['size']}}</th>
                        <td>{{$record['date']}}</th>
                    </tr>
                    @endforeach
                </table>
            </x-laraknife.layout.nav-tabs>
        </x-laraknife.panels.noform>
    </form>
@endsection
