<?php

namespace App\Enums;

enum PaymentType: string
{
    case FIXED = "Фиксированная";
    case DOGOVOR = "Договорная";
}
