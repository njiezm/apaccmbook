# 🎨 Charte Graphique — Guide Implémentation Technique

## Couleurs & Variables CSS

### Palette Officielle

```css
/* app.css ou tailwind.css */

:root {
  /* Rouges Cardinal */
  --color-cardinal: #b91c1c;      /* Accent primaire, links, barres */
  --color-cardinal-hover: #991b1b; /* Hover buttons, interactive */
  --color-cardinal-light: #dc2626; /* Optional lighter variant */
  
  /* Neutres */
  --color-anthracite: #2d3139;     /* Dark texts, old mobile nav */
  --color-text-primary: #1a1a1a;   /* Body text */
  --color-text-secondary: #444;    /* Nav, subtitles */
  --color-text-tertiary: #555;     /* Secondary content */
  
  /* Fonds */
  --color-bg-cream: #f6f3ef;       /* Main background */
  --color-bg-white: #ffffff;       /* Cards, nav, modals */
  --color-bg-footer: #f9fafb;      /* Footer area */
  
  /* Bordures */
  --color-border-light: #eee;      /* Default borders */
  --color-border-gray: #e5e7eb;    /* Alternative borders */
  
  /* Typo */
  --font-serif: 'Cinzel', serif;        /* Titles, sacred */
  --font-sans: 'Plus Jakarta Sans', sans-serif; /* Body, UI, modern */
}

@layer base {
  body {
    @apply bg-cream text-text-primary font-sans;
  }
  
  h1, h2, h3, h4, h5, h6 {
    @apply font-serif font-bold;
  }
}
```

---

## Tailwind Config Enrichi

```javascript
// tailwind.config.js

export default {
  content: [
    './resources/**/*.{html,blade.php,js}',
  ],
  
  theme: {
    colors: {
      'cardinal': '#b91c1c',
      'cardinal-hover': '#991b1b',
      'cardinal-light': '#dc2626',
      'anthracite': '#2d3139',
      'cream': '#f6f3ef',
      'white': '#ffffff',
      'text-primary': '#1a1a1a',
      'text-secondary': '#444',
      'text-tertiary': '#555',
      'border-light': '#eee',
      'border-gray': '#e5e7eb',
      'bg-footer': '#f9fafb',
      'black': '#000000',
      'gray': {
        100: '#f9fafb',
        200: '#f3f4f6',
        300: '#e5e7eb',
        400: '#9ca3af',
        500: '#6b7280',
        600: '#4b5563',
        700: '#374151',
        800: '#1f2937',
        900: '#111827',
      },
      'transparent': 'transparent',
    },
    
    fontFamily: {
      'sans': ['Plus Jakarta Sans', 'ui-sans-serif', 'system-ui', 'sans-serif'],
      'serif': ['Cinzel', 'ui-serif', 'Georgia', 'serif'],
    },
    
    extend: {
      spacing: {
        'narthex': '4px', // Ligne Narthex
      },
      
      borderRadius: {
        'arch': '50% 50% 0 0 / 40% 40% 0 0', // Arche religieuse
      },
      
      boxShadow: {
        'soft': '0 2px 8px rgba(0, 0, 0, 0.08)',
        'card': 'inset 0 0 0 1px rgba(0, 0, 0, 0.05)',
      },
      
      textTransform: {
        'uppercase': 'uppercase',
      },
      
      letterSpacing: {
        'tracked': '0.05em',
        'tracked-wide': '0.3em',
      },
    },
  },
  
  plugins: [
    require('@tailwindcss/forms'),
  ],
};
```

---

## Composants Blade Réutilisables

### 1️⃣ Conteneur Principal

```blade
<!-- resources/views/components/container.blade.php -->

<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12 {{ $class ?? '' }}">
  {{ $slot }}
</div>
```

**Usage :**
```blade
<x-container>
  <h1 class="text-4xl font-serif font-bold text-text-primary">Titre</h1>
</x-container>
```

### 2️⃣ Ligne Narthex (Séparateur)

```blade
<!-- resources/views/components/narthex-line.blade.php -->

@if($double ?? false)
  <!-- Double ligne : 1px gris + 4px rouge -->
  <div class="border-t border-border-light">
    <div class="border-t-4 border-cardinal"></div>
  </div>
@else
  <!-- Simple : 4px rouge pleine largeur -->
  <div class="border-t-4 border-cardinal w-full"></div>
@endif
```

**Usage :**
```blade
<x-narthex-line /> <!-- Simple -->
<x-narthex-line :double="true" /> <!-- Double -->
```

### 3️⃣ Arche Container (Carte)

```blade
<!-- resources/views/components/arch-card.blade.php -->

<div class="bg-white rounded-[50%_50%_0_0_/_40%_40%_0_0] shadow-soft border border-border-light overflow-hidden {{ $class ?? '' }}">
  {{ $slot }}
</div>
```

**Usage :**
```blade
<x-arch-card>
  <img src="cover.jpg" alt="Couverture" class="w-full object-cover">
  <div class="p-6">
    <h3 class="font-serif font-bold text-lg text-text-primary">Titre</h3>
    <p class="text-text-secondary mt-2">Description courte...</p>
  </div>
</x-arch-card>
```

