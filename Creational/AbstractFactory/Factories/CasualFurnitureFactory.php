<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\AbstractFactory\Factories;

use DesignPatterns\Creational\AbstractFactory\Interfaces\Chair;
use DesignPatterns\Creational\AbstractFactory\Interfaces\CoffeeTable;
use DesignPatterns\Creational\AbstractFactory\Interfaces\FurnitureFactory;
use DesignPatterns\Creational\AbstractFactory\Interfaces\Sofa;
use DesignPatterns\Creational\AbstractFactory\Product\Casual\CasualChair;
use DesignPatterns\Creational\AbstractFactory\Product\Casual\CasualCoffeeTable;
use DesignPatterns\Creational\AbstractFactory\Product\Casual\CasualSofa;

class CasualFurnitureFactory implements FurnitureFactory
{
    public function createChair(): Chair
    {
        return new CasualChair();
    }

    public function createSofa(): Sofa
    {
        return new CasualSofa();
    }

    public function createCoffeeTable(): CoffeeTable
    {
        return new CasualCoffeeTable();
    }
}
