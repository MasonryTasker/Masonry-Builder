<?php
/**
 * TestTrait.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Tests\PhpUnit\Helper;

/**
 * Class TestTrait
 * ${CARET}
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
trait TestTrait
{
    protected abstract function getObjectMethod($object, $methodName);
    public abstract function getObjectAttribute($object, $attributeName);
    public abstract function assertSame($expected, $actual, $message = '');
    public abstract function assertNotSame($expected, $actual, $message = '');
    public abstract function assertInstanceOf($expected, $actual, $message = '');
    public abstract function assertNull($actual, $message = '');
}
