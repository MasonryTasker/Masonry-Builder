<?php
/**
 * Description.php
 * PHP version 5.4
 * 2015-10-05
 *
 * @package   Foundry\Masonry-Website-Builder
 * @category
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 */


namespace Foundry\Masonry\Builder\Workers\Php\Phar;

use Foundry\Masonry\Builder\Workers\GenericDescription;

class Description extends GenericDescription
{
    /**
     * @var string The output file name
     */
    protected $fileName;

    /**
     * @var string Directory to include
     */
    protected $directory;

    /**
     * @var string The entry point script name (stub)
     */
    protected $entryPoint;


    /**
     * Description constructor.
     * @param string $fileName The output file name
     * @param string $directory Directory to include
     * @param string $entryPoint The entry point script name (stub)
     */
    public function __construct($fileName, $directory, $entryPoint)
    {
        $this->fileName = $fileName;
        $this->directory = $directory;
        $this->entryPoint = $entryPoint;
    }

    /**
     * Get fileName
     * @return string
     */
    public function getFileName()
    {
        return $this->fileName;
    }

    /**
     * Get directory
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }

    /**
     * Get entryPoint
     * @return string
     */
    public function getEntryPoint()
    {
        return $this->entryPoint;
    }
}
