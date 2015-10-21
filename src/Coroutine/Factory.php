<?php
/**
 * Factory.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Coroutine;


/**
 * Class Factory
 *
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
class Factory implements FactoryInterface
{

    /**
     * @var CoroutineRegister
     */
    protected static $coroutineFactory;

    /**
     * @inheritDoc
     */
    public static function getCoroutineRegister()
    {
        if(!static::$coroutineFactory) {
            static::$coroutineFactory = new CoroutineRegister();
        }
        return static::$coroutineFactory;
    }

}