<?php

namespace App\Jobs;

use App\Models\Order;
use App\Notifications\Worker\NewOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Notification;

class NewOrderForWorkersNotify implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public $users, public Order $order)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Notification::send($this->users, new NewOrder($this->order));
        } catch (\Throwable $th) {
        }
    }
}
