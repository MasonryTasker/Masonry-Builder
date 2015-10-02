<?php
/**
 * Description.php
 * PHP version 5.4
 * 2015-09-30
 *
 * @package   Foundry\Masonry-Website-Builder
 * @category
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 */


namespace Foundry\Masonry\Builder\Workers\MakeDirectory;

use Foundry\Masonry\Builder\Workers\GenericDescription;

/**
 * Class Description
 *
 * @package Foundry\Masonry-Website-Builder
 */
class Description extends GenericDescription
{

    /**
     * @var string
     */
    protected $name;

    /**
     * @param string $name The name of the directory to create
     */
    public function __construct($name) {
        if(!$name) {
            throw new \InvalidArgumentException('$name is required');
        }
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

}