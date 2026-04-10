<?php

namespace App\Services\Drivers;

use Symfony\Component\BrowserKit\HttpBrowser;
use Symfony\Component\HttpClient\HttpClient;

class AmazonDriver implements DriverInterface
{
    public function supports(string $url): bool
    {
        return str_contains($url, 'amazon.com');
    }

    public function fetchPrice(string $url, string $selector): ?float
    {
        try {
            $client = new HttpBrowser(HttpClient::create([
                'headers' => [
                    'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 Chrome/120.0.0.0 Safari/537.36',
                    'Accept-Language' => 'pt-BR,pt;q=0.9',
                    'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
                ],
            ]));

            $crawler = $client->request('GET', $url);

            // Seletores comuns da Amazon Brasil — tenta um por vez
            $selectors = [
                '.a-price-whole',
                '#priceblock_ourprice',
                '#priceblock_dealprice',
                '.a-offscreen',
                '[data-a-color="price"] .a-offscreen',
            ];

            foreach ($selectors as $sel) {
                try {
                    $elements = $crawler->filter($sel);
                    if ($elements->count() > 0) {
                        $text  = $elements->first()->text();
                        $price = preg_replace('/[^\d,]/', '', $text);
                        $price = str_replace(',', '.', $price);
                        $float = (float) $price;
                        if ($float > 0) {
                            logger()->info("AmazonDriver: preço R$ {$float} via seletor {$sel}");
                            return $float;
                        }
                    }
                } catch (\Exception $e) {
                    continue;
                }
            }

            // Se passou selector customizado, tenta ele também
            if ($selector && $selector !== 'auto') {
                try {
                    $text  = $crawler->filter($selector)->first()->text();
                    $price = preg_replace('/[^\d,]/', '', $text);
                    $price = str_replace(',', '.', $price);
                    return (float) $price ?: null;
                } catch (\Exception $e) {}
            }

            logger()->error("AmazonDriver: nenhum seletor funcionou para {$url}");
            return null;

        } catch (\Exception $e) {
            logger()->error("AmazonDriver erro: " . $e->getMessage());
            return null;
        }
    }
}