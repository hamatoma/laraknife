@extends('layouts.backend')

@section('content')
<form id="person-index" action="/person-index" method="POST">
    @csrf
    <x-laraknife.panels.index title="{{ __('Persons') }}">
      <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
        <x-laraknife.forms.combobox position="first" name="persongroup" label="Persongroup" :options="$optionsPersongroup" class="lkn-autoupdate" width2="4" />
        <x-laraknife.forms.combobox position="last" name="gender" label="Gender" :options="$optionsGender" class="lkn-autoupdate" width2="4" />
        <x-laraknife.forms.string position="alone" name="text" label="Text" value="{{ $context->valueOf('text') }}" width2="10" />
      </x-laraknife.panels.filter>
      <x-laraknife.panels.index-button buttonType="new"/>
      <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
        <thead>
          <tr>
            <th></th>
            <th sortId="nickname">{{__('Nickname')}}</th>
            <th sortId="lastname">{{__('Lastname')}}</th>
            <th sortId="firstname">{{__('Firstname')}}</th>
            <th sortId="persongroup">{{__('Persongroup')}}</th>
            <th sortId="info">{{__('Info')}}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
@foreach ($records as $person)
        <tr>
            <td><x-laraknife.icons.change-record module="person" no="{{ $person->id }}" /></td>
              <td>{{$person->nickname}}</td>
              <td>{{$person->lastname}}</td>
              <td>{{$person->firstname}}</td>
              <td> {{ __($person->persongroup) }}</td>
              <td>{{$person->info}}</td>
            <td><x-laraknife.icons.delete-record module="person" no="{{ $person->id }}" /></td>
        </tr>
@endforeach
      </tbody>
    </x-laraknife.panels.sortable-table>
  </x-laraknife.panels.index>
</form>
@endsection
