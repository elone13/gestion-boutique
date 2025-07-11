<?php

namespace App\Nova\Metrics;

use App\Models\Produit;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Metrics\Value;

class AlertesStock extends Value
{
    /**
     * Calculate the value of the metric.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return mixed
     */
    public function calculate(NovaRequest $request)
    {
        return $this->count($request, Produit::where('stock', '<', 10));
    }

    /**
     * Get the ranges available for the metric.
     *
     * @return array
     */
    public function ranges()
    {
        return [
            'ALL' => 'Toutes les alertes',
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
        return 'alertes-stock';
    }

    /**
     * Get the displayable name of the metric.
     *
     * @return string
     */
    public function name()
    {
        return 'Alertes Stock Faible';
    }

    /**
     * Format the value for display.
     *
     * @param  mixed  $value
     * @return string
     */
    public function format($value)
    {
        if ($value > 0) {
            return '<span style="color: red; font-weight: bold;">' . $value . '</span>';
        }
        return $value;
    }
} 