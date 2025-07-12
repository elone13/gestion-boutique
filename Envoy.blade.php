@servers(['web' => 'sc2trpa3376@strategie.o2switch.net'])

@task('deploy')
    cd gestion-boutique
    git pull origin master
    composer install
    php artisan migrate
    php artisan db:seed
@endtask