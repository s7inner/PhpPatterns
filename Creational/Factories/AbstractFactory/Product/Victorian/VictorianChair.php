<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Factories\AbstractFactory\Product\Victorian;

use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\Chair;

class VictorianChair implements Chair
{
    public function sitOn(): string
    {
        return 'Sitting on a Victorian chair';
    }
}
