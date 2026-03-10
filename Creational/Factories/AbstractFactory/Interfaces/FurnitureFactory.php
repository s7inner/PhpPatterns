<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Factories\AbstractFactory\Interfaces;

interface FurnitureFactory
{
    public function createChair(): Chair;
    public function createSofa(): Sofa;
    public function createCoffeeTable(): CoffeeTable;
}
