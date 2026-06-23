# 🌐 Points d'Accès APACC-M eBooks

## 🔴 URLs Publiques

### Accueil & Navigation
- 🏠 **Accueil** : `/`
- 📚 **Catalogue** : `/ebooks`
- 📖 **Détail eBook** : `/ebooks/{slug}`
- 👤 **Mes eBooks** : `/my-ebooks` (authentifié)
- 📖 **Lecteur PDF** : `/ebook/{slug}/read` (authentifié + acheté)

### Pages Informations
- ℹ️ **À Propos** : `/about`
- 📧 **Contact** : `/contact`
- ⚖️ **Conditions Générales** : `/terms`
- 🔒 **Politique Confidentialité** : `/privacy`
- 📋 **Mentions Légales** : `/legal`

### Authentification (Breeze)
- 🔐 **Login** : `/login`
- 📝 **Inscription** : `/register`
- 👤 **Dashboard** : `/dashboard`
- 👤 **Profil** : `/profile`

### Administration
- 🛠️ **Tableau Bord Admin** : `/admin`
- 📚 **Gestion eBooks** : `/admin/ebooks`
- 👥 **Gestion Utilisateurs** : `/admin/users` (future)
- 💰 **Gestion Ventes** : `/admin/sales` (future)

---

## 🏪 Données de Test (Pré-créées)

### Utilisateur Admin
```
Email    : admin@example.com
Password : password123
Role     : admin & author
```

### Catégories (8 pré-créées)
1. Théologie & Spiritualité
2. Liturgie & Sacrements
3. Pensée Catholique
4. Essais & Réflexions
5. Ressources Pédagogiques
6. Spiritualité Pratique
7. Bible & Exégèse
8. Histoire de l'Église

### eBooks de Démonstration (3)
- "Patrimoine en lumière" (€14.99)
- "Odes et visions" (€11.50)
- "Lumière d'appel" (€9.90)

---

## 💻 Terminal Commands

### 🟢 Démarrage Développement

```bash
# Terminal 1 - Laravel Server
cd c:\laragon\www\apacc-m-ebook
php artisan serve

# Terminal 2 - Vite Assets (nouveau terminal)
npm run dev
```

**Site** : http://localhost:8000

### 🔵 Setup Complet (D'abord)

```bash
# Script automatisé
bash setup.sh

# Ou manuel
composer install
npm install
php artisan key:generate
php artisan migrate
php artisan storage:link
npm run build
```

### 🟡 Commandes Utiles

```bash
# Interactive console
php artisan tinker

# Create data
$user = App\Models\User::create([...])
$ebook = App\Models\Ebook::create([...])

# Seeders
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=EbookSeeder

# Migrations
php artisan migrate
php artisan migrate:rollback
php artisan migrate --step=1

# Cache/Config
php artisan cache:clear
php artisan config:clear
php artisan route:clear
```

---

## 📁 Fichiers Importants

### Configuration
- `.env` → Variables d'environnement
- `tailwind.config.js` → Design tokens APACC-M
- `vite.config.js` → Build assets

### Code Application
- `app/Http/Controllers/` → Logique métier
- `app/Models/` → Données & relations
- `database/migrations/` → Schéma base données
- `database/seeders/` → Données initiales
- `routes/web.php` → Routes & URLs
- `resources/views/` → Templates Blade

### Documentation
- `SOLUTION_COMPLETE.md` → Cette solution livrée
- `INSTALLATION.md` → Setup + déploiement
- `DESIGN_SYSTEM.md` → Composants Blade
- `ROADMAP.md` → Planification projet
- `IMPROVEMENT_AXES.md` → Features futures
- `EXECUTIVE_SUMMARY.md` → Vue d'ensemble
- `IMPLEMENTATION_SUMMARY.md` → Ce qui a été fait

---

## 🎨 Accès Au Design

### Composants Blade Réutilisables

```blade
<!-- Container responsive -->
<x-container>
  <!-- Contenu -->
</x-container>

<!-- Séparateur Narthex -->
<x-narthex-line />                      <!-- Simple -->
<x-narthex-line type="double" />        <!-- Double -->

<!-- Cartes -->
<x-arch-card class="...">
  <!-- Contenu avec arche en haut -->
</x-arch-card>

<!-- Boutons -->
<x-button href="/catalog">Découvrir</x-button>
<x-button-secondary href="/contact">Contact</x-button-secondary>

<!-- Pages -->
<x-page-header title="Titre" subtitle="Sous-titre" />

<!-- Navigation -->
<x-navbar />
<x-footer />

<!-- eBooks -->
<x-ebook-card :ebook="$ebook" />
<x-ebook-grid :ebooks="$ebooks" />
```

