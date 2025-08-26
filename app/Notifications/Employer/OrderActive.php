<?php

namespace App\Notifications\Employer;

use App\Actions\User\GetLinkedMessengerUser;
use App\Actions\User\GetMessengerViaForUser;
use App\Channels\Vk\VkMessage;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class OrderActive extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Order $order)
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
        return [
            ...(new GetMessengerViaForUser())->handle($notifiable, 'mail'),
            WebPushChannel::class,
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Ваша работа \"{$this->order->title}\" активирована")
            ->line("Ваша работа \"{$this->order->title}\" успешно активирована")
            ->line("Это значит, что на нее смогут откликнуться работники")
            ->action("Перейти на сайт", env('APP_ENV') === 'local' ? env('APP_URL_PRODUCTION_EXAMPLE') : env("APP_URL"));
    }

    public function toTelegram(object $notifiable): TelegramMessage
    {
        $linked = (new GetLinkedMessengerUser())->handle($notifiable);
        return TelegramMessage::create()
            ->to($linked->user_messenger_id)
            ->line("Ваша работа \"{$this->order->title}\" успешно активирована")
            ->line("Это значит, что на нее смогут откликнуться работники")
            ->button("Перейти на сайт", env('APP_ENV') === 'local' ? env('APP_URL_PRODUCTION_EXAMPLE') : env("APP_URL"));
    }

    public function toVk($notifiable): VkMessage
    {
        $linked = (new GetLinkedMessengerUser())->handle($notifiable);
        return VkMessage::create($linked->user_messenger_id)
            ->line("Ваша работа \"{$this->order->title}\" успешно активирована")
            ->line("Это значит, что на нее смогут откликнуться работники")
            ->buttonLink(env('APP_ENV') === 'local' ? env('APP_URL_PRODUCTION_EXAMPLE') : env("APP_URL"), "Перейти на сайт");
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title($notifiable->name . " | Работа активирована")
            ->body("Ваша работа активирована | На нее теперь смогут откликнуться работники")
            ->data(['url' => env('APP_ENV') === 'local' ? env('APP_URL_PRODUCTION_EXAMPLE') : env("APP_URL"), "icon" => config('data_app.web_push_icon_path')]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
