<?php

namespace App\Helpers;

class DocumentHelper
{
    public static function generateCPF(): string
    {
        $cpf = [];
        for ($i = 0; $i < 9; $i++) {
            $cpf[] = rand(0, 9);
        }

        $cpf[] = self::calculateCPFVerifierDigit($cpf);

        $cpf[] = self::calculateCPFVerifierDigit($cpf);

        return implode('', $cpf);
    }

    private static function calculateCPFVerifierDigit($cpf): int
    {
        $length = count($cpf);
        $sum = 0;
        $factor = $length + 1;

        for ($i = 0; $i < $length; $i++) {
            $sum += $cpf[$i] * $factor--;
        }

        $remainder = $sum % 11;
        return $remainder < 2 ? 0 : 11 - $remainder;
    }
}
