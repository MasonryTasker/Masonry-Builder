<?php
/**
 * System.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Helper;

/**
 * Class System
 * System level actions
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
class System
{

    /**
     * A pseudo wrapper for exec...
     * Except now it works like someone with a brain would have made it, returning the exit code. You can still
     * optionally pass in an array to capture output and if you need the last line, use end() on the array.
     * @param string $command The command to run
     * @param string $stdOutput A reference to an array that will hold the output from the exec
     * @param string $stdError  A reference to an array that will hold the output from the exec
     * @throws \RuntimeException
     * @return int The exit code
     */
        public function exec($command, &$stdOutput = '', &$stdError = '')
        {
            $process = proc_open(
                $command,
                [
                    0 => ['pipe', 'r'],
                    1 => ['pipe', 'w'],
                    2 => ['pipe', 'w'],
                ],
                $pipes
            );

            if (!is_resource($process)) {
                throw new \RuntimeException('Could not create a valid process');
            }

            // This will prevent to program from continuing until the processes is complete
            $status = proc_get_status($process);
            while($status['running']) {
                $status = proc_get_status($process);
            }

            $stdOutput = stream_get_contents($pipes[1]);
            $stdError  = stream_get_contents($pipes[2]);

            foreach($pipes as $pipe) {
                fclose($pipe);
            }

            proc_close($process);

            return $status['exitcode'];
        }
}
