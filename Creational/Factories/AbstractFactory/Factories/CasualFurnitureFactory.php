<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Factories\AbstractFactory\Factories;

use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\Chair;
use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\CoffeeTable;
use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\FurnitureFactory;
use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\Sofa;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Casual\CasualChair;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Casual\CasualCoffeeTable;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Casual\CasualSofa;

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
