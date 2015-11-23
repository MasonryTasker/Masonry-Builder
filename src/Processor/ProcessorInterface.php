<?php
/**
 * ProcessorInterface.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Processor;

use Foundry\Masonry\Interfaces\PoolInterface;


/**
 * Class ProcessorInterface
 *
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
interface ProcessorInterface
{
    public function run(PoolInterface $pool);
}