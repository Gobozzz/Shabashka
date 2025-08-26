<?php

namespace App\Helpers;

class CorrectWord
{
    public static function getPeopleWord($number): string
    {
        $lastTwoDigits = $number % 100;
        $lastDigit = $number % 10;

        if ($lastTwoDigits >= 11 && $lastTwoDigits <= 14) {
            return 'человек';
        }

        switch ($lastDigit) {
            case 1:
                return 'человек';
            case 2:
            case 3:
            case 4:
                return 'человека';
            default:
                return 'человек';
        }
    }
}