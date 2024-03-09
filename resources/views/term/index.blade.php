@extends('layouts.backend')

@section('content')
<form id="term-index" action="/term-index" method="POST">
    @csrf
    <x-laraknife.panels.index title="{{ __('Terms') }}">
      <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
      <x-laraknife.forms.combobox position="first" name="visibility" label="Visibility" :options="$optionsVisibility" class="lkn-autoupdate" width2="4" />
      <x-laraknife.forms.combobox position="last" name="owner" label="Owner" :options="$optionsOwner" class="lkn-autoupdate" width2="4" />
      <x-laraknife.forms.string type="date" position="first" name="from" label="From" value="{{ $context->valueOf('from') }}" width2="4" />
      <x-laraknife.forms.string type="date" position="last" name="to" label="To" value="{{ $context->valueOf('to') }}" width2="4" />
      <x-laraknife.forms.string position="alone" name="text" label="Text" value="{{ $context->valueOf('text') }}" width2="4" />
      </x-laraknife.panels.filter>
      <x-laraknife.panels.index-button buttonType="new"/>
      <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
        <thead>
          <tr>
            <th></th>
            <th sortId="term">{{__('Term')}}</th>
            <th sortId="duration">{{__('Duration')}}</th>
            <th sortId="title">{{__('Title')}}</th>
            <th sortId="description">{{__('Description')}}</th>
            <th sortId="owner_id">{{__('Owner')}}</th>
            <th sortId="visibility_scope">{{__('Visibility')}}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
@foreach ($records as $term)
        <tr>
            <td><x-laraknife.icons.change-record module="term" no="{{ $term->id }}" /></td>
              <td>{{$context->asDateTimeString($term->term)}}</td>
              <td>{{$context->asDuration($term->duration)}}</td>
              <td>{{$term->title}}</td>
              <td>{{$term->description}}</td>
              <td>{{$term->owner}}</td>
              <td> {{ __($term->visibility_scope) }}</td>
            <td><x-laraknife.icons.delete-record module="term" no="{{ $term->id }}" /></td>
        </tr>
@endforeach
      </tbody>
    </x-laraknife.panels.sortable-table>
  </x-laraknife.panels.index>
</form>
@endsection
