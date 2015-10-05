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


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Workers\FileSystem\Copy;

use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;
use Foundry\Masonry\Builder\Workers\FileSystem\Copy\Description;

/**
 * Class DescriptionTest
 * @coversDefaultClass Foundry\Masonry\Builder\Workers\FileSystem\Copy\Description
 * @package Foundry\Masonry-Website-Builder
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
        $from = 'from';
        $to   = 'to';
        $description = new Description($from, $to);
        $this->assertSame(
            $from,
            $this->getObjectAttribute($description, 'from')
        );
        $this->assertSame(
            $to,
            $this->getObjectAttribute($description, 'to')
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $to is required
     * @return void
     */
    public function testConstructException1()
    {
        new Description('from', '');
    }

    /**
     * @test
     * @covers ::__construct
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $from is required
     * @return void
     */
    public function testConstructException2()
    {
        new Description('', '');
    }

    /**
     * @test
     * @covers ::getFrom
     * @uses Foundry\Masonry\Builder\Workers\FileSystem\Copy\Description::__construct
     * @return void
     */
    public function testGetFrom()
    {
        $from = 'from';
        $to   = 'to';
        $description = new Description($from, $to);
        $this->assertSame(
            $from,
            $description->getFrom()
        );
    }

    /**
     * @test
     * @covers ::getTo
     * @uses Foundry\Masonry\Builder\Workers\FileSystem\Copy\Description::__construct
     * @return void
     */
    public function testGetTo()
    {
        $from = 'from';
        $to   = 'to';
        $description = new Description($from, $to);
        $this->assertSame(
            $to,
            $description->getTo()
        );
    }
}
