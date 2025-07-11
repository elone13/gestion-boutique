# Déploiement sur O2switch - Système de Gestion de Magasin

Ce guide détaille le processus de déploiement du système de gestion de magasin sur l'hébergeur O2switch.

## Prérequis O2switch

### Compte d'hébergement
- Compte O2switch avec accès SSH activé
- Domaine configuré
- Base de données MySQL créée
- Accès FTP/SFTP

### Versions supportées par O2switch
- PHP 8.2 (recommandé)
- MySQL 8.0
- Composer disponible
- Node.js disponible (pour la compilation des assets)

## Configuration de la Base de Données

### 1. Créer la base de données
Via le panel O2switch :
1. Accédez à votre panel O2switch
2. Allez dans "Bases de données" > "MySQL"
3. Créez une nouvelle base de données
4. Notez les informations de connexion :
   - Nom de la base de données
   - Nom d'utilisateur
   - Mot de passe
   - Serveur MySQL (généralement `localhost`)

### 2. Configuration .env pour O2switch
```env
APP_NAME="Système de Gestion Magasin"
APP_ENV=production
APP_KEY=votre_clé_générée
APP_DEBUG=false
APP_URL=https://votre-domaine.com

LOG_CHANNEL=stack
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=votre_nom_base
DB_USERNAME=votre_utilisateur
DB_PASSWORD=votre_mot_de_passe

BROADCAST_DRIVER=log
CACHE_DRIVER=file
FILESYSTEM_DISK=local
QUEUE_CONNECTION=sync
SESSION_DRIVER=file
SESSION_LIFETIME=120

# Configuration Nova
NOVA_LICENSE_KEY=votre_cle_nova
NOVA_GUARD=admin
NOVA_PASSWORDS=admins
NOVA_STORAGE_DISK=public
```

## Méthodes de Déploiement

### Méthode 1 : Déploiement via Git (Recommandé)

#### 1.1 Préparation du serveur
```bash
# Se connecter en SSH à votre serveur O2switch
ssh utilisateur@votre-serveur.o2switch.net

# Aller dans le répertoire web
cd www

# Cloner le projet
git clone https://github.com/votre-username/magasin.git
cd magasin
```

#### 1.2 Installation des dépendances
```bash
# Installer les dépendances PHP
composer install --no-dev --optimize-autoloader

# Installer les dépendances Node.js
npm install

# Compiler les assets
npm run build
```

#### 1.3 Configuration de l'application
```bash
# Copier le fichier d'environnement
cp .env.example .env

# Générer la clé d'application
php artisan key:generate

# Configurer la base de données dans .env
nano .env
```

#### 1.4 Initialisation de la base de données
```bash
# Exécuter les migrations
php artisan migrate --force

# Peupler avec les données de test
php artisan db:seed --force

# Créer le lien symbolique pour le stockage
php artisan storage:link
```

#### 1.5 Optimisation pour la production
```bash
# Nettoyer le cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimiser l'application
php artisan optimize
```

#### 1.6 Configuration des permissions
```bash
# Définir les bonnes permissions
chmod -R 755 storage bootstrap/cache
chmod -R 755 public/storage
```

### Méthode 2 : Déploiement via FTP

#### 2.1 Préparation locale
```bash
# Installer les dépendances
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Créer le fichier .env
cp .env.example .env
php artisan key:generate
```

#### 2.2 Upload via FTP
1. Utilisez un client FTP (FileZilla, WinSCP)
2. Connectez-vous à votre serveur O2switch
3. Uploadez tous les fichiers dans le répertoire `www/`
4. Excluez les dossiers suivants :
   - `node_modules/`
   - `.git/`
   - `storage/logs/`
   - `storage/framework/cache/`

#### 2.3 Configuration post-upload
```bash
# Se connecter en SSH
ssh utilisateur@votre-serveur.o2switch.net
cd www/magasin

# Configurer la base de données
nano .env

# Exécuter les migrations
php artisan migrate --force
php artisan db:seed --force
php artisan storage:link

# Optimiser
php artisan optimize
```

## Configuration du Serveur Web

### Configuration Apache (.htaccess)
Le fichier `.htaccess` est déjà inclus dans le projet. Assurez-vous qu'il est présent dans le dossier `public/`.

### Configuration Nginx (si applicable)
```nginx
server {
    listen 80;
    server_name votre-domaine.com;
    root /home/utilisateur/www/magasin/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Sécurité

### 1. Protection des fichiers sensibles
```bash
# Protéger le fichier .env
chmod 600 .env

# Protéger les dossiers de stockage
chmod 755 storage
chmod 755 bootstrap/cache
```

### 2. Configuration des permissions
```bash
# Définir le propriétaire correct
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### 3. Variables d'environnement sensibles
- Ne jamais commiter le fichier `.env`
- Utiliser des mots de passe forts pour la base de données
- Configurer `APP_DEBUG=false` en production

## Maintenance et Mises à Jour

### Script de mise à jour automatisé
```bash
#!/bin/bash
# update.sh

echo "🔄 Mise à jour du système de gestion de magasin"

# Sauvegarde de la base de données
php artisan backup:run --quiet

# Mise à jour du code
git pull origin main

# Installation des dépendances
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Nettoyage du cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Exécution des migrations
php artisan migrate --force

# Optimisation
php artisan optimize

echo "✅ Mise à jour terminée"
```

### Sauvegarde automatique
```bash
#!/bin/bash
# backup.sh

DATE=$(date +%Y%m%d_%H%M%S)
BACKUP_DIR="/home/utilisateur/backups"

# Créer le dossier de sauvegarde
mkdir -p $BACKUP_DIR

# Sauvegarde de la base de données
php artisan backup:run --quiet

# Sauvegarde des fichiers uploadés
tar -czf $BACKUP_DIR/uploads_$DATE.tar.gz storage/app/public/

echo "💾 Sauvegarde créée: $DATE"
```

## Dépannage

### Problèmes courants

#### 1. Erreur 500
```bash
# Vérifier les logs
tail -f storage/logs/laravel.log

# Vérifier les permissions
ls -la storage/
ls -la bootstrap/cache/
```

#### 2. Problème de base de données
```bash
# Tester la connexion
php artisan tinker
DB::connection()->getPdo();
```

#### 3. Problème de cache
```bash
# Nettoyer tous les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

#### 4. Problème de permissions
```bash
# Redéfinir les permissions
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### Logs utiles
- **Laravel** : `storage/logs/laravel.log`
- **Apache** : `/var/log/apache2/error.log`
- **PHP** : `/var/log/php8.2-fpm.log`

## Accès à l'Application

### URL d'accès
- **Interface Nova** : `https://votre-domaine.com/nova`
- **Page de connexion** : `https://votre-domaine.com/admin/login`

### Identifiants par défaut
- **Email** : `admin@magasin.com`
- **Mot de passe** : `password123`

⚠️ **Important** : Changez le mot de passe par défaut après le premier déploiement !

## Support O2switch

En cas de problème avec l'hébergement :
- **Support technique** : support@o2switch.net
- **Documentation** : https://www.o2switch.fr/support/
- **Chat en ligne** : Disponible sur le panel client

## Monitoring

### Vérification de la santé de l'application
```bash
# Vérifier que l'application répond
curl -I https://votre-domaine.com

# Vérifier les logs d'erreur
tail -f storage/logs/laravel.log
```

### Alertes de stock
L'application envoie automatiquement des alertes visuelles pour les stocks faibles (< 10 unités) dans l'interface Nova. 