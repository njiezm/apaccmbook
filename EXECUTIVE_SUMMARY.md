# 📌 APACC-M eBooks — Synthèse Exécutive

## 🎯 Vision

**Créer une plateforme de publication et lecture d'e-books de qualité, alignée sur l'esprit éditorial de Narthex.**

État actuel : Infrastructure de base ✅ | Design & UX : À compléter  
Objectif : Plateforme opérationnelle en **6 semaines**, mature en **4 mois**

---

## 📊 État du Projet

### Existant ✅
```
Architecture     → Laravel 13 + Breeze ✅
Database         → User, Ebook, Purchase models ✅
Routes & API     → Routes web structurées ✅
Auth             → Login/Register/Profile ✅
Admin            → CRUD basic ebooks/users ✅
Payment          → HelloAsso integration (partielle)
```

### Manquant ❌
```
Design/UX        → Charte graphique manquante
Pages publiques  → Accueil, catalogue, détail incomplets
User dashboard   → Mes ebooks, lecteur PDF absent
Admin dashboard  → Stats, analytics manquants
Features        → Reviews, wishlist, newsletter, search abs.
Content         → About, contact, blog absent
```

---

## 🏗️ Architecture Recommandée

```
APACC-M eBooks
│
├─ Frontend Layer (Blade + Tailwind + Alpine.js)
│  ├─ Public Pages : home, catalog, detail, about, contact
│  ├─ User Pages : dashboard, reader, profile, wishlist
│  ├─ Admin Pages : dashboard, ebooks, users, sales, analytics
│  └─ Components : reusable (button, card, navbar, etc.)
│
├─ Backend Layer (Laravel)
│  ├─ API : internal routes + potential REST API
│  ├─ Controllers : HomeController, EbookController, AdminController
│  ├─ Models : User (extended), Ebook (enriched), Purchase, Review, etc.
│  ├─ Policies : EbookPolicy, AdminPolicy, PurchasePolicy
│  ├─ Mail : welcome, verification, purchase confirmation
│  └─ Jobs : email newsletters, PDF generation, etc.
│
├─ Data Layer
│  ├─ Database : MySQL 8.0+
│  ├─ Cache : Redis (sessions, cache)
│  └─ Storage : Local (dev), S3 (prod) pour PDFs
│
├─ External Services
│  ├─ Payment : HelloAsso API (webhooks)
│  ├─ Email : Mailgun/SendGrid (transactional + marketing)
│  ├─ Analytics : Plausible/Fathom (privacy-first)
│  ├─ Monitoring : Sentry (errors)
│  └─ CDN : Cloudflare (assets + caching)
│
└─ DevOps
   ├─ Server : Laragon (dev), Linode/DigitalOcean (prod)
   ├─ Git : GitHub avec workflow CI/CD
   ├─ Backups : Daily automated
   └─ Monitoring : Uptime + performance logs
```

---

## 🎨 Design System (APACC-M)

### Couleurs
```
Cardinal    #b91c1c  ← Accent primaire (boutons, links, barres)
Cardinal-H  #991b1b  ← Hover state
Cream       #f6f3ef  ← Fond principal
White       #ffffff  ← Cartes, modales
Text Prime  #1a1a1a  ← Titre, body text
Text Sec    #444     ← Labels, nav secondaire
Text Tert   #555     ← Petits textes, helper text
Border      #eee     ← Lignes de séparation
Footer      #f9fafb  ← Fond pied
```

### Typographie
```
Titres   → Cinzel (serif) 400/700 — "sacré, historique"
Corps    → Plus Jakarta Sans (sans-serif) 400/600/700/800 — "contemporain"

Usage :
- H1/H2 → Cinzel bold
- H3 → Cinzel bold
- P/nav → Jakarta Sans 400/600
- Label/cap → Jakarta Sans 600 uppercase tracked
```

### Motifs Graphiques
```
Narthex Line      → 4px red cardinal, full width separator
Narthex Double    → 1px gray + 4px red, under logo
Arch Container    → border-radius: 50% 50% 0 0 / 40% 40% 0 0
Tracked Text      → letter-spacing: 0.05-0.3em
Soft Shadow       → rgba(0,0,0,0.08), subtle depth
```

---

