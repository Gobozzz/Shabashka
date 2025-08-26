<?php

namespace App\Channels\Vk;

use App\Contracts\Messengers\VkInterface;
use Illuminate\Support\Facades\Log;

class VkChannel
{

    public function __construct(
        public VkInterface $vk
    ) {
    }

    public function send($notifiable, $notification)
    {
        try {
            $vk_message = $notification->toVk($notifiable);
            $params = [
                'chat_id' => $vk_message->user_id,
                'text' => $vk_message->payload_text,
            ];
            if (count($vk_message->payload_buttons) > 0) {
                $params['buttons'] = $vk_message->payload_buttons;
            }
            $this->vk->send_message($params);
        } catch (\Throwable $th) {}
    }
}