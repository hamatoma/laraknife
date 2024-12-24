@extends('layouts.backend')

@section('content')
<form id="change-index" action="/change-index" method="POST">
    @csrf
    <x-laraknife.panels.index title="{{ __('Changes') }}">
      <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
      <x-laraknife.forms.combobox position="first" name="changetype" label="Changetype" :options="$optionsChangetype" class="lkn-autoupdate" width2="4" />
      <x-laraknife.forms.combobox position="last" name="user" label="User" :options="$optionsUser" class="lkn-autoupdate" width2="4" />
      <x-laraknife.forms.combobox position="first" name="module" label="Module" :options="$optionsModule" class="lkn-autoupdate" width2="4" />
      <x-laraknife.forms.string position="last" name="reference" label="Reference" value="{{ $context->valueOf('reference_id') }}" width2="4" />
      <x-laraknife.forms.string position="alone" name="text" label="Text" value="{{ $context->valueOf('text') }}" width2="10" />
      </x-laraknife.panels.filter>
      <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
        <thead>
          <tr>
            <th></th>
            <th sortId="created_at">{{__('CreatedAt')}}</th>
            <th sortId="changetype">{{__('Changetype')}}</th>
            <th sortId="module">{{__('Module')}}</th>
            <th sortId="reference_id">{{__('Reference')}}</th>
            <th sortId="description">{{__('Description')}}</th>
            <th sortId="link">{{__('Link')}}</th>
            <th sortId="user">{{__('User')}}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
@foreach ($records as $change)
        <tr>
            <td><x-laraknife.icons.change-record module="change" no="{{ $change->id }}" /></td>
              <td>{{$change->created_at}}</td>
              <td> {{ __($change->changetype) }}</td>
              <td>{{$change->module}}</td>
              <td>{{$change->reference_id}}</td>
              <td>{{$change->description}}</td>
              @if($change->link == null || $change->link == '')
              <td></td>
              @else
              <td><a href="{{$change->link}}">{!! __('current')!!}</a></td>
              @endif
              <td>{{$change->user}}</td>
            <td><x-laraknife.icons.delete-record module="change" no="{{ $change->id }}" /></td>
        </tr>
@endforeach
      </tbody>
    </x-laraknife.panels.sortable-table>
  </x-laraknife.panels.index>
</form>
@endsection
