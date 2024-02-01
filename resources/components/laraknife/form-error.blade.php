@props(['error' => ''])
@php
if (count($errors) == 1){
    $error = $errors->all()[0];
}
$hasError = $error !== '' || count($errors) > 0;
@endphp
@if ($hasError)
<div class = "text-danger">
    @if ($error !== '')
    {{ $error  }}
    @else
        @php
        $listX = $errors->all();
        @endphp
        <ul>
            @foreach ($listX as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
        @endif
</div>
@endif
