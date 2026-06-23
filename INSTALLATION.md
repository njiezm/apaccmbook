# 🚀 Guide Installation & Déploiement — APACC-M eBooks

## Installation Locale (Développement)

### Prérequis
- PHP 8.3+
- Composer
- Node.js 18+
- MySQL 8.0+
- Git
- Laragon (Windows) ou LEMP stack (Linux/Mac)

### 1. Clone & Setup

```bash
# Clone le repo
git clone <repo-url> apacc-m-ebook
cd apacc-m-ebook

# Installer les dépendances PHP
composer install

# Installer les dépendances JS
npm install

# Créer le fichier .env
cp .env.example .env

# Générer la clé Laravel
php artisan key:generate

# Créer la base de données
mysql -u root -p -e "CREATE DATABASE apacc_m_ebook CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

### 2. Configuration .env

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=apacc_m_ebook
DB_USERNAME=root
DB_PASSWORD=

APP_NAME="APACC-M eBooks"
APP_URL=http://localhost:8000
```

### 3. Migrations & Seeders

```bash
# Exécuter les migrations
php artisan migrate

# Créer les catégories de base (optionnel)
php artisan db:seed --class=CategorySeeder

# Storage symlink
php artisan storage:link
```

### 4. Démarrer le serveur

```bash
# Terminal 1 : Laravel Server
php artisan serve

# Terminal 2 : Vite (build assets en temps réel)
npm run dev
```

**Site accessible** : http://localhost:8000

---

## Configuration Initiale

### Créer un Admin

```bash
# Via tinker
php artisan tinker

# Puis :
$user = App\Models\User::create([
    'name' => 'Admin',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'is_admin' => true,
]);
```

### Créer des Catégories

```php
App\Models\Category::create([
    'name' => 'Théologie',
    'slug' => 'theologie',
    'description' => 'Publications théologiques',
]);
```

### Télécharger un eBook Test

```php
$ebook = App\Models\Ebook::create([
    'title' => 'Test eBook',
    'slug' => 'test-ebook',
    'description' => 'Description test',
    'price' => 9.99,
    'status' => 'published',
    'category_id' => 1,
    'author_id' => 1,
]);
```

---

## Déploiement Production

### Plateforme Recommandée : DigitalOcean / Linode

#### 1. Préparation Serveur

```bash
# SSH au serveur
ssh root@your_server_ip

# Mise à jour système
apt update && apt upgrade -y

# Installer les dépendances
apt install -y php8.3-cli php8.3-fpm php8.3-mysql \
    php8.3-mbstring php8.3-xml php8.3-bcmath \
    composer nodejs npm mysql-server nginx certbot python3-certbot-nginx
```

#### 2. Clone & Configuration

```bash
cd /var/www
git clone <repo-url> apacc-m-ebook
cd apacc-m-ebook

# Installer dependencies
composer install --optimize-autoloader --no-dev
npm ci && npm run build

# Permissions
chown -R www-data:www-data /var/www/apacc-m-ebook
chmod -R 755 /var/www/apacc-m-ebook
chmod -R 775 storage bootstrap/cache
```

#### 3. Configuration .env Production

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://ebooks.apacc-m.fr

DB_CONNECTION=mysql
DB_HOST=localhost
DB_DATABASE=apacc_m_prod
DB_USERNAME=apacc_user
DB_PASSWORD=<strong_password>

CACHE_DRIVER=redis
SESSION_DRIVER=cookie
QUEUE_CONNECTION=database

MAIL_MAILER=mailgun
MAIL_HOST=smtp.mailgun.org
MAIL_PORT=587
MAIL_USERNAME=<mailgun_username>
MAIL_PASSWORD=<mailgun_password>
MAIL_FROM_ADDRESS=noreply@apacc-m.fr
```

#### 4. Migrations Production

```bash
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### 5. Nginx Config

```nginx
# /etc/nginx/sites-available/apacc-m-ebook

server {
    listen 80;
    server_name ebooks.apacc-m.fr;
    
    root /var/www/apacc-m-ebook/public;
    index index.php;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php8.3-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    # Deny access to .env
    location ~ /\.env {
        deny all;
    }
}
```

#### 6. SSL avec Let's Encrypt

```bash
certbot --nginx -d ebooks.apacc-m.fr
```

#### 7. Setup Cronjobs

```bash
# Crontab
crontab -e

# Ajouter :
* * * * * cd /var/www/apacc-m-ebook && php artisan schedule:run >> /dev/null 2>&1
```

#### 8. Monitoring & Logs

```bash
# Logs
tail -f /var/www/apacc-m-ebook/storage/logs/laravel.log

# Status PHP-FPM
systemctl status php8.3-fpm

# Status Nginx
systemctl status nginx
```

---

## Checklist Avant Lancement

- [ ] .env configuré correctement
- [ ] Base de données créée et migrée
- [ ] Admin utilisateur créé
- [ ] Catégories créées
- [ ] Premier eBook uploadé
- [ ] Tests de paiement avec HelloAsso
- [ ] HTTPS activé
- [ ] Backups configurés
- [ ] Monitoring setup (Sentry, Uptime)
- [ ] Email d'envoi fonctionnel

---

## Support & Troubleshooting

### Migration échoue
```bash
php artisan migrate:rollback
php artisan migrate --step=1  # Pour tester une par une
```

### Permissions storage
```bash
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage
```

### Cache stale
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Queue worker
```bash
# Tester
php artisan queue:work

# En production
supervisord config pour persistent queue
```

---

## Documentation Complète

Voir également :
- [ROADMAP.md](ROADMAP.md) - Vue d'ensemble projet
- [DESIGN_SYSTEM.md](DESIGN_SYSTEM.md) - Composants Blade
- [WEEK1_ACTION_PLAN.md](WEEK1_ACTION_PLAN.md) - Tâches prioritaires
- [IMPROVEMENT_AXES.md](IMPROVEMENT_AXES.md) - Features futures

---

**Installation complète : ~2h**  
**Déploiement production : ~3h**

Bon déploiement ! 🚀
