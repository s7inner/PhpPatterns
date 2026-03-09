<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\AbstractFactory\Product\Victorian;

use DesignPatterns\Creational\AbstractFactory\Interfaces\Sofa;

class VictorianSofa implements Sofa
{
    public function sitOn(): string
    {
        return 'Sitting on a Victorian sofa';
    }
}
