<?php
/**
 * System.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Helper;

/**
 * Class System
 * ${CARET}
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
class System
{

    /**
     * A pseudo wrapper for exec...
     * Except now it works like someone with a brain would have made it, returning the exit code. You can still
     * optionally pass in an array to capture output and if you need the last line, use end() on the array.
     * @param string $command The command to run
     * @param array $output A reference to an array that will hold the output from the exec
     * @return int The exit code
     */
    public function exec($command, array &$output = [])
    {
        $returnVar = null;
        exec($command, $output, $returnVar);
        return $returnVar;
    }
}
