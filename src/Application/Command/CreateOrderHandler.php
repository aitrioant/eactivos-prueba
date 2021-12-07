<?php

namespace App\Application\Command;

use App\Domain\Command\CreateOrder;
use App\Domain\Exception\InvalidDrinksAmount;
use App\Domain\Exception\InvalidFoodType;
use App\Domain\Exception\InvalidMoneyForDelivery;
use App\Domain\Exception\NotEnoughMoney;
use App\Domain\Model\Drinks\Drinks;
use App\Domain\Model\Food\Food;
use App\Domain\Model\Order\Order;
use App\Domain\Service\CalculateOrderCost;

class CreateOrderHandler
{
    /** @var CalculateOrderCost */
    private $orderCostCalculator;

    /**
     * @param CalculateOrderCost $orderCostCalculator
     */
    public function __construct(CalculateOrderCost $orderCostCalculator)
    {
        $this->orderCostCalculator = $orderCostCalculator;
    }

    public function handle(CreateOrder $command)
    {
        $this->assertFood($command->getSelectedFood());
        $this->assertDrinks($command->getDrinks());

        $order = new Order($command);

        $orderCost = $this->orderCostCalculator->calculate($order);

        $order->setCost($orderCost);

        $this->assertEnoughMoney($order);
        if ($order->isDelivery())
            $this->assertEnoughDeliveryMoney($order);

        return $order;
    }

    private function assertFood($food)
    {
        $availableFood = Food::allTypes();

        if (!in_array('type_' . $food, $availableFood))
            throw new InvalidFoodType();
    }

    private function assertDrinks($drinks)
    {
        if ($drinks < Drinks::MIN_DRINKS || $drinks > Drinks::MAX_DRINKS)
            throw new InvalidDrinksAmount();
    }

    private function assertEnoughMoney(Order $order)
    {
        if ($order->getCost() > $order->getMoney())
            throw new NotEnoughMoney();
    }

    private function assertEnoughDeliveryMoney(Order $order)
    {
        if ($order->costWithDelivery() !== $order->getMoney())
            throw new InvalidMoneyForDelivery();
    }
}