@props(['messages'])

@if ($messages)
    <ul {{ $attributes->merge(['style' => 'color: #dc2626; font-size: 12px; margin-top: 4px; font-weight: bold; list-style-type: none;']) }}>
        @foreach ((array) $messages as $message)
            <li>{{ $message }}</li>
        @endforeach
    </ul>
@endif
