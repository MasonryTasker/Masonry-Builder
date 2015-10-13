<?php
/**
 * ArrayQueue.php
 *
 * @author    Daniel Mason <daniel.mason@thefoundry.co.uk>
 * @copyright 2015 The Foundry Visionmongers
 * @license
 * @see       https://github.com/Visionmongers/
 */


namespace Foundry\Masonry\Builder\Pools;

use Foundry\Masonry\Core\Pool\Status;
use Foundry\Masonry\Interfaces\Pool\StatusInterface;
use Foundry\Masonry\Interfaces\PoolInterface;
use Foundry\Masonry\Interfaces\TaskInterface;


/**
 * Class ArrayQueue
 *
 * @package Masonry-Builder
 * @see     https://github.com/Visionmongers/
 */
class ArrayQueue implements PoolInterface
{

    /**
     * @var \SplQueue
     */
    protected $queue;

    /**
     * ArrayQueue constructor.
     * @param array $tasks Optional array of tasks to initialise the queue. Passed by reference for speed, not altered.
     */
    public function __construct(array &$tasks = [])
    {
        $this->queue = new \SplQueue();
        $this->addTasks($tasks);
    }

    /**
     * Add a set of tasks to an existing queue.
     * @param array $tasks Array of tasks. Passed by reference for speed but not altered.
     * @return $this
     */
    public function addTasks(array &$tasks)
    {
        foreach ($tasks as $key => $task) {
            if (!$task instanceof TaskInterface) {
                throw new \InvalidArgumentException("Array contains non-task element at index '$key'");
            }
            $this->addTask($task);
        }

        return $this;
    }

    /**
     * Add a task to the pool.
     * @param TaskInterface $task
     * @return $this
     */
    public function addTask(TaskInterface $task)
    {
        $this->queue->enqueue($task);

        return $this;
    }

    /**
     * Get the next task from the pool.
     * @return TaskInterface|null
     */
    public function getTask()
    {
        return $this->queue->dequeue();
    }

    /**
     * Get the current status of the pool.
     * This should allow 2 values:
     *   pending:  There are tasks pending
     *   empty:   There are no more tasks, the pool is empty
     * @return StatusInterface
     */
    public function getStatus()
    {
        return $this->queue->isEmpty()
            ? new Status(StatusInterface::STATUS_EMPTY)
            : new Status(StatusInterface::STATUS_PENDING);
    }

}