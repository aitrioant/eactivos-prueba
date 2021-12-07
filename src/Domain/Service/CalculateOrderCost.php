<?php

namespace App\Domain\Service;

use App\Domain\Model\Order\Order;

class CalculateOrderCost
{

    public function calculate(Order $order): int
    {
        $foodPrice = $order->getFood()->getPrice();
        $drinksPrice = $order->getDrinks()->drinksCost();

        return $foodPrice + $drinksPrice;
    }
}