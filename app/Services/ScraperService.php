<?php

namespace App\Services;

use App\Services\Drivers\AmazonDriver;
use App\Services\Drivers\GenericDriver;
use App\Services\Drivers\DriverInterface;

class ScraperService
{
    private array $drivers;

    public function __construct()
    {
        // Ordem importa: drivers específicos antes do genérico
        $this->drivers = [
            new AmazonDriver(),
            new GenericDriver(),
        ];
    }

    public function fetchPrice(string $url, string $selector): ?float
    {
        $driver = $this->resolveDriver($url);

        logger()->info("ScraperService: usando " . class_basename($driver) . " para {$url}");

        return $driver->fetchPrice($url, $selector);
    }

    private function resolveDriver(string $url): DriverInterface
    {
        foreach ($this->drivers as $driver) {
            if ($driver->supports($url)) {
                return $driver;
            }
        }

        return new GenericDriver();
    }

    public function getAvailableDrivers(): array
    {
        return array_map(fn($d) => class_basename($d), $this->drivers);
    }
}