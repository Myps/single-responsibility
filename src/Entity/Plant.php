<?php

namespace App\Entity;

class Plant
{
    /**
     * @var string
     */
    private $common;
    /**
     * @var string
     */
    private $botanical;
    /**
     * @var int
     */
    private $zone;
    /**
     * @var string
     */
    private $light;
    /**
     * @var float
     */
    private $price;
    /**
     * @var string
     */
    private $currency;
    /**
     * @var int
     */
    private $availability;

    public function __construct(
        string $common,
        string $botanical,
        int $zone,
        string $light,
        float $price,
        string $currency,
        int $availability)
    {
        $this->common = $common;
        $this->botanical = $botanical;
        $this->zone = $zone;
        $this->light = $light;
        $this->price = $price;
        $this->currency = $currency;
        $this->availability = $availability;
    }

    public function getCommon(): string
    {
        return $this->common;
    }

    public function getBotanical(): string
    {
        return $this->botanical;
    }

    public function getZone(): int
    {
        return $this->zone;
    }

    public function getLight(): string
    {
        return $this->light;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function getAvailability(): int
    {
        return $this->availability;
    }
}