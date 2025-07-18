# Système de Gestion de Magasin - Back Office

Un système de gestion complet pour magasin développé avec Laravel et Laravel Nova, permettant la gestion des produits, clients, commandes et stocks.

## Fonctionnalités

### 🏪 Gestion des Produits
- Ajout, modification et suppression de produits
- Gestion des images de produits
- Organisation par catégories
- Suivi des stocks avec alertes automatiques
- Prix et descriptions détaillées

### 👥 Gestion des Clients
- Enregistrement des informations clients (nom, adresse, téléphone, email)
- Modification et suppression des fiches clients
- Historique des commandes par client

### 📦 Gestion des Commandes
- Création de nouvelles commandes
- Association de produits et quantités
- Statuts de commande : En attente, Validée, Livrée
- Mise à jour automatique des stocks lors de la validation
- Calcul automatique des totaux

### 📊 Dashboard Administratif
- Statistiques en temps réel
- Nombre total de commandes
- Chiffre d'affaires
- Produits en stock
- Alertes de stock faible

### 🔐 Authentification Sécurisée
- Interface d'administration protégée
- Authentification par email/mot de passe
- Gestion des sessions sécurisées

## Technologies Utilisées

- **Laravel 12** - Framework PHP
- **Laravel Nova 5** - Interface d'administration
- **MySQL** - Base de données
- **Tailwind CSS** - Interface utilisateur
- **PHP 8.2+** - Langage de programmation

## Installation

### Prérequis
- PHP 8.2 ou supérieur
- Composer
- MySQL
- Node.js et NPM (pour les assets)

### Étapes d'installation

1. **Cloner le projet**
   ```bash
   git clone <url-du-repo>
   cd Todo-list
   ```

2. **Installer les dépendances PHP**
   ```bash
   composer install
   ```

3. **Installer les dépendances Node.js**
   ```bash
   npm install
   ```

4. **Configurer l'environnement**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configurer la base de données**
   Modifiez le fichier `.env` avec vos paramètres de base de données :
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=magasin_db
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Exécuter les migrations**
   ```bash
   php artisan migrate
   ```

7. **Peupler la base de données avec des données de test**
   ```bash
   php artisan db:seed
   ```

8. **Créer le lien symbolique pour le stockage**
   ```bash
   php artisan storage:link
   ```

9. **Compiler les assets (optionnel)**
   ```bash
   npm run build
   ```

10. **Démarrer le serveur de développement**
    ```bash
    php artisan serve
    ```

## Accès à l'application

### Interface d'administration (Nova)
- **URL** : `http://localhost:8000/nova`
- **Email** : `admin@magasin.com`
- **Mot de passe** : `password123`

### Page de connexion personnalisée
- **URL** : `http://localhost:8000/admin/login`

## Structure de la Base de Données

### Tables principales
- **admins** - Administrateurs du système
- **categories** - Catégories de produits
- **clients** - Informations des clients
- **produits** - Catalogue des produits
- **commandes** - Commandes clients
- **commande_produits** - Détails des commandes (pivot)

### Relations
- Un produit appartient à une catégorie
- Un client peut avoir plusieurs commandes
- Une commande peut contenir plusieurs produits
- Les commandes sont liées aux clients

## Fonctionnalités Avancées

### Gestion Automatique des Stocks
- Décrémentation automatique lors de la validation d'une commande
- Alertes visuelles pour les stocks faibles (< 10 unités)
- Prévention des stocks négatifs

### Calculs Automatiques
- Calcul automatique des sous-totaux par produit
- Calcul automatique du total de la commande
- Formatage des prix en euros

### Interface Utilisateur
- Interface responsive avec Tailwind CSS
- Navigation intuitive dans Nova
- Formulaires avec validation
- Messages d'erreur en français

## Développement

### Structure des Modèles
Tous les modèles incluent :
- Relations Eloquent
- Validation des données
- Champs Nova pour l'interface d'administration
- Méthodes utilitaires

### Observateurs
- `CommandeObserver` : Gère automatiquement les mises à jour de stock

### Métriques Nova
- `TotalCommandes` : Nombre total de commandes
- `ChiffreAffaires` : Chiffre d'affaires total
- `ProduitsEnStock` : Nombre de produits en stock
- `AlertesStock` : Produits avec stock faible

## Sécurité

- Authentification sécurisée avec Laravel
- Protection CSRF sur tous les formulaires
- Validation des données côté serveur
- Hachage sécurisé des mots de passe
- Sessions sécurisées

## Maintenance

### Sauvegarde de la base de données
```bash
php artisan backup:run
```

### Nettoyage du cache
```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Mise à jour des dépendances
```bash
composer update
npm update
```

## Support

Pour toute question ou problème :
1. Vérifiez les logs dans `storage/logs/`
2. Consultez la documentation Laravel
3. Vérifiez la configuration de votre environnement

## Déploiement sur O2switch

### Déploiement Rapide
1. **Cloner le projet sur votre serveur O2switch**
   ```bash
   ssh utilisateur@votre-serveur.o2switch.net
   cd www
   git clone https://github.com/votre-username/magasin.git
   cd magasin
   ```

2. **Exécuter le script de déploiement**
   ```bash
   chmod +x deploy-o2switch.sh
   ./deploy-o2switch.sh production
   ```

3. **Configurer la base de données**
   - Créez une base de données MySQL via le panel O2switch
   - Modifiez le fichier `.env` avec vos paramètres de base de données

### Documentation Complète
Consultez le fichier `DEPLOYMENT_O2SWITCH.md` pour un guide détaillé du déploiement sur O2switch.

### Scripts Disponibles
- `deploy-o2switch.sh` - Script de déploiement initial
- `update-o2switch.sh` - Script de mise à jour
- `Envoy.blade.php` - Configuration Envoy pour déploiement automatisé

## Licence

Ce projet est développé dans le cadre d'un projet académique.