## 📋 Plan de Développement (6 Semaines)

### **Semaine 1-2 : Foundation** 🔴 CRITICAL
```
✓ Tailwind config (colors, fonts, utilities)
✓ Composants Blade (button, card, navbar, footer, etc.)
✓ Pages publiques (home, catalog, detail, about, contact)
✓ User dashboard (mes ebooks, lecteur, profile)
✓ Admin (dashboard, gestion ebooks/users)
✓ Storage setup (cover images, PDF files)
✓ Payment integration (HelloAsso webhooks)
```
**Résultat** : MVP fonctionnel, design appliqué, monétisation active

---

### **Semaine 3-4 : Maturity** 🟡 IMPORTANT
```
✓ Reviews & ratings system
✓ Categories (filter + navigation)
✓ Newsletter (signup + automation)
✓ Wishlist (favorite, alerts)
✓ Promo codes (admin creation + validation)
✓ Search & advanced filters
```
**Résultat** : Platform mature, engagement up, conversion optimized

---

### **Semaine 5-6 : Polish** 🟢 NICE-TO-HAVE
```
✓ Recommendations engine
✓ Author dashboard (self-publishing)
✓ Analytics & dashboards
✓ Email automation (transactional + marketing)
✓ SEO optimization (meta, sitemap, schema)
✓ Performance tuning (Lighthouse 90+)
```
**Résultat** : Premium experience, ready for scale

---

## 🎯 Axes d'Amélioration (Priorisés)

### **TIER 1 : ESSENTIEL (Semaine 1-2)**
| Axe | Impact | Effort | Status |
|-----|--------|--------|--------|
| 🎨 Charte graphique | ⭐⭐⭐ | ⭐ | ↓ Faire |
| 📚 Catalogue & discovery | ⭐⭐⭐ | ⭐⭐ | ↓ Faire |
| 👤 User pages | ⭐⭐ | ⭐⭐ | ↓ Faire |
| 🔐 Admin basics | ⭐⭐ | ⭐⭐ | ↓ Faire |
| 💳 Payment complete | ⭐⭐⭐ | ⭐ | ↓ Faire |

### **TIER 2 : DIFFÉRENCIATEUR (Semaine 3-6)**
| Axe | Impact | Effort | Status |
|-----|--------|--------|--------|
| ⭐ Reviews & ratings | ⭐⭐ | ⭐ | ← Next |
| 🏷️ Categories | ⭐⭐ | ⭐ | ← Next |
| 📧 Newsletter | ⭐⭐ | ⭐ | ← Next |
| 💕 Wishlist | ⭐ | ⭐ | Après |
| 🔍 Search advanced | ⭐⭐⭐ | ⭐⭐⭐ | Après |
| 🎁 Promo codes | ⭐⭐ | ⭐ | Après |
| 🤖 Recommendations | ⭐⭐⭐ | ⭐⭐ | Après |

### **TIER 3 : PREMIUM (Semaine 7+)**
| Axe | Impact | Effort | Status |
|-----|--------|--------|--------|
| 👨‍✍️ Author dashboard | ⭐⭐ | ⭐⭐⭐ | Saison 2 |
| 🔄 Subscription model | ⭐⭐⭐ | ⭐⭐⭐ | Saison 2 |
| 📝 Blog & content | ⭐⭐ | ⭐⭐ | Saison 2 |
| 🎧 Audiobooks | ⭐⭐ | ⭐⭐⭐⭐ | Saison 2 |
| 🎬 Live events | ⭐ | ⭐⭐⭐ | Saison 3 |
| 🏪 Marketplace | ⭐⭐⭐ | ⭐⭐⭐⭐ | Saison 3 |
| 🧠 IA features | ⭐⭐ | ⭐⭐ | Saison 3 |

---

## 💼 Roadmap Visuel

```
Week 1-2                Week 3-4                Week 5-6
─────────────────────────────────────────────────────────
FOUNDATION              MATURITY                POLISH & LAUNCH
  │                       │                         │
  ├─ Design/UX            ├─ Reviews               ├─ Recommendations
  ├─ Catalog              ├─ Categories           ├─ Author Dashboard
  ├─ User Pages           ├─ Newsletter           ├─ Analytics
  ├─ Admin Basics         ├─ Wishlist             ├─ SEO Optimization
  ├─ Payment              ├─ Search               ├─ Performance
  └─ Storage              ├─ Promo Codes          └─ Email Automation
                          └─ Polish
```

