#!/bin/bash

# Script de mise à jour pour O2switch - Système de Gestion de Magasin
# Usage: ./update-o2switch.sh

set -e

PROJECT_NAME="magasin"
DEPLOY_PATH="/home/$(whoami)/www/$PROJECT_NAME"

echo "🔄 Mise à jour du système de gestion de magasin sur O2switch"
echo "📁 Chemin: $DEPLOY_PATH"

# Vérifier que nous sommes sur O2switch
if [[ ! -d "/home/$(whoami)/www" ]]; then
    echo "❌ Erreur: Ce script doit être exécuté sur un serveur O2switch"
    exit 1
fi

# Aller dans le répertoire de déploiement
cd "$DEPLOY_PATH"

# Vérifier que le projet existe
if [ ! -f "artisan" ]; then
    echo "❌ Erreur: Projet Laravel non trouvé dans $DEPLOY_PATH"
    exit 1
fi

# Sauvegarde de la base de données
echo "💾 Sauvegarde de la base de données..."
php artisan backup:run --quiet
echo "✅ Sauvegarde créée"

# Mise à jour du code source
echo "📥 Mise à jour du code source..."
git fetch origin
git reset --hard origin/main
echo "✅ Code source mis à jour"

# Installation des dépendances PHP
echo "📦 Installation des dépendances PHP..."
composer install --no-dev --optimize-autoloader --no-interaction
echo "✅ Dépendances PHP installées"

# Installation des dépendances Node.js
echo "📦 Installation des dépendances Node.js..."
if command -v npm &> /dev/null; then
    npm ci --production
    echo "✅ Dépendances Node.js installées"
else
    echo "⚠️ NPM non disponible"
fi

# Compilation des assets
echo "🔨 Compilation des assets..."
if command -v npm &> /dev/null; then
    npm run build
    echo "✅ Assets compilés"
else
    echo "⚠️ Compilation des assets ignorée"
fi

# Nettoyage du cache
echo "🧹 Nettoyage du cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo "✅ Cache nettoyé"

# Exécution des migrations
echo "🗄️ Exécution des migrations..."
php artisan migrate --force
echo "✅ Migrations exécutées"

# Optimisation de l'application
echo "⚡ Optimisation de l'application..."
php artisan optimize
echo "✅ Application optimisée"

# Vérification de la configuration
echo "🔍 Vérification de la configuration..."
if php artisan tinker --execute="echo 'Connexion DB: ' . (DB::connection()->getPdo() ? 'OK' : 'ERREUR');" 2>/dev/null; then
    echo "✅ Connexion à la base de données OK"
else
    echo "❌ Erreur de connexion à la base de données"
    exit 1
fi

echo ""
echo "✅ Mise à jour terminée avec succès!"
echo "📊 Application accessible sur: https://votre-domaine.com/nova" 