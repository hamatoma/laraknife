@extends('layouts.backend')

@section('content')
<form id="mandator-index" action="/mandator-index" method="POST">
    @csrf
    <x-laraknife.panels.index title="{{ __('Mandators') }}">
      <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
      <x-laraknife.forms.string position="alone" name="text" label="Text" value="{{ $context->valueOf('text') }}" width2="4" rows="2" />
      </x-laraknife.panels.filter>
      <x-laraknife.panels.index-button buttonType="new"/>
      <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
        <thead>
          <tr>
            <th></th>
            <th sortId="name">{{__('Name')}}</th>
            <th sortId="info">{{__('Info')}}</th>
            <th sortId="group">{{__('Group')}}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
@foreach ($records as $mandator)
        <tr>
            <td><x-laraknife.icons.change-record module="mandator" no="{{ $mandator->id }}" /></td>
              <td>{{$mandator->name}}</td>
              <td>{{$mandator->info}}</td>
              <td>{{$mandator->group}}</td>
            <td><x-laraknife.icons.delete-record module="mandator" no="{{ $mandator->id }}" /></td>
        </tr>
@endforeach
      </tbody>
    </x-laraknife.panels.sortable-table>
  </x-laraknife.panels.index>
</form>
@endsection
