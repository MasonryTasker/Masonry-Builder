<?php
/**
 * Git.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license   MIT
 * @see       https://github.com/Visionmongers/Masonry-Builder
 */

namespace Foundry\Masonry\Builder\Helper\VersionControl;

/**
 * Class Git
 *
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/Masonry-Builder
 */
class Git implements VersionControlSystem
{

    /**
     * Clone a repository
     * @param string $fromLocation
     * @param string $toLocation
     * @return bool True on success
     */
    public function cloneRepository($fromLocation, $toLocation)
    {
        $git       = escapeshellcmd('git');
        $clone     = escapeshellarg('clone');
        $recursive = escapeshellarg('--recursive');
        $from      = escapeshellarg($fromLocation);
        $to        = escapeshellarg($toLocation);

        $returnValue = null;
        $unusedOutput = null;
        exec("$git $clone $recursive $from $to", $unusedOutput, $returnValue);
        return $returnValue;
    }

    /**
     * Checkout a working copy from the repository
     * @param string      $workingCopy The directory into which the working copy should be placed
     * @param string|null $repository  The repository from which the working copy should be taken
     * @param string|null $identifier  The specific revision, commit, branch, or other identifier to checkout
     * @return bool True on success
     */
    public function checkout($workingCopy, $repository = null, $identifier = null)
    {
        if($repository) {
            $this->cloneRepository($repository, $workingCopy);
        }
        $git          = escapeshellcmd('git');
        $checkout     = escapeshellarg('checkout');
        $detach       = escapeshellarg('--detach');
        $setDirectory = escapeshellarg('-C');
        $directory    = escapeshellarg($workingCopy);
        $id           = $identifier ? escapeshellarg($identifier) : '';

        $returnValue = null;
        $unusedOutput = null;
        exec("$git $checkout $detach $setDirectory $directory $id", $unusedOutput, $returnValue);
        return $returnValue;
    }
}