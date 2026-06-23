# 📘 Démarche Complète — APACC-M eBooks Platform

## 📊 État du Projet
- ✅ Architecture Laravel 13 + Breeze (authentification)
- ✅ Modèles (User, Ebook, Purchase)
- ✅ Migrations + relations
- ✅ Routes et contrôleurs de base
- ⚠️ **Design et UX incomplets**
- ⚠️ **Fonctionnalités métier manquantes**
- ⚠️ **Intégration de la charte graphique absente**

---

## 🎯 PHASE 1 : FONDATIONS (Semaine 1)

### 1.1 — Appliquer la Charte Graphique
**Objectif** : Transformer le design générique en identité APACC-M

#### Tailwind Config
- [x] Ajouter les couleurs officielles (`#b91c1c`, `#991b1b`, `#2d3139`, `#f6f3ef`, etc.)
- [x] Importer les polices **Cinzel** (titres) et **Plus Jakarta Sans** (corps)
- [x] Définir les variables CSS pour les motifs (ligne Narthex, arche)
- [ ] Créer les classes Tailwind utilitaires custom (`.narthex-line`, `.arch-container`, etc.)

#### Layouts Redesignés
- [ ] **Header/Nav** : Logo APACC-M + navigation épurée, fond blanc/beige, ligne double rouge sous logo
- [ ] **Footer** : Contenu informatif + mentions légales + lien contact + fond `#f9fafb`
- [ ] **Cartes de contenu** : Arches en haut, shadows douces, fond blanc, bordures `#eee`
- [ ] **Boutons** : Rouge cardinal (`#b91c1c`), hover rouge foncé (`#991b1b`), sans animation agressive

### 1.2 — Configurer l'Asset Storage
- [ ] Créer des répertoires organisés dans `storage/app/public/` :
  - `ebooks/covers/` (images couverture)
  - `ebooks/files/` (PDFs protégés)
  - `uploads/` (contenu utilisateur)
- [ ] Setup `FILESYSTEM_DISK=public` dans `.env`
- [ ] Symlink : `php artisan storage:link`

### 1.3 — Enrichir les Modèles
**Ebook** : ajouter champs métier
```
- slug (unique, pour URLs lisibles)
- category (collections thématiques)
- author (relation vers User)
- isbn (optionnel, métadonnées)
- published_date
- page_count / word_count (pour SEO)
- cover_image (chemin protégé)
- preview_pages (nombre de pages libres)
- status (draft|published|archived)
```

**User** : enrichissement
```
- avatar_path
- bio / description
- is_author (booléen)
- social_links (JSON)
```

**Purchase** : tracking complet
```
- access_expires_at (abonnement)
- status (pending|completed|refunded)
- payment_method ('helloasso'|'stripe')
- transaction_id
```

### 1.4 — Migrations Nouvelles
```bash
php artisan make:migration add_fields_to_ebooks_table
php artisan make:migration add_fields_to_users_table
php artisan make:migration add_fields_to_purchases_table
php artisan make:migration create_categories_table
php artisan make:migration create_reviews_table
```

---

## 🎨 PHASE 2 : PAGES ET UX (Semaines 2-3)

### 2.1 — Pages Publiques

#### 🏠 Accueil (`/`)
- Banneau héro avec accroche APACC-M
- Grid de collections en avant (3-4 top sellers)
- Section "Pourquoi lire ici ?" avec 3 avantages
- CTA newsletter
- Ligne Narthex comme séparateur
- Footer complet

#### 📚 Catalogue (`/ebooks` ou `/catalogue`)
- Filtres : catégories, prix, auteur, note moyenne
- Tri : récent, prix (↑↓), tendance, top ventes
- Grille de cartes :
  - Image couverture avec arch-container
  - Titre + auteur (Cinzel pour titre, Jakarta pour auteur)
  - Prix + badge "Nouveau" / "Populaire"
  - Rating stars (si implémenté)
  - CTA "Lire un aperçu" / "Ajouter au panier"
- Pagination élégante

#### 📖 Détail eBook (`/ebooks/{slug}`)
- Cover large + zone info (titre, auteur, description)
- Prix + CTA achat (HelloAsso ou Stripe)
- Section "Aperçu" (pré-visualisation des 10-20 premières pages)
- Info : page count, catégorie(s), date pub, ISBN
- Reviews utilisateurs (si acheté)
- Recommandations (livres similaires)
- SEO optimisé (og:image, og:description, etc.)

#### 🎓 À Propos (`/about`)
- Présentation APACC-M + mission
- Équipe d'auteurs
- Mention de la qualité éditoriale
- Contact / formulaire
- Lien blog ou ressources

#### 📞 Contact (`/contact`)
- Formulaire simple (nom, email, sujet, message)
- Intégration mail ou Formspree
- Confirmation de réception
- Infos contact affichées

#### ⚖️ Pages Légales
- `/terms` - Conditions générales
- `/privacy` - Politique de confidentialité
- `/legal` - Mentions légales

### 2.2 — Pages Authentifiées

