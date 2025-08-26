<?php

namespace App\Jobs;

use App\Models\User;
use App\Notifications\Admin\SendMessageUser;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class SendNotificationUsers implements ShouldQueue
{
    use Queueable;

    protected array $userIds; // список ID пользователей
    protected string $message; // сообщение для отправки
    /**
     * Create a new job instance.
     */
    public function __construct(array $userIds, string $message)
    {
        $this->userIds = $userIds;
        $this->message = $message;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            $users = User::whereIn('id', $this->userIds)->get();
            Notification::send($users, new SendMessageUser($this->message));
        } catch (\Throwable $th) {
        }
    }
}
