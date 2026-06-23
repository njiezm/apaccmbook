<!-- Grid de Cartes E-Books -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 {{ $class ?? '' }}">
    @forelse($ebooks as $ebook)
        <x-ebook-card :ebook="$ebook" />
    @empty
        <div class="col-span-full text-center py-12">
            <p class="text-text-secondary">Aucun eBook trouvé.</p>
        </div>
    @endforelse
</div>
