@extends('layouts.backend')

@section('content')
<form id="user-show" action="/user-show/{{ $user->id }}/delete" method="POST">
    @csrf
    @if ($mode === 'delete')
    @method('DELETE')
    @endif
    @php $title = __($mode === 'delete' ? 'Deletion of an User' : 'Displaying an User'); @endphp
    <x-laraknife.show-panel title="{{ $title }}">
        <x-laraknife.text position="first" name="id" label="Id" width2="4" value="{{$user->id}}" attribute="readonly" />
        <x-laraknife.combobox position="last" name="role_id" label="Role" :options="$roleOptions" width2="4" attribute="readonly" />
        <x-laraknife.text position="first" name="name" label="Name" value="{{$user->name}}" width2="4" attribute="readonly" />
        <x-laraknife.text position="last" name="email" label="Email" value="{{$user->email}}" width2="4" attribute="readonly" />
    </x-laraknife.show-panel>
</form>
@endsection
