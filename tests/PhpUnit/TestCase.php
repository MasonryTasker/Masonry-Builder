<?php

/**
 * TestCase.php
 * PHP version 5.4
 * 2015-09-30
 *
 * @package   Foundry\Masonry-Website-Builder
 * @category
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 */

namespace Foundry\Masonry\Builder\Tests\PhpUnit;

abstract class TestCase extends \PHPUnit_Framework_TestCase
{

    /**
     * Gets returns a proxy for any method of an object, regardless of scope
     * @param object $object Any object
     * @param string $methodName The name of the method you want to proxy
     * @return \Closure
     */
    protected function getObjectMethod($object, $methodName)
    {
        if(!is_object($object)) {
            throw new \InvalidArgumentException('Can not get method of non object');
        }

        $reflectionMethod = new \ReflectionMethod($object, $methodName);
        $reflectionMethod->setAccessible(true);

        return function() use ($object, $reflectionMethod) {
            return $reflectionMethod->invokeArgs($object, func_get_args());
        };
    }

}