**➜ LAUNCH v1.0 (end week 6)**

```
Week 7-15               Week 16+                Week 24+
─────────────────────────────────────────────────────────
GROWTH                  ECOSYSTEM               PREMIUM
  │                       │                         │
  ├─ Subscription         ├─ Marketplace          ├─ AI Features
  ├─ Authors Portal       ├─ Community            ├─ Audiobooks
  ├─ Blog/Content         ├─ Advanced Analytics   ├─ Print-on-Demand
  ├─ Audiobooks          ├─ API Public           └─ Enterprise Tier
  ├─ Live Events         └─ Integrations
  └─ Advanced SEO
```

---

## 📱 User Journeys

### **Visiteur → Acheteur (MVP)**
```
Accueil
  ↓ click "Parcourir"
Catalogue [filter/search]
  ↓ click "Détails"
Détail eBook
  ├─ Lire aperçu
  ├─ Voir reviews
  ├─ Click "Acheter"
  │   ↓
  │ Redirect HelloAsso
  │   ↓ pay
  │ Webhook → Mark as paid
  │   ↓
  │ Email confirmation + accès
Mes Ebooks [dashboard]
  ↓ click "Lire"
PDF Reader [full-text]
```

### **User Connecté (Enhanced)**
```
Catalog
  ├─ Filter by category
  ├─ Search full-text
  ├─ Add to wishlist ❤️
  ├─ See personalized recommendations
Dashboard
  ├─ My ebooks
  ├─ My wishlist
  ├─ Purchase history
  ├─ Leave reviews on bought books
Newsletter signup
  ├─ Receive new releases
  ├─ Get promo codes
```

### **Author (Future)**
```
Author Signup
  ↓ Approval
Author Dashboard
  ├─ Upload ebook
  ├─ View stats
  ├─ Track revenue
  ├─ Receive payments
Public Author Profile
  ├─ Bio + ebooks
  ├─ Reviews aggregated
  ├─ Contact form
```

---

## 🔧 Stack Technique Final

### Backend
```
Framework    : Laravel 13
Database     : MySQL 8.0+
Cache        : Redis (optional but recommended)
Queue        : Redis or database
Mail         : Mailgun/SendGrid
Storage      : Local (dev) + S3 (prod)
Auth         : Laravel Breeze + Sanctum (future API)
```

### Frontend
```
Templating   : Blade components
Styling      : Tailwind CSS 4
Interactivity: Alpine.js (lightweight)
Forms        : Laravel Validation + Tailwind Forms plugin
PDF Viewer   : PDF.js
Charts       : Chart.js or Apex Charts (admin)
```

### DevOps
```
Hosting      : Laragon (dev), Linode/DigitalOcean (prod)
CDN          : Cloudflare
SSL          : Let's Encrypt
CI/CD        : GitHub Actions
Monitoring   : Sentry + Plausible Analytics
Backups      : Automated daily
```

---

## 💰 Estimation Budget & Temps

### Timeline
- **Foundation (MVP)** : 2 weeks full-time = 80h
- **Maturity** : 2 weeks full-time = 80h
- **Polish & Launch** : 1 week = 40h
- **Total Phase 1** : 5 weeks = **200h** (1 developer)
  - Part-time (20h/week) : **10 weeks**
  - Team (2x dev) : **5 weeks**

### Coûts Infrastructure (Monthly)
```
Server              : €10-30 (Linode 2GB)
Database            : included or €5-10
CDN                 : €5-20 (Cloudflare)
Email              : €10-30 (Mailgun)
Analytics          : €5-10 (Plausible)
Monitoring         : €20-50 (Sentry)
Storage (S3)       : €2-10 (variable)
─────────────────────────────
TOTAL              : €50-160 / month
```

### Revenue Potential
```
Assumptions:
- 100 ebooks @ €10-20 avg
- 1000 visitors/month → 5-10 purchases/month
- 5% conversion rate

Year 1 Revenue:
- Month 1-3  : €500-1000
- Month 4-6  : €1000-3000
- Month 7-12 : €3000-10000
─────────────────────────────
YEAR 1 TOTAL: €10K-30K (conservative)

ROI: Positive by month 3-4 (if dev cost amortized)
```

