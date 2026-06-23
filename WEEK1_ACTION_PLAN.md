# 🚀 Plan d'Action — Semaine 1 (Prochains 7 jours)

## Objectif : Poser les Fondations Solides
**Résultat attendu** : Une plateforme fonctionnelle avec design APACC-M, prête pour pages de contenu.

---

## ✅ JOUR 1-2 : Configuration Tailwind & Charte Graphique

### Tâches :

#### 1. Mettre à jour `tailwind.config.js`
- Ajouter les couleurs APACC-M (cardinal, cream, texts, etc.)
- Ajouter polices Cinzel et Plus Jakarta Sans
- Ajouter variables d'espacement custom
- Ajouter borderRadius custom (arch-container)
- Ajouter box-shadow custom (soft, card)

**Fichier** : `tailwind.config.js`
```javascript
// Remplacer le theme actuel avec les couleurs et polices APACC-M
```

#### 2. Importer les polices Google dans `app.blade.php`
```html
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
```

#### 3. Créer `resources/css/apacc.css` (variables + utilities)
- Définir CSS variables root pour couleurs
- Classes utilitaires custom (`.narthex-line`, `.arch-card`, `.text-uppercase-tracked`)

#### 4. Nettoyer `welcome.blade.php`
- Remplacer le contenu générique Laravel par un template minimal
- Garder structure HTML, appliquer classes APACC-M progressivement

**Temps estimé** : 4-6h

---

## ✅ JOUR 2-3 : Créer Composants Blade Réutilisables

### Tâches :

#### 1. Composants Structurels
Créer dans `resources/views/components/` :

```bash
resources/views/components/
├── container.blade.php         # Conteneur max-width + padding
├── narthex-line.blade.php      # Séparateur rouge cardinal
├── arch-card.blade.php         # Carte avec arche en haut
├── navbar.blade.php            # Navigation principale
├── footer.blade.php            # Pied de page
└── page-header.blade.php       # Header titre page
```

#### 2. Composants Boutons & Formulaires
```bash
resources/views/components/
├── button.blade.php            # Bouton primaire (cardinal)
├── button-secondary.blade.php  # Bouton outline
├── form-input.blade.php        # Input texte
└── form-textarea.blade.php     # Textarea
```

#### 3. Composants e-Book
```bash
resources/views/components/
├── ebook-card.blade.php        # Carte individuelle
└── ebook-grid.blade.php        # Grid responsive
```

**Fichiers à créer** : ~10 composants (voir DESIGN_SYSTEM.md)

**Temps estimé** : 6-8h

---

## ✅ JOUR 3-4 : Enrichir les Modèles & Migrations

### Tâches :

#### 1. Créer Migrations pour Ebook
```bash
php artisan make:migration add_fields_to_ebooks_table
```

**Champs à ajouter** :
```php
$table->string('slug')->unique()->after('title');
$table->unsignedBigInteger('category_id')->nullable();
$table->text('short_description')->after('description');
$table->integer('page_count')->nullable();
$table->date('published_date')->nullable();
$table->enum('status', ['draft', 'published', 'archived'])->default('published');
```

#### 2. Créer Migrations pour User
```bash
php artisan make:migration add_fields_to_users_table
```

**Champs** :
```php
$table->string('avatar_path')->nullable()->after('email');
$table->text('bio')->nullable();
$table->boolean('is_author')->default(false);
$table->json('social_links')->nullable();
```

#### 3. Créer Migrations pour Purchase
```bash
php artisan make:migration add_fields_to_purchases_table
```

**Champs** :
```php
$table->timestamp('access_expires_at')->nullable();
$table->enum('status', ['pending', 'completed', 'refunded'])->default('pending');
$table->string('payment_method')->nullable();
$table->string('transaction_id')->unique()->nullable();
```

#### 4. Créer Category Model & Migration
```bash
php artisan make:model Category -m
php artisan make:migration create_category_ebook_table
```

#### 5. Mettre à jour Models
- Ebook : ajouter accessors `slug`, relations categories, author, purchases
- User : ajouter relation ebooks (si author)
- Category : relation ebooks (belongsToMany)

**Temps estimé** : 4-5h

---

## ✅ JOUR 4-5 : Pages Publiques Essentielles

### Tâches :

#### 1. Page Accueil (`/`)
- Créer contrôleur `HomeController` ou méthode index
- Créer `resources/views/home.blade.php`
- Afficher 6-8 top e-books
- Newsletter form
- Sections : Hero + Sélections + CTA

**Route** : `Route::get('/', [HomeController::class, 'index'])->name('home');`

#### 2. Page Catalogue (`/ebooks`)
- Contrôleur `EbookController@index` (déjà existant)
- Vue `resources/views/ebooks/index.blade.php`
- Grid de cartes (composant ebook-grid)
- Filtres côté : catégories, prix range
- Tri : récent, prix, populaire
- Pagination

#### 3. Page Détail eBook (`/ebooks/{slug}`)
- Contrôleur `EbookController@show` (adapter pour slug)
- Vue `resources/views/ebooks/show.blade.php`
- Cover grande + infos détaillées
- Bouton achat / CTA
- Section aperçu (optionnel : PDF.js preview)
- Recommandations similaires

#### 4. Page À Propos (`/about`)
- Route statique
- Vue `resources/views/pages/about.blade.php`
- Info APACC-M + mission
- Lien contact

#### 5. Page Contact (`/contact`)
- Route GET (formulaire) + POST (envoi)
- Vue `resources/views/pages/contact.blade.php`
- Formulaire simple : nom, email, sujet, message
- Envoi email ou Formspree

**Temps estimé** : 10-12h

