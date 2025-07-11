<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Actions\Actionable;

class Categorie extends Model
{
    use Actionable;

    protected $fillable = [
        'nom',
        'description',
    ];

    /**
     * Get the produits for the categorie.
     */
    public function produits()
    {
        return $this->hasMany(Produit::class);
    }
}
