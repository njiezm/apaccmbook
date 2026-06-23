<!-- Bouton Primaire (Cardinal) -->
@if($href ?? false)
    <a
        href="{{ $href }}"
        class="inline-flex items-center justify-center px-6 py-3 rounded font-sans font-600 transition-all
               bg-cardinal text-white hover:bg-cardinal-hover active:scale-95
               shadow-soft disabled:opacity-50 disabled:cursor-not-allowed {{ $class ?? '' }}"
    >
        {{ $slot }}
    </a>
@else
    <button
        type="{{ $type ?? 'button' }}"
        class="inline-flex items-center justify-center px-6 py-3 rounded font-sans font-600 transition-all
               bg-cardinal text-white hover:bg-cardinal-hover active:scale-95
               shadow-soft disabled:opacity-50 disabled:cursor-not-allowed {{ $class ?? '' }}"
    >
        {{ $slot }}
    </button>
@endif
