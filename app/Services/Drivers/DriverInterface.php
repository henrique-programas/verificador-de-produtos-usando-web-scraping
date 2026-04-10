<?php

namespace App\Services\Drivers;

interface DriverInterface
{
    public function supports(string $url): bool;
    public function fetchPrice(string $url, string $selector): ?float;
}