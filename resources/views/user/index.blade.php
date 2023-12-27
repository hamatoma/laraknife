@extends('layouts.backend')

@section('content')
<form id="user-index" action="/user-index" method="POST">
    @csrf
    @php $t = __('Users'); $l = $pagination->legendText(); @endphp
    <x-laraknife.index-panel  title="{{$t}}">
        <x-laraknife.filter-panel legend="{{ $l }}">
            <x-laraknife.combobox position="first" name="role" label="Role" :options="$roleOptions" class="lkn-autoupdate" width2="4" />
            <x-laraknife.text position="last" name="text" label="Text" value="{{ $fields['text'] }}" width2="4" />
        </x-laraknife.filter-panel>
        <x-laraknife.index-button-panel buttonType="new" />
        <x-laraknife.sortable-table-panel :fields="$fields" :pagination="$pagination">
            <thead>
                <tr>
                    <th></th>
                    <th sortId="id">{{ __('Id') }}</th>
                    <th sortId="name">{{ __('Name') }}</th>
                    <th sortId="email">{{ __('Email') }}</th>
                    <th sortId="role">{{ __('Role') }}</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($records as $user)
                    <tr>
                        <td><a href="/user-edit/{{ $user->id }}">{{ __('Change') }}</a></td>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td><a href="/user-show/{{ $user->id }}/delete">{{ __('Delete') }}</a></td>
                    </tr>
                @endforeach
            </tbody>
        </x-laraknife.sortable-table-panel>
    </x-laraknife.index-panel>
</form>
@endsection
