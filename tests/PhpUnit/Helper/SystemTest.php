<?php
/**
 * SystemTest.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license   MIT
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Helper;

use Foundry\Masonry\Builder\Helper\System;
use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;

/**
 * Class SystemTest
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/Masonry-Builder
 * @coversDefaultClass Foundry\Masonry\Builder\Helper\System
 */
class SystemTest extends TestCase
{

    /**
     * @covers ::exec
     * @throws \Exception
     * @return void
     */
    public function testCopy()
    {
        $system = new System();

        $testMessage = 'Test Message';
        $output = [];

        $this->assertSame(
            0,
            $system->exec("echo $testMessage", $output)
        );

        $this->assertCount(
            1,
            $output
        );

        $this->assertSame(
            end($output),
            $testMessage
        );
    }
}
