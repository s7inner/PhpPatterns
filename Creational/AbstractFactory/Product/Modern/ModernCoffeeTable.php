<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\AbstractFactory\Product\Modern;

use DesignPatterns\Creational\AbstractFactory\Interfaces\CoffeeTable;

class ModernCoffeeTable implements CoffeeTable
{
    public function putOn(): string
    {
        return 'Put something on a modern coffee table';
    }
}