### 4️⃣ Bouton Principal

```blade
<!-- resources/views/components/button.blade.php -->

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
```

**Usage :**
```blade
<x-button href="/login">Se connecter</x-button>
<x-button type="submit">Envoyer</x-button>
```

### 5️⃣ Bouton Secondaire (Outline)

```blade
<!-- resources/views/components/button-secondary.blade.php -->

<a 
  href="{{ $href }}"
  class="inline-flex items-center justify-center px-6 py-3 rounded font-sans font-600
         border-2 border-cardinal text-cardinal hover:bg-cream transition-colors {{ $class ?? '' }}"
>
  {{ $slot }}
</a>
```

### 6️⃣ Header Titre de Page

```blade
<!-- resources/views/components/page-header.blade.php -->

<div class="bg-white border-b border-border-light">
  <x-container>
    <div class="py-8 lg:py-12">
      <h1 class="text-3xl lg:text-5xl font-serif font-bold text-text-primary mb-4">
        {{ $title }}
      </h1>
      @if($subtitle ?? false)
        <p class="text-lg text-text-secondary">{{ $subtitle }}</p>
      @endif
    </div>
  </x-container>
  <x-narthex-line :double="true" />
</div>
```

**Usage :**
```blade
<x-page-header title="Catalogue" subtitle="Découvrez nos publications" />
```

### 7️⃣ Grid de Cartes E-Books

```blade
<!-- resources/views/components/ebook-grid.blade.php -->

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 {{ $class ?? '' }}">
  @forelse($ebooks as $ebook)
    <x-ebook-card :ebook="$ebook" />
  @empty
    <div class="col-span-full text-center py-12">
      <p class="text-text-secondary">Aucun eBook trouvé.</p>
    </div>
  @endforelse
</div>
```

### 8️⃣ Carte E-Book Individuelle

```blade
<!-- resources/views/components/ebook-card.blade.php -->

<x-arch-card>
  <!-- Cover Image -->
  <div class="relative overflow-hidden bg-gray-100 aspect-[3/4]">
    <img 
      src="{{ asset('storage/' . $ebook->cover_image) }}" 
      alt="{{ $ebook->title }}"
      class="w-full h-full object-cover hover:scale-105 transition-transform duration-300"
    >
    
    @if($ebook->is_new)
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
      par <span class="font-600">{{ $ebook->author->name }}</span>
    </p>
    
    <!-- Rating -->
    @if($ebook->avg_rating)
      <div class="flex items-center gap-2 mb-4">
        <div class="flex text-cardinal">
          @for($i = 0; $i < 5; $i++)
            @if($i < floor($ebook->avg_rating))
              ⭐
            @elseif($i < $ebook->avg_rating)
              ⭐ <!-- Demi étoile optionnelle -->
            @else
              ☆
            @endif
          @endfor
        </div>
        <span class="text-xs text-text-tertiary">({{ $ebook->reviews_count }})</span>
      </div>
    @endif
    
    <!-- Price -->
    <div class="mb-6 text-lg font-bold text-cardinal">
      {{ number_format($ebook->price, 2) }} €
    </div>
    
    <!-- Actions -->
    <div class="flex gap-3">
      <x-button-secondary href="/ebooks/{{ $ebook->slug }}" class="flex-1">
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
        <x-button href="/login" class="flex-1">Acheter</x-button>
      @endauth
    </div>
  </div>
</x-arch-card>
```

### 9️⃣ Navigation Principale

```blade
<!-- resources/views/components/navbar.blade.php -->

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
        <a href="/dashboard" class="text-text-secondary hover:text-cardinal transition-colors">
          {{ Auth::user()->name }}
        </a>
        <form method="POST" action="{{ route('logout') }}" class="inline">
          @csrf
          <button class="text-sm text-text-secondary hover:text-cardinal">Déconnexion</button>
        </form>
      @else
        <x-button-secondary href="/login">Connexion</x-button-secondary>
      @endauth
    </div>
  </x-container>
  
  <!-- Ligne Narthex Double -->
  <x-narthex-line :double="true" />
</nav>
```

### 🔟 Footer

```blade
<!-- resources/views/components/footer.blade.php -->

<footer class="bg-bg-footer border-t border-border-light mt-12">
  <x-container class="py-12">
    <!-- Content -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
      <!-- Brand -->
      <div>
        <h3 class="font-serif font-bold text-lg text-text-primary mb-4">APACC-M</h3>
        <p class="text-text-secondary text-sm leading-relaxed">
          Plateforme de lecture et diffusion d'e-books inspirée par l'esprit éditorial de Narthex.
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
```

---

## Typographie Usage

### Classes Utilitaires Texte

