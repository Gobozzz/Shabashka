<?php

namespace App\Notifications\Worker;

use App\Actions\User\GetLinkedMessengerUser;
use App\Actions\User\GetMessengerViaForUser;
use App\Channels\Vk\VkMessage;
use App\Enums\PaymentType;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;

class NewOrder extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Order $order)
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
            WebPushChannel::class
        ];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("Новый заказ из вашей категории")
            ->line("На биржу поступил новый заказ, откликнитесь на него первым!")
            ->line("Работа: {$this->order->title}")
            ->line("Оплата: {$this->order->payment_type}")
            ->lineIf($this->order->payment_type === PaymentType::FIXED->value, "Объем оплаты: {$this->order->price} руб {$this->order->payment_per}")
            ->lineIf((bool) $this->order->need_count_workers, "Нужное количество людей: {$this->order->need_count_workers}")
            ->action('Перейти к заказу', route('orders.show', $this->order->getKey()));
    }

    public function toTelegram(object $notifiable): TelegramMessage
    {
        $linked = (new GetLinkedMessengerUser())->handle($notifiable);
        return TelegramMessage::create()
            ->to($linked->user_messenger_id)
            ->line("На биржу поступил новый заказ, откликнитесь на него первым!")
            ->line("Работа: {$this->order->title}")
            ->line("Оплата: {$this->order->payment_type}")
            ->lineIf($this->order->payment_type === PaymentType::FIXED->value, "Объем оплаты: {$this->order->price} руб {$this->order->payment_per}")
            ->lineIf((bool) $this->order->need_count_workers, "Нужное количество людей: {$this->order->need_count_workers}")
            ->button('Перейти к заказу', env('APP_ENV') === 'local' ? env('APP_URL_PRODUCTION_EXAMPLE') : route('orders.show', $this->order->getKey()));
    }

    public function toVk($notifiable): VkMessage
    {
        $linked = (new GetLinkedMessengerUser())->handle($notifiable);
        return VkMessage::create($linked->user_messenger_id)
            ->line("На биржу поступил новый заказ, откликнитесь на него первым!")
            ->line("Работа: {$this->order->title}")
            ->line("Оплата: {$this->order->payment_type}")
            ->lineIf($this->order->payment_type === PaymentType::FIXED->value, "Объем оплаты: {$this->order->price} руб {$this->order->payment_per}")
            ->lineIf((bool) $this->order->need_count_workers, "Нужное количество людей: {$this->order->need_count_workers}")
            ->buttonLink(env('APP_ENV') === 'local' ? env('APP_URL_PRODUCTION_EXAMPLE') : route('orders.show', $this->order->getKey()), 'Перейти к заказу');
    }

    public function toWebPush($notifiable, $notification): WebPushMessage
    {
        return (new WebPushMessage)
            ->title("{$this->order->title} | " . config('app.name'))
            ->body("Поступил новый заказ на биржу из вашей категории | Откликнитесь на него первым, чтобы увеличить шансы на получение работы")
            ->data(['url' => env('APP_ENV') === 'local' ? env('APP_URL_PRODUCTION_EXAMPLE') : route('orders.show', $this->order->getKey()), "icon" => config('data_app.web_push_icon_path')]);
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
