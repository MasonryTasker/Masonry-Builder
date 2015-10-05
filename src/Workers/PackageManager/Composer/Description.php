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
     * @param string $command The command to run composer with
     * @param string $location The location of composer.json
     */
    public function __construct($command, $location)
    {
        $location = preg_replace('/(\\\\|\\/)?composer\.json$/', '', $location);
        if (!is_file($location.'/composer.json')) {
            throw new \InvalidArgumentException("There was no composer.json found at '$location'");
        }
        $this->location = $location;

        if (!in_array($command, $this->acceptableCommands)) {
            throw new \InvalidArgumentException('$command must be one of: '.implode(', ', $this->acceptableCommands));
        }
        $this->command = $command;
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
}