```html
<!-- Titres Page (H1) -->
<h1 class="font-serif font-bold text-4xl lg:text-5xl text-text-primary">
  Titre Principal
</h1>

<!-- Titres Section (H2) -->
<h2 class="font-serif font-bold text-3xl text-text-primary">
  Titre Section
</h2>

<!-- Sous-titres (H3) -->
<h3 class="font-serif font-bold text-2xl text-text-primary">
  Sous-titre
</h3>

<!-- Labels/Nav (Uppercase tracked) -->
<p class="font-sans font-600 text-sm uppercase tracking-tracked text-text-secondary">
  CATÉGORIES
</p>

<!-- Body Text -->
<p class="font-sans text-base leading-relaxed text-text-primary">
  Texte corps avec bonne lisibilité.
</p>

<!-- Secondary Text -->
<p class="font-sans text-sm text-text-secondary">
  Texte secondaire, infos mineures.
</p>
```

---

## Espacement & Layout

```html
<!-- Grid Responsive -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
  <!-- Cartes -->
</div>

<!-- Flex Horizontal -->
<div class="flex gap-4 items-center justify-between">
  <!-- Content -->
</div>

<!-- Stack Vertical -->
<div class="space-y-6">
  <div>Item 1</div>
  <div>Item 2</div>
  <div>Item 3</div>
</div>

<!-- Container -->
<div class="max-w-7xl mx-auto px-4 lg:px-8">
  <!-- Content centered, responsive padding -->
</div>
```

---

## Interactions & Hover States

```html
<!-- Link avec transition -->
<a href="#" class="text-cardinal hover:underline transition-colors">
  Lien
</a>

<!-- Bouton hover -->
<button class="bg-cardinal text-white hover:bg-cardinal-hover transition-colors px-4 py-2 rounded">
  Action
</button>

<!-- Card hover effect -->
<div class="rounded shadow-soft hover:shadow-lg transition-shadow hover:scale-105">
  <!-- Content -->
</div>

<!-- Opacity transition -->
<div class="opacity-75 hover:opacity-100 transition-opacity">
  <!-- Content -->
</div>
```

---

## Format Formulaires

```blade
<!-- Text Input -->
<div class="mb-6">
  <label class="block font-sans font-600 text-text-primary text-sm mb-2 uppercase tracking-tracked">
    Email
  </label>
  <input 
    type="email" 
    name="email"
    class="w-full px-4 py-3 border border-border-light rounded font-sans
           focus:outline-none focus:ring-2 focus:ring-cardinal focus:border-transparent"
  >
</div>

<!-- Textarea -->
<div class="mb-6">
  <label class="block font-sans font-600 text-text-primary text-sm mb-2 uppercase tracking-tracked">
    Message
  </label>
  <textarea 
    name="message"
    rows="6"
    class="w-full px-4 py-3 border border-border-light rounded font-sans
           focus:outline-none focus:ring-2 focus:ring-cardinal focus:border-transparent"
  ></textarea>
</div>

<!-- Select -->
<select class="w-full px-4 py-3 border border-border-light rounded font-sans focus:ring-2 focus:ring-cardinal">
  <option>Sélectionner...</option>
</select>

<!-- Checkbox -->
<label class="flex items-center gap-3 cursor-pointer">
  <input type="checkbox" class="w-4 h-4 accent-cardinal">
  <span class="text-text-secondary font-sans text-sm">J'accepte les conditions</span>
</label>
```

---

## Utilisation en Templates

### Exemple Complet — Page Accueil

```blade
<!-- resources/views/welcome.blade.php -->

<x-navbar />

<!-- Hero Section -->
<div class="bg-white py-20 lg:py-32">
  <x-container>
    <div class="text-center max-w-3xl mx-auto">
      <h1 class="font-serif font-bold text-5xl lg:text-6xl text-text-primary mb-6">
        Découvrez une lecture inspirée
      </h1>
      <p class="text-lg text-text-secondary mb-8 leading-relaxed">
        APACC-M vous propose une sélection d'e-books de qualité, inspirée par l'esprit éditorial de Narthex.
      </p>
      <x-button href="/ebooks">Parcourir le catalogue</x-button>
    </div>
  </x-container>
</div>

<!-- Narthex Line -->
<x-narthex-line />

<!-- Top Selections -->
<div class="bg-cream py-20 lg:py-32">
  <x-container>
    <h2 class="font-serif font-bold text-4xl text-text-primary mb-12 text-center">
      Sélections du moment
    </h2>
    <x-ebook-grid :ebooks="$topEbooks" />
  </x-container>
</div>

<!-- Narthex Line -->
<x-narthex-line />

<!-- Newsletter -->
<div class="bg-white py-20 lg:py-32">
  <x-container class="max-w-2xl mx-auto">
    <h3 class="font-serif font-bold text-3xl text-text-primary mb-6 text-center">
      Recevez nos nouveautés
    </h3>
    <form method="POST" action="/subscribe" class="flex gap-4 flex-col sm:flex-row">
      @csrf
      <input 
        type="email" 
        name="email"
        placeholder="Votre email"
        required
        class="flex-1 px-4 py-3 border border-border-light rounded font-sans"
      >
      <x-button type="submit">S'abonner</x-button>
    </form>
  </x-container>
</div>

<!-- Narthex Line -->
<x-narthex-line />

<x-footer />
```

---

**Prêt à designer ! Cette structure garantit cohérence et facilite maintenabilité.** ✨
