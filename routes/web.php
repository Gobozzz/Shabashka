<?php
use App\Http\Controllers\Bots\TelegramBot;
use App\Http\Controllers\Bots\VkBot;
use App\Http\Controllers\EmployerController;
use App\Http\Controllers\MessengerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ResponseController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WorkerController;
use Illuminate\Support\Facades\Route;

// Telegram
Route::post("/telegram/webhook", [TelegramBot::class, 'webhook']);
// VK
Route::post("/vk/webhook", [VkBot::class, 'webhook']);

// Страницы
Route::get('/', fn() => view('pages.guest.welcome'))->name('welcome');
Route::get('/about', fn() => view('pages.guest.about'))->name('about');
Route::get('/how-search-work', fn() => view('pages.guest.how-search-work'))->name('how-search-work');
Route::get('/orders', [OrderController::class, 'index'])->name('orders');
Route::get('/politic', fn() => view('pages.guest.politic'))->name('politic');
Route::get('/rules', fn() => view('pages.guest.rules'))->name('rules');

Route::middleware('auth')->group(function () {
    Route::post('notifications/create/request/{messenger}', [MessengerController::class, 'createRequestLinked'])->name('messengers.create.request');
    Route::post('notifications/linked/{linkedMessenger}/remove', [MessengerController::class, 'removeLinked'])->name('messengers.remove.linked');
    Route::put('notifications/linked/{linkedMessenger}/select', [MessengerController::class, 'selectLinked'])->name('messengers.select.linked');
    Route::put('notifications/linked/{linkedMessenger}/unselect', [MessengerController::class, 'unselectLinked'])->name('messengers.unselect.linked');
    // Обновление пользователя
    Route::put('/users', [UserController::class, 'update'])->name('user.update');
    Route::put('/users/push', [UserController::class, 'updatePush'])->name('user.updatePush');
    Route::delete('/users/push', [UserController::class, 'removePush'])->name('user.removePush');
    // Объявление
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
});

Route::middleware(['auth', 'roles:worker'])->name('worker.')->prefix('worker')->group(function () {
    Route::get('profile', [WorkerController::class, 'profilePage'])->name('profile');
    Route::get('profile/edit', [WorkerController::class, 'profileEditPage'])->name('profile.edit');
    Route::get('notifications', [WorkerController::class, 'notificationsPage'])->name('notifications');
    Route::put('workCategories/subscription', [WorkerController::class, 'updateSubscriptionWorkCategories'])->name('updateSubscriptionWorkCategories');
});

Route::middleware(['auth', 'roles:employer'])->name('employer.')->prefix('employer')->group(function () {
    Route::get('profile', [EmployerController::class, 'profilePage'])->name('profile');
    Route::get('profile/edit', [EmployerController::class, 'profileEditPage'])->name('profile.edit');
    Route::get('notifications', [EmployerController::class, 'notificationsPage'])->name('notifications');
    Route::get('orders/create', [EmployerController::class, 'createOrderPage'])->name('orders.create');
    Route::get('orders/{order}/edit', [EmployerController::class, 'editOrderPage'])->name('orders.edit');
    Route::get('orders', [EmployerController::class, 'myOrdersPage'])->name('orders');
});

Route::middleware(['auth', 'roles:employer'])->group(function () {
    Route::post('orders/download/image', [OrderController::class, 'downloadImages'])->name('orders.downloadImages');
    Route::post('orders', [OrderController::class, 'store'])->name('orders.store');
    Route::put('orders/{order}', [OrderController::class, 'update'])->name('orders.update');
    Route::delete('orders/{order}', [OrderController::class, 'destroy'])->name('orders.destroy');
    Route::put('orders/{order}/freez', [OrderController::class, 'freezing'])->name('orders.freezing');
    Route::put('orders/{order}/unfreez', [OrderController::class, 'unfreezing'])->name('orders.unfreezing');
    Route::get('orders/{order}/responses', [OrderController::class, 'responses'])->name('orders.responses');
});

Route::middleware(['auth', 'roles:worker'])->group(function () {
    Route::post("orders/{order}/response", [ResponseController::class, 'store'])->name('orders.response');
});

require __DIR__ . '/auth.php';
require __DIR__ . '/admin.php';