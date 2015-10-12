<?php
/**
 * FileSystemTrait.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license   MIT
 * @see       https://github.com/Visionmongers/Masonry-Builder
 */

namespace Foundry\Masonry\Builder\Helper;

/**
 * Trait FileSystemTrait
 * Enable injection of the FileSystem trait
 * @package Foundry\Masonry-Builder
 * @see     https://github.com/Visionmongers/Masonry-Builder
 */
trait FileSystemTrait
{
    /**
     * @var FileSystem
     */
    private $fileSystem;

    /**
     * Set the environment helper
     * @param FileSystem $fileSystem
     * @return $this
     */
    public function setFileSystem(FileSystem $fileSystem)
    {
        $this->fileSystem = $fileSystem;
        return $this;
    }

    /**
     * Get the environment helper
     * @return FileSystem
     */
    protected function getFileSystem()
    {
        if (!$this->fileSystem) {
            $this->setFileSystem(new FileSystem());
        }
        return $this->fileSystem;
    }
}
