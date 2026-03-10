<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Factories\AbstractFactory\Product\Modern;

use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\Chair;

class ModernChair implements Chair
{
    public function sitOn(): string
    {
        return 'Sitting on a modern chair';
    }
}
