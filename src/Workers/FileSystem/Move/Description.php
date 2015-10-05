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


namespace Foundry\Masonry\Builder\Workers\FileSystem\Move;

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
    protected $from;

    /**
     * @var string
     */
    protected $to;

    /**
     * @param $from
     * @param $to
     */
    public function __construct($from, $to)
    {
        if (!$from) {
            throw new \InvalidArgumentException('$from is required');
        }
        if (!$to) {
            throw new \InvalidArgumentException('$to is required');
        }
        $this->from = $from;
        $this->to = $to;
    }

    /**
     * @return string
     */
    public function getFrom()
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getTo()
    {
        return $this->to;
    }
}
