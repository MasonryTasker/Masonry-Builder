<?php
/**
 * CoroutineRegister.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/Masonry-Builder
 */


namespace Foundry\Masonry\Builder\Coroutine;


/**
 * Class CoroutineRegister
 *
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/Masonry-Builder
 */
class CoroutineRegister
{

    /**
     * @var \Generator[]
     */
    protected $generators = [];

    /**
     * Register a generator
     * @param \Generator $generator
     * @return $this
     */
    public function register(\Generator $generator)
    {
        $this->generators[] = $generator;
        return $this;
    }

    /**
     * Loop through the generators
     * @return $this
     */
    public function tick()
    {
        foreach($this->generators as $key => $generator) {
            if(!$generator->valid()) {
                unset($this->generators[$key]);
                continue;
            }
            $generator->current();
            $generator->next();
        }
        return $this;
    }

}