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

use Foundry\Masonry\Builder\Helper\SystemTrait;

/**
 * Class Git
 *
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/Masonry-Builder
 */
class Git implements VersionControlSystem
{

    use SystemTrait;

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

        return 0 == $this->getSystem()->exec("$git $clone $recursive $from $to");
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
        $git            = escapeshellcmd('git');
        $checkout       = escapeshellarg('checkout');
        $detach         = escapeshellarg('--detach');
        $setDirectoryTo = escapeshellarg('-C');
        $directory      = escapeshellarg($workingCopy);
        $id             = $identifier ? escapeshellarg($identifier) : '';

        return 0 == $this->getSystem()->exec("$git $checkout $detach $setDirectoryTo $directory $id");
    }
}