<?php

namespace App\Http\Controllers;

use App\Http\Requests\Files\DownloadImagesRequest;
use App\Http\Requests\Order\FilterRequest;
use App\Http\Requests\Order\MyOrderRequest;
use App\Http\Requests\Order\FreezOrderRequest;
use App\Http\Requests\Order\ShowRequest;
use App\Http\Requests\Order\StoreOrderRequest;
use App\Http\Requests\Order\UnfreezOrderRequest;
use App\Http\Requests\Order\UpdateOrderRequest;
use App\Models\City;
use App\Models\Order;
use App\Models\WorkCategory;
use App\Services\FileService;
use App\Services\NotificationService;
use App\Services\OrderService;
use App\Services\ResponseService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

class OrderController extends Controller
{
    public function __construct(
        public FileService $fileService,
        public OrderService $orders,
        public NotificationService $notifications,
        public ResponseService $responses,
    ) {
    }

    public function index(FilterRequest $request): View
    {
        $filters = $request->validationData();
        $orders = $this->orders->getActiveOrdersPaginate($filters);
        $filters = [
            'cities' => City::all(),
            'work_categories' => WorkCategory::all(),
        ];
        return view('pages.guest.orders', compact('orders', 'filters'));
    }

    public function show(ShowRequest $request, Order $order)
    {
        return view('pages.guest.order', compact('order'));
    }

    public function responses(MyOrderRequest $request, Order $order)
    {
        $responses = $this->responses->getPaginate($order);
        return view('pages.employer.order-responses', compact('order', 'responses'));
    }

    public function downloadImages(DownloadImagesRequest $request): JsonResponse
    {
        $data = $request->validated();
        $images = $this->fileService->downloadImages($data['images'], 'orders');
        return response()->json($images);
    }

    public function store(StoreOrderRequest $request): RedirectResponse
    {
        $data = $request->validationData();
        $order = $this->orders->create($data, auth()->user()->employer);
        if ($order) {
            $this->notifications->orderInModeration($order);
            return redirect()->back()->with('success', 'Работа успешно создана и отправлена на модерацию, в ближайшие 10 минут будет опубликована');
        }
        return redirect()->back()->with('error', "Не удалось опубликовать работу")->withInput();
    }

    public function update(UpdateOrderRequest $request, Order $order): RedirectResponse
    {
        $data = $request->validationData();
        $order = $this->orders->update($data, $order);
        if ($order) {
            $this->notifications->orderInModeration($order);
            return redirect()->back()->with('success', 'Работа успешно отредактирована и отправлена на модерацию, в ближайшие 10 минут будет опубликована');
        }
        return redirect()->back()->with('error', "Не удалось отредактировать работу")->withInput();
    }

    public function destroy(MyOrderRequest $request, Order $order): RedirectResponse
    {
        if ($this->orders->delete($order)) {
            return redirect()->back()->with('success', 'Работа успешно удалена');
        }
        return redirect()->back()->with('error', 'Не удалось удалить работу');
    }

    public function freezing(FreezOrderRequest $request, Order $order): RedirectResponse
    {
        if ($this->orders->freez($order)) {
            return redirect()->back()->with('success', 'Работа успешно заморожена');
        }
        return redirect()->back()->with('error', 'Не удалось заморозить работу');
    }

    public function unfreezing(UnfreezOrderRequest $request, Order $order): RedirectResponse
    {
        if ($this->orders->unfreez($order)) {
            $this->notifications->orderInModeration($order);
            return redirect()->back()->with('success', 'Работа успешно разморожена и отправлена на модерацию, в ближайшие 10 минут будет опубликована');
        }
        return redirect()->back()->with('error', 'Не удалось разморозить работу');
    }

}
