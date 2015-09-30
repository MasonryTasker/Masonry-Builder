<?php
/**
 * MultiLoggerTest.php
 * PHP version 5.4
 * 2015-09-30
 *
 * @package   Foundry\Masonry-Website-Builder
 * @category
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 */


namespace Foundry\Masonry\Builder\Tests;


use Foundry\Masonry\Builder\MultiLogger;
use Psr\Log\LogLevel;

/**
 * Class MultiLoggerTest
 * @coversDefaultClass Foundry\Masonry\Builder\MultiLogger
 * @package Foundry\Masonry\Builder\Tests
 */
class MultiLoggerTest extends TestCase
{

    /**
     * @return \Psr\Log\LoggerInterface|\PHPUnit_Framework_MockObject_MockObject
     */
    protected function getMockLogger()
    {
        return $logger = $this
            ->getMockBuilder('\Psr\Log\LoggerInterface')
            ->disableOriginalConstructor()
            ->getMock();
    }

    /**
     * @test
     * @covers ::addLogger
     * @return void
     */
    public function testAddLogger()
    {
        $logger = $this->getMockLogger();
        $multiLogger = new MultiLogger();

        $this->assertSame(
            $multiLogger,
            $multiLogger->addLogger($logger)
        );

        $this->assertSame(
            [$logger],
            $this->getObjectAttribute($multiLogger, 'loggers')
        );

        $this->assertSame(
            $multiLogger,
            $multiLogger->addLogger($logger)
        );

        $this->assertSame(
            [$logger, $logger],
            $this->getObjectAttribute($multiLogger, 'loggers')
        );
    }

    /**
     * @test
     * @covers ::log
     * @uses Foundry\Masonry\Builder\MultiLogger::addLogger
     * @return void
     */
    public function testLog()
    {
        $level = LogLevel::NOTICE;
        $message = 'Test message';
        $context = ['context' => 'context'];

        $logger1 = $this->getMockLogger();
        $logger1
            ->expects($this->once())
            ->method('log')
            ->with($level, $message, $context);

        $logger2 = $this->getMockLogger();
        $logger2
            ->expects($this->once())
            ->method('log')
            ->with($level, $message, $context);

        $multiLogger = new MultiLogger();
        $multiLogger
            ->addLogger($logger1)
            ->addLogger($logger2);

        $this->assertNull(
            $multiLogger->log($level, $message, $context)
        );
    }

}