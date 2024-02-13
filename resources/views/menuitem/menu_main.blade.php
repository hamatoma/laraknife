@extends('layouts.backend')

@section('content')
    <form id="menuitem-menu_main" action="/menuitem.menu_main" method="POST">
        @csrf
        <x-laraknife.panels.menu title="{{ __('Overview') }}">
            @for ($row = 0; $row < $rows; $row++)
                 <x-laraknife.forms.set-position position="first" />
                 @for ($col = 0; $col < $cols && $row * $cols + $col < count($records); $col++)
                     @php($menuitem = $records[$row * $cols + $col])
                     <x-laraknife.icons.menuitem icon="{{ $menuitem->icon }}" label="{{ $menuitem->label }}"
                         link="{{ $menuitem->link }}" width1="0" width2="{{intval(12/$cols)}}"/>
                 @endfor()
                 <x-laraknife.forms.set-position position="last" />
             @endfor()
          </x-laraknife.panels.menu>
     </form>
@endsection
