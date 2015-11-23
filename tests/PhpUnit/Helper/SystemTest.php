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
    public function testExec()
    {
        $system = new System();

        $testMessage = 'Test Message';
        $output = '';
        $error  = '';

        $this->assertSame(
            0,
            $system->exec("echo $testMessage", $output, $error)
        );

        $this->assertEmpty(
            $error
        );

        $this->assertSame(
            $testMessage,
            trim($output)
        );

        $this->assertNotEquals(
            0,
            $system->exec("acho $testMessage", $output, $error)
        );

        $this->assertEmpty(
            $output
        );

        $this->assertContains(
            'acho',
            $error
        );
    }
}
