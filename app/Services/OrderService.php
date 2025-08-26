<?php
namespace App\Services;

use App\Enums\StatusOrder;
use App\Filters\CityFilter;
use App\Filters\WorkCategoriesFilter;
use App\Models\Employer;
use App\Models\Order;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class OrderService
{
    public function __construct(
        public CityFilter $cityFilter,
        public WorkCategoriesFilter $workCategoriesFilter,
    ) {
    }

    public function getActiveOrdersPaginate(array $filters): LengthAwarePaginator
    {
        $query = Order::actived();
        $this->filters($query, $filters);
        return $query->orderByDesc('updated_at')->paginate(30);
    }

    public function filters(Builder &$query, array $filters): Builder
    {
        if (isset($filters['city_id'])) {
            $this->cityFilter->handle($query, $filters['city_id']);
        }
        if (isset($filters['categories_selected']) && count($filters['categories_selected']) > 0) {
            $this->workCategoriesFilter->handle($query, $filters['categories_selected']);
        }
        return $query;
    }

    public function getOrdersEmployerPaginate(Employer $employer): LengthAwarePaginator
    {
        return $employer->orders()->orderByDesc('updated_at')->paginate(30)->withQueryString();
    }

    public function create(array $data, Employer $employer): Order|null
    {
        try {
            $order = $employer->orders()->create([
                "title" => $data['title'],
                "content" => $data['content'],
                "images" => $data['images'] ?? null,
                "address" => $data['address'],
                "city_id" => $data['city_id'],
                "payment_type" => $data['payment_type'],
                "price" => $data['price'] ?? null,
                "payment_per" => $data['payment_per'] ?? null,
                "need_count_workers" => $data['need_count_workers'] ?? null,
                "status" => StatusOrder::MODERATION,
            ]);
            if (isset($data['categories_selected']) && count($data['categories_selected']) > 0) {
                $order->workCategories()->sync($data['categories_selected']);
            }
            return $order;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function update(array $data, Order $order): Order|null
    {
        try {
            $order->updateOrFail([
                "title" => $data['title'],
                "content" => $data['content'],
                "images" => $data['images'] ?? null,
                "address" => $data['address'],
                "city_id" => $data['city_id'],
                "payment_type" => $data['payment_type'],
                "price" => $data['price'] ?? null,
                "payment_per" => $data['payment_per'] ?? null,
                "need_count_workers" => $data['need_count_workers'] ?? null,
                "status" => StatusOrder::MODERATION,
            ]);
            if (isset($data['categories_selected']) && count($data['categories_selected']) > 0) {
                $order->workCategories()->sync($data['categories_selected']);
            } else {
                $order->workCategories()->detach();
            }
            return $order;
        } catch (\Throwable $th) {
            return null;
        }
    }

    public function delete(Order $order): bool|null
    {
        return $order->delete();
    }
    public function active(Order $order): bool
    {
        $order->status = StatusOrder::ACTIVE;
        return $order->save();
    }
    public function freez(Order $order): bool
    {
        $order->status = StatusOrder::FREEZ;
        return $order->save();
    }
    public function moderated(Order $order): bool
    {
        $order->status = StatusOrder::MODERATION;
        return $order->save();
    }
    public function unfreez(Order $order): bool
    {
        $order->status = StatusOrder::MODERATION;
        return $order->save();
    }

    public function setAdminMessage(Order $order, string $text): bool
    {
        try {
            $order->adminOrderMessage()->updateOrCreate(
                ['order_id' => $order->getKey()],
                ['text' => $text],
            );
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

}