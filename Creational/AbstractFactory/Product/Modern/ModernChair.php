<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\AbstractFactory\Product\Modern;

use DesignPatterns\Creational\AbstractFactory\Interfaces\Chair;

class ModernChair implements Chair
{
    public function sitOn(): string
    {
        return 'Sitting on a modern chair';
    }
}
