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
        if (!is_object($object)) {
            throw new \InvalidArgumentException('Can not get method of non object');
        }

        $reflectionMethod = new \ReflectionMethod($object, $methodName);
        $reflectionMethod->setAccessible(true);

        return function () use ($object, $reflectionMethod) {
            return $reflectionMethod->invokeArgs($object, func_get_args());
        };
    }

    /**
     * Set the value of an attribute on an object
     * Note: $object and $value are passed by reference
     * @param object $object    The object on which the attribute exists
     * @param string $attribute The attribute to change
     * @param mixed  $value     The value to set the attribute to
     * @return $this
     */
    public function setObjectAttribute(&$object, $attribute, &$value)
    {
        if (!is_object($object)) {
            throw new \InvalidArgumentException('Can not set attribute of non object');
        }

        $reflectionProperty = new \ReflectionProperty($object, $attribute);
        $reflectionProperty->setAccessible(true);
        $reflectionProperty->setValue($object, $value);

        return $this;
    }

    /**
     * This method is a blunt tool, will break additional quotes
     * @param string $commandString
     * @return string
     */
    protected function fixShellArgumentQuotes($commandString)
    {
        return (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
            ? str_replace("'", '"', $commandString)  // Windows uses " to escape arguments
            : str_replace('"', "'", $commandString); // Linux uses ' to escape arguments
    }
}
