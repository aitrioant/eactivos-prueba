<?php

namespace App\Domain\Model\Order;

use App\Domain\Command\CreateOrder;
use App\Domain\Model\Drinks\Drinks;
use App\Domain\Model\Food\Food;

class Order
{
    const IS_DELIVERY = 'is_delivery';
    const IS_NOT_DELIVERY = 'is_not_delivery';

    const DELIVERY_COST = 150;

    /** @var Food */
    private $food;

    /** @var Drinks */
    private $drinks;

    /** @var string */
    private $deliveryStatus;

    /** @var int */
    private $money;

    /** @var int */
    private $cost;

    /**
     * @param Food $food
     * @param Drinks $drinks
     * @param string $deliveryStatus
     * @param int $money
     * @param int $cost
     */
    public function __construct(CreateOrder $command)
    {
        $this->food = Food::new($command->getSelectedFood());
        $this->drinks = new Drinks($command->getDrinks());
        $this->deliveryStatus = $command->isDelivery() ? self::IS_DELIVERY : self::IS_NOT_DELIVERY;
        $this->money = $command->getMoney();
    }

    /**
     * @return Food
     */
    public function getFood(): Food
    {
        return $this->food;
    }

    /**
     * @return Drinks
     */
    public function getDrinks(): Drinks
    {
        return $this->drinks;
    }

    /**
     * @return string
     */
    public function getDeliveryStatus(): string
    {
        return $this->deliveryStatus;
    }

    /**
     * @return int
     */
    public function getMoney(): int
    {
        return $this->money;
    }

    /**
     * @return int
     */
    public function getCost(): int
    {
        return $this->cost;
    }

    public function isDelivery(): bool
    {
        return $this->getDeliveryStatus() === self::IS_DELIVERY;
    }

    public function isNotDelivery(): bool
    {
        return $this->getDeliveryStatus() === self::IS_NOT_DELIVERY;
    }

    public function costWithDelivery()
    {
        return $this->getCost() + self::DELIVERY_COST;
    }

    public function setCost($cost)
    {
        $this->cost = $cost;
    }

    public function hasDrinks()
    {
        return $this->drinks->getAmount() > 0;
    }
}