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
    protected static $coroutineRegister;

    /**
     * @inheritDoc
     */
    public static function getCoroutineRegister()
    {
        if(!static::$coroutineRegister) {
            static::$coroutineRegister = new CoroutineRegister();
        }
        return static::$coroutineRegister;
    }

}