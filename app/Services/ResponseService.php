<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Response;
use App\Models\Worker;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class ResponseService
{

    public function __construct(
        public NotificationService $notifications,
    ) {
    }

    public function getPaginate(Order $order): LengthAwarePaginator
    {
        return $order->responses()->orderByDesc('updated_at')->paginate(5);
    }

    public function store(Order $order, Worker $worker): bool
    {
        try {
            $response = $worker->response()->updateOrCreate([
                'order_id' => $order->getKey(),
            ], [
                'worker_id' => $worker->getKey(),
            ]);
            $response->touch();
            $this->notifications->newResponse($response);
            return true;
        } catch (\Throwable $th) {
            return false;
        }
    }

    public function alreadyTodayResponses(Order $order, Worker $worker)
    {
        $response = Response::where('order_id', $order->getKey())->where('worker_id', $worker->getKey())->first();
        if ($response && Carbon::parse($response->updated_at)->isToday()) {
            return true;
        }
        return false;
    }

}