<?php
/**
 * VersionControlSystem.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license   MIT
 * @see       https://github.com/Visionmongers/Masonry-Builder
 */


namespace Foundry\Masonry\Builder\Helper\VersionControl;


/**
 * Interface VersionControlSystem
 * All Version Control Systems should work in 'roughly' the same way. This interface
 * uses the "Common Vocabulary" that can be found on Wikipedia
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/Masonry-Builder
 * @see     https://en.wikipedia.org/wiki/Version_control#Common_vocabulary
 */
interface VersionControlSystem
{

    /**
     * Clone a repository
     * @param string $fromLocation  Where the repository is now
     * @param string $toLocation    Where the repository should go
     * @return bool True on success
     */
    public function cloneRepository($fromLocation, $toLocation);

    /**
     * Checkout a working copy from the repository
     * @param string      $workingCopy The directory into which the working copy should be placed
     * @param string|null $repository  The repository from which the working copy should be taken
     * @param string|null $identifier  The specific revision, commit, branch, or other identifier to checkout
     * @return bool True on success
     */
    public function checkout($workingCopy, $repository = null, $identifier = null);
}