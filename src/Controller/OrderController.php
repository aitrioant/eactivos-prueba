<?php

namespace App\Controller;

use App\Application\Command\CreateOrderHandler;
use App\Domain\Command\CreateOrder;
use App\Domain\Exception\InvalidDrinksAmount;
use App\Domain\Exception\InvalidFoodType;
use App\Domain\Exception\InvalidMoneyForDelivery;
use App\Domain\Exception\NotEnoughMoney;
use http\Env\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * @Route("/orders")
 */
class OrderController extends AbstractController
{
    /**
     * @Route("/register", methods={"POST"}, name="register_order")
     * @param Request $request
     * @return JsonResponse
     */
    public function createOrder(Request $request, SerializerInterface $serializer, CreateOrderHandler $handler)
    {
        try {

            $command = $serializer->deserialize($request->getContent(), CreateOrder::class, 'json');

            $order = $handler->handle($command);

            if ($order->hasDrinks()) {
                return $this->json('Your order with drinks included has been registered.');
            }

            return $this->json('Your order has been registered.');

        } catch (InvalidFoodType $exception) {
            return $this->json('Selected food must be pizza, burger or sushi.');

        } catch (InvalidDrinksAmount $exception) {
            return $this->json('Number of drinks should be between 0 and 2.');

        } catch (NotEnoughMoney $exception) {
            return $this->json('Money does not reach the order amount.');

        } catch (InvalidMoneyForDelivery $exception) {
            return $this->json('Money must be the exact order amount on delivery orders.');
        }
    }
}