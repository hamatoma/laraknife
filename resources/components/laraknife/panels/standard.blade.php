@props([
    'title' => 'Edit',
    'error' => '',
    'class' => '',
    'fieldset' => 'true',
    'button1Name' => 'btnStore',
    'button1Label' => 'Store',
    'button1Width1' => '2',
    'button1Width2' => '4',
    'button2Name' => 'btnCancel',
    'button2Label' => 'Cancel',
    'button2Width1' => '2',
    'button2Width2' => '4',
])
<div id="main-content" class="container mt-5">
    <x-laraknife.panels.common title="{{ $title }}" />
    @if ($fieldset === 'true')
        <fieldset class="lkn-standard-panel {{ $class }}">
            {{ $slot }}
            @if ($button1Name !== '')
                <x-laraknife.buttons.button width1="{{ $button1Width1 }}" width2="{{ $button1Width2 }}"
                    name="{{ $button1Name }}" label="{{ __($button1Label) }}" />
            @endif
            @if ($button2Name !== '')
                <x-laraknife.buttons.button width1="{{ $button2Width1 }}" width2="{{ $button2Width2 }}"
                    name="{{ $button2Name }}" label="{{ __($button2Label) }}" />
            @endif
        </fieldset>
    @else
        {{ $slot }}
    @endif
</div>