---

## 🔐 Sécurité & Access

### Routes Publiques
- `/` - Page d'accueil
- `/ebooks` - Catalogue
- `/ebooks/{slug}` - Détail eBook
- `/about`, `/contact`, `/terms`, `/privacy`, `/legal` - Pages info

### Routes Authentifiées
- `/my-ebooks` - Dashboard utilisateur
- `/ebook/{slug}/read` - Lecteur PDF (+ ownership check)
- `/dashboard` - Dashboard Breeze

### Routes Admin
- `/admin` - Dashboard admin
- `/admin/ebooks` - Gestion eBooks
- Middleware : `[auth, can:manage-ebooks]`

---

## 📧 Configuration Email

Pour fonctionner complètement, configurer dans `.env` :

```env
MAIL_MAILER=mailgun
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=your@mailgun.username
MAIL_PASSWORD=your-mailgun-password
MAIL_FROM_ADDRESS=noreply@apacc-m.fr
```

Alternatives :
- SendGrid
- Gmail
- Mailtrap (dev)

---

## 💳 Paiement HelloAsso

Actuellement en mode "redirect vers HelloAsso".

Pour tester :
1. Créer lien HelloAsso pour chaque eBook
2. Ajouter URL dans admin ebook (champ `helloasso_url`)
3. Webhook HelloAsso → `POST /webhook/helloasso` (à implémenter)

---

## 🚀 Déploiement Production

Voir détails complets dans `INSTALLATION.md`.

Résumé :
1. Serveur : DigitalOcean/Linode/OVH
2. OS : Ubuntu 22.04 LTS
3. Stack : PHP 8.3 + MySQL + Nginx
4. Domain : ebooks.apacc-m.fr
5. SSL : Let's Encrypt (gratuit)
6. Email : Mailgun/SendGrid
7. Storage : Local ou S3

---

## 📞 Troubleshooting

### "Migrations ne passent pas"
```bash
# Vérifier la base données existe
mysql -u root -p -e "SHOW DATABASES;"

# Réinitialiser
php artisan migrate:reset
php artisan migrate
```

### "Assets ne se chargent pas"
```bash
php artisan storage:link
npm run build
```

### "Admin inaccessible"
```bash
# Vérifier user est admin
$user = App\Models\User::find(1);
$user->update(['is_admin' => true]);
```

### "PDF ne s'affiche pas"
```bash
# Mettre fichier PDF dans storage/app/public/ebooks/
# S'assurer storage link existe
php artisan storage:link
```

---

## 📚 Resources Complémentaires

- **Laravel Docs** : https://laravel.com/docs
- **Tailwind CSS** : https://tailwindcss.com/docs
- **Blade Components** : https://laravel.com/docs/11.x/blade#components
- **HelloAsso API** : https://dev.helloasso.com

---

## 🎯 Dashboard Rapide

```
🔐 Login
├── Email: admin@example.com
├── Password: password123
└── URL: /login

📊 Statistiques (Admin)
├── URL: /admin
├── Total eBooks: 3
├── Total Users: 1
├── Total Sales: 0
└── Revenue: €0

📚 Catalogue
├── URL: /ebooks
├── Catégories: 8
├── eBooks: 3
└── Filtres: Disponibles

👤 Utilisateur
├── URL: /dashboard
├── Mes eBooks: (selon achats)
└── Profil: Éditable
```

---

## ✨ Prochaines Actions

1. **Immédiat** :
   - [ ] Lancer `php artisan serve` & `npm run dev`
   - [ ] Visiter http://localhost:8000
   - [ ] Tester toutes les pages
   - [ ] Login avec admin

2. **Court terme** :
   - [ ] Importer vos eBooks
   - [ ] Configurer HelloAsso
   - [ ] Tester paiement
   - [ ] Vérifier design

3. **Production** :
   - [ ] Suivre INSTALLATION.md
   - [ ] Setup serveur
   - [ ] Domain & SSL
   - [ ] Email & storage
   - [ ] Deploy code

---

**APACC-M eBooks est LIVE et PRÊTE ! 🎉**

Toutes les URLs, commandes et configs sont ici.  
Documentation complète dans les fichiers Markdown.  
Support : Consulter docs ou tinker en local.

Bon succès ! 🚀✨
