#!/bin/bash

# Script de setup complet APACC-M eBooks

echo "🚀 Installation APACC-M eBooks Platform"
echo "========================================"

# 1. Installer les dépendances
echo "1️⃣  Installation des dépendances PHP..."
composer install --optimize-autoloader

echo "2️⃣  Installation des dépendances JS..."
npm install

# 2. Configuration .env
if [ ! -f .env ]; then
    echo "3️⃣  Création du fichier .env..."
    cp .env.example .env
    php artisan key:generate
else
    echo "3️⃣  Fichier .env déjà existant"
fi

# 3. Migrations
echo "4️⃣  Exécution des migrations..."
php artisan migrate --force

# 4. Seeders
echo "5️⃣  Initialisation des données..."
php artisan db:seed --class=CategorySeeder

# 5. Storage link
echo "6️⃣  Configuration du storage..."
php artisan storage:link

# 6. Cache
echo "7️⃣  Cache clear..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear

# 7. Build assets
echo "8️⃣  Build des assets..."
npm run build

echo ""
echo "✅ Installation complète !"
echo ""
echo "📝 Prochaines étapes :"
echo "  1. Configurer .env (DB, email, etc.)"
echo "  2. Créer un utilisateur admin via tinker"
echo "  3. Démarrer : php artisan serve"
echo "  4. Visiter : http://localhost:8000"
echo ""
