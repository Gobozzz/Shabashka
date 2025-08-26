<?php
namespace App\Services;

use App\Models\Order;
use App\Models\User;
use App\Models\Worker;
use Illuminate\Database\Eloquent\Collection;

class WorkerService
{

    public function getForNotifyByNewOrder(Order $order): Collection
    {
        $order_work_categories_ids = $order->workCategories->pluck('id')->toArray();
        $users = User::where('city_id', $order->city_id)->whereHas('worker', function ($query) use ($order_work_categories_ids) {
            $query->whereHas('workCategories', function ($query) use ($order_work_categories_ids) {
                $query->whereIn('work_category_worker.work_category_id', $order_work_categories_ids);
            });
        })->get();
        return $users;
    }

    public function updateSubscriptionWorkCategories(array|null $categories, Worker $worker)
    {
        try {
            if (is_null($categories) || count($categories) == 0) {
                $worker->workCategories()->detach();
            } else {
                $worker->workCategories()->sync($categories);
            }
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function getSubscriptionWorkCategories(Worker $worker, bool $only_ids = true): array
    {
        if ($only_ids) {
            return $worker->workCategories()->pluck('work_category_id')->toArray();
        }
        return $worker->workCategories;
    }

}