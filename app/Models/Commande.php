<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Actions\Actionable;

class Commande extends Model
{
    use Actionable;

    protected $fillable = [
        'client_id',
        'date_commande',
        'statut',
        'total',
    ];

    protected $casts = [
        'date_commande' => 'datetime',
        'total' => 'decimal:2',
    ];

    /**
     * Get the client that owns the commande.
     */
    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    /**
     * Get the commande_produits for the commande.
     */
    public function commandeProduits()
    {
        return $this->hasMany(CommandeProduit::class);
    }

    /**
     * Get the produits for the commande.
     */
    public function produits()
    {
        return $this->belongsToMany(Produit::class, 'commande_produits')
                    ->withPivot('quantite', 'prix_unitaire', 'sous_total')
                    ->withTimestamps();
    }

    /**
     * Calculate total of the commande
     */
    public function calculateTotal()
    {
        return $this->commandeProduits()->sum('sous_total');
    }

    /**
     * Update stock when commande is validated
     */
    public function updateStock()
    {
        if ($this->statut === 'Validée') {
            foreach ($this->commandeProduits as $commandeProduit) {
                $produit = $commandeProduit->produit;
                $produit->stock -= $commandeProduit->quantite;
                $produit->save();
            }
        }
    }
}
