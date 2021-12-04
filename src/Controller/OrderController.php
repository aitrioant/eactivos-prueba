<?php

namespace App\Infrastructure\Controller;

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
    public function register(Request $request): JsonResponse
    {
        $selectedFood = $request->get('selectedFood');
        $money = $request->get('money');
        $drinks = $request->get('drinks');
        $isDelivery = $request->get('isDelivery');

        if (!in_array($selectedFood, ['pizza', 'burger', 'sushi'])) {
            return $this->json('Selected food must be pizza, burger or sushi.');
        } else {
            $foodAmount = 0;
            switch ($selectedFood) {
                case 'pizza':
                    $foodAmount = 12.5;
                    break;
                case 'burger':
                    $foodAmount = 9;
                    break;
                case 'sushi':
                    $foodAmount = 24;
                    break;
            }

            if (is_null($drinks)) {
                $drinks = 0;
            }

            if ($drinks < 0 || $drinks > 2) {
                return $this->json('Number of drinks should be between 0 and 2.');
            } else {
                if ($isDelivery == true) {
                    $totalOrderAmount = $foodAmount + ($drinks * 2) + 1.5;
                    if ($money < $totalOrderAmount || $money > $totalOrderAmount) {
                        return $this->json('Money must be the exact order amount on delivery orders.');
                    }
                } else {
                    $totalOrderAmount = $foodAmount + ($drinks * 2);
                    if ($money < $totalOrderAmount) {
                        return $this->json('Money does not reach the order amount.');
                    }
                }

                if ($drinks > 0) {
                    $drinksIncludedString = 'with drinks included ';
                } else {
                    $drinksIncludedString = '';
                }

                return $this->json('Your order '.$drinksIncludedString.'has been registered.');
            }
        }
    }

    /**
     * @Route("/create-order", methods={"POST"}, name="create_order")
     * @param Request $request
     * @return Response
     */
    public function createOrder(Request $request, SerializerInterface $serializer, CreateOrderHandler $handler)
    {
        try {
            $command = $serializer->deserialize($request->getContent(), CreateOrder::class, 'json');

            $order = $handler->handle($command);

            if ($order->hasDrinks()) {
                return new Response('Your order with drinks included has been registered.');
            }

            return new Response('Your order has been registered.');

        } catch (InvalidFoodType $exception) {
            return new Response('Selected food must be pizza, burger or sushi.');

        }catch (InvalidDrinksAmount $exception) {
            return new Response('Number of drinks should be between 0 and 2.');
        }catch (NotEnoughMoney $exception) {
            return new Response('Money does not reach the order amount.');
        }catch (InvalidMoneyForDelivery $exception) {
            return new Response('Money must be the exact order amount on delivery orders.');
        }
    }
}