<?php
/**
 * SystemTrait.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Helper;

/**
 * Trait SystemTrait
 * Attach the System helper to another class
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
trait SystemTrait
{
    /**
     * @var System
     */
    private $system;

    /**
     * Set the environment helper
     * @param System $system
     * @return $this
     */
    public function setSystem(System $system)
    {
        $this->system = $system;
        return $this;
    }

    /**
     * Get the environment helper
     * @return System
     */
    protected function getSystem()
    {
        if (!$this->system) {
            $this->setSystem(new System());
        }
        return $this->system;
    }
}
