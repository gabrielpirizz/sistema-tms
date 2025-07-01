<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class ValidCpf implements ValidationRule
{

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!self::isValid($value)) {
            $fail('O CPF informado é inválido.');
        }
    }

    public static function isValid(?string $cpf): bool
    {
        if (empty($cpf)) {
            return false;
        }

        $cpf = self::sanitize($cpf);
        
        if (strlen($cpf) !== 11) {
            return false;
        }
        
        if (preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }
        
        return true;
    }

    public static function sanitize(?string $cpf): ?string
    {
        if (empty($cpf)) {
            return null;
        }
        
        return preg_replace('/[^0-9]/', '', $cpf);
    }

    public static function format(?string $cpf): ?string
    {
        $sanitized = self::sanitize($cpf);
        
        if (empty($sanitized) || strlen($sanitized) !== 11) {
            return $sanitized;
        }
        
        return substr($sanitized, 0, 3) . '.' . 
               substr($sanitized, 3, 3) . '.' . 
               substr($sanitized, 6, 3) . '-' . 
               substr($sanitized, 9, 2);
    }
} 