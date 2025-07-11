<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class CommandeProduit extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\CommandeProduit>
     */
    public static $model = \App\Models\CommandeProduit::class;

    /**
     * The single value that should be used to represent the resource when being displayed.
     *
     * @var string
     */
    public static $title = 'id';

    /**
     * The columns that should be searched.
     *
     * @var array
     */
    public static $search = [
        'id', 'commande.id', 'produit.nom',
    ];

    /**
     * Get the fields displayed by the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function fields(NovaRequest $request)
    {
        return [
            ID::make()->sortable(),

            BelongsTo::make('Commande', 'commande', Commande::class)
                ->sortable()
                ->rules('required'),

            BelongsTo::make('Produit', 'produit', Produit::class)
                ->sortable()
                ->rules('required'),

            Number::make('Quantité')
                ->sortable()
                ->rules('required', 'integer', 'min:1'),

            Number::make('Prix Unitaire')
                ->sortable()
                ->rules('required', 'numeric', 'min:0')
                ->step(0.01)
                ->displayUsing(function ($value) {
                    return number_format($value, 2) . ' €';
                }),

            Number::make('Sous Total')
                ->sortable()
                ->step(0.01)
                ->displayUsing(function ($value) {
                    return number_format($value, 2) . ' €';
                }),
        ];
    }

    /**
     * Get the cards available for the request.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function cards(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the filters available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function filters(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the lenses available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function lenses(NovaRequest $request)
    {
        return [];
    }

    /**
     * Get the actions available for the resource.
     *
     * @param  \Laravel\Nova\Http\Requests\NovaRequest  $request
     * @return array
     */
    public function actions(NovaRequest $request)
    {
        return [];
    }
}
