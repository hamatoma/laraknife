@extends('layouts.backend')

@section('content')
<form id="page-index" action="/page-index" method="POST">
    @csrf
    <x-laraknife.panels.index title="{{ __('Pages') }}">
      <x-laraknife.panels.filter legend="{{ $pagination->legendText() }}">
      <x-laraknife.forms.combobox position="first" name="pagetype" label="Pagetype" :options="$optionsPagetype" class="lkn-autoupdate" width2="4" />
      <x-laraknife.forms.combobox position="last" name="markup" label="Markup" :options="$optionsMarkup" class="lkn-autoupdate" width2="4" />
      <x-laraknife.forms.combobox position="first" name="language" label="Language" :options="$optionsLanguage" class="lkn-autoupdate" width2="4" />
      <x-laraknife.forms.combobox position="last" name="owner" label="Owner" :options="$optionsOwner" class="lkn-autoupdate" width2="4" />
      <x-laraknife.forms.string position="first" name="title" label="Title" value="{{ $context->valueOf('title') }}" width2="4" />
      <x-laraknife.forms.string position="last" name="contents" label="Contents" value="{{ $context->valueOf('contents') }}" width2="4" />
      </x-laraknife.panels.filter>
      <x-laraknife.panels.index-button buttonType="new"/>
      <x-laraknife.panels.sortable-table :context="$context" :pagination="$pagination">
        <thead>
          <tr>
            <th></th>
            <th sortId="title">{{__('Title')}}</th>
            <th sortId="pagetype">{{__('Pagetype')}}</th>
            <th sortId="markup">{{__('Markup')}}</th>
            <th sortId="language">{{__('Language')}}</th>
            <th sortId="owner">{{__('Owner')}}</th>
            <th></th>
          </tr>
        </thead>
        <tbody>
@foreach ($records as $page)
        <tr>
            <td><x-laraknife.icons.change-record module="page" no="{{ $page->id }}" /></td>
              <td><a href="/page-showpretty/{{ $page->id }}">{{$page->title}}</a></td>
              <td> {{ __($page->pagetype) }}</td>
              <td> {{ __($page->markup) }}</td>
              <td> {{ __($page->language) }}</td>
              <td>{{$page->owner}}</td>
            <td><x-laraknife.icons.delete-record module="page" no="{{ $page->id }}" /></td>
        </tr>
@endforeach
      </tbody>
    </x-laraknife.panels.sortable-table>
  </x-laraknife.panels.index>
</form>
@endsection
