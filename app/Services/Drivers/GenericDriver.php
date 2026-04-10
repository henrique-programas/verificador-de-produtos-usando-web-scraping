<?php

namespace App\Services\Drivers;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class GenericDriver implements DriverInterface
{
    public function supports(string $url): bool
    {
        return true; // fallback para qualquer site
    }

    public function fetchPrice(string $url, string $selector): ?float
    {
        try {
            $client  = new HttpBrowser(HttpClient::create([
                'headers' => [
                    'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                ],
            ]));

            $crawler = $client->request('GET', $url);
            $text    = $crawler->filter($selector)->first()->text();

            $price = preg_replace('/[^\d,]/', '', $text);
            $price = str_replace(',', '.', $price);

            return (float) $price ?: null;

        } catch (\Exception $e) {
            logger()->error("GenericDriver erro: " . $e->getMessage());
            return null;
        }
    }
}