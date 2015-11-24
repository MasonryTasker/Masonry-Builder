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


namespace Foundry\Masonry\Builder\Workers\PackageManager\Composer;

use Foundry\Masonry\Builder\Workers\GenericDescription;

class Description extends GenericDescription
{
    /**
     * @var string
     */
    protected $location;

    /**
     * @var string
     */
    protected $command;

    /**
     * @var bool
     */
    protected $dev;

    /**
     * @var string[]
     */
    protected $acceptableCommands = [
        'install',
        'update',
        'dump-autoload',
        'dumpautoload',
        'run-script',
        'validate'
    ];

    /**
     * @param string $command  The command to run composer with
     * @param string $location The location of composer.json
     * @param bool   $dev      Install dev dependencies
     */
    public function __construct($command, $location, $dev = false)
    {
        $this->location = preg_replace('/(\\\\|\\/)?composer\.json$/', '', $location);
        if (!in_array($command, $this->acceptableCommands)) {
            throw new \InvalidArgumentException('$command must be one of: '.implode(', ', $this->acceptableCommands));
        }
        $this->command = $command;
        $this->dev = $dev;
    }

    /**
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * @return string
     */
    public function getLocation()
    {
        return $this->location;
    }

    /**
     * @return boolean
     */
    public function isDev()
    {
        return $this->dev;
    }

}
