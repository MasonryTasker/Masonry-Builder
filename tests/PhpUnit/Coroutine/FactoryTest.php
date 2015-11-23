<?php
/**
 * FactoryTest.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */

namespace Foundry\Masonry\Builder\Tests\PhpUnit\Coroutine;

use Foundry\Masonry\Builder\Coroutine\CoroutineRegister;
use Foundry\Masonry\Builder\Coroutine\Factory;
use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;

/**
 * Class FactoryTest
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/
 * @coversDefaultClass Foundry\Masonry\Builder\Coroutine\Factory
 */
class FactoryTest extends TestCase
{
    /**
     * @test
     * @covers ::getCoroutineRegister
     * @return void
     */
    public function testGetCoroutineRegister()
    {
        $testRegister = new CoroutineRegister();

        $factoryRegister = Factory::getCoroutineRegister();

        $this->assertInstanceOf(
            CoroutineRegister::class,
            $factoryRegister
        );

        $this->assertNotSame(
            $testRegister,
            $factoryRegister
        );

        $property = new \ReflectionProperty(Factory::class, 'coroutineRegister');
        $property->setAccessible(true);
        $property->setValue(null, $testRegister);

        $factoryRegister = Factory::getCoroutineRegister();

        $this->assertInstanceOf(
            CoroutineRegister::class,
            $factoryRegister
        );

        $this->assertSame(
            $testRegister,
            $factoryRegister
        );

    }

}
