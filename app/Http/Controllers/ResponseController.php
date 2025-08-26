<?php

namespace App\Http\Controllers;
use App\Http\Requests\Response\StoreRequest;
use App\Models\Order;
use App\Services\ResponseService;
use Illuminate\Http\RedirectResponse;

class ResponseController extends Controller
{
    public function __construct(
        public ResponseService $responses
    ) {
    }

    public function store(StoreRequest $request, Order $order): RedirectResponse
    {
        $worker = auth()->user()->worker;
        if ($this->responses->alreadyTodayResponses($order, $worker)) {
            return redirect()->back()->with('error', 'Сегодня вы уже отправляли отклик на это объявление');
        }
        if ($this->responses->store($order, $worker)) {
            return redirect()->route('orders.show', $order)->with('success', 'Мы успешно отправили ваш отклик');
        }
        return redirect()->back()->with('error', 'Не удалось откликнуться на это объявление');
    }
}
