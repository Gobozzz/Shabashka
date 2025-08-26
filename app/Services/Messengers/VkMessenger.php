<?php

namespace App\Services\Messengers;
use App\Contracts\Messengers\VkInterface;
use Illuminate\Support\Facades\Http;


class VkMessenger implements VkInterface
{
    public function send_message(array $params)
    {
        try {
            $data = [
                'access_token' => env('VK_ACCESS_TOKEN'),
                'peer_id' => $params['chat_id'],
                'message' => $params['text'],
                'random_id' => rand(1000, 2147483000),
                'v' => env('VK_API_VERSION'),
            ];
            if (isset($params['reply_to'])) {
                $data['reply_to'] = $params['reply_to'];
            }
            if (isset($params['buttons'])) {
                $data['keyboard'] = json_encode([
                    'buttons' => $params['buttons'],
                ]);
            }
            Http::get(env('VK_API_URL') . "messages.send", $data);
        } catch (\Throwable $th) {
        }
    }
}