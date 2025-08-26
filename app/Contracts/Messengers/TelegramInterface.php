<?php

namespace App\Contracts\Messengers;

interface TelegramInterface extends MessengerInterface
{
    public function setWebHook();
    public function removeWebHook();
}