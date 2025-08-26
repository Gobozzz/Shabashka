<?php

namespace App\Http\Controllers\Bots\Handlers;

use App\Enums\Messengers;
use App\Services\LinkedMessengerService;
use App\Services\RequestLinkedMessengerService;
use Illuminate\Support\Facades\DB;

class HandlerCommandCreateLinked
{
    public function __construct(
        public RequestLinkedMessengerService $requestLinkedService,
        public LinkedMessengerService $linkedMessengerService,
    ) {
    }
    public function handle(int $chat_id, string $text, Messengers $messenger): string
    {
        $requestLinked = $this->requestLinkedService->getByToken($text);
        if (!$requestLinked) {
            return "Запрос на привязку не найден";
        }
        if ($messenger->value !== $requestLinked->messenger->name) {
            return "Запрос на привязку нужно подтвердить в другом мессенджере";
        }
        // Тут сравнить name пришедший сюда, с name на который завязан requestLinked, у requestLinked прокинь связь для messenger
        if (!$this->requestLinkedService->is_valid($requestLinked)) {
            return "Токен просрочен";
        }
        if ($this->linkedMessengerService->getByUserMessenger($requestLinked->user_id, $requestLinked->messenger_id)) {
            $requestLinked->delete();
            return "Ваш аккаунт уже привязан к профилю на сайте";
        }
        if ($this->linkedMessengerService->getByUserMessengerMessenger($chat_id, $requestLinked->messenger_id)) {
            return "Этот аккаунт {$requestLinked->messenger->name} уже используется";
        }
        // Попытка создания привязки в БД
        DB::beginTransaction();
        try {
            $this->linkedMessengerService->create([
                "user_id" => $requestLinked->user_id,
                "messenger_id" => $requestLinked->messenger_id,
                "user_messenger_id" => $chat_id,
            ]);
            $this->requestLinkedService->remove($requestLinked);
            DB::commit();
            return "Ваш аккаунт привязан к профилю на сайте";
        } catch (\Throwable $th) {
            DB::rollBack();
            return "Не удалось привязать ваш аккаунт к сайту";
        }
    }
}