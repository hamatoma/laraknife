@extends('layouts.backend')

@section('content')
<form id="address-index" action="/address-index" method="POST">
    @csrf
    <x-laraknife.panels.index title="{{ __('Addresses') }}">
      <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
      <x-laraknife.forms.combobox position="first" name="addresstype" label="Addresstype" :options="$optionsAddresstype" class="lkn-autoupdate" width2="4" />
      <x-laraknife.forms.combobox position="last" name="owner" label="Owner" :options="$optionsOwner" class="lkn-autoupdate" width2="4" />
      <x-laraknife.forms.string position="alone" name="text" label="Text" value="{{ $context->valueOf('text') }}" width2="10" />
      </x-laraknife.panels.filter>
      <x-laraknife.panels.index-button buttonType="new"/>
      <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
        <thead>
          <tr>
            <th></th>
            <th sortId="addresstype">{{__('Addresstype')}}</th>
            <th sortId="name">{{__('Name')}}</th>
            <th sortId="owner">{{__('Owner')}}</th>
            <th>{{__('Info')}}</th>
            <th sortId="priority">{{__('Prio')}}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
@foreach ($records as $address)
        <tr>
            <td><x-laraknife.icons.change-record module="address" no="{{ $address->id }}" /></td>
              <td> {{ __($address->addresstype) }}</td>
              <td>{{$address->name}}</td>
              <td>{{$address->owner}}</td>
              <td>{{$address->info}}</td>
              <td>{{$address->priority}}</td>
              <td><x-laraknife.icons.delete-record module="address" no="{{ $address->id }}" /></td>
        </tr>
@endforeach
      </tbody>
    </x-laraknife.panels.sortable-table>
  </x-laraknife.panels.index>
</form>
@endsection
