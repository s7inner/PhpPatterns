<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Factories\AbstractFactory\Factories;

use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\Chair;
use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\CoffeeTable;
use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\FurnitureFactory;
use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\Sofa;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Modern\ModernChair;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Modern\ModernCoffeeTable;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Modern\ModernSofa;

class ModernFurnitureFactory implements FurnitureFactory
{
    public function createChair(): Chair
    {
        return new ModernChair();
    }

    public function createSofa(): Sofa
    {
        return new ModernSofa();
    }

    public function createCoffeeTable(): CoffeeTable
    {
        return new ModernCoffeeTable();
    }
}
