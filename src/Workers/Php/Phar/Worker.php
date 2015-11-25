<?php
/**
 * Worker.php
 * PHP version 5.4
 * 2015-10-05
 *
 * @package   Foundry\Masonry-Website-Builder
 * @category
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 */


namespace Foundry\Masonry\Builder\Workers\Php\Phar;

use Foundry\Masonry\Builder\Notification\Notification;
use Foundry\Masonry\Builder\Workers\GenericWorker;
use Foundry\Masonry\Interfaces\TaskInterface;
use React\Promise\Deferred;

class Worker extends GenericWorker
{
    /**
     * Run phar
     * @param Deferred $deferred
     * @param TaskInterface $task
     * @return void
     */
    protected function processDeferred(Deferred $deferred, TaskInterface $task)
    {
        yield;

        /** @var Description $description */
        $description = $task->getDescription();

        $deferred->notify(
            new Notification(
                "Preparing to create phar archive '{$description->getFileName()}'",
                Notification::PRIORITY_NORMAL
            )
        );

        try {
            $phar = new \Phar($description->getFileName());

            $phar->buildFromDirectory($description->getDirectory());

            $stub = $phar->createDefaultStub($description->getEntryPoint());
            $stub = "#!/usr/bin/php \n".$stub;

            $phar->setStub($stub);
            $phar->stopBuffering();

            chmod($description->getFileName(), 0755);
        } catch (\Exception $e) {
            $deferred->notify($e);
            $deferred->reject("Phar archive '{$description->getFileName()}' was not created");
            return;
        }

        $deferred->resolve("Phar archive '{$description->getFileName()}' was created successfully");

        return;
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
