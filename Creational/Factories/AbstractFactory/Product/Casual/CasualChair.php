<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Factories\AbstractFactory\Product\Casual;

use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\Chair;

class CasualChair implements Chair
{
    public function sitOn(): string
    {
        return 'Sitting on a casual chair';
    }
}
