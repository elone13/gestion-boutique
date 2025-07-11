@servers(['web' => 'user@your-server.com'])

@task('deploy', ['on' => 'web'])
    cd /var/www/magasin
    
    # Mettre à jour le code
    git pull origin main
    
    # Installer les dépendances
    composer install --no-dev --optimize-autoloader
    
    # Nettoyer le cache
    php artisan cache:clear
    php artisan config:clear
    php artisan route:clear
    php artisan view:clear
    
    # Exécuter les migrations
    php artisan migrate --force
    
    # Optimiser l'application
    php artisan optimize
    
    # Redémarrer les services
    sudo systemctl restart php8.2-fpm
    sudo systemctl restart nginx
@endtask

@task('backup', ['on' => 'web'])
    cd /var/www/magasin
    
    # Créer une sauvegarde de la base de données
    php artisan backup:run
    
    # Sauvegarder les fichiers uploadés
    tar -czf /backups/uploads-$(date +%Y%m%d).tar.gz storage/app/public/
@endtask

@task('maintenance', ['on' => 'web'])
    cd /var/www/magasin
    
    # Activer le mode maintenance
    php artisan down --message="Maintenance en cours" --retry=60
    
    # Effectuer les tâches de maintenance
    php artisan migrate --force
    php artisan cache:clear
    
    # Désactiver le mode maintenance
    php artisan up
@endtask

@task('install', ['on' => 'web'])
    cd /var/www/magasin
    
    # Installer les dépendances
    composer install --no-dev --optimize-autoloader
    npm install
    npm run build
    
    # Copier le fichier d'environnement
    cp .env.example .env
    php artisan key:generate
    
    # Créer le lien symbolique
    php artisan storage:link
    
    # Exécuter les migrations et seeders
    php artisan migrate --force
    php artisan db:seed --force
    
    # Optimiser l'application
    php artisan optimize
    
    # Définir les permissions
    sudo chown -R www-data:www-data storage bootstrap/cache
    sudo chmod -R 775 storage bootstrap/cache
@endtask 