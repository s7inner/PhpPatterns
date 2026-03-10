<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Factories\AbstractFactory\Factories;

use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\Chair;
use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\CoffeeTable;
use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\FurnitureFactory;
use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\Sofa;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Victorian\VictorianChair;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Victorian\VictorianCoffeeTable;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Victorian\VictorianSofa;

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
