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


namespace Foundry\Masonry\Builder\Workers\FileSystem\Copy;

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

        $deferred->notify("Copying '{$description->getFrom()}' to '{$description->getTo()}'");

        try {
            if (!$this->recursiveCopy($description->getFrom(), $description->getTo())) {
                $deferred->notify("Directory permissions were not applied correctly");
            }
        } catch (\Exception $e) {
            $deferred->reject("Could not copy '{$description->getFrom()}' to '{$description->getTo()}'");
            return false;
        }

        $deferred->resolve("Copied '{$description->getFrom()}' to '{$description->getTo()}'");
        return true;
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

    /**
     * Copy a file or a directory one file at a time
     * @param $from
     * @param $to
     * @throws \Exception
     * @return bool
     */
    protected function recursiveCopy($from, $to)
    {
        if (is_file($from)) {
            if (@copy($from, $to)) {
                return true;
            }
            throw new \Exception("Could not copy file '$from' to '$to'");
        }

        if (is_dir($from)) {
            $returnValue = true;

            // Does the "to" directory need to be created
            $makingDirectory = !is_dir($to);
            if ($makingDirectory) {
                if (!mkdir($to, 0777, true)) {
                    throw new \Exception("Could not create directory '$to'");
                }
            }

            // Step through the directory
            $fromDirectory = opendir($from);
            while (false !== ($file = readdir($fromDirectory))) {
                if (($file != '.') && ($file != '..')) {
                    $returnValue = $returnValue && $this->recursiveCopy("$from/$file", "$to/$file");
                }
            }

            closedir($fromDirectory);

            // Fix permissions
            if ($makingDirectory) {
                $returnValue = $returnValue && chmod($to, fileperms($from));
            }

            return $returnValue;
        }

        throw new \Exception("'$from' does not exist or is not accessible");
    }
}
