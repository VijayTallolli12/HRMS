<?php

namespace App\Support;

use App\Models\ApplicationSetting;

class Currency
{
    public static function format(mixed $amount, ?string $currency = null): string
    {
        $currency ??= self::defaultCurrency();
        $amount = (float) ($amount ?? 0);

        return match (strtoupper($currency)) {
            'INR' => '₹ '.self::formatIndian($amount),
            'USD' => '$'.number_format($amount, 2),
            'EUR' => '€'.number_format($amount, 2),
            'GBP' => '£'.number_format($amount, 2),
            default => strtoupper($currency).' '.number_format($amount, 2),
        };
    }

    public static function defaultCurrency(): string
    {
        $value = ApplicationSetting::get('currency', ['raw' => 'INR']);
        $currency = is_array($value) ? ($value['raw'] ?? reset($value)) : $value;

        return $currency ?: 'INR';
    }

    private static function formatIndian(float $amount): string
    {
        $negative = $amount < 0;
        $amount = abs($amount);
        $number = number_format($amount, 2, '.', '');
        [$whole, $decimal] = explode('.', $number);

        if (strlen($whole) > 3) {
            $lastThree = substr($whole, -3);
            $rest = substr($whole, 0, -3);
            $rest = preg_replace('/\B(?=(\d{2})+(?!\d))/', ',', $rest);
            $whole = $rest.','.$lastThree;
        }

        return ($negative ? '-' : '').$whole.'.'.$decimal;
    }
}
