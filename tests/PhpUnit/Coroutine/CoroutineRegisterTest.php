<?php
/**
 * CoroutineRegisterTest.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */

namespace Foundry\Masonry\Builder\Tests\PhpUnit\Coroutine;

use Foundry\Masonry\Builder\Coroutine\CoroutineRegister;
use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;

/**
 * Class CoroutineRegisterTest
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/
 * @coversDefaultClass Foundry\Masonry\Builder\Coroutine\CoroutineRegister
 */
class CoroutineRegisterTest extends TestCase
{
    /**
     * @test
     * @covers ::register
     * @return void
     */
    public function testRegister()
    {
        $register = new CoroutineRegister();

        $this->assertEmpty(
            $this->getObjectAttribute($register, 'generators')
        );

        $generatorGenerator = function() { yield; };
        $generator = $generatorGenerator();

        $register->register($generator);

        $this->assertNotEmpty(
            $this->getObjectAttribute($register, 'generators')
        );
        $this->assertContains(
            $generator,
            $this->getObjectAttribute($register, 'generators')
        );
    }

    /**
     * @covers ::tick
     * @return void
     */
    public function testTick()
    {
        // Test data
        $count = 0;
        $iterations = 5;
        $generatorGenerator = function() use (&$count, $iterations) {
            while ($count < $iterations) {
                yield $count;
                $count++;
            }
        };
        $generator = $generatorGenerator();

        // Tests
        $register = new CoroutineRegister();
        $this->assertSame(
            $register,
            $register->tick()
        );

        // Nothing should have happened yet
        $this->assertSame(
            0,
            $count
        );

        // Set the generator
        $value = [$generator];
        $this->setObjectAttribute($register, 'generators', $value);

        for($i = 0; $i < $iterations; $i++) {
            $this->assertSame(
                $register,
                $register->tick()
            );
            $this->assertSame(
                $i+1,
                $count
            );
        }

        // After loop, final count
        $this->assertSame(
            $iterations,
            $count
        );

        $this->assertSame(
            $register,
            $register->tick()
        );

        // Should not have changed this time
        $this->assertSame(
            $iterations,
            $count
        );
    }
}
