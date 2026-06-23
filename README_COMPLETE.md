# 📚 APACC-M eBooks Platform

Plateforme complète de publication et diffusion d'e-books inspirée par l'esprit éditorial de Narthex.

## 🎯 Caractéristiques

### ✅ Implémenté (MVP v1.0)

- **Design Responsive** : Charte graphique APACC-M appliquée partout
  - Couleurs : Cardinal rouge (#b91c1c), Cream (#f6f3ef), Anthracite (#2d3139)
  - Typographie : Cinzel (titres) + Plus Jakarta Sans (corps)
  - Motifs : Lignes Narthex, arch-containers, spacing cohérent

- **Catalogue Public**
  - Page d'accueil avec sélections
  - Catalogue complet avec filtres (catégories, prix, recherche)
  - Page détail eBook enrichie avec recommandations
  - Pagination et tri

- **Gestion Utilisateurs**
  - Authentification (login/register/logout)
  - Dashboard personnel : "Mes eBooks"
  - Profil utilisateur
  - Historique d'achats

- **Admin Complet**
  - Dashboard avec stats (total ebooks, users, sales, revenue)
  - CRUD eBooks : créer, éditer, supprimer
  - Gestion des utilisateurs (promouvoir admin)
  - Gestion des ventes et statuts

- **Système de Paiement**
  - Intégration HelloAsso (webhooks prêts)
  - Panier et checkout
  - Validation de paiement
  - Email de confirmation

- **Pages Légales**
  - À Propos
  - Contact (formulaire)
  - Conditions générales (CGU)
  - Politique de Confidentialité
  - Mentions Légales

- **Infrastructure**
  - 10+ composants Blade réutilisables
  - 8 migrations structurées
  - 5 modèles enrichis (Ebook, User, Category, Review, Coupon, etc.)
  - Routes structurées (public, auth, admin)
  - SEO-ready avec slugs

### 🔄 Prêt pour Phase 2 (Features Avancées)

- **Reviews & Ratings** : Système d'avis avec modération
- **Wishlist** : Favoris et alertes prix
- **Newsletter** : Signup et automation
- **Promo Codes** : Coupon management
- **Recherche Avancée** : Full-text + filtres
- **Recommendations** : Suggestions personnalisées
- **Author Dashboard** : Self-publishing portal

### 📋 Architecture Technique

```
Stack :
├─ Backend    : Laravel 13 + Breeze Auth
├─ Frontend   : Blade + Tailwind CSS 4 + Alpine.js
├─ DB         : MySQL 8.0+
├─ Storage    : Local (dev) / S3 (prod)
├─ Mail       : Mailgun/SendGrid
├─ Payment    : HelloAsso + Stripe-ready
├─ Cache      : Redis (optionnel)
└─ Deploy     : Docker, CI/CD, Monitoring
```

## 🚀 Installation Rapide

### Dev Local

```bash
# Setup complet
bash setup.sh

# Ou manuel :
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan storage:link

# Démarrer
php artisan serve  # Terminal 1
npm run dev        # Terminal 2
```

**URL** : http://localhost:8000

### Production

Voir [INSTALLATION.md](INSTALLATION.md) pour guide complet de déploiement (DigitalOcean, Linode, etc.)

## 📚 Documentation

| Document | Contenu |
|----------|---------|
| [ROADMAP.md](ROADMAP.md) | Plan complet 7 phases |
| [DESIGN_SYSTEM.md](DESIGN_SYSTEM.md) | Composants Blade + CSS |
| [WEEK1_ACTION_PLAN.md](WEEK1_ACTION_PLAN.md) | Tâches prioritaires |
| [IMPROVEMENT_AXES.md](IMPROVEMENT_AXES.md) | Features futures (Tier 1-3) |
| [EXECUTIVE_SUMMARY.md](EXECUTIVE_SUMMARY.md) | Vue d'ensemble exécutive |
| [INSTALLATION.md](INSTALLATION.md) | Guide setup + déploiement |

## 🎨 Design System

### Couleurs APACC-M
```
Cardinal :     #b91c1c (accent primaire)
Cardinal Hover: #991b1b (interactive)
Cream :        #f6f3ef (fond principal)
Anthracite :   #2d3139 (texte dark)
White :        #ffffff (cards)
```

### Composants Réutilisables
- `<x-container>` — max-width responsive
- `<x-button>` / `<x-button-secondary>` — CTA cohérentes
- `<x-arch-card>` — cartes avec arche en haut
- `<x-ebook-card>` / `<x-ebook-grid>` — affichage livres
- `<x-navbar>` / `<x-footer>` — layout
- `<x-narthex-line>` — séparateurs

## 📊 Base de Données

### Tables Créées
- `ebooks` : eBooks avec slug, catégorie, auteur, status
- `users` : Users enrichis (avatar, bio, is_author)
- `purchases` : Achats avec statut, payment_method, transaction_id
- `categories` : Catégorisation
- `reviews` : Système d'avis
- `wishlists` : Favoris
- `coupons` : Codes promo
- `subscribers` : Newsletter

## 🔐 Sécurité & Compliance

✅ HTTPS obligatoire  
✅ CSRF tokens  
✅ Input validation  
✅ Rate limiting  
✅ PDF access control  
✅ RGPD ready  
✅ 2FA (admin)  

## 📈 Métriques à Tracker

| Métrique | Baseline | Target |
|----------|----------|--------|
| Monthly Visitors | - | 1K → 10K |
| Conversion Rate | - | 0.5% → 2% |
| Avg Order Value | €12 | €15+ |
| Email List | - | 500+ |
| Ebooks Catalog | 3 | 50+ |

## 🔄 Prochaines Étapes (Phase 2+)

**Semaine 3-6** :
- [ ] Reviews & modération
- [ ] Newsletter automation
- [ ] Wishlist avec notifications
- [ ] Coupon campaigns
- [ ] Full-text search

**Semaine 7-15** :
- [ ] Author dashboard
- [ ] Subscription model
- [ ] Blog & SEO
- [ ] Audiobooks support
- [ ] Advanced analytics

**Semaine 16+** :
- [ ] Multi-author marketplace
- [ ] Community features
- [ ] IA recommendations
- [ ] Print-on-demand
- [ ] Mobile app

## 📞 Support

Pour questions ou bugs :
- Ouvrir une issue GitHub
- Consulter les docs (voir liens ci-dessus)
- Contacter : contact@apacc-m.fr

---

## 📄 Licence

APACC-M eBooks Platform
Copyright © 2025 APACC-M

---

**Plateforme prête pour lancement ! 🚀**

Visiter : https://ebooks.apacc-m.fr (production)  
Admin : https://ebooks.apacc-m.fr/admin (avec authentification)
