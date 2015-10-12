<?php
/**
 * Worker.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Workers\VersionControl\Git\CloneRepository;

use Foundry\Masonry\Builder\Helper\VersionControl\GitTrait;
use Foundry\Masonry\Builder\Workers\GenericWorker;
use Foundry\Masonry\Interfaces\TaskInterface;
use React\Promise\Deferred;


/**
 * Class Worker
 * Clone a repository to a directory
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
class Worker extends GenericWorker
{
    use GitTrait;

    /**
     * Where the actual work is done
     * @param Deferred $deferred
     * @param TaskInterface $task
     * @return mixed
     */
    protected function processDeferred(Deferred $deferred, TaskInterface $task)
    {
        /** @var Description $description */
        $description = $task->getDescription();

        $deferred->notify("Cloning '{$description->getRepository()}' to '{$description->getDirectory()}'");

        try {
            if ($this->getGit()->cloneRepository($description->getRepository(), $description->getDirectory())) {
                $deferred->resolve("Cloned '{$description->getRepository()}' to '{$description->getDirectory()}'");
                return true;
            }
        } catch (\Exception $e) {
            // Do nothing
        }
        $deferred->reject("Could not copy '{$description->getRepository()}' to '{$description->getDirectory()}'");
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