#### 👤 Dashboard Utilisateur (`/dashboard`)
- **Mes livres** : liste achetés avec boutons "Lire", "Télécharger"
- **Mon profil** : avatar, bio, infos contact
- **Historique d'achat** : liste des commandes + factures
- **Wishlist** : liste des livres en attente (optionnel)
- **Notifications** : nouvelles sorties, réductions, etc.

#### 📖 Lecteur eBook (`/ebook/{slug}/read`)
- Lecteur PDF intégré (PDF.js ou Issuu)
- Navigation pages
- Mode plein écran
- Téléchargement autorisé
- Annotations/marque-pages (optionnel)
- Logout if access denied

#### 🛒 Panier & Checkout (`/cart`, `/checkout`)
- Panier avec suppression d'articles
- Résumé des prix
- Redirection HelloAsso ou Stripe
- Confirmation post-paiement

### 2.3 — Pages Admin

#### 📊 Dashboard Admin (`/admin`)
- Stats : revenus mois, nb ventes, top sellers, top auteurs
- Graphiques (Chart.js ou Apex Charts)
- Dernières commandes
- Alertes / notifications

#### 📚 Gestion eBooks (`/admin/ebooks`)
- Tableau : titre, auteur, catégorie, prix, nb ventes, actions (edit, delete)
- Bouton "+ Ajouter eBook"
- Modal/page de création/édition :
  - Formulaire complet (titre, description, catégorie, prix, etc.)
  - Upload cover image (drag & drop)
  - Upload fichier PDF
  - Preview_pages (nombre de pages visibles en aperçu)
  - Statut (draft/published)
  - SEO fields (slug, meta description)
- Suppression avec confirmation

#### 👥 Gestion Utilisateurs (`/admin/users`)
- Tableau : nom, email, date inscription, nb achats, rôle
- Recherche / filtres
- Bouton promouvoir/rétrograder admin
- Modifier email/infos
- Historique d'accès

#### 💰 Gestion Ventes (`/admin/sales`)
- Tableau : date, client, eBook, montant, statut, méthode paiement
- Filtres : période, statut, produit
- Export CSV/Excel
- Remboursements

---

## 🛠️ PHASE 3 : FONCTIONNALITÉS AVANCÉES (Semaines 4-5)

### 3.1 — Système d'Avis / Reviews
```
- Création Avis model + migration
- Relation : User → Reviews ← Ebook
- Affichage moyenne note sur card eBook
- Form avis sur page détail (si acheté)
- Modération (admin approuve)
```

### 3.2 — Moteur de Recherche & SEO
```
- Eloquent search ou Scout (Laravel)
- Filtres avancés (slider prix, date, auteur, catégorie)
- Pagination avec cursor (rapidité)
- Meta tags dynamiques
- Sitemap XML
- Robots.txt
```

### 3.3 — Système de Catégories
```
- Model Category + migration
- Relation Ebook → Categories (belongsToMany)
- Page liste catégorie : /categories/{category}
- Filtres dans le catalogue
```

### 3.4 — Newsletter & Marketing
```
- Model Subscriber (email, status)
- Form signup footer / popup
- Mailables : welcome, new releases, promo
- Queue jobs pour envoi en masse
- Unsubscribe link
```

### 3.5 — Recommandations Intelligentes
```
- Afficher livres similaires basés sur :
  - Même catégorie
  - Même auteur
  - Achetés par utilisateurs similaires (optionnel)
- Section "Vous aimerez aussi"
```

### 3.6 — Wishlist
```
- Model Wishlist pivot table
- Bouton ❤️ sur cartes / page détail
- Page /wishlist pour user connecté
- Email alert si livre en promo / en stock
```

### 3.7 — Système de Codes Promo
```
- Model Coupon (code, discount_percent, validity, usage_count)
- Validation au checkout
- Affichage prix réduit
- Admin CRUD coupons
```

### 3.8 — Analytics & Monitoring
```
- Tracking page views (Laravel Analytics ou Plausible)
- Conversion funnel
- Monitoring emails (logs, bounces)
- Logs d'erreur (Sentry ou LogRocket)
```

---

## 🎨 PHASE 4 : DESIGN & POLISH (Semaine 6)

