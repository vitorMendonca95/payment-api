<?php

namespace App\Helpers;

class CustomHelper
{
    public static function generatePassword($length = 9): string
    {
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $numbers = '0123456789';
        $specialCharacters = '!@#$%^&*()-_=+<>?';

        $password = '';

        $password .= $uppercase[rand(0, strlen($uppercase) - 1)];
        $password .= $lowercase[rand(0, strlen($lowercase) - 1)];
        $password .= $numbers[rand(0, strlen($numbers) - 1)];
        $password .= $specialCharacters[rand(0, strlen($specialCharacters) - 1)];

        $remainingLength = $length - 4;

        $allCharacters = $uppercase . $lowercase . $numbers . $specialCharacters;

        for ($i = 0; $i < $remainingLength; $i++) {
            $password .= $allCharacters[rand(0, strlen($allCharacters) - 1)];
        }

        return str_shuffle($password);
    }
}
