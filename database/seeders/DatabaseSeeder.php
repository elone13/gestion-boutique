<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use App\Models\Categorie;
use App\Models\Client;
use App\Models\Produit;
use App\Models\Commande;
use App\Models\CommandeProduit;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Créer un administrateur
        Admin::create([
            'nom' => 'Administrateur',
            'email' => 'admin@magasin.com',
            'password' => Hash::make('password123'),
        ]);

        // Créer des catégories
        $categories = [
            ['nom' => 'Électronique', 'description' => 'Produits électroniques et informatiques'],
            ['nom' => 'Vêtements', 'description' => 'Vêtements et accessoires'],
            ['nom' => 'Livres', 'description' => 'Livres et publications'],
            ['nom' => 'Sport', 'description' => 'Équipements et vêtements de sport'],
        ];

        foreach ($categories as $categorie) {
            Categorie::create($categorie);
        }

        // Créer des clients
        $clients = [
            [
                'nom' => 'Jean Dupont',
                'adresse' => '123 Rue de la Paix, 75001 Paris',
                'telephone' => '01 23 45 67 89',
                'email' => 'jean.dupont@email.com',
            ],
            [
                'nom' => 'Marie Martin',
                'adresse' => '456 Avenue des Champs, 69000 Lyon',
                'telephone' => '04 56 78 90 12',
                'email' => 'marie.martin@email.com',
            ],
            [
                'nom' => 'Pierre Durand',
                'adresse' => '789 Boulevard Central, 13000 Marseille',
                'telephone' => '04 91 23 45 67',
                'email' => 'pierre.durand@email.com',
            ],
        ];

        foreach ($clients as $client) {
            Client::create($client);
        }

        // Créer des produits
        $produits = [
            [
                'nom' => 'Smartphone Galaxy S23',
                'description' => 'Smartphone Samsung Galaxy S23 avec écran 6.1" et appareil photo 50MP',
                'prix' => 899.99,
                'stock' => 25,
                'categorie_id' => 1,
            ],
            [
                'nom' => 'Laptop Dell Inspiron',
                'description' => 'Ordinateur portable Dell Inspiron 15" avec processeur Intel i7',
                'prix' => 1299.99,
                'stock' => 15,
                'categorie_id' => 1,
            ],
            [
                'nom' => 'T-shirt Cotton',
                'description' => 'T-shirt en coton bio, taille M, couleur bleue',
                'prix' => 29.99,
                'stock' => 50,
                'categorie_id' => 2,
            ],
            [
                'nom' => 'Jeans Classic',
                'description' => 'Jeans classique, taille 32/32, couleur bleu marine',
                'prix' => 79.99,
                'stock' => 30,
                'categorie_id' => 2,
            ],
            [
                'nom' => 'Livre Laravel Guide',
                'description' => 'Guide complet Laravel pour développeurs',
                'prix' => 49.99,
                'stock' => 20,
                'categorie_id' => 3,
            ],
            [
                'nom' => 'Ballon de Football',
                'description' => 'Ballon de football professionnel, taille 5',
                'prix' => 39.99,
                'stock' => 8,
                'categorie_id' => 4,
            ],
            [
                'nom' => 'Raquette de Tennis',
                'description' => 'Raquette de tennis professionnelle avec cordage',
                'prix' => 159.99,
                'stock' => 5,
                'categorie_id' => 4,
            ],
        ];

        foreach ($produits as $produit) {
            Produit::create($produit);
        }

        // Créer des commandes
        $commandes = [
            [
                'client_id' => 1,
                'date_commande' => now()->subDays(5),
                'statut' => 'Validée',
                'total' => 0,
            ],
            [
                'client_id' => 2,
                'date_commande' => now()->subDays(3),
                'statut' => 'En attente',
                'total' => 0,
            ],
            [
                'client_id' => 3,
                'date_commande' => now()->subDays(1),
                'statut' => 'Livrée',
                'total' => 0,
            ],
        ];

        foreach ($commandes as $commande) {
            Commande::create($commande);
        }

        // Créer des commande_produits
        $commandeProduits = [
            [
                'commande_id' => 1,
                'produit_id' => 1,
                'quantite' => 1,
                'prix_unitaire' => 899.99,
                'sous_total' => 899.99,
            ],
            [
                'commande_id' => 1,
                'produit_id' => 3,
                'quantite' => 2,
                'prix_unitaire' => 29.99,
                'sous_total' => 59.98,
            ],
            [
                'commande_id' => 2,
                'produit_id' => 2,
                'quantite' => 1,
                'prix_unitaire' => 1299.99,
                'sous_total' => 1299.99,
            ],
            [
                'commande_id' => 3,
                'produit_id' => 5,
                'quantite' => 1,
                'prix_unitaire' => 49.99,
                'sous_total' => 49.99,
            ],
            [
                'commande_id' => 3,
                'produit_id' => 6,
                'quantite' => 1,
                'prix_unitaire' => 39.99,
                'sous_total' => 39.99,
            ],
        ];

        foreach ($commandeProduits as $commandeProduit) {
            CommandeProduit::create($commandeProduit);
        }

        // Mettre à jour les totaux des commandes
        $commandes = Commande::all();
        foreach ($commandes as $commande) {
            $total = $commande->commandeProduits()->sum('sous_total');
            $commande->update(['total' => $total]);
        }
    }
}
