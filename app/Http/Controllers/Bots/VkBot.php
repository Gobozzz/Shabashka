<?php

namespace App\Http\Controllers\Bots;

use App\Contracts\Messengers\VkInterface;
use App\Enums\Messengers;
use App\Http\Controllers\Bots\Handlers\HandlerCommandCreateLinked;

define("CALLBACK_API_EVENT_MESSAGE_NEW", 'message_new');
define("CALLBACK_API_EVENT_CONFIRMATION", 'confirmation');

use App\Actions\Messenger\IsTextLinkedMessenger;
use Illuminate\Http\Request;

class VkBot
{
    public function __construct(
        public IsTextLinkedMessenger $isTextLinkedMessenger,
        public VkInterface $vk,
        public HandlerCommandCreateLinked $handlerCommandCreateLinked
    ) {
    }

    public function webhook(Request $request)
    {
        $data = $request->all();
        if ($data['type'] === CALLBACK_API_EVENT_CONFIRMATION) {
            return env('VK_CALLBACK_API_CONFIRMATION_TOKEN');
        }
        if (!isset($data['secret']) || $data['secret'] !== env('VK_SECRET_KEY')) {
            return response()->json("Взрослых дома нету, уходите!", 403);
        }
        if ($data['type'] === CALLBACK_API_EVENT_MESSAGE_NEW) {
            $this->handlerMessage($data);
        }
        return "OK";
    }


    public function handlerMessage(array $data)
    {
        $text = $data['object']['message']['text'];
        $chat_id = $data['object']['message']['peer_id'];
        $get_message_id = $data['object']['message']['id'];
        if ($text === null) {
            return;
        }
        if ($this->isTextLinkedMessenger->handle($text)) { // команда на привязку аккаунта
            $result = $this->handlerCommandCreateLinked->handle($chat_id, $text, Messengers::VK);
            $this->vk->send_message([
                'chat_id' => $chat_id,
                'text' => $result,
                'reply_to' => $get_message_id,
            ]);
            return;
        }
        $this->vk->send_message([
            'chat_id' => $chat_id,
            'text' => "Бот не отвечает на обычные сообщения",
            'reply_to' => $get_message_id,
        ]);
    }

}