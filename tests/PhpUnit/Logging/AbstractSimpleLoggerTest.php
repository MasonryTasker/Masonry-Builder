<?php
/**
 * AbstractSimpleLoggerTest.php
 * PHP version 5.4
 * 2015-09-30
 *
 * @package   Foundry\Masonry-Website-Builder
 * @category
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Logging;

use Foundry\Masonry\Builder\Logging\AbstractSimpleLogger;
use Foundry\Masonry\Builder\Tests\PhpUnit\TestCase;
use Psr\Log\LogLevel;

/**
 * Class AbstractSimpleLoggerTest
 * @coversDefaultClass Foundry\Masonry\Builder\Logging\EchoLogger
 * @package Foundry\Masonry\Builder\Tests
 */
class AbstractSimpleLoggerTest extends TestCase
{

    /**
     * @return AbstractSimpleLogger|\PHPUnit_Framework_MockObject_MockObject
     */
    public function getAbstractLogger()
    {
        return $this
            ->getMockBuilder(AbstractSimpleLogger::class)
            ->enableProxyingToOriginalMethods()
            ->getMockForAbstractClass();
    }

    /**
     * @test
     * @covers ::formatLog
     * @uses Foundry\Masonry\Builder\Logging\AbstractSimpleLogger::formatLevel
     * @uses Foundry\Masonry\Builder\Logging\AbstractSimpleLogger::translateLevel
     * @uses Foundry\Masonry\Builder\Logging\AbstractSimpleLogger::decorateLevel
     * @uses Foundry\Masonry\Builder\Logging\AbstractSimpleLogger::colorForLevel
     * @uses Foundry\Masonry\Builder\Logging\AbstractSimpleLogger::formatMessage
     * @uses Foundry\Masonry\Builder\Logging\AbstractSimpleLogger::decorateMessage
     * @return void
     */
    public function testFormatLog()
    {
        $logger = $this->getAbstractLogger();
        $formatLog = $this->getObjectMethod($logger, 'formatLog');

        $level = 'info';
        $message = 'First Message';

        $this->assertSame(
            "info   :  First Message",
            $formatLog($level, $message)
        );

        $level = 'emergency';
        $message = 'Second Message';

        $this->assertSame(
            "EMERGENCY :  Second Message",
            $formatLog($level, $message)
        );
    }

    /**
     * @test
     * @covers ::translateLevel
     * @return void
     */
    public function testTranslateLevel()
    {
        $logger = $this->getAbstractLogger();
        $translateLevel = $this->getObjectMethod($logger, 'translateLevel');

        $levels = [
            'notice'    => 'notice',
            'info'      => 'info',
            'debug'     => 'debug',
            'emergency' => 'EMERGENCY',
            'alert'     => 'ALERT',
            'critical'  => 'CRITICAL',
            'error'     => 'ERROR',
            'warning'   => 'WARNING',
            'oThEr'     => 'OTHER',
        ];
        foreach($levels as $level => $translatedLevel) {
            $this->assertSame(
                $translatedLevel,
                $translateLevel($level)
            );
        }
    }

    /**
     * @test
     * @covers ::formatMessage
     * @uses Foundry\Masonry\Builder\Logging\AbstractSimpleLogger::decorateMessage
     * @return void
     */
    public function testFormatMessage()
    {
        $logger = $this->getAbstractLogger();
        $message = 'The quick brown fox jumped over the lazy dog.';
        $formatMessage = $this->getObjectMethod($logger, 'formatMessage');

        $this->assertSame(
            $message,
            $formatMessage($message)
        );
    }

    /**
     * @test
     * @covers ::formatLevel
     * @uses Foundry\Masonry\Builder\Logging\AbstractSimpleLogger::translateLevel
     * @uses Foundry\Masonry\Builder\Logging\AbstractSimpleLogger::decorateLevel
     * @uses Foundry\Masonry\Builder\Logging\AbstractSimpleLogger::colorForLevel
     * @return void
     */
    public function testFormatLevel()
    {
        $logger = $this->getAbstractLogger();
        $formatLevel = $this->getObjectMethod($logger, 'formatLevel');

        $levels = [
            'notice'    => 'notice :  ',
            'info'      => 'info   :  ',
            'debug'     => 'debug  :  ',
            'emergency' => 'EMERGENCY :  ',
            'alert'     => 'ALERT  :  ',
            'critical'  => 'CRITICAL :  ',
            'error'     => 'ERROR  :  ',
            'warning'   => 'WARNING :  ',
            'oThEr'     => 'OTHER  :  ',
        ];
        foreach($levels as $test => $expected) {
            $this->assertSame(
                $expected,
                $formatLevel($test)
            );
        }
    }

    /**
     * @test
     * @covers ::decorateLevel
     * @return void
     */
    public function testDecorateLevel()
    {
        $logger = $this->getAbstractLogger();
        $shortMessage = 'wee';
        $longMessage  = 'much longer';
        $decorateLevel = $this->getObjectMethod($logger, 'decorateLevel');

        $this->assertSame(
            $shortMessage.'    :  ',
            $decorateLevel($shortMessage)
        );

        $this->assertSame(
            $longMessage.' :  ',
            $decorateLevel($longMessage)
        );
    }

    /**
     * @test
     * @covers ::colorForLevel
     * @return void
     */
    public function testColorForLevel()
    {
        $logger = $this->getAbstractLogger();
        $message = 'The quick brown fox jumped over the lazy dog.';
        $colorForLevel = $this->getObjectMethod($logger, 'colorForLevel');

        $this->assertSame(
            $message,
            $colorForLevel(LogLevel::ALERT, $message)
        );
    }

    /**
     * @test
     * @covers ::decorateMessage
     * @return void
     */
    public function testDecorateMessage()
    {
        $logger = $this->getAbstractLogger();
        $message = 'The quick brown fox jumped over the lazy dog.';
        $decorateMessage = $this->getObjectMethod($logger, 'decorateMessage');

        $this->assertSame(
            $message,
            $decorateMessage($message)
        );
    }

}
