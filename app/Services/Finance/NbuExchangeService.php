<?php

namespace App\Services\Finance;

class NbuExchangeService
{
    private array $rates = [];

    public function __construct()
    {
        $this->loadRates();
    }

    /**
     * Загружаем курсы НБУ
     */
    private function loadRates(): void
    {
        $json = file_get_contents(
            'https://bank.gov.ua/NBUStatService/v1/statdirectory/exchange?json'
        );

        $data = json_decode($json, true);

        foreach ($data as $item) {
            $this->rates[$item['cc']] = (float) $item['rate'];
        }

        // вручную добавим UAH как базу
        $this->rates['UAH'] = 1.0;
    }

    /**
     * Конвертация в UAH
     */
    public function toUah(int $amount, string $currency): int
    {
        $currency = strtoupper($currency);

        if (!isset($this->rates[$currency])) {
            return $amount; // fallback
        }

        return (int) round($amount * $this->rates[$currency]);
    }

    /**
     * Получить курс
     */
    public function getRate(string $currency): float
    {
        return $this->rates[strtoupper($currency)] ?? 1.0;
    }
}
