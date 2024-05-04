@props(['info' => '', 'fieldset' => 'true'])
<ul class="nav nav-tabs lkn-nav-tab">
    @foreach ($info->items as $item)
        <li class="nav-item{!! $info->activeClass($item) !!}">
        @if ($item->disabled)
            <a class="nav-link disabled""
                href="{{ $item->link }}" tabindex="-1" aria-disabled="true">{{ __($item->text) }}</a>
        @else()
            <a class="nav-link{!! $info->activeClass($item) !!}"{!! $info->active($item) !!}{!! $info->ariaCurrent($item) !!}
                href="{{ $item->link }}">{{ __($item->text) }}</a>
    @endif()
    </li>
    @endforeach
</ul>
@if ($fieldset === 'true')
<div class="lkn-fieldset lkn-nav-tab-panel">
    {{ $slot }}
</div>
@else
<div class="lkn-nav-tab-panel">
    {{ $slot }}
</div>
@endif
