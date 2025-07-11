<?php

namespace App\Nova\Dashboards;

use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;
use App\Nova\Metrics\TotalCommandes;
use App\Nova\Metrics\ChiffreAffaires;
use App\Nova\Metrics\ProduitsEnStock;
use App\Nova\Metrics\AlertesStock;

class Main extends Dashboard
{
    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new TotalCommandes,
            new ChiffreAffaires,
            new ProduitsEnStock,
            new AlertesStock,
        ];
    }
}