---

## ✅ JOUR 5-6 : Pages Utilisateurs & Admin Basiques

### Tâches :

#### 1. Dashboard Utilisateur (`/dashboard`)
- Mettre à jour existant
- Ajouter : Mes e-books, Mon profil, Historique achats
- Cards avec design APACC-M

#### 2. Lecteur eBook (`/ebook/{slug}/read`)
- Route + contrôleur `EbookController@read`
- Vérifier achat avec `abortUnlessPaid()`
- Intégrer lecteur PDF (PDF.js simple)
- Bouton téléchargement

#### 3. Admin Dashboard (`/admin`)
- Route + contrôleur AdminDashboard
- Stats : revenus, nb ventes, top sellers
- Cartes info + graphique simple (optionnel)

#### 4. Admin Gestion e-Books (`/admin/ebooks`)
- Contrôleur existant : adapter pour nouveau schema
- Tableau : titre, catégorie, prix, actions
- Bouton "+ Ajouter" → modal/page création
- Form édition : champs enrichis
- Supprimer avec confirmation

#### 5. Admin Gestion Utilisateurs (`/admin/users`)
- Contrôleur simple
- Tableau utilisateurs avec rôles
- Toggle admin

**Temps estimé** : 12-14h

---

## ✅ JOUR 6-7 : Polish, Tests & Responsive

### Tâches :

#### 1. Responsive Design
- Mobile-first check (375px)
- Tablet (768px)
- Desktop (1024px+)
- Test sur réels appareils ou DevTools

#### 2. Animations Subtiles
- Fade-in au scroll (Alpine.js)
- Hover effects légers
- Pas d'animations agressives
- Transitions smooth (300-500ms)

#### 3. Accessibilité Basique
- Contrast check (WCAG AA)
- Alt text sur images
- Labels on inputs
- Keyboard nav (Tab, Enter)

#### 4. Performance
- Image optimization (WebP lazy loading)
- CSS/JS minification (Vite)
- Lighthouse check
- PDF lazy loading

#### 5. Tests manuels
- Flow achat complet
- Login/logout
- Admin actions
- Recherche/filtres
- Mobile navigation

#### 6. Docs & Cleanup
- Mettre à jour README.md
- Commenter code complexe
- Valider migrations

**Temps estimé** : 8-10h

---

## 📋 Checklist Quotidienne

### **Jour 1** ✅
- [ ] Tailwind config + polices
- [ ] CSS variables + utilities
- [ ] `resources/css/apacc.css`
- [ ] `welcome.blade.php` nettoyé

### **Jour 2** ✅
- [ ] 10+ composants Blade
- [ ] Composants testés localement
- [ ] Arche CSS correcte

### **Jour 3** ✅
- [ ] Migrations Ebook/User/Purchase
- [ ] Category model + migration
- [ ] Models mis à jour
- [ ] Migrations runables

### **Jour 4** ✅
- [ ] Accueil (`/`) fonctionnelle
- [ ] Catalogue (`/ebooks`) avec filtres
- [ ] Détail eBook (`/ebooks/{slug}`)
- [ ] À Propos + Contact

### **Jour 5** ✅
- [ ] Dashboard utilisateur
- [ ] Lecteur PDF (`/ebook/{slug}/read`)
- [ ] Admin dashboard
- [ ] Admin gestion ebooks + users

### **Jour 6** ✅
- [ ] Design responsive partout
- [ ] Animations légères
- [ ] Accessibilité basique
- [ ] Performance OK

### **Jour 7** ✅
- [ ] Tests manuels complets
- [ ] Docs finalisées
- [ ] Cleanup + commit git
- [ ] Demo prête ! 🎉

---

## 🛠️ Commandes de Départ

```bash
# Initialiser repo git
git init
git config user.name "APACC-M Team"
git config user.email "dev@apacc-m.fr"

# Créer branch dev
git checkout -b develop
git add .
git commit -m "Initial commit : structure de base + plan"

# Migrations
php artisan make:migration add_fields_to_ebooks_table
php artisan make:migration add_fields_to_users_table
php artisan make:migration add_fields_to_purchases_table
php artisan make:model Category -m
php artisan make:migration create_category_ebook_table

# Contrôleurs
php artisan make:controller HomeController
php artisan make:controller Admin/AdminController
php artisan make:controller Admin/DashboardController

# Composants Blade
mkdir -p resources/views/components

# Seed données test
php artisan make:seeder CategorySeeder
php artisan make:seeder EbookSeeder

# Tests
npm run dev
php artisan serve
```

---

## 📊 Estimations Temps

| Phase | Tâche | Temps | Total |
|-------|-------|-------|-------|
| 1 | Config Tailwind | 4-6h | **10-12h** |
| 2 | Composants | 6-8h | |
| 3 | Migrations | 4-5h | **14-16h** |
| 4 | Pages publiques | 10-12h | **20-24h** |
| 5 | Pages user/admin | 12-14h | **24-28h** |
| 6 | Polish + tests | 8-10h | **16-20h** |
| **TOTAL** | | | **84-100h** |

**Soit ~2 semaines full-time ou 4-5 semaines part-time (20h/semaine)**

---

## 🎯 Après Semaine 1

Si timeline respectée, vous aurez :
✅ Design APACC-M appliqué globalement  
✅ Plateforme visiteur fonctionnelle  
✅ Admin basique opérationnel  
✅ Fondations solides pour features avancées  

Prochaines étapes :
→ Reviews / Ratings  
→ Newsletter  
→ Wishlist  
→ Système promo  
→ Analytics  

---

**À vos éditeurs ! 🚀 C'est parti ! ✨**
