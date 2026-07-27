@props(['type' => 'submit'])

<button {{ $attributes->merge(['type' => $type, 'class' => 'btn-danger']) }}>
    {{ $slot }}
</button>
