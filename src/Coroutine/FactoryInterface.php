<?php
/**
 * FactoryInterface.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license   MIT
 * @see       https://github.com/Visionmongers/Masonry-Builder
 */


namespace Foundry\Masonry\Builder\Coroutine;


/**
 * Interface FactoryInterface
 *
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/Masonry-Builder
 */
interface FactoryInterface
{

    /**
     * Return an instance of the CoroutineRegister
     * @static
     * @return CoroutineRegister
     */
    public static function getCoroutineRegister();

}