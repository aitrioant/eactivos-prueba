<?php

namespace App\Tests\integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;

class OrderControllerTest extends WebTestCase
{
    public function testOrderWithDrinks()
    {
        $client = static::createClient();

        $parameters = [
            'selectedFood' => 'burger',
            'money' => 100,
            'isDelivery' => false,
            'drinks' => 1,
        ];

        $crawler = $client->request('POST', '/orders/create-order', [], [], [], json_encode($parameters));
        $response = $client->getResponse();

        $this->assertEquals('Your order with drinks included has been registered.', json_decode($response->getContent()));
    }

    public function testOrderWithoutDrinks()
    {
        $client = static::createClient();

        $parameters = [
            'selectedFood' => 'burger',
            'money' => 100,
            'isDelivery' => false,
            'drinks' => 0,
        ];

        $crawler = $client->request('POST', '/orders/create-order', [], [], [], json_encode($parameters));
        $response = $client->getResponse();

        $this->assertEquals('Your order has been registered.', json_decode($response->getContent()));
    }

    public function testFoodTypes()
    {
        $client = static::createClient();

        $parameters = [
            'selectedFood' => 'tacos',
            'money' => 100,
            'isDelivery' => false,
            'drinks' => 1,
        ];

        $crawler = $client->request('POST', '/orders/create-order', [], [], [], json_encode($parameters));
        $response = $client->getResponse();

        $this->assertEquals('Selected food must be pizza, burger or sushi.', json_decode($response->getContent()));
    }

    public function testHigherNumberOfDrinks()
    {
        $client = static::createClient();

        $parameters = [
            'selectedFood' => 'burger',
            'money' => 100,
            'isDelivery' => false,
            'drinks' => 3,
        ];

        $crawler = $client->request('POST', '/orders/create-order', [], [], [], json_encode($parameters));
        $response = $client->getResponse();

        $this->assertEquals('Number of drinks should be between 0 and 2.', json_decode($response->getContent()));
    }

    public function testNotEnoughMoney()
    {
        $client = static::createClient();

        $parameters = [
            'selectedFood' => 'burger',
            'money' => 12,
            'isDelivery' => false,
            'drinks' => 2,
        ];

        $crawler = $client->request('POST', '/orders/create-order', [], [], [], json_encode($parameters));
        $response = $client->getResponse();

        $this->assertEquals('Money does not reach the order amount.', json_decode($response->getContent()));
    }

    public function testNotExactMoneyForDelivery()
    {
        $client = static::createClient();

        $parameters = [
            'selectedFood' => 'burger',
            'money' => 14,
            'isDelivery' => true,
            'drinks' => 2,
        ];

        $crawler = $client->request('POST', '/orders/create-order', [], [], [], json_encode($parameters));
        $response = $client->getResponse();

        $this->assertEquals('Money must be the exact order amount on delivery orders.', json_decode($response->getContent()));
    }
}