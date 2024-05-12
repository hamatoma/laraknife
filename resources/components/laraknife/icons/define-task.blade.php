@props(['module' => '', 'method' => 'edit', 'no' => '', 'parameter' => ''])
@if ($no === '')
<a href="/{{$module}}-{{$method}}{{$parameter}}"><x-laraknife.icons.task class="text-primary" /></a>
@else
<a href="/{{$module}}-{{$method}}/{{$no}}{{$parameter}}"><x-laraknife.icons.task class="text-primary" /></a>
@endif