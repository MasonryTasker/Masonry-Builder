<?php
/**
 * EnvironmentTrait.php
 * PHP version 5.4
 * 2015-10-06
 *
 * @package   Foundry\Masonry-Website-Builder
 * @category
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 */


namespace Foundry\Masonry\Builder\Helper;

/**
 * Class Environment
 * A wrapper for getting and setting environment variables
 * @package Foundry\Masonry\Builder\Helpers
 */
trait EnvironmentTrait
{

    private $environment;

    /**
     * Set the environment helper
     * @param Environment $environment
     * @return $this
     */
    public function setEnvironment(Environment $environment)
    {
        $this->environment = $environment;
        return $this;
    }

    /**
     * Get the environment helper
     * @return Environment
     */
    protected function getEnvironment()
    {
        if (!$this->environment) {
            $this->setEnvironment(new Environment());
        }
        return $this->environment;
    }
}
