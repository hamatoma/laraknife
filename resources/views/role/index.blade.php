@extends('layouts.backend')

@section('content')
<form id="role-index" action="/role-index" method="POST">
    @csrf
    <x-laraknife.index-panel title="{{ __('Roles') }}">
        <x-laraknife.filter-panel legend="{{ $pagination->legendText() }}">
          <x-laraknife.text position="alone" name="name" label="Name" value="{{$fields['name']}}" width2="4" />
          <div class="row">
            <x-laraknife.btn-search width2="10" />
          </div>
        </x-laraknife.filter-panel>

        <x-laraknife.index-button-panel buttonType="new"/>
        
        <x-laraknife.sortable-table-panel :fields="$fields" :pagination="$pagination">
          <thead>
            <tr>
              <th></th>
              <th sortId="name">{{__('Name')}}</th>
              <th sortId="priority">{{__('Priority')}}</th>
            </tr>
          </thead>
          <tbody>
        @foreach ($records as $role)
          <tr>
            <td><a href="/role-edit/{{$role->id}}">{{ __('Change')}}</a></td>
            <td>{{$role->name}}</td>
            <td>{{$role->priority}}</td>
          </tr>
        @endforeach
        </tbody>
      </x-laraknife.sortable-table-panel>
  </x-laraknife.index-panel>
</form>
@endsection
