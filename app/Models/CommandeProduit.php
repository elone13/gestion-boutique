<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Actions\Actionable;

class CommandeProduit extends Model
{
    use Actionable;

    protected $table = 'commande_produits';

    protected $fillable = [
        'commande_id',
        'produit_id',
        'quantite',
        'prix_unitaire',
        'sous_total',
    ];

    protected $casts = [
        'prix_unitaire' => 'decimal:2',
        'sous_total' => 'decimal:2',
    ];

    /**
     * Get the commande that owns the commande_produit.
     */
    public function commande()
    {
        return $this->belongsTo(Commande::class);
    }

    /**
     * Get the produit that owns the commande_produit.
     */
    public function produit()
    {
        return $this->belongsTo(Produit::class);
    }

    /**
     * Calculate sous_total
     */
    public function calculateSousTotal()
    {
        $this->sous_total = $this->quantite * $this->prix_unitaire;
        return $this->sous_total;
    }
}
