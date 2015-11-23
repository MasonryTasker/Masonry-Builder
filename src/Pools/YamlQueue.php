<?php
/**
 * YamlQueue.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Pools;

use Foundry\Masonry\Builder\Helper\ClassRegistry;
use Foundry\Masonry\Core\Task;
use Foundry\Masonry\Interfaces\Task\DescriptionInterface;


/**
 * Class YamlQueue
 *
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
class YamlQueue extends ArrayQueue
{

    protected $descriptionRegistry;

    /**
     * YamlQueue constructor.
     * @param array         $tasks               Things that might be tasks
     * @param ClassRegistry $descriptionRegistry A place to see if those things really are tasks.
     */
    public function __construct(array $tasks, ClassRegistry $descriptionRegistry)
    {
        $fakeTasks = [];
        parent::__construct($fakeTasks);

        $this->descriptionRegistry = $descriptionRegistry;

        foreach($tasks as $name => $parameters) {
            $this->addPotentialTask($name, $parameters);
        }
    }

    /**
     * Try to add a task
     * @param $taskName
     * @param $parameters
     * @return $this
     */
    protected function addPotentialTask($taskName, $parameters)
    {
        $className = '';
        try {
            $className = $this->descriptionRegistry->getClass($taskName);
        }
        catch(\Exception $e) {
            // Do nothing
        }

        if(!class_exists($className)) {
            if(is_array($parameters)) {
                foreach($parameters as $name => $potentialParameters) {
                    $this->addPotentialTask($name, $potentialParameters);
                }
                return $this;
            }
            throw new \UnexpectedValueException("'{$taskName}' did not match a class");
        }

        $descriptionReflection = new \ReflectionClass($className);
        $description = $descriptionReflection->newInstanceArgs($parameters);

        if($description instanceof DescriptionInterface) {
            return $this->addTask(new Task($description));
        }

        throw new \RuntimeException("'{$className}' was not a description");

    }

}