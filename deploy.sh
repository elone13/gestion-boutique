#!/bin/bash

# Script de déploiement automatisé pour le système de gestion de magasin
# Usage: ./deploy.sh [production|staging]

set -e

ENVIRONMENT=${1:-staging}
PROJECT_NAME="magasin"
DEPLOY_PATH="/var/www/$PROJECT_NAME"

echo "🚀 Déploiement du système de gestion de magasin"
echo "📋 Environnement: $ENVIRONMENT"
echo "📁 Chemin de déploiement: $DEPLOY_PATH"

# Vérifier que l'environnement est valide
if [[ "$ENVIRONMENT" != "production" && "$ENVIRONMENT" != "staging" ]]; then
    echo "❌ Erreur: L'environnement doit être 'production' ou 'staging'"
    exit 1
fi

# Créer le répertoire de déploiement s'il n'existe pas
if [ ! -d "$DEPLOY_PATH" ]; then
    echo "📁 Création du répertoire de déploiement..."
    sudo mkdir -p "$DEPLOY_PATH"
    sudo chown $USER:$USER "$DEPLOY_PATH"
fi

# Aller dans le répertoire de déploiement
cd "$DEPLOY_PATH"

# Sauvegarde de la base de données avant déploiement
echo "💾 Sauvegarde de la base de données..."
if [ -f "artisan" ]; then
    php artisan backup:run --quiet
fi

# Mise à jour du code source
echo "📥 Mise à jour du code source..."
if [ -d ".git" ]; then
    git fetch origin
    git reset --hard origin/main
else
    echo "❌ Erreur: Répertoire Git non trouvé"
    exit 1
fi

# Installation des dépendances PHP
echo "📦 Installation des dépendances PHP..."
composer install --no-dev --optimize-autoloader --no-interaction

# Installation des dépendances Node.js
echo "📦 Installation des dépendances Node.js..."
npm ci --production

# Compilation des assets
echo "🔨 Compilation des assets..."
npm run build

# Configuration de l'environnement
echo "⚙️ Configuration de l'environnement..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate
fi

# Mise à jour de la configuration selon l'environnement
if [ "$ENVIRONMENT" = "production" ]; then
    echo "🏭 Configuration pour la production..."
    sed -i 's/APP_ENV=local/APP_ENV=production/' .env
    sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
    sed -i 's/LOG_LEVEL=debug/LOG_LEVEL=error/' .env
fi

# Exécution des migrations
echo "🗄️ Exécution des migrations..."
php artisan migrate --force

# Nettoyage du cache
echo "🧹 Nettoyage du cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimisation de l'application
echo "⚡ Optimisation de l'application..."
php artisan optimize

# Création du lien symbolique pour le stockage
echo "🔗 Création du lien symbolique..."
if [ ! -L "public/storage" ]; then
    php artisan storage:link
fi

# Définition des permissions
echo "🔐 Définition des permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Redémarrage des services
echo "🔄 Redémarrage des services..."
sudo systemctl reload php8.2-fpm
sudo systemctl reload nginx

# Vérification de la santé de l'application
echo "🏥 Vérification de la santé de l'application..."
if curl -f -s "http://localhost/health" > /dev/null 2>&1; then
    echo "✅ Application déployée avec succès!"
else
    echo "⚠️ Application déployée mais la vérification de santé a échoué"
fi

echo "🎉 Déploiement terminé!"
echo "📊 Dashboard: http://localhost/nova"
echo "📝 Logs: tail -f storage/logs/laravel.log" 