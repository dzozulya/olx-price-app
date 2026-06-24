<?php

namespace App\Services\Olx;

class OlxClient
{
    public function getHtml(string $url): ?string
    {
        try {
            return file_get_contents($url) ?: null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
