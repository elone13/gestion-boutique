<?php

namespace App\Observers;

use App\Models\Commande;

class CommandeObserver
{
    /**
     * Handle the Commande "updated" event.
     */
    public function updated(Commande $commande): void
    {
        // Si le statut a changé vers "Validée", mettre à jour les stocks
        if ($commande->wasChanged('statut') && $commande->statut === 'Validée') {
            $this->updateStock($commande);
        }
    }

    /**
     * Mettre à jour les stocks des produits de la commande
     */
    private function updateStock(Commande $commande): void
    {
        foreach ($commande->commandeProduits as $commandeProduit) {
            $produit = $commandeProduit->produit;
            $nouveauStock = $produit->stock - $commandeProduit->quantite;
            
            if ($nouveauStock >= 0) {
                $produit->update(['stock' => $nouveauStock]);
            }
        }
    }
} 