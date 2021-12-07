<?php

namespace App\Domain\Model\Drinks;

class Drinks
{
    const MIN_DRINKS = 0;
    const MAX_DRINKS = 2;

    const COST = 200;

    /** @var int */
    private $amount;

    /**
     * @param int $amount
     */
    public function __construct(int $amount)
    {
        $this->amount = $amount;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    public function drinksCost()
    {
        return $this->amount * self::COST;
    }
}