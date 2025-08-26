<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class CityFilter
{

    public function handle(Builder &$query, int|string $city_id): Builder
    {
        return $query->where('city_id', $city_id);
    }

}