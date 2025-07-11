<?php

namespace App\Nova;

use Laravel\Nova\Fields\ID;
use Laravel\Nova\Fields\DateTime;
use Laravel\Nova\Fields\Select;
use Laravel\Nova\Fields\Number;
use Laravel\Nova\Fields\BelongsTo;
use Laravel\Nova\Fields\HasMany;
use Laravel\Nova\Http\Requests\NovaRequest;
use Laravel\Nova\Resource;

class Commande extends Resource
{
    /**
     * The model the resource corresponds to.
     *
     * @var class-string<\App\Models\Commande>
     */
    public static $model = \App\Models\Commande::class;

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
        'id', 'client.nom', 'statut',
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

            BelongsTo::make('Client', 'client', Client::class)
                ->sortable()
                ->rules('required'),

            DateTime::make('Date Commande', 'date_commande')
                ->sortable()
                ->rules('required'),

            Select::make('Statut')
                ->options([
                    'En attente' => 'En attente',
                    'Validée' => 'Validée',
                    'Livrée' => 'Livrée',
                ])
                ->sortable()
                ->rules('required')
                ->displayUsing(function ($value) {
                    $colors = [
                        'En attente' => 'orange',
                        'Validée' => 'green',
                        'Livrée' => 'blue',
                    ];
                    $color = $colors[$value] ?? 'gray';
                    return '<span style="color: ' . $color . '; font-weight: bold;">' . $value . '</span>';
                })->asHtml(),

            Number::make('Total')
                ->sortable()
                ->step(0.01)
                ->displayUsing(function ($value) {
                    return number_format($value, 2) . ' €';
                }),

            HasMany::make('Commande Produits', 'commandeProduits'),
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
