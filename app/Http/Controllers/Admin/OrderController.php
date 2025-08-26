<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Order\ModerationRequest;
use App\Models\Order;
use App\Services\NotificationService;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;

class OrderController extends Controller
{
    public function __construct(
        public OrderService $orders,
        public NotificationService $notifications,
    ) {
    }
    public function actived(Order $order): RedirectResponse
    {
        if ($this->orders->active($order)) {
            $this->notifications->orderInActiveForEmployer($order);
            $this->notifications->orderInActiveForWorkers($order);
            return redirect()->back()->with('success', 'Объявление успешно активировано');
        }
        return redirect()->back()->with('error', 'Не удалось активировать объявление');
    }
    public function moderated(ModerationRequest $request, Order $order): RedirectResponse
    {
        $data = $request->validated();
        if ($this->orders->moderated($order)) {
            if (isset($data['text'])) {
                $this->orders->setAdminMessage($order, $data['text']);
            }
            $this->notifications->orderInModeration($order, $data['text'] ?? null);
            return redirect()->back()->with('success', 'Объявление успешно отправлено на модерацию');
        }
        return redirect()->back()->with('error', 'Не удалось отправить объявление на модерацию');
    }
}
