<?php

namespace App\Actions\Messenger;

use Str;

class IsTextLinkedMessenger
{
    public function handle(string $text)
    {
        return Str::startsWith($text, env('START_TEXT_COMMAND_FOR_LINKED_ACCOUNT')) && mb_strlen($text) > mb_strlen(env('START_TEXT_COMMAND_FOR_LINKED_ACCOUNT'));
    }
}