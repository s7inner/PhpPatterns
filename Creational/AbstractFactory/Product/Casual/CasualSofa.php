<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\AbstractFactory\Product\Casual;

use DesignPatterns\Creational\AbstractFactory\Interfaces\Sofa;

class CasualSofa implements Sofa
{
    public function sitOn(): string
    {
        return 'Sitting on a casual sofa';
    }
}
