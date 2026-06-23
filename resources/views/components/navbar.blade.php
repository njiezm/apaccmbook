<!-- Navigation Principale -->
<nav class="bg-white border-b border-border-light sticky top-0 z-50 shadow-soft">
    <x-container class="flex justify-between items-center py-4">
        <!-- Logo -->
        <a href="/" class="flex items-center gap-2">
            <span class="font-serif font-bold text-2xl text-cardinal">APACC-M</span>
        </a>

        <!-- Links -->
        <div class="hidden md:flex gap-8">
            <a href="/" class="text-text-secondary hover:text-cardinal transition-colors font-sans font-600 text-sm uppercase tracking-tracked">
                Accueil
            </a>
            <a href="/ebooks" class="text-text-secondary hover:text-cardinal transition-colors font-sans font-600 text-sm uppercase tracking-tracked">
                Catalogue
            </a>
            <a href="/about" class="text-text-secondary hover:text-cardinal transition-colors font-sans font-600 text-sm uppercase tracking-tracked">
                À Propos
            </a>
            <a href="/contact" class="text-text-secondary hover:text-cardinal transition-colors font-sans font-600 text-sm uppercase tracking-tracked">
                Contact
            </a>
        </div>

        <!-- Auth -->
        <div class="flex gap-4 items-center">
            @auth
                <div class="hidden sm:flex items-center gap-4">
                    <a href="/dashboard" class="text-text-secondary hover:text-cardinal transition-colors text-sm font-600">
                        {{ Auth::user()->name }}
                    </a>
                    @can('manage-ebooks')
                        <a href="/admin" class="text-text-secondary hover:text-cardinal transition-colors text-sm font-600">
                            Admin
                        </a>
                    @endcan
                </div>
                <form method="POST" action="{{ route('logout') }}" class="inline">
                    @csrf
                    <button class="text-sm text-text-secondary hover:text-cardinal transition-colors font-600">
                        Déconnexion
                    </button>
                </form>
            @else
                <x-button-secondary href="/login" class="text-sm">Connexion</x-button-secondary>
            @endauth
        </div>
    </x-container>

    <!-- Ligne Narthex Double -->
    <x-narthex-line :double="true" />
</nav>
