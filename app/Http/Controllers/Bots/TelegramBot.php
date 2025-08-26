<?php
namespace App\Http\Controllers\Bots;

use App\Actions\Messenger\IsTextLinkedMessenger;
use App\Contracts\Messengers\TelegramInterface;
use App\Enums\Messengers;
use App\Http\Controllers\Bots\Handlers\HandlerCommandCreateLinked;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TelegramBot extends Controller
{
    public function __construct(
        public TelegramInterface $telegram,
        public IsTextLinkedMessenger $isTextLinkedMessenger,
        public HandlerCommandCreateLinked $handlerCommandCreateLinked
    ) {
    }
    public function webhook(Request $request): string
    {
        $update = $request->all();
        if (!isset($update['message']['text']) || !isset($update['message']['chat']['id'])) {
            return "OK";
        }
        $text = $update['message']['text'];
        $chat_id = $update['message']['chat']['id'];
        if ($this->isTextLinkedMessenger->handle($text)) { // команда на создание привязки
            $result = $this->handlerCommandCreateLinked->handle($chat_id, $text, Messengers::TELEGRAM); // тут передать name Мессенджера
            $this->telegram->send_message([
                'chat_id' => $chat_id,
                'text' => $result,
            ]);
        } else {
            $this->telegram->send_message([
                'chat_id' => $chat_id,
                'text' => "Команда не распознана",
            ]);
        }
        return "OK";
    }

    public function setWebHook()
    {
        $this->telegram->setWebHook();
    }
    public function removeWebHook()
    {
        $this->telegram->removeWebHook();
    }
}
