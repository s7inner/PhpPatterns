<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Factories\AbstractFactory\Product\Casual;

use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\CoffeeTable;

class CasualCoffeeTable implements CoffeeTable
{
    public function putOn(): string
    {
        return 'Put something on a casual coffee table';
    }
}
