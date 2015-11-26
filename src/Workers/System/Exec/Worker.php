<?php
/**
 * Worke.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Workers\System\Exec;

use Foundry\Masonry\Builder\Helper\System\ExecProcess;
use Foundry\Masonry\Builder\Helper\SystemTrait;
use Foundry\Masonry\Builder\Notification\Notification;
use Foundry\Masonry\Builder\Workers\GenericWorker;
use Foundry\Masonry\Interfaces\TaskInterface;
use React\Promise\Deferred;


/**
 * Class Worker
 *
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
class Worker extends GenericWorker
{

    use SystemTrait;

    /**
     * Where the actual work is done
     * @param Deferred $deferred
     * @param TaskInterface $task
     * @return mixed
     */
    protected function processDeferred(Deferred $deferred, TaskInterface $task)
    {
        yield;

        /** @var Description $description */
        $description = $task->getDescription();

        $deferred->notify(
            new Notification(
                "Executing '{$description}'",
                Notification::PRIORITY_NORMAL
            )
        );

        try {
            $process = ExecProcess::exec($description->getCommandString());

            // Yield until the process is complete
            while(is_null($exitCode = $process->getExitCode())) {
                $this->debugNotifyProcess($deferred, $process);
                yield;
            }

            // Check outputs one last time
            $this->debugNotifyProcess($deferred, $process);

            if (0 === $exitCode) {
                $deferred->resolve("Executed '{$description}'");
                return;
            }
        } catch (\Exception $e) {
            // Do nothing
        }
        $deferred->reject("Failed to execute '{$description}'");
    }

    /**
     * Create debug notifications for all output
     * @param Deferred $deferred
     * @param ExecProcess $process
     * @return $this
     */
    protected function debugNotifyProcess(Deferred $deferred, ExecProcess $process)
    {
        foreach($process->getOutputArray() as $output) {
            $deferred->notify(new Notification($output, Notification::PRIORITY_DEBUG));
        }
        foreach($process->getErrorArray() as $error) {
            $deferred->notify(new Notification($error, Notification::PRIORITY_DEBUG));
        }
        return $this;
    }

    /**
     * Lists, as strings, the class/interface names this worker can handle.
     * Each worker should be responsible for one type of Task, however there might be multiple ways to describe the
     * task. The names of each possible description should be returned here.
     * @return string[]
     */
    public function getDescriptionTypes()
    {
        return [
            Description::class
        ];
    }
}