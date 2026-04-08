<?php

namespace App\Services;

use Symfony\Component\Panther\Client;

class ScraperService
{
    public function fetchPrice(string $url, string $selector): ?float
    {
        $driverPath = base_path('drivers/chromedriver.exe');
        
        // Seta o binário do Brave via variável de ambiente
        putenv('PANTHER_CHROME_BINARY=C:\\Program Files\\BraveSoftware\\Brave-Browser\\Application\\brave.exe');
        putenv('PANTHER_CHROME_DRIVER_BINARY=' . $driverPath);

        $client = Client::createChromeClient($driverPath, [
            '--headless',
            '--disable-gpu',
            '--no-sandbox',
            '--disable-dev-shm-usage',
            '--window-size=1280,800',
        ]);

        try {
            $crawler = $client->request('GET', $url);
            sleep(5);

            file_put_contents(base_path('debug_page.html'), $crawler->html());

            $elements = $crawler->filter($selector);
            echo "Elementos encontrados: " . $elements->count() . "\n";

            if ($elements->count() === 0) {
                echo "Seletor nao encontrou nada!\n";
                $client->quit();
                return null;
            }

            $text = $elements->first()->text();
            echo "Texto encontrado: " . $text . "\n";

            $price = preg_replace('/[^\d,]/', '', $text);
            $price = str_replace(',', '.', $price);

            $client->quit();
            return (float) $price ?: null;

        } catch (\Exception $e) {
            echo "ERRO: " . $e->getMessage() . "\n";
            file_put_contents(base_path('debug_page.html'), $e->getMessage());
            try { $client->quit(); } catch (\Exception $e) {}
            return null;
        }
    }
}