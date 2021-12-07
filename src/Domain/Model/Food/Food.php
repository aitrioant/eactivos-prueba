<?php

namespace App\Domain\Model\Food;

class Food
{
    const TYPE_PIZZA = 'type_pizza';
    const TYPE_BURGER = 'type_burger';
    const TYPE_SUSHI = 'type_sushi';

    /** @var string */
    private $type;

    /** @var int */
    private $price;

    /**
     * @param string $type
     * @param int $price
     */
    private function __construct(string $type, int $price)
    {
        $this->type = $type;
        $this->price = $price;
    }

    public static function new(string $type): self
    {
        $methodName = 'new' . ucfirst($type);

        return self::$methodName();
    }

    public static function newPizza(): self
    {
        return new self(self::TYPE_PIZZA, 1250);
    }

    public static function newBurger(): self
    {
        return new self(self::TYPE_BURGER, 900);
    }

    public static function newSushi(): self
    {
        return new self(self::TYPE_SUSHI, 2400);
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getPrice(): int
    {
        return $this->price;
    }

    public static function allTypes(): array
    {
        return [
            self::TYPE_SUSHI,
            self::TYPE_BURGER,
            self::TYPE_PIZZA,
        ];
}
}