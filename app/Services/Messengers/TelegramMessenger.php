<?php

namespace App\Services\Messengers;

use App\Contracts\Messengers\TelegramInterface;
use Illuminate\Support\Facades\Http;

/**
 * Summary of TelegramMessenger
 * Этот Сервис предназначен для работы Telegram Бота, не для отправки писем пользователем, для отправок писем мы используем отдельный Telegram Channel
 * Реализован он посредством общения с публичным API телеграма
 */
class TelegramMessenger implements TelegramInterface
{
    protected string $BASE_URL_TG_BOT;

    public function __construct()
    {
        $this->BASE_URL_TG_BOT = "https://api.telegram.org/bot" . env('TELEGRAM_BOT_TOKEN');
    }

    /**
     * Summary of send_message
     * @param array $params
     * Example params - ["chat_id" => 1111, "text" => "Some Text"]
     */
    public function send_message(array $params): void
    {
        try {
            $url = "{$this->BASE_URL_TG_BOT}/sendMessage";
            Http::post($url, [
                'chat_id' => $params['chat_id'],
                'text' => $params['text'],
            ]);
        } catch (\Throwable $th) {
        }
    }

    public function setWebHook(): void
    {
        Http::get("{$this->BASE_URL_TG_BOT}/setWebhook", [
            'url' => env('TELEGRAM_BOT_URL_WEBHOOK'),
        ]);
    }
    public function removeWebHook(): void
    {
        Http::get("{$this->BASE_URL_TG_BOT}/deleteWebhook");
    }

}