@echo off
echo ========================================
echo RESET COMPLET DE LA BASE DE DONNEES
echo ========================================
echo.

echo 1. Suppression de toutes les tables...
php artisan db:wipe

echo.
echo 2. Exécution de toutes les migrations...
php artisan migrate

echo.
echo 3. Peuplement avec les données de test...
php artisan db:seed

echo.
echo 4. Nettoyage du cache...
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

echo.
echo 5. Optimisation de l'application...
php artisan optimize

echo.
echo ========================================
echo RESET TERMINE AVEC SUCCES !
echo ========================================
echo.
echo Acces a l'application:
echo - Nova: http://localhost:8000/nova
echo - Login: http://localhost:8000/admin/login
echo.
echo Identifiants par defaut:
echo - Email: admin@magasin.com
echo - Mot de passe: password123
echo.
pause 