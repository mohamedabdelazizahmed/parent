<?php

namespace App;

use App\Services\ProviderService;

trait Filterable
{
    /**
     * Filter a result set.
     *
     * @param  Builder      $query
     * @param  QueryFilters $filters
     * @return Builder
     */
    public static function Filter( QueryFilter $filters, ProviderService $providerService)
    {
        return $filters->apply($providerService);
    }
}