<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class WorkCategoriesFilter
{

    public function handle(Builder &$query, array $categories_selected): Builder
    {
        return $query->whereHas('workCategories', function ($subQuery) use ($categories_selected) {
            $subQuery->whereIn('order_work_category.work_category_id', $categories_selected);
        });
    }

}