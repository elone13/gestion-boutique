<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Actions\Actionable;

class Produit extends Model
{
    use Actionable;

    protected $fillable = [
        'nom',
        'description',
        'prix',
        'stock',
        'categorie_id',
        'image_url',
    ];

    protected $casts = [
        'prix' => 'decimal:2',
    ];

    /**
     * Get the categorie that owns the produit.
     */
    public function categorie()
    {
        return $this->belongsTo(Categorie::class);
    }

    /**
     * Get the commande_produits for the produit.
     */
    public function commandeProduits()
    {
        return $this->hasMany(CommandeProduit::class);
    }

    /**
     * Check if stock is low (less than 10 items)
     */
    public function isStockLow()
    {
        return $this->stock < 10;
    }
}
