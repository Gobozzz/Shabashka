<?php
namespace App\Services;

use App\Jobs\NewOrderForWorkersNotify;
use App\Models\Order;
use App\Models\Response;
use App\Models\User;
use App\Notifications\Admin\NewUser;
use App\Notifications\Admin\OrderInModeration;
use App\Notifications\Employer\NewResponse;
use App\Notifications\Employer\OrderActive;
use App\Notifications\Employer\OrderModeration;
use App\Notifications\User\ActiveAccount;
use App\Notifications\User\BlockedAccount;
use App\Notifications\User\ModerationAccount;
use App\Notifications\User\SendPasswordUser;
use App\Notifications\User\Welcome;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Notification;

class NotificationService
{
    public function __construct(
        public WorkerService $workers
    ) {
    }
    public function userModerated(User $user): void
    {
        $user->notify(new ModerationAccount());
    }
    public function userActived(User $user): void
    {
        $user->notify(new ActiveAccount());
    }
    public function userBlocked(User $user): void
    {
        $user->notify(new BlockedAccount());
    }

    public function createNewUserNotify(User $user): void
    {
        event(new Registered($user));
        $user->notify(new Welcome());
        Notification::route('mail', config('data_app.admin_email'))->notify(new NewUser($user));
    }
    public function sendPasswordUser(User $user, string $password): void
    {
        $user->notify(new SendPasswordUser($password));
    }

    public function orderInModeration(Order $order, string|null $text = null): void
    {
        Notification::route('mail', config('data_app.admin_email'))->notify(new OrderInModeration($order));
        $order->employer->user->notify(new OrderModeration($order, $text));
    }
    public function orderInActiveForEmployer(Order $order): void
    {
        $order->employer->user->notify(new OrderActive($order));
    }
    public function orderInActiveForWorkers(Order $order): void
    {
        $users_workers = $this->workers->getForNotifyByNewOrder($order);
        $users_workers->chunk(100)->each(function ($users) use ($order) {
            NewOrderForWorkersNotify::dispatch($users, $order);
        });
    }

    public function newResponse(Response $response)
    {
        $order = $response->order;
        $worker = $response->worker;
        $order->employer->user->notify(new NewResponse($order, $worker));
    }

}