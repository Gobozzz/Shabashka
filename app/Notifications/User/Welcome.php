<?php

namespace App\Notifications\User;

use App\Actions\User\GetUrlLkForUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Welcome extends Notification implements ShouldQueue
{
    use Queueable;
    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $link = (new GetUrlLkForUser())->handle($notifiable);
        return (new MailMessage)
            ->subject(__('email.welcome_title'))
            ->line(__('email.welcome_hello', ['name' => $notifiable->name]))
            ->line(__('email.welcome_description'))
            ->action(__('email.welcome_btn_action'), $link)
            ->line(__('email.welcome_btn_thank'));
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
