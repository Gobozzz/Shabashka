<?php

use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\NotificateController;

Route::middleware('moonshine')->prefix('admin')->name('admin.')->group(function () {
    // Уведомления
    Route::post('/send/message/user/{user}', [NotificateController::class, "sendMessageOneUser"])->name('send.message.user');
    Route::post('/send/message/allUsers', [NotificateController::class, "sendMessageAllUsers"])->name('send.message.allUser');
    // Пользователи
    Route::get('/users/{user}/blocked', [UserController::class, "blocked"])->name('user.blocked');
    Route::get('/users/{user}/actived', [UserController::class, "actived"])->name('user.actived');
    Route::post('/users/{user}/moderated', [UserController::class, "moderated"])->name('user.moderated');
    // Объявления
    Route::get('/orders/{order}/actived', [OrderController::class, "actived"])->name('order.actived');
    Route::post('/orders/{order}/moderated', [OrderController::class, "moderated"])->name('order.moderated');
});
