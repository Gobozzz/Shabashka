<?php

namespace App\Http\Controllers;

use App\Http\Requests\Worker\UpdateSubscriptionWorkCategoryRequest;
use App\Models\WorkCategory;
use App\Services\CityService;
use App\Services\WorkCategoryService;
use App\Services\WorkerService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class WorkerController
{
    public function __construct(
        public WorkerService $workers,
        public CityService $cities,
        public WorkCategoryService $work_categories,
    ) {
    }
    public function profilePage(): View
    {
        return view('pages.worker.profile');
    }
    public function profileEditPage(): View
    {
        $cities = $this->cities->getCitiesByAlphabet();
        return view('pages.worker.profile-edit', compact('cities'));
    }
    public function notificationsPage(): View
    {
        $categories_works = $this->work_categories->all();
        $subscription_categories_works = $this->workers->getSubscriptionWorkCategories(auth()->user()->worker);
        return view('pages.worker.notifications', compact('categories_works', 'subscription_categories_works'));
    }

    public function updateSubscriptionWorkCategories(UpdateSubscriptionWorkCategoryRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($this->workers->updateSubscriptionWorkCategories($data['categories_selected'] ?? null, auth()->user()->worker)) {
            return redirect()->back()->with('success', 'Успешно обновили подписки на категории работ');
        }
        return redirect()->back()->with('error', 'Не удалось обновить подписки на категории работ');
    }

}