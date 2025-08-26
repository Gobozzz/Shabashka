<?php

namespace App\Http\Controllers;

use App\Http\Requests\User\UpdatePersonalDataRequest;
use App\Services\UserService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class UserController
{
    public function __construct(
        public UserService $users
    ) {
    }

    public function update(UpdatePersonalDataRequest $request): RedirectResponse
    {
        $data = $request->validated();
        if ($this->users->updatePersonalData($data, auth()->user())) {
            return redirect()->back()->with('success-update-personal-data', 'Изменения внесены');
        }
        return redirect()->back()->with('error-update-personal-data', 'Произошла ошибка при изменении данных');
    }

    public function updatePush(Request $request)
    {
        $data = $request->validate([
            'endpoint' => "required|string",
            'auth_token' => "required|string",
            'encoding' => "required|string",
            'public_key' => "required|string",
        ]);
        if ($this->users->updatePushSubscription($data, auth()->user())) {
            return response()->json(['message' => "Успешно подписались на PUSH-уведомления"]);
        }
        return response()->json(['message' => "Не удалось подписаться на PUSH-уведомления"], 400);
    }

    public function removePush(Request $request)
    {
        $data = $request->validate([
            'endpoint' => "required|string",
        ]);
        if ($this->users->removePushSubscription($data['endpoint'], auth()->user())) {
            return response()->json(['message' => "Успешно отписались от PUSH-уведомлений"]);
        }
        return response()->json(['message' => "Не удалось отписаться от PUSH-уведомлений"], 400);
    }

}