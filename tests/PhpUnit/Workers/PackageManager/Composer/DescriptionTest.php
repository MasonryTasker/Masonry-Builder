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


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Workers\PackageManager\Composer;

use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;
use Foundry\Masonry\Builder\Workers\PackageManager\Composer\Description;
use org\bovigo\vfs\vfsStream;

/**
 * Class DescriptionTest
 * @coversDefaultClass Foundry\Masonry\Builder\Workers\PackageManager\Composer\Description
 * @package Foundry\Masonry\Builder\Tests\PhpUnit\Workers\FileSystem\MakeDirectory
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
        $command = 'install';

        $fileSystem = vfsStream::setup('root');
        $fileSystem->addChild(vfsStream::create(['composer.json' => '']));
        $location = $fileSystem->url();

        $description = new Description($command, $location);
        $this->assertSame(
            $command,
            $this->getObjectAttribute($description, 'command')
        );
        $this->assertSame(
            $location,
            $this->getObjectAttribute($description, 'location')
        );
    }

    /**
     * @test
     * @covers ::__construct
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage There was no composer.json found at
     * @return void
     */
    public function testConstructExceptionLocation()
    {
        new Description('does not matter', 'not a location');
    }

    /**
     * @test
     * @covers ::__construct
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage $command must be one of:
     * @return void
     */
    public function testConstructExceptionCommand()
    {
        $fileSystem = vfsStream::setup('root');
        $fileSystem->addChild(vfsStream::create(['composer.json' => '']));
        $location = $fileSystem->url();

        new Description('not-a-command', $location);
    }

    /**
     * @test
     * @covers ::getCommand
     * @uses Foundry\Masonry\Builder\Workers\PackageManager\Composer\Description::__construct
     * @return void
     */
    public function testGetFrom()
    {
        $command = 'install';

        $fileSystem = vfsStream::setup('root');
        $fileSystem->addChild(vfsStream::create(['composer.json' => '']));
        $location = $fileSystem->url();

        $description = new Description($command, $location);
        $this->assertSame(
            $command,
            $description->getCommand()
        );

    }

    /**
     * @test
     * @covers ::getLocation
     * @uses Foundry\Masonry\Builder\Workers\PackageManager\Composer\Description::__construct
     * @return void
     */
    public function testGetTo()
    {
        $command = 'install';

        $fileSystem = vfsStream::setup('root');
        $fileSystem->addChild(vfsStream::create(['composer.json' => '']));
        $location = $fileSystem->url();

        $description = new Description($command, $location);
        $this->assertSame(
            $location,
            $description->getLocation()
        );
    }
}
