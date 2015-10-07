<?php
/**
 * Environment.php
 * PHP version 5.4
 * 2015-10-06
 *
 * @category  Helper
 * @package   Foundry\Masonry-Website-Builder
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 */


namespace Foundry\Masonry\Builder\Helper;

/**
 * Class Environment
 * A wrapper for getting and setting environment variables
 * @package Foundry\Masonry\Builder\Helpers
 */
class Environment
{

    /**
     * Set an environment variable
     * @param string $name  The name of the variable
     * @param string $value The value to set it to
     * @return $this
     */
    public function set($name, $value)
    {
        putenv("$name=$value");
        return $this;
    }

    /**
     * Get an environment variable
     * @param string $name The name of the variable
     * @return string
     */
    public function get($name)
    {
        return getenv($name);
    }
}
