<?php
/**
 * SystemTestTrait.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Helper;

use Foundry\Masonry\Builder\Helper\System;
use Foundry\Masonry\Builder\Helper\SystemTrait;


/**
 * Trait SystemTestTrait
 * ${CARET}
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
trait SystemTestTrait
{

    use TestTrait;

    /**
     *
     * @return SystemTrait
     */
    protected abstract function getTestSubject();

    /**
     * @covers ::setSystem
     * @return void
     */
    public function testSetSystem()
    {
        $systemUser = $this->getTestSubject();

        $system = new System();
        $systemUser->setSystem($system);

        $this->assertSame(
            $system,
            $this->getObjectAttribute($systemUser, 'system')
        );
    }

    /**
     * @covers ::getSystem
     * @uses Foundry\Masonry\Builder\Helper\SystemTrait::setSystem
     * @return void
     */
    public function testGetSystem()
    {
        $systemUser = $this->getTestSubject();

        $system = new System();

        $getSystem = $this->getObjectMethod($systemUser, 'getSystem');

        $this->assertInstanceOf(
            System::class,
            $getSystem()
        );

        $this->assertNotSame(
            $system,
            $getSystem()
        );

        $systemUser->setSystem($system);

        $this->assertInstanceOf(
            System::class,
            $getSystem()
        );

        $this->assertSame(
            $system,
            $getSystem()
        );
    }

}