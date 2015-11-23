<?php
/**
 * EchoLoggerTest.php
 * PHP version 5.4
 * 2015-09-30
 *
 * @package   Foundry\Masonry-Website-Builder
 * @category
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Logging;

use Foundry\Masonry\Builder\Logging\EchoLogger;
use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;
use Psr\Log\LogLevel;

/**
 * Class EchoLoggerTest
 * @coversDefaultClass Foundry\Masonry\Builder\Logging\EchoLogger
 * @package Foundry\Masonry\Builder\Tests
 */
class EchoLoggerTest extends TestCase
{

    /**
     * @test
     * @covers ::log
     * @uses Foundry\Masonry\Builder\Logging\AbstractSimpleLogger
     * @return void
     */
    public function testLog()
    {
        $logger = new EchoLogger();

        ob_start();
        $logger->log(LogLevel::ALERT, 'Alert message');
        $this->assertSame(
            ob_get_contents(),
            'ALERT  :  Alert message'.PHP_EOL
        );
        ob_end_clean();

        ob_start();
        $logger->log(LogLevel::NOTICE, 'Notice message');
        $this->assertSame(
            ob_get_contents(),
            'notice :  Notice message'.PHP_EOL
        );
        ob_end_clean();
    }
}
