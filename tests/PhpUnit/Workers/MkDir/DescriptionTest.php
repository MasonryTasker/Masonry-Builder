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


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Workers\MkDir;



use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;
use Foundry\Masonry\Builder\Workers\MkDir\Description;

/**
 * Class DescriptionTest
 * @coversDefaultClass Foundry\Masonry\Builder\Workers\MkDir\Description
 * @package Foundry\Masonry\Builder\Tests\PhpUnit\Workers\MkDir
 */
class DescriptionTest extends TestCase
{
    /**
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
}