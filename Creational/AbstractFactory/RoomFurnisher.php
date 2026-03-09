<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\AbstractFactory;

use DesignPatterns\Creational\AbstractFactory\Interfaces\FurnitureFactory;

/**
 * Client code - works only with FurnitureFactory interface.
 * No if/switch on style. The caller chooses style by passing the appropriate factory.
 */
class RoomFurnisher
{
    public static function furnishRoom(FurnitureFactory $factory): array
    {
        return [
            'chair' => $factory->createChair(),
            'sofa' => $factory->createSofa(),
            'table' => $factory->createCoffeeTable(),
        ];
    }
}
