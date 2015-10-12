<?php
/**
 * EnvironmentTestTrait.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Helper;

use Foundry\Masonry\Builder\Helper\Environment;
use Foundry\Masonry\Builder\Helper\EnvironmentTrait;


/**
 * Trait EnvironmentTestTrait
 * ${CARET}
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
trait EnvironmentTestTrait
{

    use TestTrait;

    /**
     *
     * @return EnvironmentTrait
     */
    protected abstract function getTestSubject();

    /**
     * @covers ::setEnvironment
     * @return void
     */
    public function testSetEnvironment()
    {
        $environmentUser = $this->getTestSubject();

        $environment = new Environment();
        $environmentUser->setEnvironment($environment);

        $this->assertSame(
            $environment,
            $this->getObjectAttribute($environmentUser, 'environment')
        );
    }

    /**
     * @covers ::getEnvironment
     * @uses Foundry\Masonry\Builder\Helper\EnvironmentTrait::setEnvironment
     * @return void
     */
    public function testGetEnvironment()
    {
        $environmentUser = $this->getTestSubject();

        $environment = new Environment();

        $getEnvironment = $this->getObjectMethod($environmentUser, 'getEnvironment');

        $this->assertInstanceOf(
            Environment::class,
            $getEnvironment()
        );

        $this->assertNotSame(
            $environment,
            $getEnvironment()
        );

        $environmentUser->setEnvironment($environment);

        $this->assertInstanceOf(
            Environment::class,
            $getEnvironment()
        );

        $this->assertSame(
            $environment,
            $getEnvironment()
        );
    }

}