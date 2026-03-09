<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\AbstractFactory\Product\Casual;

use DesignPatterns\Creational\AbstractFactory\Interfaces\CoffeeTable;

class CasualCoffeeTable implements CoffeeTable
{
    public function putOn(): string
    {
        return 'Put something on a casual coffee table';
    }
}
