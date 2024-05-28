@props([
    'info' => '',
    'fieldset' => 'true',
    'button1Name' => 'btnStore',
    'button1Label' => 'Store',
    'button1Width1' => 2,
    'button1Width2' => 4,
    'button2Name' => 'btnCancel',
    'button2Label' => 'Cancel',
    'button2Width1' => 2,
    'button2Width2' => 4,
])
<ul class="nav nav-tabs lkn-nav-tab">
    @foreach ($info->items as $item)
        <li class="nav-item{!! $info->activeClass($item) !!}">
            @if ($item->disabled)
                <a class="nav-link disabled"" href="{{ $item->link }}" tabindex="-1"
                    aria-disabled="true">{{ __($item->text) }}</a>
            @else()
                <a class="nav-link{!! $info->activeClass($item) !!}"{!! $info->active($item) !!}{!! $info->ariaCurrent($item) !!}
                    href="{{ $item->link }}">{{ __($item->text) }}</a>
            @endif()
        </li>
    @endforeach
</ul>
<div class="@if ($fieldset === 'true') lkn-panel @endif lkn-nav-tab-panel">
    {{ $slot }}
    @if ($button1Name !== '' || $button2Name !== '')
        <x-laraknife.layout.row-empty />
        <div class="row">
    @endif
    @if ($button1Name !== '')
        <x-laraknife.buttons.button-position width1="{{ $button1Width1 }}" width2="{{ $button1Width2 }}"
            name="{{ $button1Name }}" label="{{ __($button1Label) }}" />
    @endif
    @if ($button2Name !== '')
        <x-laraknife.buttons.button-position width1="{{ $button2Width1 }}" width2="{{ $button2Width2 }}"
            name="{{ $button2Name }}" label="{{ __($button2Label) }}" />
    @endif
    @if ($button1Name !== '' || $button2Name !== '')
        </div>
    @endif
</div>
