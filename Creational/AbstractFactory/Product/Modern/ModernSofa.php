<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\AbstractFactory\Product\Modern;

use DesignPatterns\Creational\AbstractFactory\Interfaces\Sofa;

class ModernSofa implements Sofa
{
    public function sitOn(): string
    {
        return 'Sitting on a modern sofa';
    }
}
