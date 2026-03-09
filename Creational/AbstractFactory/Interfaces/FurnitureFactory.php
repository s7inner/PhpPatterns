<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\AbstractFactory\Interfaces;

interface FurnitureFactory
{
    public function createChair(): Chair;
    public function createSofa(): Sofa;
    public function createCoffeeTable(): CoffeeTable;
}
