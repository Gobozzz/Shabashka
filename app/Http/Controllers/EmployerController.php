<?php

namespace App\Http\Controllers;

use App\Http\Requests\Order\MyOrderRequest;
use App\Models\Order;
use App\Models\PaymentPer;
use App\Services\CityService;
use App\Services\OrderService;
use App\Services\PaymentPerService;
use App\Services\WorkCategoryService;
use Illuminate\View\View;

class EmployerController
{
    public function __construct(
        public CityService $cities,
        public WorkCategoryService $work_categories,
        public PaymentPerService $payment_pers,
        public OrderService $orders,
    ) {
    }
    public function profilePage(): View
    {
        return view('pages.employer.profile');
    }
    public function profileEditPage(): View
    {
        $cities = $this->cities->getCitiesByAlphabet();
        return view('pages.employer.profile-edit', compact('cities'));
    }
    public function notificationsPage(): View
    {
        return view('pages.employer.notifications');
    }

    public function createOrderPage(): View
    {
        $cities = $this->cities->getCitiesByAlphabet();
        $payment_pers = $this->payment_pers->all();
        $categories_works = $this->work_categories->all();
        return view('pages.employer.create-order', compact('cities', 'payment_pers', 'categories_works'));
    }

    public function editOrderPage(MyOrderRequest $request, Order $order): View
    {
        $cities = $this->cities->getCitiesByAlphabet();
        $payment_pers = $this->payment_pers->all();
        $categories_works = $this->work_categories->all();
        return view('pages.employer.edit-order', compact('cities', 'payment_pers', 'categories_works', 'order'));
    }

    public function myOrdersPage(): View
    {
        $orders = $this->orders->getOrdersEmployerPaginate(auth()->user()->employer);
        return view('pages.employer.my-orders', compact('orders'));
    }

}