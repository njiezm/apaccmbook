<!-- Header Titre de Page -->
<div class="bg-white border-b border-border-light">
    <x-container class="py-8 lg:py-12">
        <h1 class="text-3xl lg:text-5xl font-serif font-bold text-text-primary mb-4">
            {{ $title }}
        </h1>
        @if($subtitle ?? false)
            <p class="text-lg text-text-secondary">{{ $subtitle }}</p>
        @endif
    </x-container>
    <x-narthex-line :double="true" />
</div>
