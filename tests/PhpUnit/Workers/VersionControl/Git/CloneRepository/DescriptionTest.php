<?php
/**
 * DescriptionTest.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Workers\VersionControl\Git\CloneRepository;

use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;
use Foundry\Masonry\Builder\Workers\VersionControl\Git\CloneRepository\Description;

/**
 * Class DescriptionTest
 *
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 * @coversDefaultClass Foundry\Masonry\Builder\Workers\VersionControl\Git\CloneRepository\Description
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
        $repository = 'repository';
        $directory  = 'directory';
        $description = new Description($repository, $directory);

        $this->assertSame(
            $repository,
            $this->getObjectAttribute($description, 'repository')
        );
        $this->assertSame(
            $directory,
            $this->getObjectAttribute($description, 'directory')
        );
    }

    /**
     * @test
     * @covers ::getRepository
     * @uses Foundry\Masonry\Builder\Workers\VersionControl\Git\CloneRepository\Description::__construct
     * @return void
     */
    public function testGetRepository()
    {
        $repository = 'repository';
        $directory  = 'directory';
        $description = new Description($repository, $directory);

        $this->assertSame(
            $repository,
            $description->getRepository()
        );
    }

    /**
     * @test
     * @covers ::getDirectory
     * @uses Foundry\Masonry\Builder\Workers\VersionControl\Git\CloneRepository\Description::__construct
     * @return void
     */
    public function testGetDirectory()
    {
        $repository = 'repository';
        $directory  = 'directory';
        $description = new Description($repository, $directory);


        $this->assertSame(
            $directory,
            $description->getDirectory()
        );
    }
}
