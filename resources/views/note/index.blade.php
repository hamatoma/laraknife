@extends('layouts.backend')

@section('content')
<form id="note-index" action="/note-index" method="POST">
    @csrf
    <x-laraknife.panels.index title="{{ __('Notes') }}">
      <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
      <x-laraknife.forms.combobox position="first" name="category" label="Category" :options="$optionsCategory" class="lkn-autoupdate" width2="4" />
      <x-laraknife.forms.combobox position="last" name="notestatus" label="Status" :options="$optionsNotestatus" class="lkn-autoupdate" width2="4" />
      <x-laraknife.forms.string position="first" name="title" label="Title" value="{{ $context->valueOf('title') }}" width2="4" />
      <x-laraknife.forms.string position="last" name="body" label="Body" value="{{ $context->valueOf('body') }}" width2="4" />
      <x-laraknife.forms.string position="first" name="text" label="Text" value="{{ $context->valueOf('text') }}" width2="4" />
      <x-laraknife.forms.combobox position="last" name="user" label="User" :options="$optionsUser" class="lkn-autoupdate" width2="4" />
      </x-laraknife.panels.filter>
      <x-laraknife.panels.index-button buttonType="new"/>
      <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
        <thead>
          <tr>
            <th></th>
            <th sortId="title">{{__('Title')}}</th>
            <th sortId="body">{{__('Body')}}</th>
            <th sortId="category_scope">{{__('Category')}}</th>
            <th sortId="notestatus_scope">{{__('Status')}}</th>
            <th sortId="user_id">{{__('User')}}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
@foreach ($records as $note)
        <tr>
            <td><x-laraknife.icons.change-record module="note" no="{{ $note->id }}" /></td>
              <td>{{$note->title}}</td>
              <td>{{$note->body_short}}</td>
              <td>{{ __($note->category_scope) }}</td>
              <td>{{ __($note->notestatus_scope) }}</td>
              <td>{{$note->user_id}}</td>
            <td><x-laraknife.icons.delete-record module="note" no="{{ $note->id }}" /></td>
        </tr>
@endforeach
      </tbody>
    </x-laraknife.panels.sortable-table>
  </x-laraknife.panels.index>
</form>
@endsection
