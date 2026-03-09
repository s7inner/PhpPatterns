<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\AbstractFactory\Factories;

use DesignPatterns\Creational\AbstractFactory\Interfaces\Chair;
use DesignPatterns\Creational\AbstractFactory\Interfaces\CoffeeTable;
use DesignPatterns\Creational\AbstractFactory\Interfaces\FurnitureFactory;
use DesignPatterns\Creational\AbstractFactory\Interfaces\Sofa;
use DesignPatterns\Creational\AbstractFactory\Product\Modern\ModernChair;
use DesignPatterns\Creational\AbstractFactory\Product\Modern\ModernCoffeeTable;
use DesignPatterns\Creational\AbstractFactory\Product\Modern\ModernSofa;

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
