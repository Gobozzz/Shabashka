<?php

namespace App\Notifications\Employer;

use App\Actions\User\GetLinkedMessengerUser;
use App\Actions\User\GetMessengerViaForUser;
use App\Channels\Vk\VkMessage;
use App\Models\Order;
use App\Models\Worker;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\WebPush\WebPushChannel;
use NotificationChannels\WebPush\WebPushMessage;

class NewResponse extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Order $order, public Worker $worker)
    {
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
            ->subject("{$notifiable->name}, мы получили новый отклик на вашу работу")
            ->line("На вашу работу \"{$this->order->title}\" откликнулись")
            ->line("Кандидат: {$this->worker->user->name}")
            ->line("Его данные для связи:")
            ->line("Телефон: {$this->worker->user->phone}")
            ->line("Почта: {$this->worker->user->email}")
            ->action('Перейти к откликам', route('orders.responses', $this->order));
    }

    public function toTelegram(object $notifiable): TelegramMessage
    {
        $linked = (new GetLinkedMessengerUser())->handle($notifiable);
        return TelegramMessage::create()
            ->to($linked->user_messenger_id)
            ->line("На вашу работу \"{$this->order->title}\" откликнулись")
            ->line("Кандидат: {$this->worker->user->name}")
            ->line("Его данные для связи:")
            ->line("Телефон: {$this->worker->user->phone}")
            ->line("Почта: {$this->worker->user->email}")
            ->button("Перейти к откликам", env('APP_ENV') === 'local' ? env('APP_URL_PRODUCTION_EXAMPLE') : route('orders.responses', $this->order));
    }

    public function toVk($notifiable): VkMessage
    {
        $linked = (new GetLinkedMessengerUser())->handle($notifiable);
        return VkMessage::create($linked->user_messenger_id)
            ->line("На вашу работу \"{$this->order->title}\" откликнулись")
            ->line("Кандидат: {$this->worker->user->name}")
            ->line("Его данные для связи:")
            ->line("Телефон: {$this->worker->user->phone}")
            ->line("Почта: {$this->worker->user->email}")
            ->buttonLink(env('APP_ENV') === 'local' ? env('APP_URL_PRODUCTION_EXAMPLE') : route('orders.responses', $this->order), "Перейти к откликам");
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title("{$notifiable->name} | Новый отклик на вашу работу")
            ->body("{$this->worker->user->name} откликнулся на вашу работу, можете перейти на страницу откликов вашей работы, нажав на текущее уведомление")
            ->data(['url' => env('APP_ENV') === 'local' ? env('APP_URL_PRODUCTION_EXAMPLE') : route('orders.responses', $this->order), "icon" => config('data_app.web_push_icon_path')]);
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
