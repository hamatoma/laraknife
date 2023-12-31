@extends('layouts.backend')

@section('content')
<form id="role-show" action="/role-show/{{ $role->id }}/{{ $mode }}" method="POST">
    @csrf
    @if ($mode === 'delete')
    @method('DELETE')
    @endif
    <x-laraknife.create-panel title="{{ __($mode === 'delete' ? 'Deletion of a Role' : 'A Role') }}">
        <x-laraknife.text position="first" name="id" label="Id" value="{{ $role->id }}" width2="4"
            attribute="readonly" />
        <x-laraknife.text position="alone" name="name" label="Name" value="{{ $role->name }}" width2="4"
            attribute="readonly" />
        <x-laraknife.text position="alone" name="priority" label="Priority" value="{{ $role->priority }}" width2="4"
            attribute="readonly" />
    </x-laraknife.create-panel>
</form>
@endsection
