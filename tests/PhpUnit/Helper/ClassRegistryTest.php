<?php
/**
 * ClassRegistryTest.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Helper;

use Foundry\Masonry\Builder\Helper\ClassRegistry;
use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;


/**
 * Class ClassRegistryTest
 *
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/
 * @coversDefaultClass Foundry\Masonry\Builder\Helper\ClassRegistry
 */
class ClassRegistryTest extends TestCase
{

    /**
     * @test
     * @covers ::addClassNames
     * @uses Foundry\Masonry\Builder\Helper\ClassRegistry::addClassName
     * @return void
     */
    public function testAddClassNames()
    {
        // With out any classes
        $classes = [];
        $expected = [];
        $classRegistry = new ClassRegistry();
        $this->assertSame(
            $classRegistry,
            $classRegistry->addClassNames($classes)
        );
        $this->assertSame(
            $expected,
            $this->getObjectAttribute($classRegistry, 'classNames')
        );

        // With some classes
        $classes = [
            ClassRegistry::class,
            static::class,
        ];
        $expected = [
            ClassRegistry::class => ClassRegistry::class,
            static::class => static::class,
        ];
        $classRegistry = new ClassRegistry();
        $this->assertSame(
            $classRegistry,
            $classRegistry->addClassNames($classes)
        );
        $this->assertSame(
            $expected,
            $this->getObjectAttribute($classRegistry, 'classNames')
        );
    }

    /**
     * @test
     * @covers ::addClassName
     * @return void
     */
    public function testAddClassName()
    {
        $class1 = static::class;
        $class2 = ClassRegistry::class;

        $classRegistry = new ClassRegistry();

        $this->assertSame(
            [],
            $this->getObjectAttribute($classRegistry, 'classNames')
        );

        $this->assertSame(
            $classRegistry,
            $classRegistry->addClassName($class1)
        );

        $this->assertSame(
            [
                $class1 => $class1,
            ],
            $this->getObjectAttribute($classRegistry, 'classNames')
        );

        $this->assertSame(
            $classRegistry,
            $classRegistry->addClassName($class2)
        );

        $this->assertSame(
            [
                $class1 => $class1,
                $class2 => $class2,
            ],
            $this->getObjectAttribute($classRegistry, 'classNames')
        );

        // Add existing class
        $this->assertSame(
            $classRegistry,
            $classRegistry->addClassName($class2)
        );
        // Nothing extra added
        $this->assertSame(
            [
                $class1 => $class1,
                $class2 => $class2,
            ],
            $this->getObjectAttribute($classRegistry, 'classNames')
        );
    }

    /**
     * @test
     * @covers ::addClassName
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Class names must be strings
     * @return void
     */
    public function testAddClassNameException()
    {
        $classRegistry = new ClassRegistry();
        $classRegistry->addClassName($classRegistry);
    }

    /**
     * @test
     * @covers ::getClass
     * @uses Foundry\Masonry\Builder\Helper\ClassRegistry::classNameLookup
     * @return void
     */
    public function testGetClass()
    {
        $classRegistry = new ClassRegistry();
        $classNames = [ ClassRegistry::class => ClassRegistry::class ];
        $this->setObjectAttribute($classRegistry, 'classNames', $classNames);
        $this->assertSame(
            ClassRegistry::class,
            $classRegistry->getClass('ClassRegistry')
        );
    }

    /**
     * @test
     * @covers ::getClass
     * @uses Foundry\Masonry\Builder\Helper\ClassRegistry::classNameLookup
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Class 'Fake/Namespace/FakeClass' was registered but could not be found
     * @return void
     */
    public function testGetClassException()
    {
        $classRegistry = new ClassRegistry();
        $classNames = [ 'Fake/Namespace/FakeClass' => 'Fake/Namespace/FakeClass' ];
        $this->setObjectAttribute($classRegistry, 'classNames', $classNames);
        $this->assertSame(
            ClassRegistry::class,
            $classRegistry->getClass('FakeClass')
        );
    }

    /**
     * @test
     * @covers ::classNameLookup
     * @return void
     */
    public function testClassNameLookup()
    {
        $classRegistry = new ClassRegistry();

        $classNameLookup = $this->getObjectMethod($classRegistry, 'classNameLookup');

        $classNames = [ ClassRegistry::class => ClassRegistry::class ];
        $this->setObjectAttribute($classRegistry, 'classNames', $classNames);

        $this->assertSame(
            ClassRegistry::class,
            $classNameLookup(ClassRegistry::class)
        );

        $this->assertSame(
            ClassRegistry::class,
            $classNameLookup('ClassRegistry')
        );

        $this->assertSame(
            ClassRegistry::class,
            $classNameLookup('Class')
        );
    }

    /**
     * @test
     * @covers ::classNameLookup
     * @expectedException \UnexpectedValueException
     * @expectedExceptionMessage Could not find class matching 'NotRegistry'
     * @return void
     */
    public function testClassNameLookupException()
    {
        $classRegistry = new ClassRegistry();

        $classNameLookup = $this->getObjectMethod($classRegistry, 'classNameLookup');

        $classNames = [ ClassRegistry::class => ClassRegistry::class ];
        $this->setObjectAttribute($classRegistry, 'classNames', $classNames);

        $classNameLookup('NotRegistry');
    }



}
