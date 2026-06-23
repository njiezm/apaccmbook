<!-- Carte E-Book Individuelle -->
<x-arch-card>
    <!-- Cover Image -->
    <div class="relative overflow-hidden bg-gray-100 aspect-[3/4]">
        <img
            src="{{ asset('storage/' . ($ebook->cover_image ?? 'ebooks/default-cover.jpg')) }}"
            alt="{{ $ebook->title }}"
            class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
        >

        @if($ebook->status === 'draft')
            <div class="absolute top-4 right-4 bg-cardinal text-white px-3 py-1 rounded font-sans text-sm font-600">
                Brouillon
            </div>
        @elseif($ebook->created_at > now()->subDays(7))
            <div class="absolute top-4 right-4 bg-cardinal text-white px-3 py-1 rounded font-sans text-sm font-600">
                Nouveau
            </div>
        @endif
    </div>

    <!-- Content -->
    <div class="p-6">
        <h3 class="font-serif font-bold text-lg text-text-primary line-clamp-2 mb-2">
            {{ $ebook->title }}
        </h3>

        <p class="text-sm text-text-secondary mb-4">
            par <span class="font-600">{{ $ebook->author->name ?? 'Auteur inconnu' }}</span>
        </p>

        <!-- Rating -->
        @if($ebook->avg_rating ?? false)
            <div class="flex items-center gap-2 mb-4">
                <div class="flex text-cardinal">
                    @for($i = 0; $i < 5; $i++)
                        @if($i < floor($ebook->avg_rating))
                            <span>⭐</span>
                        @elseif($i < $ebook->avg_rating)
                            <span>✨</span>
                        @else
                            <span>☆</span>
                        @endif
                    @endfor
                </div>
                <span class="text-xs text-text-tertiary">({{ $ebook->reviews_count ?? 0 }})</span>
            </div>
        @endif

        <!-- Price -->
        <div class="mb-6 text-lg font-bold text-cardinal">
            {{ number_format($ebook->price ?? 0, 2, ',', ' ') }} €
        </div>

        <!-- Actions -->
        <div class="flex gap-3">
            <x-button-secondary href="/ebooks/{{ $ebook->slug ?? $ebook->id }}" class="flex-1">
                Détails
            </x-button-secondary>

            @auth
                <form action="{{ route('purchases.store') }}" method="POST" class="flex-1">
                    @csrf
                    <input type="hidden" name="ebook_id" value="{{ $ebook->id }}">
                    <button
                        type="submit"
                        class="w-full bg-cardinal text-white px-4 py-2 rounded hover:bg-cardinal-hover transition-colors font-sans font-600"
                    >
                        Acheter
                    </button>
                </form>
            @else
                <x-button href="/login" class="flex-1 text-sm">Acheter</x-button>
            @endauth
        </div>
    </div>
</x-arch-card>
