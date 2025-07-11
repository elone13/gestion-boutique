<?php

namespace App\Nova\Metrics;

use App\Models\Commande;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class ChiffreAffaires extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->sum($request, Commande::class, 'total');
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'TODAY' => 'Aujourd\'hui',
            'MTD' => 'Ce mois',
            'QTD' => 'Ce trimestre',
            'YTD' => 'Cette année',
            'ALL' => 'Tout le temps',
        ];
    }

    /**
     * Determine the amount of time the results of the metric should be cached.
     *
     * @return \DateTimeInterface|\DateInterval|float|int|null
     */
    public function cacheFor()
    {
        return now()->addMinutes(5);
    }

    /**
     * Get the URI key for the metric.
     *
     * @return string
     */
    public function uriKey()
    {
        return 'chiffre-affaires';
    }

    /**
     * Format the value for display.
     *
     * @param  mixed  $value
     * @return string
     */
    public function format($value)
    {
        return number_format($value, 2) . ' €';
    }
} 