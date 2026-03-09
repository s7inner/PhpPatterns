<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\AbstractFactory\Factories;

use DesignPatterns\Creational\AbstractFactory\Interfaces\Chair;
use DesignPatterns\Creational\AbstractFactory\Interfaces\CoffeeTable;
use DesignPatterns\Creational\AbstractFactory\Interfaces\FurnitureFactory;
use DesignPatterns\Creational\AbstractFactory\Interfaces\Sofa;
use DesignPatterns\Creational\AbstractFactory\Product\Victorian\VictorianChair;
use DesignPatterns\Creational\AbstractFactory\Product\Victorian\VictorianCoffeeTable;
use DesignPatterns\Creational\AbstractFactory\Product\Victorian\VictorianSofa;

class VictorianFurnitureFactory implements FurnitureFactory
{
    public function createChair(): Chair
    {
        return new VictorianChair();
    }

    public function createSofa(): Sofa
    {
        return new VictorianSofa();
    }

    public function createCoffeeTable(): CoffeeTable
    {
        return new VictorianCoffeeTable();
    }
}
