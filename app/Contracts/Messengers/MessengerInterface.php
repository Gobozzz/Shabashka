<?php

namespace App\Contracts\Messengers;

interface MessengerInterface
{
    // Need Required Params chat_id(user_id), text
    public function send_message(array $params);
}