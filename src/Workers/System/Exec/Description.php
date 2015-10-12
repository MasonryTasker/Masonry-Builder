<?php
/**
 * Description.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Workers\System\Exec;

use Foundry\Masonry\Builder\Workers\GenericDescription;


/**
 * Class Description
 *
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
class Description extends GenericDescription
{

    /**
     * @var string
     */
    protected $command = '';

    /**
     * @var string[]
     */
    protected $arguments = [];

    public function __construct($commandString)
    {
        $commandArray = explode(' ', $commandString);

        $this->command = array_shift($commandArray);
        $this->arguments = $commandArray;
    }

    /**
     * Get the command that is going to be run
     * @return string
     */
    public function getCommand()
    {
        return $this->command;
    }

    /**
     * Get a list of arguments that this command will use
     * @return string[]
     */
    public function getArguments()
    {
        return $this->arguments;
    }

    /**
     * Get the full command string with arguments, escaped
     * @return string
     */
    public function getCommandString()
    {
        $commandString = escapeshellcmd($this->getCommand());
        foreach($this->getArguments() as $argument) {
            $commandString .= ' '.escapeshellarg($argument);
        }
        return $commandString;
    }

    /**
     * Get the full command string with arguments, escaped
     * @see ::getCommandString
     * @return string
     */
    public function __toString()
    {
        return $this->getCommandString();
    }

}