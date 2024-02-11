@extends('layouts.backend')

@section('content')
    <form id="menu-menu" action="/menu-menu" method="POST">
        @csrf
        <x-laraknife.panels.menu title="{{ __('Overview') }}">
            @for ($row = 0; $row < $rows; $row++)
                @php($menu = $records[$row * $cols + $col])
                <x-laraknife.forms.set-position "first" />
                @for ($col = 0; $col < $cols && $row * $cols + $col < count($records); $cols++)
                    <x-laraknife.icons.icon-as-link icon="{{ $menu->icon }}" label="{{ $menu->label }}"
                        link="{{ $menu->link }}" />
                @endfor()
                <x-laraknife.forms.set-position "last" />
            @endfor()
        </x-laraknife.panels.menu>
    </form>
@endsection
