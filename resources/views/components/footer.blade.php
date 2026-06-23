<!-- Footer -->
<footer class="bg-bg-footer border-t border-border-light mt-12">
    <x-container class="py-12">
        <!-- Content -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <!-- Brand -->
            <div>
                <h3 class="font-serif font-bold text-lg text-text-primary mb-4">APACC-M</h3>
                <p class="text-text-secondary text-sm leading-relaxed">
                    Plateforme de lecture et diffusion d'e-books de qualité, inspirée par l'esprit éditorial de Narthex.
                </p>
            </div>

            <!-- Links -->
            <div>
                <h4 class="font-sans font-600 text-text-primary mb-4 uppercase text-sm tracking-tracked">Navigation</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/ebooks" class="text-text-secondary hover:text-cardinal transition-colors">Catalogue</a></li>
                    <li><a href="/about" class="text-text-secondary hover:text-cardinal transition-colors">À Propos</a></li>
                    <li><a href="/contact" class="text-text-secondary hover:text-cardinal transition-colors">Contact</a></li>
                </ul>
            </div>

            <!-- Legal -->
            <div>
                <h4 class="font-sans font-600 text-text-primary mb-4 uppercase text-sm tracking-tracked">Légal</h4>
                <ul class="space-y-2 text-sm">
                    <li><a href="/terms" class="text-text-secondary hover:text-cardinal transition-colors">CGU</a></li>
                    <li><a href="/privacy" class="text-text-secondary hover:text-cardinal transition-colors">Confidentialité</a></li>
                    <li><a href="/legal" class="text-text-secondary hover:text-cardinal transition-colors">Mentions légales</a></li>
                </ul>
            </div>
        </div>

        <!-- Narthex Line -->
        <x-narthex-line />

        <!-- Copyright -->
        <div class="mt-8 text-center text-sm text-text-tertiary">
            <p>&copy; {{ date('Y') }} APACC-M. Tous droits réservés.</p>
        </div>
    </x-container>
</footer>
