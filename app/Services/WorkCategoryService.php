<?php
namespace App\Services;

use App\Models\WorkCategory;
use Illuminate\Database\Eloquent\Collection;


class WorkCategoryService
{
    public function all(): Collection
    {
        return WorkCategory::all();
    }
}