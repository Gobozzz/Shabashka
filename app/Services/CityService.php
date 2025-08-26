<?php
namespace App\Services;

use App\Models\City;
use Illuminate\Database\Eloquent\Collection;

class CityService
{
    public function getCitiesByAlphabet(string $order = "asc"): Collection
    {
        return City::orderBy('name', $order)->get();
    }
}