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


namespace Foundry\Masonry\Builder\Workers\PackageManager\Composer;

use Foundry\Masonry\Builder\Workers\GenericWorker;
use Foundry\Masonry\Interfaces\TaskInterface;
use React\Promise\Deferred;
use Composer\Console\Application as Composer;
use Symfony\Component\Console\Input\ArrayInput;

class Worker extends GenericWorker
{

    /**
     * @var Composer
     */
    protected $composer;

    /**
     * Run composer
     * @param Deferred $deferred
     * @param TaskInterface $task
     * @return bool
     */
    protected function processDeferred(Deferred $deferred, TaskInterface $task)
    {
        /** @var Description $description */
        $description = $task->getDescription();

        $deferred->notify("Preparing to run composer '{$description->getCommand()}'");

        try {
            if (!getenv('COMPOSER_HOME')) {
                $this->setComposerHome();
            }
            $input = new ArrayInput([
                'command' => $description->getCommand(),
                '-d' => $description->getLocation(),
            ]);
            $this->getComposer()->run($input);
        } catch (\Exception $e) {
            $deferred->reject("Composer '{$description->getCommand()}' failed");

            return false;
        }


        $deferred->resolve("Composer '{$description->getCommand()}' ran successfully");

        return true;
    }

    /**
     * @return Composer
     */
    public function getComposer()
    {
        if (!$this->composer) {
            $this->composer = new Composer();
            $this->composer->setAutoExit(false);
        }

        return $this->composer;
    }

    /**
     * Set the composer object.
     * Useful for mocking in tests
     * @param Composer|null $composer
     * @return $this
     */
    public function setComposer(Composer $composer = null)
    {
        $this->composer = $composer;

        return $this;
    }

    /**
     * Set composer home
     * @param null $composerHome
     * @throws \Exception
     * @return void
     */
    public function setComposerHome($composerHome = null)
    {
        if (!$composerHome) {
            $composerHome = sys_get_temp_dir() . '/composer_home';
        }
        if (!is_dir($composerHome) && !mkdir($composerHome)) {
            throw new \Exception('Could not create composer home.');
        }
        putenv('COMPOSER_HOME=' . $composerHome);
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
