<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Nova\Actions\Actionable;

class Client extends Model
{
    use Actionable;

    protected $fillable = [
        'nom',
        'adresse',
        'telephone',
        'email',
    ];

    /**
     * Get the commandes for the client.
     */
    public function commandes()
    {
        return $this->hasMany(Commande::class);
    }
}
