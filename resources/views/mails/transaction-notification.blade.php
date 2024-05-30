<p>{{ __('Hello') }} {{ $name }}, </p>
<p>{{ __('The responsibility has been transferred to you') }} ({{ $from }}).<br />
    <a href="{{ $link }}">{{ $link }}</a>
</p>
<hr>
<ul>
    <li>{{ __('Mandator') }}:{{ $mandator }} </li>
    <li>{{ __('Account') }}: {{ $account }}</li>
    <li>{{ __('Transaction') }}:{{$transaction}}</li>
</ul>
<p>{{ $body }}
</p>
