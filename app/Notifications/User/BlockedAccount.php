<?php

namespace App\Notifications\User;

use App\Actions\User\GetLinkedMessengerUser;
use App\Actions\User\GetMessengerViaForUser;
use App\Channels\Vk\VkMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;

class BlockedAccount extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return (new GetMessengerViaForUser())->handle($notifiable, 'mail');
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('email.blocked_subject'))
            ->line(__('email.blocked_text', ['name' => $notifiable->name]));
    }

    public function toTelegram(object $notifiable): TelegramMessage
    {
        $linked = (new GetLinkedMessengerUser())->handle($notifiable);
        return TelegramMessage::create()
            ->to($linked->user_messenger_id)
            ->line(__('email.blocked_text', ['name' => $notifiable->name]))
            ->button("Перейти на сайт", env('APP_ENV') === 'local' ? env('APP_URL_PRODUCTION_EXAMPLE') : env("APP_URL"));
    }

    public function toVk($notifiable): VkMessage
    {
        $linked = (new GetLinkedMessengerUser())->handle($notifiable);
        return VkMessage::create($linked->user_messenger_id)
            ->line(__('email.blocked_text', ['name' => $notifiable->name]))
            ->buttonLink(env('APP_ENV') === 'local' ? env('APP_URL_PRODUCTION_EXAMPLE') : env("APP_URL"), "Перейти на сайт");
    }
}
