<?php

declare(strict_types=1);

namespace DesignPatterns\Creational\Factories\AbstractFactory\Tests;

use DesignPatterns\Creational\Factories\AbstractFactory\Factories\CasualFurnitureFactory;
use DesignPatterns\Creational\Factories\AbstractFactory\Factories\ModernFurnitureFactory;
use DesignPatterns\Creational\Factories\AbstractFactory\Factories\VictorianFurnitureFactory;
use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\Chair;
use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\CoffeeTable;
use DesignPatterns\Creational\Factories\AbstractFactory\Interfaces\Sofa;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Casual\CasualChair;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Casual\CasualCoffeeTable;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Casual\CasualSofa;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Modern\ModernChair;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Modern\ModernCoffeeTable;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Modern\ModernSofa;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Victorian\VictorianChair;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Victorian\VictorianCoffeeTable;
use DesignPatterns\Creational\Factories\AbstractFactory\Product\Victorian\VictorianSofa;
use DesignPatterns\Creational\Factories\AbstractFactory\RoomFurnisher;
use PHPUnit\Framework\TestCase;

class AbstractFactoryTest extends TestCase
{
    public function testCasualFactoryCreatesCasualFurniture(): void
    {
        $factory = new CasualFurnitureFactory();

        $chair = $factory->createChair();
        $sofa = $factory->createSofa();
        $table = $factory->createCoffeeTable();

        $this->assertInstanceOf(Chair::class, $chair);
        $this->assertInstanceOf(Sofa::class, $sofa);
        $this->assertInstanceOf(CoffeeTable::class, $table);

        $this->assertInstanceOf(CasualChair::class, $chair);
        $this->assertInstanceOf(CasualSofa::class, $sofa);
        $this->assertInstanceOf(CasualCoffeeTable::class, $table);

        $this->assertStringContainsString('casual', $chair->sitOn());
        $this->assertStringContainsString('casual', $sofa->sitOn());
        $this->assertStringContainsString('casual', $table->putOn());
    }

    public function testVictorianFactoryCreatesVictorianFurniture(): void
    {
        $factory = new VictorianFurnitureFactory();

        $chair = $factory->createChair();
        $sofa = $factory->createSofa();
        $table = $factory->createCoffeeTable();

        $this->assertInstanceOf(VictorianChair::class, $chair);
        $this->assertInstanceOf(VictorianSofa::class, $sofa);
        $this->assertInstanceOf(VictorianCoffeeTable::class, $table);

        $this->assertStringContainsString('Victorian', $chair->sitOn());
    }

    public function testModernFactoryCreatesModernFurniture(): void
    {
        $factory = new ModernFurnitureFactory();

        $chair = $factory->createChair();
        $sofa = $factory->createSofa();
        $table = $factory->createCoffeeTable();

        $this->assertInstanceOf(ModernChair::class, $chair);
        $this->assertInstanceOf(ModernSofa::class, $sofa);
        $this->assertInstanceOf(ModernCoffeeTable::class, $table);

        $this->assertStringContainsString('modern', $chair->sitOn());
    }

    public function testFurnishRoomReturnsMatchingFamily(): void
    {
        $casualRoom = RoomFurnisher::furnishRoom(new CasualFurnitureFactory());
        $this->assertInstanceOf(CasualChair::class, $casualRoom['chair']);
        $this->assertInstanceOf(CasualSofa::class, $casualRoom['sofa']);
        $this->assertInstanceOf(CasualCoffeeTable::class, $casualRoom['table']);

        $victorianRoom = RoomFurnisher::furnishRoom(new VictorianFurnitureFactory());
        $this->assertInstanceOf(VictorianChair::class, $victorianRoom['chair']);
        $this->assertInstanceOf(VictorianSofa::class, $victorianRoom['sofa']);
        $this->assertInstanceOf(VictorianCoffeeTable::class, $victorianRoom['table']);

        $modernRoom = RoomFurnisher::furnishRoom(new ModernFurnitureFactory());
        $this->assertInstanceOf(ModernChair::class, $modernRoom['chair']);
        $this->assertInstanceOf(ModernSofa::class, $modernRoom['sofa']);
        $this->assertInstanceOf(ModernCoffeeTable::class, $modernRoom['table']);
    }
}
