@props(['messages' => []])

@if ($messages)
    <div class="space-y-0.5">
        @foreach ((array) $messages as $message)
            <p class="input-error-msg">{{ $message }}</p>
        @endforeach
    </div>
@endif