---

## 🎯 Success Metrics (First 6 Months)

| Metric | Month 1 | Month 3 | Month 6 |
|--------|---------|---------|---------|
| Monthly Visitors | 500 | 2K | 5K |
| Ebooks Catalog | 20 | 50 | 100+ |
| Monthly Sales | 10 | 50 | 150+ |
| Email Subscribers | 100 | 500 | 2K |
| Avg Order Value | €15 | €17 | €20 |
| Reviews Count | 20 | 150 | 500 |
| Author Signups | 0 | 5 | 20 |

---

## 🚀 Next Steps (Today)

```
[ ] 1. Read ROADMAP.md (full project overview)
[ ] 2. Read DESIGN_SYSTEM.md (components & patterns)
[ ] 3. Read WEEK1_ACTION_PLAN.md (actionable tasks)
[ ] 4. Read IMPROVEMENT_AXES.md (features prioritized)
[ ] 5. Setup git repository with these docs
[ ] 6. Start implementing Week 1 tasks
    [ ] Tailwind config
    [ ] Blade components
    [ ] Migrations
    [ ] Pages public
```

---

## 📚 Documentation Files

| File | Purpose | Audience |
|------|---------|----------|
| **ROADMAP.md** | Full project roadmap (7 phases) | Project managers, devs |
| **DESIGN_SYSTEM.md** | Visual design + Blade components | Designers, frontend devs |
| **WEEK1_ACTION_PLAN.md** | Daily actionable tasks | Frontend devs |
| **IMPROVEMENT_AXES.md** | Detailed features & prioritization | Product managers, stakeholders |
| **THIS FILE** | Executive summary | Everyone |

---

## 🎓 Key Principles

### Design
- ✅ **Sobriété** : Pas d'animations agressives, palette réduite
- ✅ **Éditorial** : Tone de publication, pas marketing-y
- ✅ **Minimaliste** : Whitespace, typographie contrastée
- ✅ **Accessible** : Contrast, keyboard nav, alt text

### Development
- ✅ **Mobile-first** : Design responsive par défaut
- ✅ **Performance** : Lighthouse 90+, assets optimized
- ✅ **Maintainability** : Composants réutilisables, code documented
- ✅ **Security** : HTTPS, CSRF, input validation, rate limiting

### Business
- ✅ **User-centric** : Clear navigation, friction reduction
- ✅ **Scalable** : Database normalized, caching strategy
- ✅ **Measurable** : Analytics on key flows
- ✅ **Revenue-focused** : Conversion optimization, AOV growth

---

## 💬 Questions Fréquentes

**Q: Combien de temps pour lancer ?**  
A: 6 semaines en full-time (1 dev). 12 semaines en part-time.

**Q: Combien ça coûte en infra ?**  
A: €50-160/mois. Très économe pour un SaaS.

**Q: Peut-on ajouter des auteurs plus tard ?**  
A: Oui, phase 2 (semaine 7+). Easy migration.

**Q: Comment gérer les paiements ?**  
A: HelloAsso avec webhooks. Simple et efficace.

**Q: Peut-on upgrade vers subscription plus tard ?**  
A: Oui, phase 2. Architecture pensée pour ça.

**Q: Faut-il du marketing ?**  
A: Phase 3. Phase 1-2 focus sur product. Blog + SEO + email.

---

## ✨ Vision APACC-M

> **"Une plateforme sobre, éditorialement exigeante, inspirée par Narthex. Où se croisent pensée catholique et technologie moderne. Où chaque e-book est un acte de partage du Beau et du Vrai."**

Cette plateforme permettra à APACC-M de :
- 📚 Diffuser largement ses publications
- 💰 Générer revenue récurrent
- 👥 Construire une community engagée
- 🌱 Inviter auteurs et contributeurs
- 🔍 Renforcer autorité éditorialePrêt à transformer cette vision en réalité ? **Allons-y ! 🚀**

---

**Document créé le 12 juin 2025**  
**Prochaine mise à jour : Fin semaine 1 de développement**
