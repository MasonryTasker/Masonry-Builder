<?php
/**
 * Description.php
 * PHP version 5.4
 * 2015-10-01
 *
 * @package   Foundry\Masonry-Website-Builder
 * @category
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Workers\FileSystem\Delete;

use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;
use Foundry\Masonry\Builder\Workers\FileSystem\Delete\Description;

/**
 * Class DescriptionTest
 * @coversDefaultClass Foundry\Masonry\Builder\Workers\FileSystem\Delete\Description
 * @package Foundry\Masonry\Builder\Tests\PhpUnit\Workers\FileSystem\Delete
 */
class DescriptionTest extends TestCase
{
    /**
     * @test
     * @covers ::__construct
     * @return void
     */
    public function testConstruct()
    {
        $name = 'test';
        $description = new Description($name);
        $this->assertSame(
            $name,
            $this->getObjectAttribute($description, 'name')
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $name is required
     * @return void
     */
    public function testConstructException()
    {
        new Description('');
    }

    /**
     * @test
     * @covers ::getName
     * @uses Foundry\Masonry\Builder\Workers\FileSystem\Delete\Description::__construct
     * @return void
     */
    public function testGetName()
    {
        $name = 'test';
        $description = new Description($name);
        $this->assertSame(
            $name,
            $description->getName()
        );
    }
}
