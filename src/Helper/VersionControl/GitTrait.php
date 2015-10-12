<?php
/**
 * GitTrait.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Helper\VersionControl;

/**
 * Trait GitTrait
 *
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
trait GitTrait
{

    private $git;

    /**
     * Set the git helper
     * @param Git $git
     * @return $this
     */
    public function setGit(Git $git)
    {
        $this->git = $git;
        return $this;
    }

    /**
     * Get the git helper
     * @return Git
     */
    protected function getGit()
    {
        if (!$this->git) {
            $this->setGit(new Git());
        }
        return $this->git;
    }
}
