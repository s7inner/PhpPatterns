<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\AbstractFactory\Product\Victorian;

use DesignPatterns\Creational\AbstractFactory\Interfaces\CoffeeTable;

class VictorianCoffeeTable implements CoffeeTable
{
    public function putOn(): string
    {
        return 'Put something on a Victorian coffee table';
    }
}
