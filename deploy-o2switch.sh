#!/bin/bash

# Script de déploiement pour O2switch - Système de Gestion de Magasin
# Usage: ./deploy-o2switch.sh [production|staging]

set -e

ENVIRONMENT=${1:-production}
PROJECT_NAME="magasin"
DEPLOY_PATH="/home/$(whoami)/www/$PROJECT_NAME"

echo "🚀 Déploiement sur O2switch - Système de Gestion de Magasin"
echo "📋 Environnement: $ENVIRONMENT"
echo "📁 Chemin de déploiement: $DEPLOY_PATH"

# Vérifier que l'environnement est valide
if [[ "$ENVIRONMENT" != "production" && "$ENVIRONMENT" != "staging" ]]; then
    echo "❌ Erreur: L'environnement doit être 'production' ou 'staging'"
    exit 1
fi

# Vérifier que nous sommes sur O2switch
if [[ ! -d "/home/$(whoami)/www" ]]; then
    echo "❌ Erreur: Ce script doit être exécuté sur un serveur O2switch"
    exit 1
fi

# Créer le répertoire de déploiement s'il n'existe pas
if [ ! -d "$DEPLOY_PATH" ]; then
    echo "📁 Création du répertoire de déploiement..."
    mkdir -p "$DEPLOY_PATH"
fi

# Aller dans le répertoire de déploiement
cd "$DEPLOY_PATH"

# Sauvegarde de la base de données avant déploiement
echo "💾 Sauvegarde de la base de données..."
if [ -f "artisan" ]; then
    php artisan backup:run --quiet
    echo "✅ Sauvegarde créée"
else
    echo "⚠️ Pas de sauvegarde possible (artisan non trouvé)"
fi

# Mise à jour du code source
echo "📥 Mise à jour du code source..."
if [ -d ".git" ]; then
    git fetch origin
    git reset --hard origin/main
    echo "✅ Code source mis à jour"
else
    echo "❌ Erreur: Répertoire Git non trouvé"
    echo "💡 Utilisez: git clone https://github.com/votre-username/magasin.git"
    exit 1
fi

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
    echo "⚠️ NPM non disponible, compilation des assets ignorée"
fi

# Compilation des assets
echo "🔨 Compilation des assets..."
if command -v npm &> /dev/null; then
    npm run build
    echo "✅ Assets compilés"
else
    echo "⚠️ Compilation des assets ignorée (NPM non disponible)"
fi

# Configuration de l'environnement
echo "⚙️ Configuration de l'environnement..."
if [ ! -f ".env" ]; then
    cp .env.example .env
    php artisan key:generate
    echo "✅ Fichier .env créé"
else
    echo "✅ Fichier .env existe déjà"
fi

# Mise à jour de la configuration selon l'environnement
if [ "$ENVIRONMENT" = "production" ]; then
    echo "🏭 Configuration pour la production..."
    sed -i 's/APP_ENV=local/APP_ENV=production/' .env
    sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env
    sed -i 's/LOG_LEVEL=debug/LOG_LEVEL=error/' .env
    echo "✅ Configuration production appliquée"
fi

# Exécution des migrations
echo "🗄️ Exécution des migrations..."
php artisan migrate --force
echo "✅ Migrations exécutées"

# Nettoyage du cache
echo "🧹 Nettoyage du cache..."
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
echo "✅ Cache nettoyé"

# Optimisation de l'application
echo "⚡ Optimisation de l'application..."
php artisan optimize
echo "✅ Application optimisée"

# Création du lien symbolique pour le stockage
echo "🔗 Création du lien symbolique..."
if [ ! -L "public/storage" ]; then
    php artisan storage:link
    echo "✅ Lien symbolique créé"
else
    echo "✅ Lien symbolique existe déjà"
fi

# Définition des permissions
echo "🔐 Définition des permissions..."
chmod -R 755 storage bootstrap/cache
chmod -R 755 public/storage
chmod 600 .env
echo "✅ Permissions définies"

# Vérification de la configuration de la base de données
echo "🔍 Vérification de la configuration..."
if php artisan tinker --execute="echo 'Connexion DB: ' . (DB::connection()->getPdo() ? 'OK' : 'ERREUR');" 2>/dev/null; then
    echo "✅ Connexion à la base de données OK"
else
    echo "❌ Erreur de connexion à la base de données"
    echo "💡 Vérifiez votre fichier .env"
    exit 1
fi

# Test de l'application
echo "🧪 Test de l'application..."
if curl -f -s "http://localhost/" > /dev/null 2>&1; then
    echo "✅ Application accessible"
else
    echo "⚠️ Test d'accessibilité échoué (normal si pas de domaine configuré)"
fi

echo ""
echo "🎉 Déploiement terminé avec succès!"
echo ""
echo "📊 Accès à l'application:"
echo "   - Interface Nova: https://votre-domaine.com/nova"
echo "   - Page de connexion: https://votre-domaine.com/login"
echo ""
echo "🔑 Identifiants par défaut:"
echo "   - Email: admin@magasin.com"
echo "   - Mot de passe: password123"
echo ""
echo "⚠️ IMPORTANT: Changez le mot de passe par défaut!"
echo ""
echo "📝 Logs: tail -f storage/logs/laravel.log"
echo "🔧 Maintenance: ./update-o2switch.sh" 