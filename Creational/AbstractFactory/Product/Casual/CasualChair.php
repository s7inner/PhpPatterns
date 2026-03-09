<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\AbstractFactory\Product\Casual;

use DesignPatterns\Creational\AbstractFactory\Interfaces\Chair;

class CasualChair implements Chair
{
    public function sitOn(): string
    {
        return 'Sitting on a casual chair';
    }
}
