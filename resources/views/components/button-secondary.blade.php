<!-- Bouton Secondaire (Outline) -->
@if($href ?? false)
    <a
        href="{{ $href }}"
        class="inline-flex items-center justify-center px-6 py-3 rounded font-sans font-600
               border-2 border-cardinal text-cardinal hover:bg-cream transition-colors {{ $class ?? '' }}"
    >
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type ?? 'button' }}"
        class="inline-flex items-center justify-center px-6 py-3 rounded font-sans font-600
               border-2 border-cardinal text-cardinal hover:bg-cream transition-colors {{ $class ?? '' }}"
    >
        {{ $slot }}
    </button>
@endif
