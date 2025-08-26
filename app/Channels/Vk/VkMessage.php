<?php

namespace App\Channels\Vk;


/**
 * Summary of VkMessenger
 * Класс для удобного создания писем в ВК, в виде цепочки методов
 */
class VkMessage
{
    public string $payload_text = "";
    public array $payload_buttons = [];
    public string $user_id;

    public function __construct(string $user_id)
    {
        $this->user_id = $user_id;
    }

    public static function create(string $user_id): self
    {
        return new self($user_id);
    }

    public function line(string $text): static
    {
        $this->payload_text .= "$text\n";
        return $this;
    }

    public function lineIf(bool $condition, string $text): static
    {
        return $condition ? $this->line($text) : $this;
    }

    public function buttonLink(string $url, string $label): static
    {
        $this->payload_buttons = [
            ...$this->payload_buttons,
            [
                [
                    "action" => [
                        "type" => "open_link",
                        "link" => $url,
                        "label" => $label,
                    ]
                ]
            ]
        ];
        return $this;
    }

}