<?php
/**
 * Exec.php
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/TheFoundryVisionmongers/Masonry-Builder
 */


namespace Foundry\Masonry\Builder\Helper\System;


/**
 * Class Exec
 *
 * @package Masonry-Builder
 * @see       https://github.com/TheFoundryVisionmongers/Masonry-Builder
 */
class Exec
{

    const STREAM_INPUT  = 0;
    const STREAM_OUTPUT = 1;
    const STREAM_ERROR  = 2;

    /**
     * @var int|null
     */
    protected $exitCode;

    /**
     * @var Resource
     */
    protected $process;

    /**
     * @var Resource[]
     */
    protected $pipes;

    /**
     * @var
     */
    protected $status;

    /**
     * Not constructable as it is misleading
     */
    protected function __construct() { }

    /**
     * Begin executing a task
     * @param $command
     * @return static
     */
    public static function exec($command)
    {
        $exec = new static();

        $exec->process = proc_open(
            $command,
            [
                static::STREAM_INPUT  => ['pipe', 'r'],
                static::STREAM_OUTPUT => ['pipe', 'w'],
                static::STREAM_ERROR  => ['pipe', 'w'],
            ],
            $exec->pipes
        );

        if (!is_resource($exec->process)) {
            throw new \RuntimeException('Could not create a valid process');
        }

        return $exec;
    }

    /**
     * Gets the exit code.
     * Returns null if the executed command is not complete
     * @return int|null
     */
    public function getExitCode()
    {
        $status = proc_get_status($this->process);
        if(!$status['running']) {
            $this->exitCode = $status['exitcode'];
        }
        return $this->exitCode;
    }

    /**
     * Cleans up
     */
    public function __destruct()
    {
        // If the processes hasn't finished, kill it
        if(is_null($this->getExitCode())) {
            proc_terminate($this->process);
        }
        // Close all pipes
        foreach($this->pipes as $pipe) {
            fclose($pipe);
        }
        // Close the processes
        proc_close($this->process);
    }

}