### 4.1 — Responsive Design
- [ ] Mobile-first : tous pages
- [ ] Test tablet (iPad)
- [ ] Test desktop (27"+ moniteurs)
- [ ] No overflow horizontal
- [ ] Touch-friendly buttons (48x48 min)

### 4.2 — Animations & Transitions
- [ ] Fade-in au scroll (Intersection Observer ou Alpine.js)
- [ ] Hover effects subtils (opacité, scale léger)
- [ ] Page transitions douces
- [ ] Loading spinners (rouge cardinal)
- [ ] **Pas d'animations agressives** → sobriété APACC-M

### 4.3 — Accessibilité
- [ ] Contrast ratios (WCAG AA minimum)
- [ ] Alt text pour images
- [ ] Labels for form inputs
- [ ] Keyboard navigation (tab, enter)
- [ ] Screen reader friendly

### 4.4 — Performance
- [ ] Image optimization (WebP, lazy loading)
- [ ] CSS/JS minification
- [ ] Caching headers
- [ ] Lighthouse score ≥ 90
- [ ] PDF upload: max 50MB, scan virus

### 4.5 — Testing
- [ ] Unit tests (models, policies)
- [ ] Feature tests (auth, purchase flow)
- [ ] Browser tests (Dusk pour les pages critiques)

---

## 📦 PHASE 5 : DÉPLOIEMENT & DOCUMENTATION (Semaine 7)

### 5.1 — Environnement Production
```
- Setup .env.production
- Migrations on server
- Storage symlink
- Cache warming
- SSL certificate (Let's Encrypt)
```

### 5.2 — Configuration Services
```
- EmailProvider (Mailgun, SendGrid)
- HelloAsso API (webhooks pour vérifier paiements)
- Storage (AWS S3 optionnel pour PDFs)
- CDN (Cloudflare)
- Monitoring (Sentry, New Relic)
```

### 5.3 — Documentation
```
- README.md complet (setup local, deployment)
- Architecture.md (structure et flux)
- API.md (endpoints si API)
- CHANGELOG.md
- Admin guide (comment manager e-books, utilisateurs)
```

### 5.4 — Lancement
```
- Email announcement
- Social media posts
- Press release (optionnel)
- Monitoring live (logs, erreurs, analytics)
```

---

## 🌟 AXES D'AMÉLIORATION & FEATURES FUTURES

### Tier 1 (Post-Lancement Rapide)
1. **Autheur Dashboard**
   - Tableau de bord personnel pour auteurs
   - Stats ventes en temps réel
   - Accès aux reviews
   - Rapport de revenus

2. **Abonnement / Subscription**
   - Modèle freemium (certains livres gratuits)
   - Subscription mensuelle (accès unlimited)
   - Tiers: Free, Premium, VIP
   - Intégration Stripe Billing

3. **Blog / Content Hub**
   - Articles APACC-M (actualités, conseils de lecture)
   - Relation Article → Ebooks (recommandations)
   - RSS feed

4. **Intégration Réseaux Sociaux**
   - Share button (Twitter, LinkedIn, Facebook)
   - Social login (optionnel)
   - Social proof (nb shares)

### Tier 2 (Croissance)
5. **App Mobile**
   - Flutter ou React Native
   - Synchronisation avec le site
   - Lecteur hors ligne
   - Notifications push

6. **Live Events / Webinaires**
   - Calendar d'auteurs
   - Zoom integration
   - Enregistrements
   - Accès abonnés

7. **Marketplace d'Auteurs**
   - Profils publics pour auteurs
   - Commission sur ventes (70/30)
   - Self-publishing portal
   - Paiement automatique (Stripe Connect)

8. **Audiobooks**
   - Upload fichiers audio
   - Lecteur audio
   - SoundCloud intégration

### Tier 3 (Maturation)
9. **IA & Recommandations**
   - Moteur ML pour recommandations
   - Summary generation (résumés IA)
   - Full-text search avancée

10. **Print-on-Demand**
    - Intégration Blurb ou KDP
    - Livre physique option
    - Royalties depuis impression

11. **Corporate / B2B**
    - Licenses pour organisations
    - Group subscriptions
    - Bulk discounts
    - Invoice generation

12. **Community**
    - Forums discussions
    - Book clubs
    - User-generated content
    - Gamification (badges, leaderboards)

---

## 🔐 Considérations Importantes

### Sécurité
- ✅ HTTPS obligatoire
- ✅ CSRF tokens sur formulaires
- ✅ Validation input (server-side)
- ✅ Rate limiting (API)
- ✅ PDF: protection access (user ID check)
- ✅ Admin auth: 2FA recommandé

### Légal & Compliance
- ✅ RGPD (consentement cookies, droit oubli)
- ✅ CGU/Confidentialité complets
- ✅ Mentions légales
- ✅ Copyright notices
- ✅ ISB validation (si utilisé)

### Monitoring
- ✅ Error tracking (Sentry)
- ✅ Uptime monitoring
- ✅ Log aggregation
- ✅ Alertes performance
- ✅ Daily backups

---

## 📋 Checklist Rapide : Prochaines Étapes

- [ ] **Jour 1-2** : Appliquer charte graphique (Tailwind config + components)
- [ ] **Jour 3-4** : Migrations + enrichir modèles
- [ ] **Jour 5-10** : Pages publiques (accueil, catalogue, détail)
- [ ] **Jour 11-15** : Pages utilisateur (dashboard, lecteur)
- [ ] **Jour 16-20** : Pages admin (gestion ebooks, users, sales)
- [ ] **Jour 21-25** : Features avancées (reviews, newsletter, wishlist)
- [ ] **Jour 26-30** : Polish, responsive, performance, tests
- [ ] **Jour 31-35** : Documentation + setup prod
- [ ] **Jour 36+** : Lancement + monitoring

---

## 📚 Ressources
- [Laravel Docs](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com)
- [HelloAsso API](https://dev.helloasso.com)
- [PDF.js](https://mozilla.github.io/pdf.js/)
- [Cinzel + Plus Jakarta Sans Fonts](https://fonts.google.com)

---

**Bon développement ! 🚀 APACC-M va briller en ligne.**
