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


namespace Foundry\Masonry\Builder\Workers\MakeDirectory;

use Foundry\Masonry\Builder\Workers\GenericWorker;
use Foundry\Masonry\Interfaces\TaskInterface;
use React\Promise\Deferred;

class Worker extends GenericWorker
{

    /**
     * Make a directory as described in the task description
     * @param Deferred $deferred
     * @param TaskInterface $task
     * @return bool
     */
    protected function processDeferred(Deferred $deferred, TaskInterface $task)
    {
        /** @var Description $description */
        $description = $task->getDescription();

        $deferred->notify("Creating directory '{$description->getName()}'");

        if(mkdir($description->getName(), 0777, true)) {
            $deferred->resolve("Created directory '{$description->getName()}'");
            return true;
        }

        $deferred->reject("Directory '{$description->getName()}' could not be created");
        return false;
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