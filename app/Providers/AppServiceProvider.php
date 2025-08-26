<?php

namespace App\Providers;
use App\Channels\Vk\VkChannel;
use App\Contracts\Messengers\TelegramInterface;
use App\Contracts\Messengers\VkInterface;
use App\Models\LinkedMessenger;
use App\Policies\LinkedMessengerPolicy;
use App\Services\Messengers\TelegramMessenger;
use App\Services\Messengers\VkMessenger;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\Notification;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(TelegramInterface::class, TelegramMessenger::class);
        $this->app->bind(VkInterface::class, VkMessenger::class);
        Notification::extend('vk', function ($app) {
            return new VkChannel(new VkMessenger());
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::policy(LinkedMessenger::class, LinkedMessengerPolicy::class);
    }
}
