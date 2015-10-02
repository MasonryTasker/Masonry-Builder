<?php
/**
 * Worker.php
 * PHP version 5.4
 * 2015-09-30
 *
 * @package   Foundry\Masonry-Website-Builder
 * @category
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 */


namespace Foundry\Masonry\Builder\Workers;


use Foundry\Masonry\Interfaces\TaskInterface;
use Foundry\Masonry\Interfaces\WorkerInterface;
use React\Promise\Deferred;
use React\Promise\Promise;

/**
 * Class GenericWorker
 *
 * @package Foundry\Masonry-Website-Builder
 */
abstract class GenericWorker implements WorkerInterface
{

    /**
     * Where the actual work is done
     * @param Deferred $deferred
     * @param TaskInterface $task
     * @return mixed
     */
    protected abstract function processDeferred(Deferred $deferred, TaskInterface $task);

    /**
     * Set the task the worker needs to complete.
     * Returns a promise that can be used for asynchronous monitoring of progress.
     * @param TaskInterface $task
     * @return Promise
     */
    public function process(TaskInterface $task)
    {
        $deferred = new Deferred();

        if(!$this->isTaskDescriptionValid($task)) {
            $deferred->reject('Invalid Task Description');
            return $deferred->promise();
        }

        $this->processDeferred($deferred, $task);

        return $deferred->promise();
    }

    /**
     * Check if the worker can process the task
     * @param TaskInterface $task
     * @return bool
     */
    protected function isTaskDescriptionValid(TaskInterface $task)
    {
        foreach($this->getDescriptionTypes() as $type) {
            if(!$task->getDescription() instanceof $type) {
                return false;
            }
        }
        return true;
    }
}