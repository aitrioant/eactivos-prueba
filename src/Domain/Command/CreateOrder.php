<?php

namespace App\Domain\Command;

class CreateOrder
{

    /** @var string */
    private $selectedFood;
    /** @var float */
    private $money;
    /** @var int */
    private $drinks;
    /** @var bool */
    private $isDelivery;

    /**
     * @return string
     */
    public function getSelectedFood(): string
    {
        return $this->selectedFood;
    }

    /**
     * @param string $selectedFood
     */
    public function setSelectedFood(string $selectedFood): void
    {
        $this->selectedFood = $selectedFood;
    }

    /**
     * @return float
     */
    public function getMoney(): float
    {
        return $this->money;
    }

    /**
     * @param float $money
     */
    public function setMoney($money): void
    {
        $this->money = intval($money * 100);
    }

    /**
     * @return int
     */
    public function getDrinks(): int
    {
        return $this->drinks;
    }

    /**
     * @param int $drinks
     */
    public function setDrinks(int $drinks): void
    {
        $this->drinks = $drinks;
    }

    /**
     * @return bool
     */
    public function isDelivery(): bool
    {
        return $this->isDelivery;
    }


    /**
     * @param bool $isDelivery
     */
    public function setIsDelivery(bool $isDelivery): void
    {
        $this->isDelivery = $isDelivery;
    }
}