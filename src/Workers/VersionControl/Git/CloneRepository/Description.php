<?php
/**
 * Description.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license   MIT
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Workers\VersionControl\Git\CloneRepository;

use Foundry\Masonry\Builder\Workers\GenericDescription;

/**
 * Class Description
 *
 * @package Foundry\Masonry-Website-Builder
 * @see     https://github.com/Visionmongers/
 */
class Description extends GenericDescription
{

    /**
     * The location of the repository being cloned from
     * @var string
     */
    protected $repository;

    /**
     * The location where the clone should be put
     * @var string
     */
    protected $directory;

    /**
     * Description constructor.
     * @param string $repository The location of the repository being cloned from
     * @param string $directory The location where the clone should be put
     */
    public function __construct($repository, $directory)
    {
        $this->repository = $repository;
        $this->directory = $directory;
    }

    /**
     * @return string
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @return string
     */
    public function getDirectory()
    {
        return $this->directory;
    }
}
